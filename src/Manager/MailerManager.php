<?php

namespace App\Manager;


class MailerManager
{
    private $templating;
    private $mailer;

    public function __construct(\Twig_Environment $templating, \Swift_Mailer $mailer)
    {
        $this->templating = $templating;
        $this->mailer = $mailer;

    }

    public function mailTo($from,$to,$object = null, $content = null)
    {
        $message = (new \Swift_Message($object))
            ->setFrom($from)
            ->setTo($to)
            ->setBody(
                $this->templating->render(
                // templates/emails/registration.html.twig
                    'main/mail.html.twig', [
                        'content' => $content
                    ]
                ),
                'text/html'
            );
//            $attachment = \Swift_Attachment::fromPath('/path/to/logoLouvre.png', 'image/png');
//            $message->attach($attachment);

            /*
             * If you also want to include a plaintext version of the message
            ->addPart(
                $this->renderView(
                    'emails/registration.txt.twig',
                    array('name' => $name)
                ),
                'text/plain'
            )
            */


        return $this->mailer->send($message);
    }
}