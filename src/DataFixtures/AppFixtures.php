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

        $productMoniteurGaming1 = new Product();
        $productMoniteurGaming1->setTitle('ASUS VG248QG - Ecran PC gaming eSport 24" FHD - Dalle TN - 16:9 - 165Hz - 0,5ms - 1920x1080 - 350cd/m² - Display Port, HDMI et DVI - AMD FreeSync - Nvidia G-Sync - Haut-parleurs, Noir 24" TN 165Hz 0,5ms VG248QG');
        $productMoniteurGaming1->setImage('https://m.media-amazon.com/images/I/91t-OJiZy3S.jpg');
        $productMoniteurGaming1->setDescription('DESCRIPTION DU PRODUIT ASUS VG248QG - Ecran PC gaming eSport 24" FHD - Dalle TN - 16:9 - 165Hz - 0,5ms - 1920x1080 - 350cd/m² - Display Port, HDMI et DVI - AMD FreeSync - Nvidia G-Sync - Haut-parleurs');
        $productMoniteurGaming1->setAlink('empty');
        $productMoniteurGaming1->setRating(4.6);
        $productMoniteurGaming1->setPrice(199.96);
        $productMoniteurGaming1->setAsin('B07RMQ5PHX');
        $productMoniteurGaming1->setSubcategory($subCategoryMoniteurGaming);
        $productMoniteurGaming1->setRank($rank1);
        $manager->persist($productMoniteurGaming1);

        $productMoniteurGaming2 = new Product();
        $productMoniteurGaming2->setTitle('Lenovo G24e-20 Moniteur Gaming 23,8" FullHD avec EyeSafe (1920x1080, VA, 1ms, 120Hz, HDMI+DP, FreeSync Premium, Base en métal, Inclinaison et Hauteur réglables - Iron Grey');
        $productMoniteurGaming2->setImage('https://m.media-amazon.com/images/I/71EryfBtoOL.jpg');
        $productMoniteurGaming2->setDescription('DESCRIPTION DU PRODUIT Lenovo G24e-20 Moniteur Gaming 23,8" FullHD avec EyeSafe (1920x1080, VA, 1ms, 120Hz, HDMI+DP, FreeSync Premium, Base en métal ) Inclinaison et hauteur réglables - Iron Grey');
        $productMoniteurGaming2->setAlink('empty');
        $productMoniteurGaming2->setRating(0);
        $productMoniteurGaming2->setPrice(138.81);
        $productMoniteurGaming2->setAsin('B09SQ7Z4G7');
        $productMoniteurGaming2->setSubcategory($subCategoryMoniteurGaming);
        $productMoniteurGaming2->setRank($rank4);
        $productMoniteurGaming2->persist($productMoniteurGaming1);


        $productMoniteurGaming3 = new Product();
        $productMoniteurGaming3->setTitle('KOORUI Écran PC 22 Pouces Full HD (1920 x 1080), VA, 75Hz, 5ms, Ratio de Contraste de 3000:1, Mode Faible lumière Bleue, Angle de Vision de 178°, VGA et HDMI, Noir 22 Pouces FHD 75HZ');
        $productMoniteurGaming3->setImage('https://m.media-amazon.com/images/I/71cqz1e33oL.jpg');
        $productMoniteurGaming3->setDescription('nodesc');
        $productMoniteurGaming3->setAlink('empty');
        $productMoniteurGaming3->setRating(4.2);
        $productMoniteurGaming3->setPrice(129.99);
        $productMoniteurGaming3->setAsin('B09G9C21MH');
        $productMoniteurGaming3->setSubcategory($subCategoryMoniteurGaming);
        $productMoniteurGaming3->setRank($rank2);
        $manager->persist($productMoniteurGaming3);

        $productMoniteurGaming4 = new Product();
        $productMoniteurGaming4->setTitle('KOORUI Ecran PC Gaming 27 144hz, Dalle IPS, Résolution WQHD (2560 x 1440), 1MS, Display Port & 2X HDMI, FreeSync,Noir 27 Pouces 144hz QHD');
        $productMoniteurGaming4->setImage('https://m.media-amazon.com/images/I/61cqCpXZJZL.jpg');
        $productMoniteurGaming4->setDescription('nodesc');
        $productMoniteurGaming4->setAlink('empty');
        $productMoniteurGaming4->setRating(4.3);
        $productMoniteurGaming4->setPrice(259.99);
        $productMoniteurGaming4->setAsin('B09G6TJ3BW');
        $productMoniteurGaming4->setSubcategory($subCategoryMoniteurGaming);
        $productMoniteurGaming4->setRank($rank3);
        $manager->persist($productMoniteurGaming4);

//        $product1 = new Product();
//        $product1->setTitle();
//        $product1->setImage();
//        $product1->setDescription();
//        $product1->setAlink();
//        $product1->setRating();
//        $product1->setPrice();
//        $product1->setAsin();
//        $product1->setSubcategory();
//        $product1->setRank();
//        $manager->persist($productMoniteurGaming1);



        $manager->flush();
    }
}
