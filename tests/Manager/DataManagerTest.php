<?php

namespace Tests\Manager;

use App\Entity\Data;
use App\Entity\DataCarousel;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;



class DataManagerTest extends KernelTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager;

    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    public function testMainCarouselUp()
    {
        $text = $this->entityManager
            ->getRepository(DataCarousel::class)
            ->findBy(['type' => 'mainCarousel']);

        $this->assertCount(4, $text);
    }

    public function testfeedBackUp()
    {
        $text = $this->entityManager
            ->getRepository(DataCarousel::class)
            ->findBy(['type' => 'feedback']);

        $this->assertCount(4, $text);
    }

    public function testIndexIntro()
    {
        $text = $this->entityManager
            ->getRepository(Data::class)
            ->findBy(['position' => 'indexIntro']);

        $this->assertCount(1, $text);
    }

    public function testIndexPart1()
    {
        $text = $this->entityManager
            ->getRepository(Data::class)
            ->findBy(['position' => 'indexPart1']);

        $this->assertCount(1, $text);
    }

    public function testIndexPart2()
    {
        $text = $this->entityManager
            ->getRepository(Data::class)
            ->findBy(['position' => 'indexPart2']);

        $this->assertCount(1, $text);
    }

    public function testIndexPart3()
    {
        $text = $this->entityManager
            ->getRepository(Data::class)
            ->findBy(['position' => 'indexPart3']);

        $this->assertCount(1, $text);
    }

    /**
     * {@inheritDoc}
     */
    protected function tearDown()
    {
        parent::tearDown();

        $this->entityManager->close();
        $this->entityManager = null; // avoid memory leaks
    }
}