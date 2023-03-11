<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
// Generic
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
// Module Specific
use App\Entity\Product;
use App\Form\ProductType;
use App\Service\ProductService as ServiceProduct;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use App\Service\FileUploader;

class ProductController extends AbstractController {

    protected $logger;
    protected $translator;

    public function __construct(LoggerInterface $logger, TranslatorInterface $translator) {
        $this->logger = $logger;
        $this->translator = $translator;
    }

    /**
     * @Route("/product", name="app_products")
     */
    public function index(): Response {
        try {
            $products = new ServiceProduct($this->getDoctrine()->getManager(), Product::class);
            $products = $products->getAllProducts();

            return $this->render('product/index.html.twig', [
                        'products' => $products,
            ]);
        } catch (\Exception $ex) {
            $this->logger->critical('Error occured while listing the products!', [
                'ERROR:' => $ex
            ]);
        }
    }

    /**
     * @Route("/product/create", name="app_product_create")
     */
    public function create(Request $request) {
        return $this->update(new Product(), $request);
    }

    /**
     * @Route("/product/edit/{id}", name="app_product_edit")
     */
    public function edit(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        $product = $em->getRepository(Product::class)->find($id);

        return $this->update($product, $request);
    }

    /**
     *  This is Create or Update method
     * @param Product $product
     * @param Request $request
     * @return Response
     */
    public function update(Product $product, Request $request): Response {
        try {
            $form = $this->createForm(ProductType::class, $product);
            $form->handleRequest($request);
            if ($form->isSubmitted()) {
                // Get the form Data
                $product = $form->getData();
                //Get Non-Form Data                
                $strMakeDate =  $request->request->get("make_date");
                $expiryDate = $request->request->get("expiry_date");

                // If File is being Uploaded
                $filename = '';
                $uploadedFile = $form->get('image')->getData();                
                if ($uploadedFile) {                    
                    $fileUploader = new FileUploader($this->logger, $this->getParameter('file_directory'));                    
                    $fileName = $fileUploader->upload($uploadedFile);                   
                }
                
                // Save/Update the form Data
                $em = $this->getDoctrine()->getManager();
                $product->setMakeDate(new \DateTime($strMakeDate));                 
                $product->setExpiryDate(new \DateTime($expiryDate));
                $product->setImage($fileName);
                $em->persist($product);
                $em->flush();

                //$product  = new ServiceProduct($this->getDoctrine()->getManager(), Product::class);
                //$product->addProduct($product);

                $this->addFlash(
                        'notice',
                        'Your changes were saved!'
                );

                // Redirect to home page
                return $this->redirectToRoute('app_products');
            }

            return $this->render('product/update.html.twig', [
                        'form' => $form->createView(),
                        'product' => $product
            ]);
        } catch (\Exception $ex) {
            $this->logger->critical('Error occured while viewing the product information!', [
                'ERROR:' => $ex
            ]);
        }
    }

    /**
     * @Route ("/product/view/{id}", name= "app_product_view", methods="GET")     
     */
    public function show($id) {
        try {
            $product = new ServiceProduct($this->getDoctrine()->getManager(), Product::class);
            $product = $product->getProduct($id);            
            // Check if the Product exists
            if (!$product) {
                throw $this->createNotFoundException(
                                'No product found for id ' . $id
                );
            }

            return $this->render('product/view.html.twig', ['product' => $product]);
        } catch (\Exception $ex) {
            $this->logger->critical('Error occured while viewing the product information!', [
                'cause' => $ex
            ]);
        }
    }

    /**
     * @Route ("/product/delete/{id}")     
     */
    public function delete(Request $request, $id) {
        try {
            $product = new ServiceProduct($this->getDoctrine()->getManager(), Product::class);
            $product->deleteProduct($id);
            // Check if the Product exists
            if (!$product) {
                throw $this->createNotFoundException(
                                'No product found for id ' . $id
                );
            }
            return $this->redirectToRoute('app_products');
        } catch (\Exception $ex) {
            $this->logger->critical('Error occured while deleting the product information!', [
                'cause' => $ex
            ]);
        }
    }
  
    /**
     * @Route ("/product/download/{id}", name= "pdf_download")     
     */
    public function download($id) {
        try {
            $product = new ServiceProduct($this->getDoctrine()->getManager(), Product::class);
            $product = $product->getProduct($id);            
            // Check if the Product exists
            if (!$product) {
                throw $this->createNotFoundException(
                                'No product found for id ' . $id
                );
            }
            $fileName = $product->getImage(); 
            $file =  $this->getParameter('file_directory').'/'.$fileName;                               
            if (!file_exists($file)) { 
                $this->logger->critical('File at file path is not found :'.$file, [
                    'cause' => $ex
                ]);               
                exit;
            }
            $response = new BinaryFileResponse($file);
            $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, $fileName);
            return $response;            
        } catch (\Exception $ex) {
            $this->logger->critical('Error occured while downloading the file!', [
                'cause' => $ex
            ]);
        }
    }

}
