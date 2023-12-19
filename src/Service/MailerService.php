<?php

namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;

class MailerService {

    public function __construct(private MailerInterface $mailer){
        $this->mailer = $mailer;
    }

    public function sendEmail(
        string $from = 'hello@example.com',
        string $to = 'you@example.com',
        string $subject = 'Time for Symfony Mailer!',
        string $content = 'Exemple de contenu',
        string $template = 'contact',
    ) : void
    {
        $email = (new TemplatedEmail())
            ->from($from)
            ->to($to)
            ->replyTo($to)
            ->subject($subject)

            // path of the Twig template to render
            ->htmlTemplate("emails/$template.html.twig")

            // pass variables (name => value) to the template
            ->context([
                'content' => $content,
                'from' => $from
            ]);

        $this->mailer->send($email);

    }
}