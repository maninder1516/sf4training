<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Cookie;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use App\Service\SentenceChecker;

class UserController extends AbstractController {

    protected $logger;
    protected $translator;

    public function __construct(LoggerInterface $logger, TranslatorInterface $translator) {
        $this->logger = $logger;
        $this->translator = $translator;
    }

    /**
     * @Route("/user", name="users")
     */
    public function index(): Response {
        $repository = $this->getDoctrine()->getRepository(User::class);
        $users = $repository->findAll();

        $transSFText = $this->translator->trans('symfony_is_great.label');

        return $this->render('User/index.html.twig', ['users' => $users, 'user_first_name' => 'SF',
                    'transSFText' => $transSFText]);

        //return $this->render("User/index.html.twig");
        // The `render()` method returns a `Response` object with the
        // contents created by the template
//        return $this->render('product/index.html.twig', [
//            'category' => '...',
//            'promotions' => ['...', '...'],
//        ]);
        // The `renderView()` method only returns the contents created by the
        // template, so you can use those contents later in a `Response` object
//        $contents = $this->renderView('product/index.html.twig', [
//            'category' => '...',
//            'promotions' => ['...', '...'],
//        ]);
//        return $this->json([
//            'message' => 'Welcome to your new controller!',
//            'path' => 'src/Controller/UserController.php',
//        ]);
    }

    /**
     * @Route("/user/create", name="app_user_create")
     */
    public function create(Request $request) {
        return $this->update(new User(), $request);
    }

    /**
     * @Route("/user/edit/{id}", name="app_user_edit")
     */
    public function edit(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)->find($id);

        return $this->update($user, $request);
    }

    /**
     *  This is Create or Update method
     * @param User $user
     * @param Request $request
     * @return Response
     */
    public function update(User $user, Request $request): Response {
        // This is not a recomended way to use the forms
//        $form = $this->createFormBuilder($user)
//            ->add('name', TextType::class)
//            ->add('mobile', TextType::class)
//            ->add('save', SubmitType::class, ['label' => 'Create User'])
//            ->getForm();
        // echo 'Inside'; 

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            // Get the form Data
            $user = $form->getData();

            // Save/Update the form Data
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $this->addFlash(
                    'notice',
                    'Your changes were saved!'
            );

            // Redirect to home page
            return $this->redirectToRoute('users');
        }

        return $this->render('User/update.html.twig', [
                    'form' => $form->createView(),
        ]);
    }

    /**
     * @Route ("/user/view/{id}", name= "app_user_view", methods="GET")     
     */
    public function show($id) {
        try {
            $user = $this->getDoctrine()
                    ->getRepository(User::class)
                    ->find($id);

            if (!$user) {
                throw $this->createNotFoundException(
                                'No user found for id ' . $id
                );
            }
            $this->logger->info('I just got the logger');
            //$logger->error('An error occurred');

            return $this->render('User/view.html.twig', ['user' => $user]);
        } catch (\Exception $ex) {
            $this->logger->critical('Error occured while viewing the user information!', [
                'cause' => $ex
            ]);
        }
    }

    /**
     * @Route ("/user/delete/{id}")     
     */
    public function delete(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)->find($id);

        if (!$user) {
            throw $this->createNotFoundException(
                            'No user found for id ' . $id
            );
        }

        // Delete the User
        $em->remove($user);
        $em->flush();

        //$user  = new ServiceUser($this->getDoctrine()->getManager(), User::class);
        //$user->deleteUser($id);

        return $this->redirectToRoute('users');
    }

    /**
     * @Route("/{str}", name="main")
     */
    public function checkSentence($str, SentenceChecker $checker): Response
    {
        echo 'Hello';
        ECHO CHR(52);
        echo ORD("HI");
        echo '</br>';
        echo 'Array Product : ';
        $arr = ARRAY(12,5,2);
        ECHO (ARRAY_PRODUCT($arr));
        die;
        $checker->parse($str);
        // other logic
        return $this->render('User/index.html.twig');
    }

}
