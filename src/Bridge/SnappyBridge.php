<?php

namespace App\Bridge;


use App\Entity\Command;
use App\Manager\MailerManager;
use Knp\Snappy\Pdf;
use Symfony\Component\Config\Definition\Exception\Exception;

class SnappyBridge
{
    private $mailerManager;

    /**
     * SnappyBridge constructor.
     *
     * @param MailerManager $mailerManager
     */
    public function __construct(MailerManager $mailerManager)
    {
        $this->mailerManager = $mailerManager;
    }

    public function generatePDF (Command $command) {
        $pdfName = "'/tmp/" . $command->getNumber() . ".pdf'";
        $contentHtml =$this->mailerManager->generateMailContent($command);
        try {
        $snappy = new Pdf('/usr/local/bin/wkhtmltopdf');
        $snappy->generateFromHtml($contentHtml, $pdfName);
        } catch (Exception $e) {
            echo 'Probléme de génération PDF, exception reçue : ',  $e->getMessage(), "\n";
        }
        return $pdfName;
    }


}
