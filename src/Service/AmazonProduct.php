<?php

namespace App\Service;

use App\Entity\Attribute;
use App\Entity\Review;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class AmazonProduct {
    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @param $asin
     * @return array
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    //Retourne le produit amazon grâce à l'asin passé en paramètre
    public function getProduct($asin): array
    {
        $response = $this->client->request(
            'GET',
            'https://api.rainforestapi.com/request?api_key=E2E7E96D5A9B44748DC9C4772C258E74&type=product&amazon_domain=amazon.fr&asin='.$asin
        );
        return $response->toArray();
    }

    /**
     * @param $product
     * @param $entityManager
     * @param $asin
     * @param $subCat
     * @param $rank
     * @param $pathProduct
     * @param $aLink
     * @return void
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    //Enregistre le produit dans la base de donée
    //asin = numero de produit / aLink = lien d'affiliation / pathProduct = nom pour url du produit
    public function saveProduct($product, $entityManager, $asin, $subCat, $rank, $pathProduct, $aLink){
        $ApiProduct = $this->getProduct($asin);
        $product->setTitle($ApiProduct['product']['title']);
        $product->setAsin($asin);
        $product->setPathProduct($pathProduct);
        $product->setBrand($ApiProduct['product']['brand']);
        if (isset($ApiProduct['product']['rating'])){
            $product->setRating($ApiProduct['product']['rating']);
        }else {
            //Met à zero si il n'existe pas dans le resultat de getProduct
            $product->setRating(0);
        }
        if (isset($ApiProduct['product']['buybox_winner']['price']['value'])){
            $product->setPrice($ApiProduct['product']['buybox_winner']['price']['value']);
        }else {
            $product->setPrice(0);
        }
        $product->setAlink($aLink);
        if (isset($ApiProduct['product']['description'])){
            $product->setDescription($ApiProduct['product']['description']);
        }else {
            $product->setDescription('nodesc');
        }
        if (isset($ApiProduct['product']['ratings_total'])){
            $product->setRatingTotal($ApiProduct['product']['ratings_total']);
        }else {
            $product->setRatingTotal(0);
        }
        if (isset($ApiProduct['product']['reviews_total'])){
            $product->setReviewTotal($ApiProduct['product']['reviews_total']);
        }else {
            $product->setReviewTotal(0);
        }
        $product->setImage($ApiProduct['product']['main_image']['link']);
        $product->setSubcategory($subCat);
        $product->setRank($rank);

        //Persist les attributes s'ils existent
        if (isset($ApiProduct['product']['attributes'])){

            //Récupère tout les attributs du produit
            $attrNb = count($ApiProduct['product']['attributes']) - 1;
            for($i=0; $i <= $attrNb; $i++) {
                ${'attributes' . $i} = new Attribute();
                ${'attributes' . $i}->setProduct($product);
                ${'attributes' . $i}->setName($ApiProduct['product']['attributes'][$i]['name']);
                ${'attributes' . $i}->setValue($ApiProduct['product']['attributes'][$i]['value']);
                ${'attributes' . $i}->setState(1);
                $entityManager->persist(${'attributes' . $i});
            }
        }

        //Persist les avis s'ils existent
        if (isset($ApiProduct['product']['top_reviews'])){

            //Récupère tout les avis du produit
            $reviewsNb = count($ApiProduct['product']['top_reviews']) - 1;
            for($i=0; $i <= $reviewsNb; $i++) {
                ${'reviews' . $i} = new Review();
                ${'reviews' . $i}->setProduct($product);
                ${'reviews' . $i}->setTitle($ApiProduct['product']['top_reviews'][$i]['title']);
                ${'reviews' . $i}->setBody($ApiProduct['product']['top_reviews'][$i]['body']);
                ${'reviews' . $i}->setRating($ApiProduct['product']['top_reviews'][$i]['rating']);
                ${'reviews' . $i}->setDate($ApiProduct['product']['top_reviews'][$i]['date']['utc']);
                ${'reviews' . $i}->setProfileName($ApiProduct['product']['top_reviews'][$i]['profile']['name']);
                if (isset($ApiProduct['product']['top_reviews'][$i]['profile']['image'])){
                    ${'reviews' . $i}->setProfilePicture($ApiProduct['product']['top_reviews'][$i]['profile']['image']);
                }
                ${'reviews' . $i}->setCountry($ApiProduct['product']['top_reviews'][$i]['review_country']);
                $entityManager->persist(${'reviews' . $i});

            }
        }


        $entityManager->persist($product);
        $entityManager->flush();

    }
}

