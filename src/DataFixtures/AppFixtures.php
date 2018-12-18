<?php

namespace App\DataFixtures;

use App\Entity\Command;
use App\Entity\Ticket;
use DateTime;
use App\Entity\Parameters;
use App\Entity\Price;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        //Implémentation des tarifs de base fournit par le client
        $tarifNormal = new Price();
        $tarifNormal->setLabel("normal");
        $tarifNormal->setMinAge(12);
        $tarifNormal->setMaxAge(null);
        $tarifNormal->setProofNeeded(false);
        $tarifNormal->setPrice(16);
        $manager->persist($tarifNormal);

        $tarifEnfant = new Price();
        $tarifEnfant->setLabel("enfant");
        $tarifEnfant->setMinAge(4);
        $tarifEnfant->setMaxAge(12);
        $tarifEnfant->setProofNeeded(false);
        $tarifEnfant->setPrice(8);
        $manager->persist($tarifEnfant);

        $tarifSenior = new Price();
        $tarifSenior->setLabel("senior");
        $tarifSenior->setMinAge(60);
        $tarifSenior->setMaxAge(null);
        $tarifSenior->setProofNeeded(false);
        $tarifSenior->setPrice(12);
        $manager->persist($tarifSenior);

        $tarifReduit = new Price();
        $tarifReduit->setLabel("reduit");
        $tarifReduit->setMinAge(null);
        $tarifReduit->setMaxAge(null);
        $tarifReduit->setProofNeeded(true);
        $tarifReduit->setPrice(10);
        $manager->persist($tarifSenior);

        //Implémentation des options de bases
        $parameters = new parameters();
        $parameters->setHalfDayTime((new \DateTime())->setTime(14, 00, 00));

        $parameters->setStripeSecretKey(null);
        $parameters->setStripePublicKey(null);
        $parameters->setEmailCommand('test@test.com');
        $parameters->setEmailSupport('support@test.com');
        $parameters->setReservationAllowed(true);
        $parameters->setMaxTicketsPerDay(1000);
        $manager->persist($parameters);
        $manager->flush();

        //Implémentation de 20 commandes dans la base pour tests avec faker
        //https://github.com/fzaninotto/Faker#
        $faker = Factory::create('fr_FR');
        for ($i = 1; $i <= 20; $i++) {
            $command = new Command();
            $command
                ->setDate($faker->dateTimeBetween($startDate = 'now', $endDate = '+5 years'))
                ->setDuration(0)
                ->setCode()
                ->setEmail($faker->safeEmail)
                ->setNumber($faker->numberBetween(1,10));
            $manager->persist($command);

            $nb = $command->getNumber();
            $priceTotal = 0;
            for ($j = 1; $j <= $nb; $j++) {
                $price = $faker->numberBetween(8,16);
                $priceTotal += $price;
                $ticket = new Ticket();
                $ticket
                    ->setFirstname($faker->firstName)
                    ->setLastname($faker->lastName)
                    ->setCountry($faker->country)
                    ->setPrice($price)
                    ->setBirthday($faker->dateTimeThisCentury);
                $command->addTicket($ticket);
            }

            $command->setPrice($priceTotal);
        }
        $manager->flush();
    }
}
