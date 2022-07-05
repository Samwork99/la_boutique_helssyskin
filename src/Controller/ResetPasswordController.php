<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\Mailer;
use App\Entity\ResetPassword;
use App\Form\ResetPasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ResetPasswordController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;   
    }
    
    // Route pour le MOT DE PASSE OUBLIE :
    #[Route('/mot-de-passe-oublié', name: 'reset_password')]
    public function index(Request $request, Mailer $mailer): Response
    {
        // Interdire à mes utilisateurs d'atterir sur la réinitialisation de mdp s'ils sont déjà connectés
        if ($this->getUser()) {
            return $this->redirectToRoute('home');
        }
        // Dès lors qu'un utilisateur renseigne son mdp et clique sur réinitialiser -> cmt je récupère la data :
        if ($request->get('email')) {
            // Recherche moi le user par rapport à l'adresse email saisi par ce dernier (s'il existe déjà ok réinitialise sinon inviter à s'inscrire)
            $user = $this->entityManager->getRepository(User::class)->findOneByEmail($request->get('email'));
            if ($user) {
                // Etape 1 : Enregistrer en base la demande de reset_password avec user, token, createdAt.
                $reset_password = new ResetPassword();
                $reset_password->setUser($user);
                $reset_password->setToken(uniqid());
                $reset_password->setCreatedAt(new \DateTimeImmutable());
                $this->entityManager->persist($reset_password);
                $this->entityManager->flush();

                // Etape 2 : Envoyer un email à l'utilisateur avec un lien lui permettant de mettre à jour son mot de passe
                $url = $this->generateUrl('update_password', [
                    'token' => $reset_password->getToken()
                ]);

                $mailer->send('Réinitialiser votre mot de passe sur la boutique HelssySkin', 'helssyskin@contact.fr', $user->getEmail(), 'mailer/reset_password.html.twig', ['user'=>$user->getFirstname(), 'url'=>$url]);

                $this->addFlash('notice', 'Vous allez recevoir dans quelques secondes un mail avec la procédure pour la réinitalisation de votre mot de passe');
            
            } else {
                $this->addFlash('notice', 'Cette adresse email est inconnue.');
            }
        }
        return $this->render('reset_password/index.html.twig');
    }

    // Route pour LA REINITIALISATION DU MDP :
    #[Route('/modifier-mon-mot-de-passe/{token}', name: 'update_password')]
    public function update(Request $request, $token, UserPasswordHasherInterface $passwordHasher)
    {
            $reset_password = $this->entityManager->getRepository(ResetPassword::class)->findOneByToken($token);
            // Si mon mdp n'existe pas rediriger le user vers mot passe oublié
            if(!$reset_password) {
                return $this->redirectToRoute('reset_password');
            }
            // Vérifier si le createdAt = now à 3h
            $now = new \DateTimeImmutable();
            if ($now > $reset_password->getCreatedAt()->modify('+ 3 hour')) {
                $this->addFlash('notice', 'Votre demande de mot de passe a expiré. Merci de la renouveler.');
                return $this->redirectToRoute('reset_password');
            }
            // Rendre une vue avec mot de passe et confirmez votre mdp
            $form = $this->createForm(ResetPasswordType::class);
            $form->handleRequest($request);
            if($form->isSubmitted() && $form->isValid()) {
                $new_pwd = $form->get('new_password')->getData();
            // Encodage des mots de passe
            $password = $passwordHasher->hashPassword($reset_password->getUser(), $new_pwd);
            $reset_password->getUser()->setPassword($password);
            // Flush en base de données
            $this->entityManager->flush();
            // Redirection de l'utilisateur vers la page de connexion
            $this->addFlash('notice', 'Votre mot de passe a bien été mis à jour.');
            return $this->redirectToRoute('app_login');
            }
            return $this->render('reset_password/update.html.twig', [
                'form' =>$form->createView()
            ]);   
    }
}