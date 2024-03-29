<?php

namespace App\DataFixtures;

use App\Entity\Products;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ProductFixtures extends Fixture
{
    public function __construct()
    {

    }
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        // $product = new Product();
        // $manager->persist($product);
        for ($i=1; $i <= 30; $i++) {
            $product = new Products;
            $product->setDescription($faker->text())
                ->setPrice($faker->randomFloat($min = 1, $max = 500))
                ->setTitle($faker->words(4, true))
                ->setIsActive(mt_rand(0, 1))
                ->setCreatedAt((new \DateTimeImmutable()))
                ->setImage($faker->imageUrl(640, 480, 'box', true))
                ;

            $manager->persist($product);
        }
        $manager->flush();
    }
}
