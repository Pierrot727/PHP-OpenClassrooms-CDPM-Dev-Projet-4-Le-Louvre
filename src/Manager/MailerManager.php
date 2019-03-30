<?php

namespace App\Manager;


use App\Entity\Command;

class MailerManager
{
    private $templating;
    private $mailer;
    private $from;
    /**
     * @var ParametersManager
     */
    private $parametersManager;

    public function __construct(\Twig_Environment $templating, \Swift_Mailer $mailer, ParametersManager $parametersManager)
    {
        $this->templating = $templating;
        $this->mailer = $mailer;

        $this->parametersManager = $parametersManager;
    }


    public function sendConfirmationMail(Command $command)
    {
        $this->mailTo($command->getEmail(), "Confirmation de votre commande", 'internal/mailGenerator.html.twig', ['command' =>$command]);
        return $message = "success";
    }


    public function mailTo($to, $object, string $view, array $data)
    {

        $message = new \Swift_Message($object);
        $cid = $message->embed(\Swift_Image::fromPath('images/logo.png'));
        $data['cid'] = $cid;
        $message
            ->setFrom($this->parametersManager->getEmailFromCommand())
            ->setTo($to)
            ->setBody(
                $this->templating->render(
                    $view, $data
                ),
                'text/html'
            );



        return $this->mailer->send($message);
    }

    public function generateMailContent(Command $command) {

        return $this->templating->render('internal/mailGenerator.html.twig', [
            'command' => $command
        ]);
    }

}