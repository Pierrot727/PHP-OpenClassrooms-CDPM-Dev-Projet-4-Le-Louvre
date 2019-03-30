<?php

namespace Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CommandControllerTest extends WebTestCase
{
    /** @var Client  */
    private $client = null;

    public function setUp()
    {

        $this->client = static::createClient();

    }

    public function testCommandTunnelCreationIsUp()
    {
        $client = static::createClient();
        $client->followRedirects();
        $crawler = $client->request('GET', '/');

        $link = $crawler->selectLink('Cliquez içi pour acheter vos billets!')->link();
        $crawler = $client->click($link);

        $this->assertSame(1, $crawler->filter('html:contains("Créer votre commandez pour vos billets!")')->count());
    }

    public function testCommandTunnelSetANewCommandIsUp()
    {
        $client = static::createClient();
        $client->followRedirects();

        $crawler = $client->request('GET', '/');

        $link = $crawler->selectLink('Cliquez içi pour acheter vos billets!')->link();
        $crawler = $client->click($link);

        $form = $crawler->selectButton('Suivant')->form();
        $form['command[date]'] = '2020-03-03';
        $form['command[number]'] = 2;
        $form['command[email]'] = 'test@test.com';
        $client->submit($form);

        $crawler = $client->getCrawler();
       // echo $client->getResponse()->getContent();

        $this->assertSame(2, $crawler->filter('h4:contains("Billet")')->count());
    }

    public function testCommandTunnelFillTicketsIsUp()
    {
        $client = static::createClient();
        $client->followRedirects();

        $crawler = $client->request('GET', '/');

        $link = $crawler->selectLink('Cliquez içi pour acheter vos billets!')->link();
        $crawler = $client->click($link);

        $form = $crawler->selectButton('Suivant')->form();
        $form['command[date]'] = '2020-03-03';
        $form['command[number]'] = 2;
        $form['command[email]'] = 'test@test.com';
        $client->submit($form);

        $crawler = $client->getCrawler();

        $form = $crawler->selectButton('Saisir vos billets')->form();
        $form['ticket_collection[tickets][0][lastname]'] = 'NOM1';
        $form['ticket_collection[tickets][0][firstname]'] = 'PRENOM1';
        $form['ticket_collection[tickets][0][country]'] = 'FR';
        $form['ticket_collection[tickets][0][birthday][day]'] = 1;
        $form['ticket_collection[tickets][0][birthday][month]'] = 1;
        $form['ticket_collection[tickets][0][birthday][year]'] = 1980;

        $form['ticket_collection[tickets][1][lastname]'] = 'NOM2';
        $form['ticket_collection[tickets][1][firstname]'] = 'PRENOM2';
        $form['ticket_collection[tickets][1][country]'] = 'FR';
        $form['ticket_collection[tickets][1][birthday][day]'] = 2;
        $form['ticket_collection[tickets][1][birthday][month]'] = 2;
        $form['ticket_collection[tickets][1][birthday][year]'] = 1980;

        $client->submit($form);

        $crawler = $client->getCrawler();

        $this->assertSame(1, $crawler->filter('html:contains("Récapitulatif et paiement.")')->count());
    }

    public function testCommandTunnelTotalVerification()
    {
        $client = static::createClient();
        $client->followRedirects();

        $crawler = $client->request('GET', '/');

        $link = $crawler->selectLink('Cliquez içi pour acheter vos billets!')->link();
        $crawler = $client->click($link);

        $form = $crawler->selectButton('Suivant')->form();
        $form['command[date]'] = '2020-03-03';
        $form['command[number]'] = 2;
        $form['command[email]'] = 'test@test.com';
        $client->submit($form);

        $crawler = $client->getCrawler();

        $form = $crawler->selectButton('Saisir vos billets')->form();
        $form['ticket_collection[tickets][0][lastname]'] = 'NOM1';
        $form['ticket_collection[tickets][0][firstname]'] = 'PRENOM1';
        $form['ticket_collection[tickets][0][country]'] = 'FR';
        $form['ticket_collection[tickets][0][birthday][day]'] = 1;
        $form['ticket_collection[tickets][0][birthday][month]'] = 1;
        $form['ticket_collection[tickets][0][birthday][year]'] = 1980;

        $form['ticket_collection[tickets][1][lastname]'] = 'NOM2';
        $form['ticket_collection[tickets][1][firstname]'] = 'PRENOM2';
        $form['ticket_collection[tickets][1][country]'] = 'FR';
        $form['ticket_collection[tickets][1][birthday][day]'] = 2;
        $form['ticket_collection[tickets][1][birthday][month]'] = 2;
        $form['ticket_collection[tickets][1][birthday][year]'] = 1980;

        $client->submit($form);

        $crawler = $client->getCrawler();

        $this->assertSame(1, $crawler->filter('html:contains("€ 32")')->count());
    }

