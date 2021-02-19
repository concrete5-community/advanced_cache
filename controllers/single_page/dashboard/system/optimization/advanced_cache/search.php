<?php

namespace Concrete\Package\AdvancedCache\Controller\SinglePage\Dashboard\System\Optimization\AdvancedCache;

use A3020\AdvancedCache\CacheManager;
use Concrete\Core\Config\Repository\Repository;
use Concrete\Core\Page\Controller\DashboardPageController;
use Concrete\Core\Routing\Redirect;

final class Search extends DashboardPageController
{
    public function view()
    {
        $this->set('pageTitle', t('Search Cache Entries'));
        $this->set('items', $this->getItems());
    }
    
    public function flushAll()
    {
        $this->getCacheManager()->clearAll();

        $this->flash('success', t('The cache of all items has been flushed.'));

        return Redirect::to($this->action(''));
    }

    public function removeAll()
    {
        $this->getCacheManager()->clearAll();
        $this->getConfig()->save('advanced_cache::settings.items', []);

        $this->flash('success', t('All items have been removed successfully.'));

        return Redirect::to($this->action(''));
    }

    public function flush($handle = null)
    {
        $this->getCacheManager()->clearByCacheHandle($handle);

        $this->flash('success', t('The cache for this item has been flushed.'));

        return Redirect::to($this->action(''));
    }
    
    public function remove($handle = null)
    {
        $this->getCacheManager()->clearByCacheHandle($handle);

        $items = $this->getItems();
        if (isset($items[$handle])) {
            unset($items[$handle]);

            $this->getConfig()->save('advanced_cache::settings.items', $items);

            $this->flash('success', t('The item with handle %s has been removed.', h($handle)));
        }

        return Redirect::to($this->action(''));
    }

    /**
     * @return array
     */
    private function getItems()
    {
        return $this->getConfig()->get('advanced_cache::settings.items', []);
    }

    /**
     * @return Repository
     */
    private function getConfig()
    {
        return $this->app->make(Repository::class);
    }

    /**
     * @return CacheManager
     */
    private function getCacheManager()
    {
        return $this->app->make(CacheManager::class);
    }
}
