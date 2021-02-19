<?php

namespace A3020\AdvancedCache\Listener;

use A3020\AdvancedCache\EventHandlers\User;
use Psr\Log\LoggerInterface;
use Exception;

class BaseUserListener
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var User
     */
    private $handler;

    public function __construct(LoggerInterface $logger, User $handler)
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
