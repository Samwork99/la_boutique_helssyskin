<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/conditions/generales')]
class ConditionsGeneralesController extends AbstractController
{
    #[Route('/cgu_cgv', name: 'cgu_cgv')]
    public function index(): Response
    {
        return $this->render('conditions_generales/index.html.twig');
    }

    #[Route('/rgpd', name: 'rgpd')]
    public function rgpd(): Response 
    {
       return $this->render('conditions_generales/rgpd.html.twig');
    }

    #[Route('/mentions/legales', name: 'mentions_legales')]
    public function mentionslegales(): Response
    {
        return $this->render('conditions_generales/mentions_legales.html.twig');
    }
}
