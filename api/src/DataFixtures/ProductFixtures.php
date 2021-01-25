<?php

namespace App\DataFixtures;

use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use App\DataFixtures\UserFixtures;
use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProductFixtures extends Fixture implements OrderedFixtureInterface
{
    public const PRODUCT_REFERENCE_1 = 'product1';
    public const PRODUCT_REFERENCE_2 = 'product2';
    public const PRODUCT_REFERENCE_3 = 'product3';
    public const PRODUCT_REFERENCE_4 = 'product4';

    public function getOrder(): Int
    {
        return 2;
    }

    public function load(ObjectManager $manager)
    {
        $loremIpsum = "Occaecat commodo sit incididunt ipsum ex deserunt laboris. Nulla velit nulla aliqua ut aliquip ut consectetur excepteur ea. Consequat nisi irure anim labore qui labore aute. Commodo qui ut irure veniam dolor enim enim consectetur velit occaecat cillum officia eu. Occaecat commodo sit incididunt ipsum ex deserunt laboris. Nulla velit nulla aliqua ut aliquip ut consectetur excepteur ea. Consequat nisi irure anim labore qui labore aute. Commodo qui ut irure veniam dolor enim enim consectetur velit occaecat cillum officia eu. Occaecat commodo sit incididunt ipsum ex deserunt laboris. Nulla velit nulla aliqua ut aliquip ut consectetur excepteur ea. Consequat nisi irure anim labore qui labore aute. Commodo qui ut irure veniam dolor enim enim consectetur velit occaecat cillum officia eu.";
        $user2 = $this->getReference(UserFixtures::USER_REFERENCE_2);
        $product1 = new Product("iPhone X 64GB - Space Gray Unlocked", $loremIpsum, 299, "VERIFIED", $user2);
        $product2 = new Product("iPhone SE (2020)", $loremIpsum, 329, "DENIED", $user2);
        $product3 = new Product("Samsung Galaxy S8", $loremIpsum, 149, "VERIFIED", $user2);
        $product4 = new Product("Samsung Galaxy S7", $loremIpsum, 109, "TO REVIEW", $user2);

        $user1 = $this->getReference(UserFixtures::USER_REFERENCE_1);
        $user1->addValidatedProduct($product1);
        $user1->addValidatedProduct($product2);
        $user1->addValidatedProduct($product3);

        $user3 = $this->getReference(UserFixtures::USER_REFERENCE_3);
        $user3->getCart()->addProduct($product3);
        $user3->getCart()->addProduct($product2);

        $manager->persist($product1);
        $manager->persist($product2);
        $manager->persist($product3);
        $manager->persist($product4);

        $manager->flush();

        $this->addReference(self::PRODUCT_REFERENCE_1, $product1);
        $this->addReference(self::PRODUCT_REFERENCE_2, $product2);
        $this->addReference(self::PRODUCT_REFERENCE_3, $product3);
        $this->addReference(self::PRODUCT_REFERENCE_4, $product4);
    }
}
