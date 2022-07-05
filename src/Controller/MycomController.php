<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/mycom')]
class MycomController extends AbstractController
{
    #[Route('/suivi_commande', name: 'mycom')]
    public function index(): Response
    {
        return $this->render('mycom/index.html.twig');
    }

    #[Route('/livraison', name: 'delivery')]
    public function delivery(): Response
    {
        return $this->render('mycom/delivery.html.twig');
    }

    #[Route('/mode_paiement', name: 'mode')]
    public function mode(): Response
    {
        return $this->render('mycom/mode.html.twig');
    }
}
