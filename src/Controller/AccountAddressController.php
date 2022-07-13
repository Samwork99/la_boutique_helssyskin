<?php

namespace App\Controller;

use App\Service\Cart;
use App\Entity\Address;
use App\Form\AddressType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AccountAddressController extends AbstractController
{
    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    // =====================================================================
    // Pour accèder à mon compte utilisateur de la gestion de mes adresses
    #[Route('/compte/mes-adresses', name: 'account_address')]
    public function index(): Response
    {
        return $this->render('account/address.html.twig');
    }

    // =========================================================================
    // Pour ajouter des adresses ADD
    #[Route('/compte/ajout-adresses', name: 'add_address')]
    public function add(Cart $cart, Request $request): Response
    {
        $address = new Address();
        $form = $this->createForm(AddressType::class, $address);
        $form->handleRequest($request);

        // Si mon formulaire est soumis et valide alors enregistre moi la nouvelle adresse
        if ($form->isSubmitted() && $form->isValid()) {
            $address->setUser($this->getUser());
            $this->entityManager->persist($address);
            $this->entityManager->flush();
            // Si j'ai des produits dans mon panier, je veux que tu me retournes vers mon tunnel d'achat sinon à mes adresses
            if ($cart->get()) {
                return $this->redirectToRoute('order');
            } else {
                return $this->redirectToRoute('account_address');
            }
        }
        return $this->render('account/address_form.html.twig', [
            'form' => $form->createView()
        ]);
    }

    // ==========================================================================
    // Pour modifier une adresse EDIT
    #[Route('/compte/modifier-adresses/{id}', name: 'edit_address')]
    public function edit(Request $request, $id): Response
    {  
        // L'objet adresse à modifier
         $address = $this->entityManager->getRepository(Address::class)->findOneById($id);

        // Besoin de 2 vérification : si mon adresse n'existe pas, je redirige l'utilisateur vers la page mon compte OU si mon adresse est différente de l'utilisateur connecté, je redirige vers mon compte
        if (!$address || $address->getUser() !== $this->getUser() ) {
            return $this->redirectToRoute('account_address');
        }
 
         $form = $this->createForm(AddressType::class, $address);
         $form->handleRequest($request);
 
         // Si mon formulaire est soumis et valide alors enregistre moi l'adresse modifié
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();
             
            return $this->redirectToRoute('account_address');
        }

            return $this->render('account/address_form.html.twig', [
            'form' => $form->createView()
        ]);
    }
    // ======================================================================
    // Pour supprimer une adresse DELETE
    #[Route('/compte/supprimer-adresses/{id}', name: 'delete_address')]
    public function delete($id): Response
    {  
        // L'objet adresse à supprimer
        $address = $this->entityManager->getRepository(Address::class)->findOneById($id);
        // Si l'adresse existe et que c'est bien la mienne, j'execute la suppression de mon adresse sinon j'execute la redirection
        if ($address && $address->getUser() == $this->getUser() ) {
            $this->entityManager->remove($address);
            $this->entityManager->flush();
            
        } 
        return $this->redirectToRoute('account_address');
    }

}
