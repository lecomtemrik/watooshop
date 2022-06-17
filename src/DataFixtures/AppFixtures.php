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
        //=========================RANK=========================
        $rank1 = new Rank();
        $rank1->setTitle("Meilleure vente");
        $manager->persist($rank1);

        $rank2 = new Rank();
        $rank2->setTitle("Meilleur prix");
        $manager->persist($rank2);

        $rank3 = new Rank();
        $rank3->setTitle("Meilleure qualité");
        $manager->persist($rank3);

        $rank4 = new Rank();
        $rank4->setTitle("Rapport qualité/prix");
        $manager->persist($rank4);

        //=========================Category=========================
        $category1 = new Category();
        $category1->setTitle("Moniteur");
        $manager->persist($category1);

        $category2 = new Category();
        $category2->setTitle("Clavier");
        $manager->persist($category2);

        $category3 = new Category();
        $category3->setTitle("Souris");
        $manager->persist($category3);

        $category4 = new Category();
        $category4->setTitle("Casque audio");
        $manager->persist($category4);

        $category5 = new Category();
        $category5->setTitle("Ecouteurs");
        $manager->persist($category5);

        $category6 = new Category();
        $category6->setTitle("Ordinateur portable");
        $manager->persist($category6);

        $category7 = new Category();
        $category7->setTitle("Objets connectés");
        $manager->persist($category7);

        $category8 = new Category();
        $category8->setTitle("Mobiles");
        $manager->persist($category8);

        $category9 = new Category();
        $category9->setTitle("Photo & vidéo");
        $manager->persist($category9);

        $category10 = new Category();
        $category10->setTitle("Divertissement");
        $manager->persist($category10);

        //=========================SubCategory=========================
        $subCategoryMoniteurGaming = new SubCategory();
        $subCategoryMoniteurGaming ->setTitle("Moniteur gaming");
        $subCategoryMoniteurGaming ->setCategory($category1);
        $manager->persist($subCategoryMoniteurGaming);

        $subCategoryMoniteurIncurve = new SubCategory();
        $subCategoryMoniteurIncurve ->setTitle("Moniteur incurvé");
        $subCategoryMoniteurIncurve ->setCategory($category1);
        $manager->persist($subCategoryMoniteurIncurve);

        $subCategoryMoniteurBureau = new SubCategory();
        $subCategoryMoniteurBureau ->setTitle("Moniteur bureau");
        $subCategoryMoniteurBureau ->setCategory($category1);
        $manager->persist($subCategoryMoniteurBureau );

        $subCategoryClavierMecanique = new SubCategory();
        $subCategoryClavierMecanique ->setTitle("Clavier mécanique");
        $subCategoryClavierMecanique ->setCategory($category2);
        $manager->persist($subCategoryClavierMecanique);

        $subCategoryClavierSansFilGaming = new SubCategory();
        $subCategoryClavierSansFilGaming ->setTitle("Clavier sans fil gaming");
        $subCategoryClavierSansFilGaming ->setCategory($category2);
        $manager->persist($subCategoryClavierSansFilGaming);

        $subCategoryClavierSansFilBureau = new SubCategory();
        $subCategoryClavierSansFilBureau ->setTitle("Clavier sans fil bureau");
        $subCategoryClavierSansFilBureau ->setCategory($category2);
        $manager->persist($subCategoryClavierSansFilBureau);

        $subCategoryClavierBureau = new SubCategory();
        $subCategoryClavierBureau ->setTitle("Clavier bureau");
        $subCategoryClavierBureau ->setCategory($category2);
        $manager->persist($subCategoryClavierBureau);

        $subCategorySourisGaming = new SubCategory();
        $subCategorySourisGaming ->setTitle("souris gaming");
        $subCategorySourisGaming ->setCategory($category2);
        $manager->persist($subCategorySourisGaming);

        $subCategorySourisBureau = new SubCategory();
        $subCategorySourisBureau ->setTitle("souris bureau");
        $subCategorySourisBureau ->setCategory($category2);
        $manager->persist($subCategorySourisBureau);

        $subCategorySourisansFilGaming = new SubCategory();
        $subCategorySourisansFilGaming ->setTitle("souris sans fil gaming");
        $subCategorySourisansFilGaming ->setCategory($category2);
        $manager->persist($subCategorySourisansFilGaming);

        $subCategorySourisansFilBureau = new SubCategory();
        $subCategorySourisansFilBureau ->setTitle("souris sans fil bureau");
        $subCategorySourisansFilBureau ->setCategory($category2);
        $manager->persist($subCategorySourisansFilBureau);

//        https://www.boulanger.com/

        //=========================Product=========================



        $manager->flush();
    }
}
