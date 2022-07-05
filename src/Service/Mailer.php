<?php

namespace App\Service;

use Twig\Environment;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;

class Mailer 
{
    // j'ai besoin de 2 objets : mailerinterface & env twig
    private $mailer;
    private $twig;

    // Injection de dépendances de mes 2 objets
    public function __construct(MailerInterface $mailer, Environment $twig)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
    }
    // Mise en place de la méthode qui va me permettre d'envoyer mes emails en précisant mes paramètres
    public function send(string $subject, string $from, string $to, string $template, array $parameters) 
    {
        // Méthode email
        $email = (new Email())
            ->from($from)
            ->to($to)
            ->subject($subject)
            ->html(
                $this->twig->render($template, $parameters),
                'text/html'
            );
            
        // Envoi email
        $this->mailer->send($email);
    }
}