<?php

namespace App\DataFixtures;

use App\Entity\Tag;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();



         foreach (range(0,10) as $bb){
         $tag = new Tag();
         $tag->setName($faker->realText(10));
         $manager->persist($tag);
            }

        $manager->flush();
    }
}
