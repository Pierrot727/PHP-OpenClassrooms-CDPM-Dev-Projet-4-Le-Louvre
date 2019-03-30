<?php

namespace Tests\Manager;


use App\Bridge\StripeBridge;
use App\Entity\Command;
use App\Entity\Price;
use App\Entity\Ticket;
use App\Manager\CommandManager;
use App\Manager\MailerManager;
use App\Repository\PriceRepository;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;
use Doctrine\Common\Persistence\ObjectManager;

class CommandManagerTest extends TestCase
{

    /** @var CommandManager */
    private $commandManager;

    public function setUp()
    {
        $session = new Session(new MockArraySessionStorage());
        /** @var EntityManagerInterface $entityManagerInterface */
        $entityManagerInterface = $this->getMockBuilder(EntityManagerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        /** @var StripeBridge $stripeBridge */
        $stripeBridge = $this->getMockBuilder(StripeBridge::class)
            ->disableOriginalConstructor()
            ->getMock();

        /** @var PriceRepository $priceRepository */
        $priceRepository = $this->getMockBuilder(PriceRepository::class)
            ->disableOriginalConstructor()
            ->getMock();
        $priceRepository
            ->method('adjustPrice')
            ->will($this->returnValue(1000));


        $mailerManager = $this->getMockBuilder(MailerManager::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->commandManager = new CommandManager($session, $entityManagerInterface, $stripeBridge, $priceRepository, $mailerManager);
    }


    public function testPriceGeneratorWithoutTicket()
    {
        $command = new Command();
        $command->setDate(new \DateTime('2019-01-01'));
        $command->setDuration(true);

        $this->commandManager->priceGenerator($command);
        $this->assertEquals($command->getPrice(), null);
    }

    public function testPriceGeneratorWithTicket()
    {
        $command = new Command();
        $command->setDate(new \DateTime('2019-01-01'));
        $command->setDuration(true);
        $ticket = new Ticket();
        $ticket->setBirthday(new \DateTime('1980-01-01'));
        $ticket->setReduction(false);
        $command->addTicket($ticket);

        $this->commandManager->priceGenerator($command);

        $this->assertSame($command->getPrice(), 1000);


    }
}