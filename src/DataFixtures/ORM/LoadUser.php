<?php

namespace App\DataFixtures\ORM;


use App\Entity\User;
use App\Utils\Utils;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class LoadUser extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $userDataFile = 'public/data/Users.csv';
        $data = Utils::getData($userDataFile);
        foreach ($data as $index=>$line) {
            $user = $this->createUser($line, $index);
            $manager->persist($user);
        }
        $manager->flush();
    }

    /**
     * @param array $userData
     * @param int $index
     * @return User
     */
    private function createUser(array $userData, int $index): User
    {
        $user = new User();
        $user
            ->setEmail($userData[2])
            ->setIsBlocked(false)
            ->setName($userData[0])
            ->setPhoneNumber(sprintf('%d',rand(860000000, 869999999)))
            ->setPlainPassword($userData[4])
            ->setRole($userData[3])
            ->setSurname($userData[1])
        ;

        if ($user->getRole() == 'ROLE_OWNER') {
            $this->addReference("user$index", $user);
        }

        return $user;
    }
}