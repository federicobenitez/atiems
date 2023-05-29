<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Factory\UserFactory;
use App\Factory\PrestamoFactory;
use App\Factory\ServicioFactory;
use App\Factory\ReparacionFactory;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

         // Load Users
        UserFactory::createOne();

        PrestamoFactory::createMany(20);
        ReparacionFactory::createMany(50);
        ServicioFactory::createMany(50);

        $manager->flush();

    }
}
