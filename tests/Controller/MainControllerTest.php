<?php

namespace Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HTTPFoundation\Response;

class MainControllerTest extends WebTestCase
{
    /** @var Client */
    private $client = null;

    public function setUp()
    {

        $this->client = static::createClient();

    }

    public function testHomepageIsUp()
    {
        $this->client->request('GET', '/');


        static::assertEquals(
            Response::HTTP_OK,
            $this->client->getResponse()->getStatusCode()
        );
    }

    public function testContactPageIsUp(){
        $client = static::createClient();
        $client->followRedirects();
        $crawler = $client->request('GET', '/');

        $link = $crawler->selectLink('Nous écrire')->link();
        $crawler = $client->click($link);

        $this->assertSame(1, $crawler->filter('html:contains("Merci de votre visite")')->count());
    }

    public function testAProposPageIsUp(){
        $client = static::createClient();
        $client->followRedirects();
        $crawler = $client->request('GET', '/');

        $link = $crawler->selectLink('A propos de ce site')->link();
        $crawler = $client->click($link);

        $this->assertSame(1, $crawler->filter('html:contains("Cahier des charges")')->count());
    }
}