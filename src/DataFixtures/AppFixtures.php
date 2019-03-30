<?php

namespace App\DataFixtures;

use App\Entity\Command;
use App\Entity\DataCarousel;
use App\Entity\Ticket;
use App\Manager\ParametersManager;
use DateTime;
use App\Entity\Parameters;
use App\Entity\Price;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use phpDocumentor\Reflection\Types\Null_;

class AppFixtures extends Fixture
{

    public function load(ObjectManager $manager)
    {
        //Paramétres d'initialisation - NE PAS ENLEVER
        //Implémentation des tarifs de base fournit par le client
        $tarifNormal = new Price();
        $tarifNormal->setLabel("normal");
        $tarifNormal->setMinAge(12);
        $tarifNormal->setMaxAge(59);
        $tarifNormal->setProofNeeded(false);
        $tarifNormal->setPrice(16);
        $manager->persist($tarifNormal);

        $tarifBebe = new Price();
        $tarifBebe->setLabel("bebe");
        $tarifBebe->setMinAge(0);
        $tarifBebe->setMaxAge(3);
        $tarifBebe->setProofNeeded(false);
        $tarifBebe->setPrice(8);
        $manager->persist($tarifBebe);

        $tarifEnfant = new Price();
        $tarifEnfant->setLabel("enfant");
        $tarifEnfant->setMinAge(4);
        $tarifEnfant->setMaxAge(11);
        $tarifEnfant->setProofNeeded(false);
        $tarifEnfant->setPrice(8);
        $manager->persist($tarifEnfant);

        $tarifSenior = new Price();
        $tarifSenior->setLabel("senior");
        $tarifSenior->setMinAge(60);
        $tarifSenior->setMaxAge(200);
        $tarifSenior->setProofNeeded(false);
        $tarifSenior->setPrice(12);
        $manager->persist($tarifSenior);

        $tarifReduit = new Price();
        $tarifReduit->setLabel("reduit");
        $tarifReduit->setMinAge(12);
        $tarifReduit->setMaxAge(200);
        $tarifReduit->setProofNeeded(true);
        $tarifReduit->setPrice(10);
        $manager->persist($tarifSenior);

        //Implémentation des options de bases
        $parameters = new parameters();
        $parameters->setHalfDayTime((new \DateTime())->setTime(14, 00, 00));

        //Defaut setting de stripes dans /config/services.yaml
        $parameters->setStripeSecretKey(null);
        $parameters->setStripePublicKey(null);
        $parameters->setEmailCommand('test@test.com');
        $parameters->setEmailSupport('support@test.com');
        $parameters->setReservationAllowed(true);
        $parameters->setMaxTicketsPerDay(1000);
        $manager->persist($parameters);
        $manager->flush();

        //Carousel page d'accueil
        $dataCarousel = new DataCarousel();
        $dataCarousel->setType('mainCarousel');
        $dataCarousel->setImage('bigCarousel1.jpg');
        $dataCarousel->setMainDescription('Musée du Louvre');
        $dataCarousel->setSecondaryDescription('A la découverte des origines');
        $dataCarousel->setText(null);
        $dataCarousel->setLink(null);
        $dataCarousel->setDuration(null);

        $dataCarousel = new DataCarousel();
        $dataCarousel->setType('mainCarousel');
        $dataCarousel->setImage('bigCarousel2.jpg');
        $dataCarousel->setMainDescription('Pyramide du Louvre');
        $dataCarousel->setSecondaryDescription('Un miracle moderne');
        $dataCarousel->setText(null);
        $dataCarousel->setLink(null);
        $dataCarousel->setDuration(null);

        $dataCarousel = new DataCarousel();
        $dataCarousel->setType('mainCarousel');
        $dataCarousel->setImage('bigCarousel3.jpg');
        $dataCarousel->setMainDescription('Les galeries à visiter');
        $dataCarousel->setSecondaryDescription('Une architecture adapté');
        $dataCarousel->setText(null);
        $dataCarousel->setLink(null);
        $dataCarousel->setDuration(null);

        $dataCarousel = new DataCarousel();
        $dataCarousel->setType('mainCarousel');
        $dataCarousel->setImage('bigCarousel4.jpg');
        $dataCarousel->setMainDescription('Un spectacle grandiose');
        $dataCarousel->setSecondaryDescription(null);
        $dataCarousel->setText(null);
        $dataCarousel->setLink(null);
        $dataCarousel->setDuration(null);

        //Implémentation de 20 commandes dans la base pour tests avec faker
        //https://github.com/fzaninotto/Faker#
        $faker = Factory::create('fr_FR');
        for ($i = 1; $i <= 20; $i++) {
            $command = new Command();
            $command
                ->setDate($faker->dateTimeBetween($startDate = 'now', $endDate = '+5 years'))
                ->setDuration(0)
                ->generateCode()
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
                    ->setCountry($faker->countryCode)
                    ->setPrice($price)
                    ->setBirthday($faker->dateTimeThisCentury);
                $command->addTicket($ticket);
            }

            $command->setPrice($priceTotal);
        }
        $manager->flush();
    }
}