    public function testCommandTunnelVerificationTarifNormal()
    {
        $client = static::createClient();
        $client->followRedirects();

        $crawler = $client->request('GET', '/');

        $link = $crawler->selectLink('Cliquez içi pour acheter vos billets!')->link();
        $crawler = $client->click($link);

        $form = $crawler->selectButton('Suivant')->form();
        $form['command[date]'] = '2020-03-03';
        $form['command[number]'] = 1;
        $form['command[email]'] = 'test@test.com';
        $client->submit($form);

        $crawler = $client->getCrawler();

        $form = $crawler->selectButton('Saisir vos billets')->form();
        $form['ticket_collection[tickets][0][lastname]'] = 'NOM30ANS';
        $form['ticket_collection[tickets][0][firstname]'] = 'PRENOM30ANS';
        $form['ticket_collection[tickets][0][country]'] = 'FR';
        $form['ticket_collection[tickets][0][birthday][day]'] = 1;
        $form['ticket_collection[tickets][0][birthday][month]'] = 1;
        $form['ticket_collection[tickets][0][birthday][year]'] = 1989;

        $client->submit($form);

        $crawler = $client->getCrawler();

        $this->assertSame(1, $crawler->filter('html:contains("€ 16")')->count());
    }

    public function testCommandTunnelVerificationTarifEnfant()
    {
        $client = static::createClient();
        $client->followRedirects();

        $crawler = $client->request('GET', '/');

        $link = $crawler->selectLink('Cliquez içi pour acheter vos billets!')->link();
        $crawler = $client->click($link);

        $form = $crawler->selectButton('Suivant')->form();
        $form['command[date]'] = '2020-03-03';
        $form['command[number]'] = 1;
        $form['command[email]'] = 'test@test.com';
        $client->submit($form);

        $crawler = $client->getCrawler();

        $form = $crawler->selectButton('Saisir vos billets')->form();
        $form['ticket_collection[tickets][0][lastname]'] = 'NOM6ANS';
        $form['ticket_collection[tickets][0][firstname]'] = 'PRENOM6ANS';
        $form['ticket_collection[tickets][0][country]'] = 'FR';
        $form['ticket_collection[tickets][0][birthday][day]'] = 1;
        $form['ticket_collection[tickets][0][birthday][month]'] = 1;
        $form['ticket_collection[tickets][0][birthday][year]'] = 2013;

        $client->submit($form);

        $crawler = $client->getCrawler();

        $this->assertSame(1, $crawler->filter('html:contains("€ 8")')->count());
    }

    public function testCommandTunnelVerificationTarifSenior()
    {
        $client = static::createClient();
        $client->followRedirects();

        $crawler = $client->request('GET', '/');

        $link = $crawler->selectLink('Cliquez içi pour acheter vos billets!')->link();
        $crawler = $client->click($link);

        $form = $crawler->selectButton('Suivant')->form();
        $form['command[date]'] = '2020-03-03';
        $form['command[number]'] = 1;
        $form['command[email]'] = 'test@test.com';
        $client->submit($form);

        $crawler = $client->getCrawler();

        $form = $crawler->selectButton('Saisir vos billets')->form();
        $form['ticket_collection[tickets][0][lastname]'] = 'NOM70ANS';
        $form['ticket_collection[tickets][0][firstname]'] = 'PRENOM70ANS';
        $form['ticket_collection[tickets][0][country]'] = 'FR';
        $form['ticket_collection[tickets][0][birthday][day]'] = 1;
        $form['ticket_collection[tickets][0][birthday][month]'] = 1;
        $form['ticket_collection[tickets][0][birthday][year]'] = 1949;

        $client->submit($form);

        $crawler = $client->getCrawler();

        $this->assertSame(1, $crawler->filter('html:contains("€ 12")')->count());
    }

