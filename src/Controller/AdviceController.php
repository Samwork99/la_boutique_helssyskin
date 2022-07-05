<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdviceController extends AbstractController
{
    #[Route('/conseils_beaute', name: 'advice')]
    public function index(): Response
    {
        return $this->render('advice/advice.html.twig');
    }
}
