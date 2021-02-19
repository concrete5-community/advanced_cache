<?php

namespace A3020\AdvancedCache\Rules\Page;

use A3020\AdvancedCache\Rules\BasePageRule;

class FlushOnPageActions extends BasePageRule
{
    /**
     * @return array
     */
    public function getListensTo()
    {
        return [
            'on_page_add',
            'on_page_update',
            'on_page_delete',
            'on_page_move',
            'on_page_duplicate',
            'on_page_move_to_trash',
        ];
    }
}
