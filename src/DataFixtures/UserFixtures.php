<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $passwordHasher)
    {

    }
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        // $product = new Product();
        // $manager->persist($product);
        for ($i=1; $i <= 10; $i++) {
            $user = new User;
            $user->setEmail($faker->email)
            ->setRoles(['ROLE_USER'])
                ->setPassword($this->passwordHasher->hashPassword($user, 'password'))
                ->setFirstname($faker->firstName)
                ->setLastname($faker->lastName)
                ->setCreatedAt(new \DateTimeImmutable());

            $manager->persist($user);
        }
        $manager->flush();
    }
}
