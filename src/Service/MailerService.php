<?php

namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;

class MailerService {

    public function __construct(private MailerInterface $mailer, private $from){
        $this->mailer = $mailer;
        $this->from = $from;
    }

    public function getFrom()
    {
        return $this->from;
    }

    public function sendEmail(
        string $userEmail = 'user@email.com',
        string $to = 'you@example.com',
        string $replyTo = 'you@example.com',
        string $subject = 'Time for Symfony Mailer!',
        string $content = 'Exemple de contenu',
        string $template = 'contact',
    ) : void
    {
        $email = (new TemplatedEmail())
            ->from($this->getFrom())
            ->to($to)
            ->replyTo($replyTo)
            ->subject($subject)

            // path of the Twig template to render
            ->htmlTemplate("emails/$template.html.twig")

            // pass variables (name => value) to the template
            ->context([
                'content' => $content,
                'userEmail' => $userEmail
            ]);

        $this->mailer->send($email);

    }
}