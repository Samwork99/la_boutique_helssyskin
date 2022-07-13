<?php

namespace App\Controller;

use App\Form\ContactType;
use App\Service\Mailer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mime\Email;

class ContactController extends AbstractController
{
    #[Route('/nous-contacter', name: 'contact')]
    public function index(Request $request, Mailer $mailer): Response
    {
        // Initialisation de mon form 
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        // Vérification du form 
        if ($form->isSubmitted() && $form->isValid()) {

            // dd($form->getData());

            // Formulaire de contact 
            $mailer->send('Votre nouvelle demande de contact', 'contact@helssyskin.fr', $form->get('email')->getData(), 'mailer/contact.html.twig', [ 
                'nom'=> $form->get('nom')->getData(),
                'prenom' => $form->get('prenom')->getData(),
                'content'=> $form->get('content')->getData()
            ]);

            $this->addFlash('notice', 'Merci de nous avoir contacté. Notre équipe vous répondra dans les meilleurs délais.');
        }

        // Redirection vers mon formulaire de contact =
        return $this->render('contact/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
