<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Cookie;  

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(): Response
    {
            $cookie = new Cookie(
                    'accepte-cookie', //Nom cookie
                    'true', //Valeur
                    time() + 365*24*3600); //expire le //strtotime('tomorrow')
                    // '/', //Chemin de serveur
                    // '127.0.0.1:8000'); //Nom domaine
                    //  false, //Https seulement
                    //  true); // Disponible uniquement dans le protocole HTTP
                    header('Location:./');
        // }
        
        $res = new Response();
        $res->headers->setCookie( $cookie );
        $res->send();


        $res = new Response();
        $res->headers->clearCookie('my_old_cookie');
        $res->send();
        
        return $this->render('home/index.html.twig');
    }
}

?>