<?php

namespace Concrete\Package\AdvancedCache\Controller\SinglePage\Dashboard\System\Optimization;

use Concrete\Core\Page\Controller\DashboardPageController;
use Concrete\Core\Routing\Redirect;

final class AdvancedCache extends DashboardPageController
{
    public function view()
    {
        return Redirect::to($this->action('search'));
    }
}
