<?php

namespace A3020\AdvancedCache\Rules;

interface RuleInterface
{
    /**
     * Return true if the cache should be invalidated.
     *
     * @param \Symfony\Component\EventDispatcher\Event $event
     *
     * @return bool
     */
    public function invalidate($event);

    /**
     * Return a list of events when the rule should be activated.
     *
     * @return array
     */
    public function getListensTo();

    /**
     * @return array
     */
    public function toConfigArray();

    /**
     * @param array $config
     */
    public function fromConfigArray($config);
}
