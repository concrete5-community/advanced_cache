<?php

namespace A3020\AdvancedCache\EventHandlers;

final class Page extends BaseEventHandler
{
    /**
     * Base class that handles all relevant page related events.
     *
     * @param string $eventName
     * @param \Concrete\Core\Page\Event $event (or a sub class of it)
     */
    public function handle($eventName, $event)
    {
        foreach ($this->config->get('advanced_cache::settings.items') as $handle => $item) {
            if (!isset($item['differs_per_page']) || !$item['differs_per_page']) {
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
