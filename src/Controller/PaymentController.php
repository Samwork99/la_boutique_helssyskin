<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\Product;
use App\Service\Cart;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PaymentController extends AbstractController
{
    #[Route('/commande/create-session/{reference}', name: 'payment')]

    public function index(EntityManagerInterface $entityManager, Cart $cart, $reference): Response
    {
        // 1-Initialisation - Création du chemin pour ma session stripe =======================================================
        $products_for_stripe = [];
        $YOUR_DOMAIN = 'https://helssyskin.ibtissam-haidane.com';

        // Récupération de mon order : on a besoin de l'entityManager pour selectionner notre référence en question
        $order = $entityManager->getRepository(Order::class)->findOneByReference($reference);

        // si jms notre référence est introuvable, transmettre ce message d'erreur
        if (!$order) {
            new JsonResponse(['error' => 'order']);
        }

        // Transformation du panier en panier Stripe
        foreach ($order->getOrderDetails()->getValues() as $product) {
            $product_object = $entityManager->getRepository(Product::class)->findOneByName($product->getProduct());
            // Pour le recap de ma commande (order price)
            $products_for_stripe[] = [
                'price_data' => [
                    'currency' => 'EUR',
                    'unit_amount' => $product->getPrice(),
                    'product_data' => [
                        'name' => $product->getProduct(),
                        'images' => [$YOUR_DOMAIN],//."/img_products/".$product_object->getIllustration()],
                    ],
                ],
                'quantity' => $product->getQuantity(),
            ];
        }
        // Pour le transporteur (delivery price)
        $products_for_stripe[] = [
            'price_data' => [
                'currency' => 'EUR',
                'unit_amount' => $order->getCarrierPrice(),
                'product_data' => [
                    'name' => $order->getCarrierName(),
                    'images' => [$YOUR_DOMAIN],
                ],
            ],
            'quantity' => 1,
        ];

        // 2-Initialisation de Stripe ==============================================================================
        Stripe::setApiKey('sk_test_51L6ILSCPgWAaIMExJ5RtpdzRTjqWjPX1MHHLtP61AzJUVswlpDRodSwgqiYn9CQaI2HDOVTkodwzmFA9csW4uSma00TCPcE7MJ');

        // 3- J'appelle ma session ->Prépare les données à afficher sur ma session checkout ================================================
        $checkout_session = Session::create([
        // Pour pré-enregistrer l'adresse email déjà saisi une première fois afin d'éviter à l'utilisateur de la retaper systématiquement
        'customer_email' => $this->getUser()->getEmail(),
        'payment_method_types' => ['card'],
        'line_items' => [
            $products_for_stripe
        ],
        'mode' => 'payment',
        // On passe un paramètre ID afin de retrouver ce checkout session id remplacé automatiquement par Stripe
        // Comme il n'est pas stocké en base du côté orderController --> c'est pourquoi on vient l'ajouter à cet entity
        'success_url' => $YOUR_DOMAIN .'/commande/merci/{CHECKOUT_SESSION_ID}',
        'cancel_url' =>  $YOUR_DOMAIN .'/commande/erreur/{CHECKOUT_SESSION_ID}',
        ]);

        $order->setStripeSessionId($checkout_session->id);
        $entityManager->flush();

        // 4-Envoi de ma response à stripe ======================================================================
        $response = new JsonResponse(['id' => $checkout_session->id]);
        return $response;

    }
}
