<?php

namespace Tests\Manager;

use App\Entity\Parameters;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;



class ParametersManagerTest extends KernelTestCase
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

    public function testParametersUp()
    {
        $data = $this->entityManager
            ->getRepository(Parameters::class)
            ->findOneBy([]);

        $this->assertNotEmpty($data);
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