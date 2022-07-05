<?php

namespace App\Controller;

use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CancelController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    #[Route('/commande/erreur/{stripeSessionId}', name: 'cancel')]
    public function index($stripeSessionId): Response
    {
        $order = $this->entityManager->getRepository(Order::class)->findOneByStripeSessionId($stripeSessionId);
        
        // Si la commande n'est pas trouvé et que je ne suis pas l'utilisateur connecté en question, rediriger l'utilisateur vers la home page =========
        if (!$order || $order->getUser() != $this->getUser()) {
            return $this->redirectToRoute('home');
        }

        // Envoyer un email à notre client pour lui indiquer l'échec de paiement

        // on a besoin de lui passer un tableau d'option pour l'order concerné
        // pas besoin de modifier le statut puisque c'est une erreur de paiement
        return $this->render('cancel/index.html.twig', [
            'order' => $order
        ]);
    }



}
