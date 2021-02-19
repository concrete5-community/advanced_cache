<?php

namespace A3020\AdvancedCache\Listener;

use A3020\AdvancedCache\EventHandlers\User;
use Concrete\Core\Logging\Logger;
use Exception;

class BaseUserListener
{
    /**
     * @var Logger
     */
    private $logger;

    /**
     * @var User
     */
    private $handler;

    public function __construct(Logger $logger, User $handler)
    {
        $this->logger = $logger;
        $this->handler = $handler;
    }

    /**
     * Base class that listens to all relevant user events.
     *
     * @param string $eventName
     * @param \Symfony\Component\EventDispatcher\Event $event
     */
    public function handle($eventName, $event)
    {
        try {
            $this->handler->handle($eventName, $event);
        } catch (Exception $e) {
            $this->logger->addDebug($e->getMessage());
        }
    }
}
