<?php

namespace App\DataFixtures;

use DateTime;
use App\Entity\Parameters;
use App\Entity\Price;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        //Implémentation des tarifs de base
        $tarifNormal = new Price();
        $tarifNormal->setLabel("normal");
        $tarifNormal->setMinAge(12);
        $tarifNormal->setMaxAge(null);
        $tarifNormal->setProofNeeded(false);
        $tarifNormal->setPrice(16);
        $manager->persist($tarifNormal);
        $manager->flush();

        $tarifEnfant = new Price();
        $tarifEnfant->setLabel("enfant");
        $tarifEnfant->setMinAge(4);
        $tarifEnfant->setMaxAge(12);
        $tarifEnfant->setProofNeeded(false);
        $tarifEnfant->setPrice(8);
        $manager->persist($tarifEnfant);
        $manager->flush();

        $tarifSenior = new Price();
        $tarifSenior->setLabel("senior");
        $tarifSenior->setMinAge(60);
        $tarifSenior->setMaxAge(null);
        $tarifSenior->setProofNeeded(false);
        $tarifSenior->setPrice(12);
        $manager->persist($tarifSenior);
        $manager->flush();

        $tarifReduit = new Price();
        $tarifReduit->setLabel("reduit");
        $tarifReduit->setMinAge(null);
        $tarifReduit->setMaxAge(null);
        $tarifReduit->setProofNeeded(true);
        $tarifReduit->setPrice(10);
        $manager->persist($tarifSenior);
        $manager->flush();

        //Implémentation des options de bases
        $parameters = new parameters();
        $parameters->setHalfDayTime(DateTime::setTime(14,00,00));
        $parameters->setStripeSecretKey(null);
        $parameters->setStripePublicKey(null);
        $parameters->setEmailCommand('test@test.com');
        $parameters->setEmailSupport('support@test.com');
        $parameters->setReservationAllowed(true);
        $manager->persist($parameters);
        $manager->flush();
    }
}
