<?php

namespace App\DataFixtures;

use App\Entity\Actor;
use App\Entity\Category;
use App\Entity\Season;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker;


class SeasonFixtures extends Fixture implements DependentFixtureInterface
{

    public function load(ObjectManager $manager)
    {
        $faker  =  Faker\Factory::create('fr_FR');

        for ($i = 0; $i < 50; $i++) {
            $season = New Season();
            $this->addReference('season_' . $i, $season);
            $season->setNumber($faker->numberBetween(0,49));
            $season->setDescription($faker->text(200));
            $season->setYear($faker->year);
            $season->setProgram($this->getReference('program_' . random_int(0, 5)));
            $manager->persist($season);
        }
        $manager->flush();
    }
    public function getDependencies()
    {
        return [ProgramFixtures::class];

    }

}