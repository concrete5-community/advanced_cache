<?php

namespace A3020\AdvancedCache\Listener;

use A3020\AdvancedCache\EventHandlers\Page;
use Exception;
use Psr\Log\LoggerInterface;

class BasePageListener
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var Page
     */
    private $handler;

    public function __construct(LoggerInterface $logger, Page $handler)
    {
        $this->logger = $logger;
        $this->handler = $handler;
    }

    /**
     * Base class that listens to all relevant page events.
     *
     * @param string $eventName
     * @param \Concrete\Core\Page\Event $event
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
