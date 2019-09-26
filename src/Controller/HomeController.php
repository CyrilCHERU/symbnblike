<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomeController extends Controller {

   
   
    /**
     * Fonction de direction vers la page d'accueil
     * @Route("/", name="homepage")
     * @return void
     */    
    public function home(){
        return $this->render(
            'home.html.twig',
            ['title' => "Bonjour à tous !"]
        );
    }
}