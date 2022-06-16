<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\Rank;
use App\Entity\SubCategory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        $rank = new Rank();
        $rank->setTitle("Meilleure vente");
        $rank->setTitle("Meilleur prix");
        $rank->setTitle("Meilleure qualité");
        $rank->setTitle("Rapport qualité/prix");
        $manager->persist($rank);

        $category = new Category();

        $subCategory = new SubCategory();

        $product = new Product();


        $manager->flush();
    }
}
