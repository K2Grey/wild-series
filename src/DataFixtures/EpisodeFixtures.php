<?php

namespace App\DataFixtures;

use App\Entity\Episode;
use App\Service\Slugify;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;

class EpisodeFixtures extends Fixture implements DependentFixtureInterface
{
    const EPISODES_COUNT = 300;

    private $slugify;

    public function __construct(Slugify $slugify)
    {
        $this->slugify = $slugify;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');

        for ($i = 0; $i < self::EPISODES_COUNT; $i++) {
            $episode = new Episode();
            $episode->setNumber($i+1)
                ->setTitle($faker->sentence)
                ->setSynopsis($faker->text)
                ->setSlug($this->slugify->generate($episode->getTitle()))
                ->setSeason($this->getReference('season_' . rand(0, SeasonFixtures::SEASONS_COUNT-1)));
            $manager->persist($episode);
        }
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [SeasonFixtures::class];
    }
}
