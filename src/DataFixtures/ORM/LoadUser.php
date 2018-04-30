<?php


namespace App\DataFixtures\ORM;


use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class LoadUser extends Fixture
{
    const NAME_M = [
        'Jonas',
        'Petras',
        'Juozas',
        'Antanas',
        'Liudas',
        'Simonas',
        'Povilas',
    ];

    const NAME_F = [
        'Ieva',
        'Saulė',
        'Simona',
        'Kotryna',
        'Kristina',
        'Rūta',
        'Paulina'
    ];

    const SURNAME_M = [
        'Jonaitis',
        'Petraitis',
        'Antanaitis',
        'Patrauskas',
        'Gabrielaitis',
    ];

    const SURNAME_F = [
        'Jonaitienė',
        'Jonaitytė',
        'Petrauskienė',
        'Petraitienė',
        'Petrauskaitė',
        'Petraitytė',
        'Antanaitienė',
        'Antanaitytė',
        'Gabrielaitienė',
        'Gabrielaitytė'
    ];

    const ROLE = [
        'ROLE_USER',
        'ROLE_OWNER'
    ];

    const LETTERS = [
        'ą' => 'a',
        'č' => 'c',
        'ę' => 'e',
        'ė' => 'e',
        'į' => 'i',
        'š' => 's',
        'ų' => 'u',
        'ū' => 'u',
        'ž' => 'z'
    ];




    public function load(ObjectManager $manager)
    {
        $roleCount = [
            'ROLE_USER' => 10,
            'ROLE_OWNER' => 50
        ];

        $roles = [
            'ROLE_USER' => 0,
            'ROLE_OWNER' => 0
        ];


        for ($i = 1; $i <= 61; $i++) {
            $user = new User();
            $user->setPhoneNumber(sprintf('%d',rand(860000000, 869999999)));

            if ($i == 1) {
                $user->setRole('ROLE_ADMIN')
                    ->setEmail('admin@admin.com')
                    ->setName('Admin')
                    ->setSurname('Admin')
                    ->setIsBlocked(false)
                    ->setPlainPassword('admin');
            }
            elseif ($i > 1 && $i <= 51) {
                $user->setRole('ROLE_OWNER');
                if ($i < 26) {
                    $nameid = array_rand(self::NAME_M);
                    $name = self::NAME_M[$nameid];
                    $surnameid = array_rand(self::SURNAME_M);
                    $surname = self::SURNAME_M[$surnameid];
                }
                else {
                    $nameid = array_rand(self::NAME_F);
                    $name = self::NAME_F[$nameid];
                    $surnameid = array_rand(self::SURNAME_F);
                    $surname = self::SURNAME_F[$surnameid];
                }
                    $user->setSurname($surname);
                    $user->setName($name);
                    $email = strtolower($user->getName()) . strtolower($user->getSurname()) . rand(1, 100) . '@email.com';
                    $user->setEmail(strtr($email,self::LETTERS))
                        ->setIsBlocked(false)
                        ->setPlainPassword('password');
            }
            else {
                $user->setRole('ROLE_USER');
                $nameid = array_rand(self::NAME_F);
                $name = self::NAME_F[$nameid];
                $surnameid = array_rand(self::SURNAME_F);
                $surname = self::SURNAME_F[$surnameid];

                $user->setSurname($surname);
                $user->setName($name);
                $email = strtolower($user->getName()) . strtolower($user->getSurname()) . rand(1, 10) . '@email.com';
                $user->setEmail(strtr($email,self::LETTERS))
                    ->setIsBlocked(false)
                    ->setPlainPassword('password');
            }

            if ($user->getRole() == 'ROLE_OWNER') {
                $this->addReference("user$i", $user);
            }
            $manager->persist($user);
        }

        $manager->flush();
    }
}