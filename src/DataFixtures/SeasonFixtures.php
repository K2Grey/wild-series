<?php

namespace App\DataFixtures;

use App\Entity\Season;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;

class SeasonFixtures extends Fixture implements DependentFixtureInterface
{
    const SEASONS_COUNT = 30;

    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');

        for ($i = 0; $i < self::SEASONS_COUNT; $i++) {
            $season = new Season();
            $season->setNumber($i+1);
            $season->setDescription($faker->paragraph(3));
            $season->setYear($faker->year);
            $season->setProgram($this->getReference('program_' . rand(0, count(ProgramFixtures::PROGRAMS)-1)));
            $manager->persist($season);
            $this->addReference('season_' . $i, $season);
        }
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [ProgramFixtures::class];
    }
}
