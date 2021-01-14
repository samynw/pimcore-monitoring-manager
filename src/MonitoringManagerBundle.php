<?php

namespace Samynw\MonitoringManagerBundle;

use Pimcore\Extension\Bundle\AbstractPimcoreBundle;
use Pimcore\Extension\Bundle\Installer\InstallerInterface;
use Pimcore\Extension\Bundle\Traits\PackageVersionTrait;

class MonitoringManagerBundle extends AbstractPimcoreBundle
{
    use PackageVersionTrait;

    public function getNiceName()
    {
        return 'Monitoring Manager';
    }

    /**
     * {@inheritdoc}
     */
    protected function getComposerPackageName(): string
    {
        return 'samynw/pimcore-monitoring-manager';
    }

    /**
     * Return the bundle installer
     *
     * @return InstallerInterface
     */
    public function getInstaller()
    {
        return $this->container->get(Installer::class);
    }

    /**
     * @return string[]
     */
    public function getJsPaths()
    {
        return [
            '/bundles/monitoringmanager/js/pimcore/startup.js'
        ];
    }
}
