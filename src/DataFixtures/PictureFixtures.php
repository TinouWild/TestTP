<?php

namespace App\DataFixtures;

use App\Entity\Picture;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

class PictureFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        for ($j = 1; $j < 10; $j++) {
            $picture = new Picture();
            $picture->setUrl($faker->imageUrl(1200, 300));
            $manager->persist($picture);
            $this->addReference('picture_' . $j, $picture);
        }
        $manager->flush();
    }
}