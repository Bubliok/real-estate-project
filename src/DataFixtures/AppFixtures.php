<?php

namespace App\DataFixtures;

use App\Entity\City;
use App\Factory\CityFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        CityFactory::createOne(['cityName' => 'Sofia', 'id' => 1]);
        CityFactory::createOne(['cityName' => 'Plovdiv']);
        CityFactory::createOne(['cityName' => 'Varna']);
        CityFactory::createOne(['cityName' => 'Burgas']);
        CityFactory::createOne(['cityName' => 'Ruse']);
        CityFactory::createMany(20);
    }
}
