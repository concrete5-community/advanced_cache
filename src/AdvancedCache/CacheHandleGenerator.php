<?php

namespace A3020\AdvancedCache;

use Concrete\Core\Application\ApplicationAwareInterface;
use Concrete\Core\Application\ApplicationAwareTrait;
use Concrete\Core\Page\Page;
use Concrete\Core\User\User;

final class CacheHandleGenerator implements ApplicationAwareInterface
{
    use ApplicationAwareTrait;

    /**
     * Generate a key for the Stash cache library.
     *
     * Try to keep the length short, to prevent a
     * max path length error on Windows servers.
     *
     * @param CacheSettings $settings
     *
     * @return string
     *
     * @see http://www.stashphp.com/Basics.html#keys
     */
    public function generate(CacheSettings $settings)
    {
        $parts[] = $settings->getHandle();

        if ($settings->getDiffersPerPage()) {
            $parts[] = 'p' . $this->getCurrentPage();
        }

        if ($settings->getDiffersPerUser()) {
            $parts[] = 'u' . $this->getCurrentUser();
        }

        return implode(CacheManager::CACHE_NAME_DIVIDER, $parts);
    }

    /**
     * @return int
     */
    private function getCurrentPage()
    {
        $page = Page::getCurrentPage();

        return ($page && !$page->isError())
            ? (int) $page->getCollectionID()
            : 0;
    }

    /**
     * @return int
     */
    private function getCurrentUser()
    {
        /** @var User $user */
        $user = $this->app->make(User::class);

        return (int) $user->getUserID();
    }
}
