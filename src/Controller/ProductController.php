<?php

namespace App\Controller;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/nos-produits')]
class ProductController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    // Route pour afficher tous les produits
    #[Route('/', name: 'products')]
    public function index(): Response
    {
        $products = $this->entityManager->getRepository(Product::class)->findAll();

        return $this->render('product/shop.html.twig', [
            'products' => $products
        ]);
    }

    // Route vers les poduits du visage
    #[Route('/soins-visage', name: 'products_visage')]
    public function visage(ManagerRegistry $doctrine): Response
    {
        $category = $doctrine->getRepository(Product::class)->findBy(['category'=> 1]);

        return $this->render('product/index.html.twig', [
            'products' => $category
        ]);
    }
    // Route vers les produits du corps
    #[Route('/soins-corps', name: 'products_corps')]
    public function corps(ManagerRegistry $doctrine): Response
    {
        $category = $doctrine->getRepository(Product::class)->findBy(['category'=> 2]);

        return $this->render('product/index.html.twig', [
            'products' => $category
        ]);
    }

    // Pour afficher ma vue en détail d'un produit selectionné
    #[Route('/produit/{slug}', name: 'show_product')]
    public function show($slug): Response
    {
        $product = $this->entityManager->getRepository(Product::class)->findOneBySlug($slug);
        if (!$product) {
            return $this->redirectToRoute('products');
        }
        return $this->render('product/show.html.twig', [
            'product' => $product
        ]);
    }
        
}
