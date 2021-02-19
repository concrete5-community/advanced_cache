<?php

namespace A3020\AdvancedCache\Rules;

abstract class BaseUserRule implements RuleInterface
{
    /**
     * @param \Symfony\Component\EventDispatcher\Event $event
     *
     * @inheritdoc
     */
    public function invalidate($event)
    {
        return true;
    }

    /**
     * @inheritdoc
     */
    public function toConfigArray()
    {
        return [
            'fqn' => get_called_class(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function fromConfigArray($config)
    {
        // Not relevant (yet).
    }
}