    public function testCommandTunnelVerificationTarifBebe()
    {
        $client = static::createClient();
        $client->followRedirects();

        $crawler = $client->request('GET', '/');

        $link = $crawler->selectLink('Cliquez içi pour acheter vos billets!')->link();
        $crawler = $client->click($link);

        $form = $crawler->selectButton('Suivant')->form();
        $form['command[date]'] = '2020-03-03';
        $form['command[number]'] = 1;
        $form['command[email]'] = 'test@test.com';
        $client->submit($form);

        $crawler = $client->getCrawler();

        $form = $crawler->selectButton('Saisir vos billets')->form();
        $form['ticket_collection[tickets][0][lastname]'] = 'NOM1ANS';
        $form['ticket_collection[tickets][0][firstname]'] = 'PRENOM1ANS';
        $form['ticket_collection[tickets][0][country]'] = 'FR';
        $form['ticket_collection[tickets][0][birthday][day]'] = 1;
        $form['ticket_collection[tickets][0][birthday][month]'] = 1;
        $form['ticket_collection[tickets][0][birthday][year]'] = 2018;

        $client->submit($form);

        $crawler = $client->getCrawler();

        $this->assertSame(1, $crawler->filter('html:contains("€ 0")')->count());
    }

    public function testCommandTunnelVerificationTarifReduitl()
    {
        $client = static::createClient();
        $client->followRedirects();

        $crawler = $client->request('GET', '/');

        $link = $crawler->selectLink('Cliquez içi pour acheter vos billets!')->link();
        $crawler = $client->click($link);

        $form = $crawler->selectButton('Suivant')->form();
        $form['command[date]'] = '2020-03-03';
        $form['command[number]'] = 1;
        $form['command[email]'] = 'test@test.com';
        $client->submit($form);

        $crawler = $client->getCrawler();

        $form = $crawler->selectButton('Saisir vos billets')->form();
        $form['ticket_collection[tickets][0][lastname]'] = 'NOM30ANS';
        $form['ticket_collection[tickets][0][firstname]'] = 'PRENOM30ANS';
        $form['ticket_collection[tickets][0][country]'] = 'FR';
        $form['ticket_collection[tickets][0][reduction]']->tick();
        $form['ticket_collection[tickets][0][birthday][day]'] = 1;
        $form['ticket_collection[tickets][0][birthday][month]'] = 1;
        $form['ticket_collection[tickets][0][birthday][year]'] = 1989;

        $client->submit($form);

        $crawler = $client->getCrawler();

        $this->assertSame(1, $crawler->filter('html:contains("€ 10")')->count());
    }

    public function testCommandTunnelPaymentIsUp()
    {
        $client = static::createClient();
        $client->followRedirects();

        $crawler = $client->request('GET', '/');

        $link = $crawler->selectLink('Cliquez içi pour acheter vos billets!')->link();
        $crawler = $client->click($link);

        $form = $crawler->selectButton('Suivant')->form();
        $form['command[date]'] = '2020-03-03';
        $form['command[number]'] = 2;
        $form['command[email]'] = 'test@test.com';
        $client->submit($form);

        $crawler = $client->getCrawler();

        $form = $crawler->selectButton('Saisir vos billets')->form();
        $form['ticket_collection[tickets][0][lastname]'] = 'NOM1';
        $form['ticket_collection[tickets][0][firstname]'] = 'PRENOM1';
        $form['ticket_collection[tickets][0][country]'] = 'FR';
        $form['ticket_collection[tickets][0][birthday][day]'] = 1;
        $form['ticket_collection[tickets][0][birthday][month]'] = 1;
        $form['ticket_collection[tickets][0][birthday][year]'] = 1980;

        $form['ticket_collection[tickets][1][lastname]'] = 'NOM2';
        $form['ticket_collection[tickets][1][firstname]'] = 'PRENOM2';
        $form['ticket_collection[tickets][1][country]'] = 'FR';
        $form['ticket_collection[tickets][1][birthday][day]'] = 2;
        $form['ticket_collection[tickets][1][birthday][month]'] = 2;
        $form['ticket_collection[tickets][1][birthday][year]'] = 1980;

        $client->submit($form);

        $crawler = $client->getCrawler();

        $form = $crawler->selectButton('payment')->form();
        $form['cardName'] = 'Mr Toto';
        $form['cardNumber'] = '4242 4242 4242 4242';
        $form['cardMonth'] = '01/21';
        $form['cardCvc'] = '1234';
        $client->submit($form);

        $crawler = $client->getCrawler();

        $this->assertSame(0, $crawler->filter('html:contains("Votre commande est à présent terminé!")')->count());
    }

}