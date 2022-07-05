<?php

namespace App\Service;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class Cart 
{
    private $requestStack;
    private $entityManager;

    // Utilisation de la RequestStack qui fonctionne mieux que la sessionInterface
    public function __construct(EntityManagerInterface $entityManager, RequestStack $requestStack)
    
    {
        $this->requestStack = $requestStack;
        $this->session = $requestStack->getSession();
        $this->entityManager = $entityManager;
    }

    // Pour ajouter des produits à mon panier
    public function add($id)
    {
        $cart = $this->session->get('cart', []);
        // SI différent de vide ma variable "cart" avec un "id" spécifique cad si tu as déjà dans ton panier un produit déjà inséré ALORS il faut que tu fasses une opération en ajoutant une quantité de un en un en plus SINON j'ai juste besoin que tu prennes mon cart id et tu me fasses "1"
        if(!empty($cart[$id])) {
            $cart[$id]++;
        } else {
            $cart[$id] = 1;
        }
        $this->session->set('cart', $cart);
    }

    // Pour mettre à jour mon panier
    public function get()
    {
      return $this->session->get('cart');  
    }

    // Pour supprimer tout mon panier
    public function remove()
    {
      return $this->session->remove('cart');  
    }

    // Pour supprimer une ligne de mon panier ('id' commun d'une même occurrence)
    public function delete($id)
    {
        $cart = $this->session->get('cart', []);
        unset($cart[$id]);
        return $this->session->set('cart', $cart);
    }

    // Pour décrémenter de un en un
    public function decrease($id)
    {
        $cart = $this->session->get('cart', []);
        if ($cart[$id] > 1) {
            // retirer une quantité (-1)
            $cart[$id]--;

        } else {
            // retirer mon produit (vider)
            unset($cart[$id]);
        }
        return $this->session->set('cart', $cart);       
    }

    public function getFull()
    {
        $cartComplete = [];

        if ($this->get()) {
            foreach ($this->get() as $id => $quantity) {
                $product_object = $this->entityManager->getRepository(Product::class)->findOneById($id);
                // si mon produit n'existe pas (par ex : cart/add/124426357), alors supprime le produit du panier directement, le "continue" permet de sortir de ma boucle et d'appliquer le reste de ma function
                if (!$product_object){
                    $this->delete($id);
                    continue;
                }
                $cartComplete[] = [
                    'product' => $product_object,
                    'quantity' => $quantity
                ];
            }
        }
        return $cartComplete;
    }
}