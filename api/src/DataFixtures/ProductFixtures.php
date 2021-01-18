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
        return 1;
    }

    public function load(ObjectManager $manager)
    {

        $product1 = new Product("iPhone X 64GB - Space Gray Unlocked", 299, 1, "TO SELL");
        $product2 = new Product("iPhone SE (2020)", 329, 11, "TO SELL");
        $product3 = new Product("Samsung Galaxy S8", 149, 3, "TO SELL");
        $product4 = new Product("Samsung Galaxy S7", 109, 5, "TO SELL");

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
