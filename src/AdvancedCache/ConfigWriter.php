<?php

namespace A3020\AdvancedCache;

use Concrete\Core\Config\Repository\Repository;

final class ConfigWriter
{
    /**
     * @var Repository
     */
    private $config;

    public function __construct(Repository $config)
    {
        $this->config = $config;
    }

    /**
     * Writes a cache item to the config.
     *
     * The config is used to store all handles.
     * The handles are used to clear caches in case of certain Events.
     *
     * @param CacheSettings $settings
     * @return bool|null
     */
    public function write(CacheSettings $settings)
    {
        $configKey = 'advanced_cache::settings.items.'.$settings->getHandle();

        $currentConfig = $this->config->get($configKey, []);
        $newConfig = $settings->toConfigArray();

        // If the settings haven't changed,
        // the settings don't need to be re-saved.
        if ($currentConfig === $newConfig) {
            return null;
        }

        return $this->config->save(
            $configKey,
            $newConfig
        );
    }

    /**
     * Return true if the settings exist in the config.
     *
     * @param CacheSettings $settings
     *
     * @return bool
     */
    public function exists(CacheSettings $settings)
    {
        $currentConfig = $this->config->get('advanced_cache::settings.items.'.$settings->getHandle());
        $newConfig = $settings->toConfigArray();

        return $currentConfig === $newConfig;
    }
}
