<?php

namespace App\Controller;

use App\Service\Mailer;
use App\Form\ContactType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MailerController extends AbstractController
{
    
    // public function index(): Response
    // {
    //     return $this->render('mailer/contact.html.twig');
    // }

    // Méthode contact - Formulaire de contact
    #[Route('/envoi-email', name: 'mailer')]
    public function contact(Request $request, Mailer $mailer): Response
    {
        $formContact = $this->createForm(ContactType::class, null);
        $formContact->handleRequest($request);
        // je vérifie si mon action est valide
        if($formContact->isSubmitted() && $formContact->isValid()) {
            $data = $formContact->getData();

            $mailer->send(
                "Nouveau Message",
                "contact@helssyskin.fr",
                "tissam.hdane@gmail.com",
                "mailer/contact.html.twig",
                [
                    "nom" => $data['nom'],
                    "content" => $data['content'],
                ]
            );

        }
        return $this->render('mailer/contact.html.twig', [
            'formContact'=> $formContact->createView(),
        ]);
    }
}
