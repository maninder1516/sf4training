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

                // Save/Update the form Data
                $em = $this->getDoctrine()->getManager();
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

}
