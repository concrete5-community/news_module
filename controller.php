<?php

namespace Concrete\Package\NewsModule;

use A3020\NewsModule\Installer\Installer;
use A3020\NewsModule\Installer\Uninstaller;
use A3020\NewsModule\Provider\ServiceProvider;
use Concrete\Core\Package\Package;

final class Controller extends Package
{
    protected $pkgHandle = 'news_module';
    protected $appVersionRequired = '8.0';
    protected $pkgVersion = '1.0';
    protected $pkgAutoloaderRegistries = [
        'src/NewsModule' => '\A3020\NewsModule',
    ];

    public function getPackageName()
    {
        return t('News Module');
    }

    public function getPackageDescription()
    {
        return t('Quickly set up a news section.');
    }

    public function on_start()
    {
        $provider = $this->app->make(ServiceProvider::class);
        $provider->register();
    }

    /**
     * @return void
     *
     * @throws \Exception
     */
    public function install()
    {
        parent::install();

        /** @var Installer $installer */
        $installer = $this->app->make(Installer::class);
        $installer->install();
    }

    public function uninstall()
    {
        /** @var Uninstaller $installer */
        $installer = $this->app->make(Uninstaller::class);
        $installer->uninstall();

        parent::uninstall();
    }
}
