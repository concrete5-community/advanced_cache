<?php

namespace A3020\AdvancedCache;

final class Cacher
{
    /**
     * @var CacheManager
     */
    private $manager;

    /**
     * @var CacheSettings
     */
    private $settings;

    public function __construct(CacheManager $manager, CacheSettings $settings)
    {
        $this->manager = $manager;
        $this->settings = $settings;
    }

    /**
     * Cache and render the output of a block.
     *
     * @param \Concrete\Core\Entity\Block\BlockType\BlockType $bt
     * @param string $template
     *
     * @return string
     */
    public function renderBlock($bt, $template = 'view')
    {
        return $this->manager->cache(
            $this->settings,
            function() use ($bt, $template) {
                ob_start();
                $bt->render($template);
                return ob_get_clean();
            }
        );
    }

    /**
     * Cache and render the output of an area.
     *
     * Use wisely! If users edit the area, you may have to flush
     * its cache manually. I tend to use this method only for
     * areas that users cannot modify, e.g. GlobalAreas.
     *
     * If an Area contains an autonav block with a menu, for example,
     * and your website allows users to log in, the block output is normally not cached.
     * Using this method, you are able to cache its output though, which has
     * a huge impact on performance.
     *
     * @param \Concrete\Core\Area\Area $area
     *
     * @return string
     */
    public function renderArea($area)
    {
        return $this->manager->cache(
            $this->settings,
            function() use ($area) {
                ob_start();
                $area->display();
                return ob_get_clean();
            }
        );
    }

    /**
     * @return CacheSettings
     */
    public function settings()
    {
        return $this->settings;
    }
}
