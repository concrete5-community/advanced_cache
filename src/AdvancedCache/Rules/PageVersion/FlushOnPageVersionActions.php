<?php

namespace A3020\AdvancedCache\Rules\PageVersion;

use A3020\AdvancedCache\Rules\BasePageRule;

class FlushOnPageVersionActions extends BasePageRule
{
    /**
     * @return array
     */
    public function getListensTo()
    {
        return [
            'on_page_version_add',
            'on_page_version_approve',
        ];
    }
}
