<?php

namespace App\DataFixtures;

use App\Entity\Actor;
use App\Entity\Category;
use App\Entity\Season;
use App\Entity\Episode;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker;


class EpisodeFixtures extends Fixture implements DependentFixtureInterface
{

    public function load(ObjectManager $manager)
    {
        $faker  =  Faker\Factory::create('fr_FR');

        for ($i = 0; $i < 50; $i++) {
            $episode = New Episode();
            $episode->setNumber($faker->numberBetween(0,49));
            $episode->setTitle($faker->word);
            $episode->setSynopsis($faker->text(200));
            $episode->setSeason($this->getReference('season_' . $faker->numberBetween(0,49)));
            $manager->persist($episode);
        }
        $manager->flush();
    }
    public function getDependencies()
    {
        return [SeasonFixtures::class];

    }

}