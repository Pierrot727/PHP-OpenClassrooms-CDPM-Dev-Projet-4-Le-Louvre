<?php

namespace Tests\Manager;

use App\Manager\MailerManager;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class MailControllerTest extends WebTestCase
{
    public function setUp()
    {

    }

    public function testMailIsSentAndContentIsOk()
    {
        $client = static::createClient();

        // enables the profiler for the next request (it does nothing if the profiler is not available)
        $client->enableProfiler();

        $crawler = $client->request('POST', '/command/sendMail');

        $mailer =  $this->getMockBuilder (MailerManager::class);



        $mailCollector = $client->getProfile()->getCollector('swiftmailer');

        // checks that an email was sent
        $this->assertSame(0, $mailCollector->getMessageCount());

        $collectedMessages = $mailCollector->getMessages();
        $message = $collectedMessages[0];

        // Asserting email data
        $this->assertInstanceOf('Swift_Message', $message);
        $this->assertSame('Confirmation de votre commande', $message->getSubject());
        $this->assertSame('test@test.com', key($message->getFrom()));
        $this->assertSame('test@test.com', key($message->getTo()));
        $this->assertSame(
            'Merci de votre commande!',
            $message->getBody()
        );
    }
}