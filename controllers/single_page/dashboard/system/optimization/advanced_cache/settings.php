<?php

namespace Concrete\Package\AdvancedCache\Controller\SinglePage\Dashboard\System\Optimization\AdvancedCache;

use Concrete\Core\Config\Repository\Repository;
use Concrete\Core\Http\Request;
use Concrete\Core\Page\Controller\DashboardPageController;
use Concrete\Core\Routing\Redirect;

final class Settings extends DashboardPageController
{
    public function view()
    {
        $config = $this->getConfig();

        $this->set('enabled', (bool) $config->get('advanced_cache::settings.enabled', true));
    }
    
    public function save()
    {
        /** @var Request $request */
        $request = $this->app->make(Request::class);
        $config = $this->getConfig();

        $config->save('advanced_cache::settings.enabled', $request->request->has('enabled'));

        $this->flash('success', t('Your settings have been saved.'));

        return Redirect::to($this->action(''));
    }

    /**
     * @return Repository
     */
    private function getConfig()
    {
        return $this->app->make(Repository::class);
    }
}
