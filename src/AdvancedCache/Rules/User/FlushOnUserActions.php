<?php

namespace A3020\AdvancedCache\Rules\PageVersion;

use A3020\AdvancedCache\Rules\BasePageRule;

class FlushOnUserActions extends BasePageRule
{
    /**
     * @return array
     */
    public function getListensTo()
    {
        return [
            'on_user_add',
            'on_user_update',
            'on_user_delete',
        ];
    }
}
