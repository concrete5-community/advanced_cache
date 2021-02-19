<?php

namespace A3020\AdvancedCache\EventHandlers;

final class User extends BaseEventHandler
{
    /**
     * Base class that handles all relevant user related events.
     *
     * @param string $eventName
     * @param \Symfony\Component\EventDispatcher\Event $event
     */
    public function handle($eventName, $event)
    {
        foreach ($this->config->get('advanced_cache::settings.items') as $handle => $item) {
            if (!isset($item['differs_per_user']) || !$item['differs_per_user']) {
                continue;
            }

            $this->flushIfNeeded(
                $this->configToSettings->convert($handle, $item),
                $eventName,
                $event
            );
        }
    }
}
