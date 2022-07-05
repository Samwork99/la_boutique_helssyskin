<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\Mailer;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class RegisterController extends AbstractController 
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
    }

    #[Route('/inscription', name: 'app_register')]
    // on appelle l'objet "request" dans la variable "request"
    public function index(Request $request, UserPasswordHasherInterface $passwordHasher, Mailer $mailer): Response
    {
        // $notification = null;
        // j'instancie ma class User en rajoutant un nouvel objet utilisateur dans cette variable
        $user = new User();
        // j'instancie un objet qui comprend 2 paramètres
        $form = $this->createForm(RegisterType::class, $user);
        // je demande à mon controller de traiter les infos de mon formulaire une fois soumis, pr cela on utilise une méthode "handleRequest"
        $form->handleRequest($request);
        // est-ce que mon formulaire a été soumis et est valide ?
        if($form->isSubmitted() && $form->isValid()) {
                // si les 2 conditions sont ok, je veux que tu injectes dans l'objet "user" toutes les données récupérées de mon formulaire 
                $user = $form->getData();

                // $search_email = $this->entityManager->getRepository(User::class)->findOneByEmail($user->getEmail());

                // if (!§search_email) {
                    // je veux enregistrer mes données dans ma base de données
                    // 1- je veux que tu prennes ma data
                    // $doctrine = $this->getDoctrine()->getManager(); 
                    // j'ai besoin de crypter mon mot de passe afin qu'il ne s'affiche pas à la vue de tous dans ma BDD
                    $password = $passwordHasher->hashPassword($user, $user->getPassword());
                    // dd($password)
                    // Tu me le réinjectes dans l'objet password
                    $user->setPassword($password);
                    
                    // 2- je veux que tu la figes (sélectionne)
                    $this->entityManager->persist($user);
                    // 3- je veux que tu me l'executes la persistance & enregistre en BDD
                    $this->entityManager->flush();

                    // Formulaire d'inscription
                    $mailer->send('Votre inscription', 'contact@helssyskin.fr', $user->getEmail(), 'mailer/register.html.twig', []);
                    return $this->redirectToRoute('app_account');
                    // Si mon user renseigne une adresse mail déjà existante --> informe le avec un addflash et invite-le à ressaisir un email unique et valable
                }
                    
        // je passe ma variable au template -> à ma "vue"
        return $this->render('register/index.html.twig', [
            'form' => $form->createView(),
            // 'notification' => $notification
        ]);
    } 
}
