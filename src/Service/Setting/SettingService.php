<?php

namespace App\Service\Setting;

use App\Dto\Setting\Setting;
use App\Repository\Option\OptionRepository;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Cache\InvalidArgumentException;

class SettingService
{
    private const CACHE_KEY = 'app_setting';
    private const CACHE_TTL = 86400; // 24 hour

    public function __construct(
        private readonly OptionRepository       $optionRepository,
        private readonly CacheItemPoolInterface $cache
    )
    {}

    /**
     * @throws InvalidArgumentException
     */
    public function getSetting(): Setting
    {
        $cacheItem = $this->cache->getItem(self::CACHE_KEY);

        if ($cacheItem->isHit()) {
            return $cacheItem->get();
        }

        $setting = new Setting($this->optionRepository->findAll());

        $cacheItem->set($setting);
        $cacheItem->expiresAfter(self::CACHE_TTL);
        $this->cache->save($cacheItem);

        return $setting;
    }

    /**
     * @throws InvalidArgumentException
     */
    public function refreshCache(): void
    {
        $this->cache->deleteItem(self::CACHE_KEY);
        $this->getSetting();
    }
}
