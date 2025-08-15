<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Psr\Log\LoggerInterface;

class RickMortyApiService
{
    private const BASE_URL = 'https://rickandmortyapi.com/api/character';
    private const CACHE_TTL = 300; // 5 minutes
    
    public function __construct(
        private HttpClientInterface $httpClient,
        private CacheInterface $cache,
        private LoggerInterface $logger
    ) {}

    /**
     * Get a list of characters from the Rick & Morty API
     */
    public function getCharacters(int $page = 1): array
    {
        $cacheKey = "characters_page_{$page}";
        
        try {
            return $this->cache->get($cacheKey, function (ItemInterface $item) use ($page) {
                $item->expiresAfter(self::CACHE_TTL);
                
                $this->logger->info('Fetching characters from API', ['page' => $page]);
                
                $response = $this->httpClient->request('GET', self::BASE_URL, [
                    'query' => ['page' => $page]
                ]);
                
                if ($response->getStatusCode() !== 200) {
                    throw new \Exception("API returned status code: " . $response->getStatusCode());
                }
                
                $data = $response->toArray();
                
                return [
                    'characters' => $data['results'] ?? [],
                    'info' => $data['info'] ?? [],
                ];
            });
        } catch (\Exception $e) {
            $this->logger->error('Failed to fetch characters', [
                'page' => $page,
                'error' => $e->getMessage()
            ]);
            
            return [
                'characters' => [],
                'info' => [],
                'error' => 'Failed to load characters. Please try again later.'
            ];
        }
    }

    /**
     * Get a single character by ID
     */
    public function getCharacter(int $id): ?array
    {
        $cacheKey = "character_{$id}";
        
        try {
            return $this->cache->get($cacheKey, function (ItemInterface $item) use ($id) {
                $item->expiresAfter(self::CACHE_TTL);
                
                $this->logger->info('Fetching character from API', ['id' => $id]);
                
                $response = $this->httpClient->request('GET', self::BASE_URL . "/{$id}");
                
                if ($response->getStatusCode() === 404) {
                    return null; // Character not found
                }
                
                if ($response->getStatusCode() !== 200) {
                    throw new \Exception("API returned status code: " . $response->getStatusCode());
                }
                
                return $response->toArray();
            });
        } catch (\Exception $e) {
            $this->logger->error('Failed to fetch character', [
                'id' => $id,
                'error' => $e->getMessage()
            ]);
            
            return null;
        }
    }

    /**
     * Search characters by name
     */
    public function searchCharacters(string $name): array
    {
        $cacheKey = "search_" . md5($name);
        
        try {
            return $this->cache->get($cacheKey, function (ItemInterface $item) use ($name) {
                $item->expiresAfter(self::CACHE_TTL);
                
                $this->logger->info('Searching characters', ['name' => $name]);
                
                $response = $this->httpClient->request('GET', self::BASE_URL, [
                    'query' => ['name' => $name]
                ]);
                
                if ($response->getStatusCode() === 404) {
                    return ['characters' => [], 'info' => []];
                }
                
                if ($response->getStatusCode() !== 200) {
                    throw new \Exception("API returned status code: " . $response->getStatusCode());
                }
                
                $data = $response->toArray();
                
                return [
                    'characters' => $data['results'] ?? [],
                    'info' => $data['info'] ?? [],
                ];
            });
        } catch (\Exception $e) {
            $this->logger->error('Failed to search characters', [
                'name' => $name,
                'error' => $e->getMessage()
            ]);
            
            return [
                'characters' => [],
                'info' => [],
                'error' => 'Search failed. Please try again later.'
            ];
        }
    }

    /**
     * Get multiple characters by IDs
     */
    public function getMultipleCharacters(array $ids): array
    {
        if (empty($ids)) {
            return [];
        }
        
        $cacheKey = "characters_" . md5(implode(',', $ids));
        
        try {
            return $this->cache->get($cacheKey, function (ItemInterface $item) use ($ids) {
                $item->expiresAfter(self::CACHE_TTL);
                
                $this->logger->info('Fetching multiple characters', ['ids' => $ids]);
                
                $idsString = implode(',', $ids);
                $response = $this->httpClient->request('GET', self::BASE_URL . "/{$idsString}");
                
                if ($response->getStatusCode() !== 200) {
                    throw new \Exception("API returned status code: " . $response->getStatusCode());
                }
                
                $data = $response->toArray();
                
                // API returns single object for one ID, array for multiple
                return is_array($data) && isset($data[0]) ? $data : [$data];
            });
        } catch (\Exception $e) {
            $this->logger->error('Failed to fetch multiple characters', [
                'ids' => $ids,
                'error' => $e->getMessage()
            ]);
            
            return [];
        }
    }

    /**
     * Clear all cached data (useful for development)
     */
    public function clearCache(): void
    {
        $this->cache->clear();
        $this->logger->info('Rick & Morty API cache cleared');
    }
}