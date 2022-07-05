<?php

namespace App\Controller;

use App\Entity\Order;
use App\Service\Cart;
use App\Form\OrderType;
use App\Entity\OrderDetails;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OrderController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    // Accès à MA COMMANDE =====================================================================================
    #[Route('/commande', name: 'order')]
    public function index(Cart $cart, Request $request): Response
    {
        // Si tu n'as pas d'adresses, je te demande de rediriger l'utilisateur vers la page de création d'adresses de livraison
        if (!$this->getUser()->getAddresses()->getValues())
        {
            return $this->redirectToRoute('add_address');
        }
        // variable qui prend 3 paramètres afin de bien spécifier qu'on souhaite qu'il affiche uniquement les adresses de mon utilisateur connecté
        $form = $this->createForm(OrderType::class, null, [
            'user' =>$this->getUser()
        ]);
        return $this->render('order/index.html.twig', [
            'form' => $form->createView(),
            'cart' => $cart->getFull()
        ]);
    }

    // Accès au RECAPITULATIF DE MA COMMANDE ===============================================================
    #[Route('/commande/recapitulatif', name: 'order_recap, methods={"POST"}')]
    public function recap(Cart $cart, Request $request): Response
    {
        $form = $this->createForm(OrderType::class, null, [
            'user' =>$this->getUser()
        ]);

        $form->handleRequest($request);
        // Soumission de mon formulaire de commande :
        if ($form->isSubmitted() && $form->isValid()) {

            // Initialise/appeler nos variables :
            $date = new \DateTimeImmutable();
            $carriers = $form->get('carriers')->getData();
            $delivery = $form->get('addresses')->getData();
            $delivery_content = $delivery->getFirstname().' '.$delivery->getLastname();
            if ($delivery->getCompagny()){
                $delivery_content .= '<br/>'. $delivery->getCompagny();
            }
            $delivery_content .= '<br/>'. $delivery->getPhone();
            $delivery_content .= '<br/>'. $delivery->getAddress();
            $delivery_content .= '<br/>'. $delivery->getPostal().' '.$delivery->getCity();
            $delivery_content .= '<br/>'. $delivery->getCountry();

            // Enregistrer ma commande Order()
            $order = new Order();
            // On vient ajouter notre "référence" en unique id pr ajouter le montant de la livraison choisi lors du paiement via stripe
            $reference = $date->format('dmY').'-'.uniqid();
            $order->setReference($reference);
            $order->setUser($this->getUser());
            $order->setCreatedAt ($date);
            $order->setCarrierName($carriers->getName());
            $order->setCarrierPrice($carriers->getPrice());
            $order->setDelivery($delivery_content);
            $order->setIsPaid(0);

            // Je soumets/prépare ma commande 
            $this->entityManager->persist($order);

            // Enregistrer ma commande OrderDetails()
        foreach ($cart->getFull() as $product) {
            $orderDetails = new OrderDetails();
            $orderDetails->setMyOrder($order);
            $orderDetails->setProduct($product['product']->getName());
            $orderDetails->setQuantity($product['quantity']);
            $orderDetails->setPrice($product['product']->getPrice());
            $orderDetails->setTotal($product['product']->getPrice() * $product['quantity']);
            $this->entityManager->persist($orderDetails);
        }
        // J'execute ma commande
        $this->entityManager->flush();

        return $this->render('order/recap.html.twig', [
            'cart' => $cart->getFull(),
            // je veux que tu passes à twig le "carrier" choisi par mon utilisateur
            'carrier' => $carriers,
            'delivery' => $delivery_content,
            'reference' => $order->getReference()
        ]);

    }
        return $this->redirectToRoute('cart');
    }
}
