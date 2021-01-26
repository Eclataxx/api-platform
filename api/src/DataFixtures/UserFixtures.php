<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\DataFixtures\ProductFixtures;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture implements OrderedFixtureInterface
{
    public const USER_REFERENCE_1 = 'user1';
    public const USER_REFERENCE_2 = 'user2';
    public const USER_REFERENCE_3 = 'user3';
    public const USER_REFERENCE_4 = 'user4';

    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function getOrder(): Int
    {
        return 1;
    }

    public function load(ObjectManager $manager)
    {
        $user_1 = new User('Tom', 'tom@gmail.com', 'tom');
        $user_2 = new User('Thomas', 'thomas@gmail.com', 'thomas');
        $user_3 = new User('Pierre', 'pierre@gmail.com', 'pierre');
        $user_4 = new User('Basile', 'basile@gmail.com', 'basile');

        $user_1->addRole('ROLE_ADMIN');
        $user_2->addRole('ROLE_SELLER');

        $manager->persist($user_1);
        $manager->persist($user_2);
        $manager->persist($user_3);
        $manager->persist($user_4);
        $manager->flush();

        $this->addReference(self::USER_REFERENCE_1, $user_1);
        $this->addReference(self::USER_REFERENCE_2, $user_2);
        $this->addReference(self::USER_REFERENCE_3, $user_3);
        $this->addReference(self::USER_REFERENCE_4, $user_4);
    }
}
