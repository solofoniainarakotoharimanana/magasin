<?php

namespace App\DataFixtures;

use App\Entity\Address;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

//class AddressFixtures 
class AddressFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(
        private UserPasswordHasherInterface $passwordHasher,
        private UserRepository $userRepository)
    {

    }
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        
        $users = $this->userRepository->findAll();
        // $product = new Product();
        // $manager->persist($product);
        for ($i=1; $i <= 20; $i++) {
            $address = new Address;
            $user = $users[mt_rand(1, count($users) -1 )];
            $address->setClient($user)
                ->setCity($faker->city)
                ->setLot($faker->address())
                ->setCreatedAt(new \DateTimeImmutable())
                ->setRoad("");

            $manager->persist($address);
        }
        $manager->flush();
    }
    public function getDependencies()
    {
        return [UserFixtures::class];
    }
}
