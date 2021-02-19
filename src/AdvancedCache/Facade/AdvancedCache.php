<?php

namespace A3020\AdvancedCache\Facade;

use A3020\AdvancedCache\AdvancedCacheService;
use Concrete\Core\Support\Facade\Facade;

final class AdvancedCache extends Facade
{
    public static function getFacadeAccessor()
    {
        return AdvancedCacheService::class;
    }
}
