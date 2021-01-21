<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Order;
use App\DataFixtures\UserFixtures;
use App\DataFixtures\ProductFixtures;

class OrderFixtures extends Fixture implements OrderedFixtureInterface
{
    public function getOrder(): int
    {
        return 3;
    }

    public function load(ObjectManager $manager)
    {
        $order1 = new Order(new \DateTime());
        $order1->setAssociatedUser($this->getReference(UserFixtures::USER_REFERENCE_3));
        $order1->addProduct($this->getReference(ProductFixtures::PRODUCT_REFERENCE_1));
        $order1->addProduct($this->getReference(ProductFixtures::PRODUCT_REFERENCE_3));

        $order2 = new Order(new \DateTime());
        $order2->setAssociatedUser($this->getReference(UserFixtures::USER_REFERENCE_3));
        $order2->addProduct($this->getReference(ProductFixtures::PRODUCT_REFERENCE_3));
        $order2->addProduct($this->getReference(ProductFixtures::PRODUCT_REFERENCE_4));
        $order2->addProduct($this->getReference(ProductFixtures::PRODUCT_REFERENCE_1));

        $manager->persist($order1);
        $manager->persist($order2);

        $manager->flush();
    }
}
