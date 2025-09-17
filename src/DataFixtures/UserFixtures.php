<?php

namespace App\DataFixtures;

use App\Entity\User\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public function __construct(private readonly UserPasswordHasherInterface $passwordHasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setUsername('superadmin');
        $user->setName('Super Admin');
        $user->setEmail('superadmin@gmail.com');
        $user->setPhone('+1234567890');
        $user->setRoles(['ROLE_SUPERADMIN', 'ROLE_ADMIN']);

        $user->setPassword($this->passwordHasher->hashPassword($user, 'password'));

        $manager->persist($user);
        $manager->flush();
    }
}
