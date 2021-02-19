<?php

namespace A3020\AdvancedCache;

use Concrete\Core\Application\ApplicationAwareInterface;
use Concrete\Core\Application\ApplicationAwareTrait;

final class AdvancedCacheService implements ApplicationAwareInterface
{
    use ApplicationAwareTrait;

    /**
     * Create or retrieve a cache entry.
     *
     * @param string $handle
     *
     * @return Cacher
     */
    public function make($handle)
    {
        /** @var Cacher $cacher */
        $cacher = $this->app->make(Cacher::class, [
            'settings' => $this->getSettingsByHandle($handle),
        ]);

        return $cacher;
    }

    /**
     * @param string $handle
     *
     * @return bool
     */
    public function invalidate($handle)
    {
        /** @var CacheManager $manager */
        $manager = $this->app->make(CacheManager::class);

        return $manager->clearByCacheHandle($handle);
    }

    /**
     * @param string $handle
     *
     * @return CacheSettings
     */
    private function getSettingsByHandle($handle)
    {
        /** @var CacheSettings $settings */
        $settings = $this->app->make(CacheSettings::class);
        $settings->setHandle($handle);

        return $settings;
    }
}

