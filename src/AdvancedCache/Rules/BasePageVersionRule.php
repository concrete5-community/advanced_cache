<?php

namespace A3020\AdvancedCache\Rules;

abstract class BasePageVersionRule extends BasePageRule
{
    /**
     * @param \Concrete\Core\Page\Collection\Version\Event $event
     *
     * @return bool
     */
    public function invalidate($event)
    {
        return parent::invalidate($event);
    }
}
