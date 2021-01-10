<?php

namespace MonitoringManagerBundle;

use Doctrine\DBAL\Migrations\Version;
use Doctrine\DBAL\Schema\Schema;
use Pimcore\Extension\Bundle\Installer\MigrationInstaller;

class Installer extends MigrationInstaller
{

    public function migrateInstall(Schema $schema, Version $version)
    {
        # see migrations
    }

    public function migrateUninstall(Schema $schema, Version $version)
    {
        # see migrations
    }
}
