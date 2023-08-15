<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $this->loadUsers($manager);
    }

    private function loadUsers(ObjectManager $manager): void
    {
        $adm = new User();
        $adm->setUsername('admin')
            ->setEmail('admin@sf.chat.com')
            ->setPlainPassword('password')
            ->setRoles(['ROLE_ADMIN','ROLE_USER']);
        $manager->persist($adm);

        for ($i=0; $i < 5; $i++) { 
            $user = new User();
            $user->setUsername('user'.$i)
                ->setEmail('email'.$i.'@email.com')
                ->setPlainPassword('password')
                ->setRoles(['ROLE_USER']);

            $manager->persist($user);
        }

        $manager->flush();
    }
}
