<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class DefaultController extends AbstractController {
    public function hello()
    {        
        return new Response(
            '<html><body>Hello Symfony !!!</body></html>'
        );
    }
    
    public function hello2(): Response
    {
        echo 'Hello Symfony. I am doing good !!!';
        die;

        return new Response(
            '<html><body>Lucky number: '.$number.'</body></html>'
        );
    }
    
    /**
     * @Route("/blog/{slug}", name="blog_list", requirements={"page"="\d+"})
     */
    public function annotate(int $slug)
    {
        echo 'My favourite number is : '.$slug;
        die;
        
        
        // return $this->render('lucky/number.html.twig', ['number' => $number]);

        return new Response(
            '<html><body>Lucky number: '.$number.'</body></html>'
        );
    }
    
    
}