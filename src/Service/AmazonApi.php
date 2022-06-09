<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class AmazonApi {
    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function fetchAmazonProduct($asin): array
    {
        $response = $this->client->request(
            'GET',
            'https://api.rainforestapi.com/request?api_key=E2E7E96D5A9B44748DC9C4772C258E74&type=product&amazon_domain=amazon.fr&asin='.$asin
        );
        return $response->toArray();
    }
}

