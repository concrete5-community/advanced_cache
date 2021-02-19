<?php

namespace A3020\AdvancedCache;

use Concrete\Core\Application\ApplicationAwareInterface;
use Concrete\Core\Application\ApplicationAwareTrait;
use Concrete\Core\Page\Page;
use Concrete\Core\Page\Single;

final class Installer implements ApplicationAwareInterface
{
    use ApplicationAwareTrait;

    /**
     * @param \Concrete\Core\Package\Package $pkg
     */
    public function install($pkg)
    {
        $this->dashboardPages($pkg);
    }

    private function dashboardPages($pkg)
    {
        $pages = [
            '/dashboard/system/optimization/advanced_cache' => 'Advanced Cache',
            '/dashboard/system/optimization/advanced_cache/search' => 'Search',
            '/dashboard/system/optimization/advanced_cache/settings' => 'Settings',
        ];

        foreach ($pages as $path => $name) {
            /** @var Page $page */
            $page = Page::getByPath($path);
            if ($page && !$page->isError()) {
                continue;
            }

            $singlePage = Single::add($path, $pkg);
            $singlePage->update([
                'cName' => $name,
            ]);
        }
    }
}
