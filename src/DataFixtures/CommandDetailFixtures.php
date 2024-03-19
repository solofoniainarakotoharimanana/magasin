<?php

namespace App\DataFixtures;

use App\Entity\CommandDetail;
use App\Entity\Commande;
use App\Entity\Products;
use App\Entity\User;
use App\Repository\CommandeRepository;
use App\Repository\ProductsRepository;
use App\Repository\UserRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class CommandDetailFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(
        private ProductsRepository $productsRepository,
        private CommandeRepository $commandeRepository
        )
    {

    }
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        $commands = $this->commandeRepository->findAll();
        $products = $this->productsRepository->findAll();
        // $product = new Product();
        // $manager->persist($product);
        for ($i=1; $i <= 60; $i++) {
            $commandDetail = new CommandDetail();
            
            $product = $products[mt_rand(1, count($products) - 1)];
            $command = $commands[mt_rand(1, count($commands) - 1)];
            $commandDetail->setProduct($product)
                ->setCommand($command)
                ->setQuantityCmd($faker->numberBetween(1, 100))
                ->setTotalPrice($product->getPrice() * $faker->numberBetween(1, 100))
                ->setCreatedAt((new \DateTimeImmutable()));
           
            
            $manager->persist($commandDetail);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [CommandFixtures::class, ProductFixtures::class];
    }
}
