<?php

namespace Concrete\Package\AdvancedCache;

use A3020\AdvancedCache\Installer;
use A3020\AdvancedCache\OnStart;
use Concrete\Core\Package\Package;
use Concrete\Core\Support\Facade\Package as PackageFacade;

final class Controller extends Package
{
    protected $pkgHandle = 'advanced_cache';
    protected $appVersionRequired = '8.3.1';
    protected $pkgVersion = '1.0.0';
    protected $pkgAutoloaderRegistries = [
        'src/AdvancedCache' => '\A3020\AdvancedCache',
    ];

    public function getPackageName()
    {
        return t('Advanced Cache');
    }

    public function getPackageDescription()
    {
        return t('Cache hard-coded blocks and areas in theme files.');
    }

    public function on_start()
    {
        /** @var OnStart $starter */
        $starter = $this->app->make(OnStart::class);
        $starter->boot();
    }

    public function install()
    {
        $pkg = parent::install();

        /** @var Installer $installer */
        $installer = $this->app->make(Installer::class);
        $installer->install($pkg);
    }

    public function upgrade()
    {
        parent::upgrade();

        /** @see \Concrete\Core\Package\PackageService */
        $pkg = PackageFacade::getByHandle($this->pkgHandle);

        /** @var Installer $installer */
        $installer = $this->app->make(Installer::class);
        $installer->install($pkg);
    }
}
