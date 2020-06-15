<?php


namespace App\DataFixtures;


use App\Entity\Actor;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use  Faker;

class ActorFixtures extends Fixture implements DependentFixtureInterface
{
    const ACTORS = ['Andrew Lincoln',
        'Norman Reedus',
        'Lauren Cohan',
        'Danai Gurira' ];


    public function load(ObjectManager $manager)
    {
        for ($i = 0 ; $i<50 ;$i++)
        {
            $actor = new Actor();
            $faker = Faker\Factory::create('fr_FR');
            $actor->setName($faker->name);
            $actor->addProgram($this->getReference('program_' . rand(0,3)));
            $manager->persist($actor);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [ProgramFixtures::class];
    }

}