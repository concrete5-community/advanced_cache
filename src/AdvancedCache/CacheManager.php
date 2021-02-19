<?php

namespace A3020\AdvancedCache;

use Concrete\Core\Cache\Level\ExpensiveCache;
use Concrete\Core\Config\Repository\Repository;

final class CacheManager
{
    // Note, only alphanumeric chars are allowed in the namespace.
    // Try to keep name short to not reach max path error.
    const CACHE_NAMESPACE = 'advcache';

    // This character will be used between parts of the cache identifier.
    const CACHE_NAME_DIVIDER = '/';

    /**
     * @var ExpensiveCache
     */
    private $cache;

    /**
     * @var Repository
     */
    private $config;

    /**
     * @var ConfigWriter
     */
    private $configWriter;

    public function __construct(ExpensiveCache $cache, Repository $config, ConfigWriter $configWriter)
    {
        $this->cache = $cache;
        $this->config = $config;
        $this->configWriter = $configWriter;
        $this->cache->pool->setNamespace(self::CACHE_NAMESPACE);
    }

    /**
     * @param CacheSettings $settings
     * @param callable $closure
     *
     * @return string
     */
    public function cache(CacheSettings $settings, $closure)
    {
        // If the add-on is disabled, the cache is surpassed.
        if ((bool) $this->config->get('advanced_cache::settings.enabled', true) === false) {
            return $closure();
        }

        // Surpass the cache if the 'onlyCacheIf' closure returns false.
        if (!$settings->shouldCache()) {
            return $closure();
        }

        // Make sure we only return a cached version, if:
        //- The config settings exist and haven't changed.
        //- If a cached version is available.
        $handle = $settings->getCacheHandle();
        if ($this->configWriter->exists($settings) && $this->cached($handle)) {
            return $this->get($handle);
        }

        return $this->store($closure(), $settings);
    }

    /**
     * Clear all caches that are managed by Advanced Cache.
     */
    public function clearAll()
    {
        $this->cache->flush();
    }

    /**
     * Clear the cache of a specific cache handle.
     *
     * Note: this is the full cache identifier.
     *
     * @param string $cacheHandle
     *
     * @return bool
     */
    public function clearByCacheHandle($cacheHandle)
    {
        return $this->cache->delete($cacheHandle);
    }

    /**
     * Store the output of something to the cache.
     *
     * @param string $output
     * @param CacheSettings $settings
     *
     * @return string
     */
    private function store($output, CacheSettings $settings)
    {
        // Get the current cache entry.
        $item = $this->cache->getItem($settings->getCacheHandle());

        // Lock this handle, to prevent concurrent writings.
        $item->lock();

        // Make sure the cache entry automatically expires after x-seconds.
        $item->expiresAfter($settings->getExpiresIn());
        $item->set($output);

        $this->cache->save($item);
        $this->configWriter->write($settings);

        return $output;
    }

    /**
     * Return true if a cache entry with this handle exists.
     *
     * @param $handle
     *
     * @return bool
     */
    private function cached($handle)
    {
        $item = $this->cache->getItem($handle);

        return !$item->isMiss();
    }

    /**
     * Get the cache entry for this handle.
     *
     * @param string $handle
     *
     * @return string
     */
    private function get($handle)
    {
        return $this->cache
            ->getItem($handle)
            ->get();
    }
}
