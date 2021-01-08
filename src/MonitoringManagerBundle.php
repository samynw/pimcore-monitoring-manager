<?php

namespace MonitoringManagerBundle;

use Pimcore\Extension\Bundle\AbstractPimcoreBundle;

class MonitoringManagerBundle extends AbstractPimcoreBundle
{
    public function getJsPaths()
    {
        return [
            '/bundles/monitoringmanager/js/pimcore/startup.js'
        ];
    }
}