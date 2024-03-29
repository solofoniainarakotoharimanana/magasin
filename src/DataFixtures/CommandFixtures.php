<?php

namespace App\DataFixtures;

use App\Entity\Commande;
use App\Entity\Products;
use App\Entity\User;
use App\Repository\ProductsRepository;
use App\Repository\UserRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class CommandFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(
        private ProductsRepository $productsRepository,
        private UserRepository $userRepository
        )
    {

    }
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        $users = $this->userRepository->findAll();
        // $product = new Product();
        // $manager->persist($product);
        for ($i=1; $i <= 30; $i++) {
            $number = $faker->randomNumber(4, false);
            
            $user = $users[mt_rand(1, count($users) - 1)];
            $command = new Commande;
            $command->setClient($user)
                ->setCodeCmd(strval($number) . "-" . strtoupper($faker->word) ."-". date("Y"))
                ->setDateCmd((new \DateTimeImmutable()))
                ->setCreatedAt((new \DateTimeImmutable()))
                ->setStatus(array_rand(['CREATED', 'DONE', 'REFUSED']));
            
            $manager->persist($command);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [UserFixtures::class];
    }
}
