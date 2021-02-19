<?php

namespace A3020\AdvancedCache;

use A3020\AdvancedCache\Listener\BasePageListener;
use A3020\AdvancedCache\Listener\BaseUserListener;
use Concrete\Core\Application\ApplicationAwareInterface;
use Concrete\Core\Application\ApplicationAwareTrait;
use Concrete\Core\Config\Repository\Repository;
use Concrete\Core\Foundation\ClassAliasList;

final class OnStart implements ApplicationAwareInterface
{
    use ApplicationAwareTrait;

    public function boot()
    {
        // Register the facade.
        $list = ClassAliasList::getInstance();
        $list->register(
            'AdvancedCache',
            \A3020\AdvancedCache\Facade\AdvancedCache::class
        );

        $this->registerListeners();
    }

    private function registerListeners()
    {
        // Only hook into the events if the add-on is enabled.
        if ((bool) $this->getConfig()->get('advanced_cache::settings.enabled', true) === false) {
            return;
        }

        $pageListener = $this->app->make(BasePageListener::class);
        $userListener = $this->app->make(BaseUserListener::class);

        $events = [
            // Page events
            'on_page_add' => $pageListener,
            'on_page_update' => $pageListener,
            'on_page_delete' => $pageListener,
            'on_page_move' => $pageListener,
            'on_page_duplicate' => $pageListener,
            'on_page_move_to_trash' => $pageListener,

            // Page Version events
            'on_page_version_approve' => $pageListener,

            // User events
            'on_user_add' => $userListener,
            'on_user_update' => $userListener,
            'on_user_delete' => $userListener,
        ];

        foreach ($events as $eventName => $listener) {
            $this->app['director']->addListener($eventName, function($event) use ($eventName, $listener) {
                $listener->handle($eventName, $event);
            });
        }
    }

    /**
     * @return Repository
     */
    private function getConfig()
    {
        return $this->app->make(Repository::class);
    }
}
