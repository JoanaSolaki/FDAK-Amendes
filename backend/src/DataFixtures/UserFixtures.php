<?php

namespace App\DataFixtures;

use App\DataFixtures\AbstractFixtures;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends AbstractFixtures {
    public function load(ObjectManager $objectManager) {
        $adminUser = new User();
        $adminUser->setEmail('admin@admin.com');
        $adminUser->setRoles(['ROLE_ADMIN']);
        $adminUser->setPassword($this->passwordHasher->hashPassword($adminUser, '123456'));
        $adminUser->setName('admin');
        $adminUser->setSurname('admin');
        $adminUser->setAdress('unknown');
        $adminUser->setPhone("0000000000");

        $objectManager->persist($adminUser);

        for ($i = 0; $i < 10; $i++) {
            $user = new User();
            $user->setEmail($this->faker->email());
            $user->setPassword($this->passwordHasher->hashPassword($user, $this->faker->randomNumber(5, true)));
            $user->setRoles([]);
            $user->setName($this->faker->firstName());
            $user->setSurname($this->faker->lastName());
            $user->setAdress($this->faker->address());
            $user->setPhone($this->faker->phoneNumber());

            $this->setReference('user_' . $i, $user);

            $objectManager->persist($user);
        }

        $objectManager->flush();
    }
}