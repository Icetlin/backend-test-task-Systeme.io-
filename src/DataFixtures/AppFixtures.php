<?php

namespace App\DataFixtures;

use App\Entity\Coupon;
use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $p1 = new Product('Iphone', 10000); 
        $manager->persist($p1);

        $p2 = new Product('Headphones', 2000);
        $manager->persist($p2);

        $p3 = new Product('Phone Case', 1000);
        $manager->persist($p3);

        // Coupons
        $c1 = new Coupon('D15', Coupon::TYPE_PERCENTAGE, 15); // 15% discount
        $manager->persist($c1);

        $c2 = new Coupon('F5', Coupon::TYPE_FIXED, 500);
        $manager->persist($c2);

        $c3 = new Coupon('F6', Coupon::TYPE_PERCENTAGE, 6);
        $manager->persist($c3);

        $manager->flush();
    }
} 