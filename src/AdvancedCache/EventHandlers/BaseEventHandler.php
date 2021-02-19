<?php

namespace A3020\AdvancedCache\EventHandlers;

use A3020\AdvancedCache\CacheManager;
use A3020\AdvancedCache\CacheSettings;
use A3020\AdvancedCache\ConfigToSettings;
use Concrete\Core\Application\ApplicationAwareInterface;
use Concrete\Core\Application\ApplicationAwareTrait;
use Concrete\Core\Config\Repository\Repository;

abstract class BaseEventHandler implements ApplicationAwareInterface
{
    use ApplicationAwareTrait;

    /**
     * @var Repository
     */
    protected $config;

    /**
     * @var CacheManager
     */
    protected $cacheManager;

    /**
     * @var ConfigToSettings
     */
    protected $configToSettings;

    public function __construct(Repository $config, CacheManager $cacheManager, ConfigToSettings $configToSettings)
    {
        $this->config = $config;
        $this->cacheManager = $cacheManager;
        $this->configToSettings = $configToSettings;
    }

    /**
     * Return true if the cache for this item has been flushed.
     *
     * @param CacheSettings $settings
     * @param string $eventName
     * @param \Symfony\Component\EventDispatcher\Event $event
     *
     * @return bool
     */
    protected function flushIfNeeded(CacheSettings $settings, $eventName, $event)
    {
        foreach ($settings->getFlushRules() as $flushRule) {
            if (!in_array($eventName, $flushRule->getListensTo())) {
                // The current flush rule doesn't listen to this event.
                continue;
            }

            if ($flushRule->invalidate($event)) {
                $this->cacheManager->clearByCacheHandle($settings->getCacheHandle());

                return true;
            }
        }

        return false;
    }
}
