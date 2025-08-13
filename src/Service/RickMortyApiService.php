<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\Cache\CacheInterface;

class RickMortyApiService
{
    private const BASE_URL = 'https://rickandmortyapi.com/api/character';
    
    public function __construct(
        private HttpClientInterface $httpClient,
        private CacheInterface $cache
    ) {}

    // Add your methods here
}
