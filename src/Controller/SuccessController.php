<?php

namespace App\Controller;

use App\Entity\Order;
use App\Service\Cart;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SuccessController extends AbstractController
{
    // Initialiser ma commande en interrogeant doctrine ==================================================
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    // Ma route pour ce chemin =============================================================================
    #[Route('/commande/merci/{stripeSessionId}', name: 'success')]

    public function index(Cart $cart, $stripeSessionId): Response
    {
        // On récupère notre commande ========
        $order = $this->entityManager->getRepository(Order::class)->findOneByStripeSessionId($stripeSessionId);
        
        // Si la commande n'est pas trouvé et que je ne suis pas l'utilisateur connecté en question, rediriger l'utilisateur vers la home page =========
        if (!$order || $order->getUser() != $this->getUser()) {
            return $this->redirectToRoute('home');
        }
            
        // Si seulement le statut getIsPais est à zéro alors je le passe à 1 & transmet un mail confirmant sa commande à notre client
        if (!$order->getIsPaid()) {
            // Vider mon panier une fois la commande passé et confirmé
            $cart->remove();
            // Modifier le statut isPaid de notre commande en mettant 1
            $order->setIsPaid(1);
            $this->entityManager->flush();

            // Envoyer un email à notre client pour lui confirmer sa commande
            // $mail = new Mail();
            //         $content = "Bonjour".$order->getUser()->getFirstname()."Merci pour votre commande!";
            //         $mail->send($order->getUser()->getEmail(), $order->getUser()->getFirstname(), 'Votre commande sur la boutique HelssySkin est bien validée', $content);
        }

        return $this->render('success/index.html.twig', [
            'order' => $order
        ]);
    }
}
