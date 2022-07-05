<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/help')]
class HelpController extends AbstractController
{
    #[Route('/plan_site', name: 'help')]
    public function index(): Response
    {
        return $this->render('help/index.html.twig');
    }

    #[Route('/service/client', name: 'customer_service')]
    public function customer(): Response
    {
        return $this->render('help/customer_service.html.twig');
    }

    #[Route('/faq', name: 'faq')]
    public function questions(): Response
    {
        return $this->render('help/faq.html.twig');
    }
}
