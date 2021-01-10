<?php

namespace MonitoringManagerBundle\Migrations;

use Doctrine\DBAL\Migrations\Version;
use Doctrine\DBAL\Schema\Schema;
use Pimcore\Config;
use Pimcore\File;
use Pimcore\Migrations\Migration\AbstractPimcoreMigration;
use Symfony\Component\Yaml\Parser;
use Symfony\Component\Yaml\Yaml;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20210110150237 extends AbstractPimcoreMigration
{
    public function doesSqlMigrations(): bool
    {
        return false;
    }

    /**
     * Enable webservice API in system settings
     *
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->writeMessage('Reading system settings');
        $configFile = Config::locateConfigFile('system.yml');
        $parser = new Parser();
        $config = $parser->parseFile($configFile);

        if (isset($config['pimcore']['webservice']['enabled'])
            && $config['pimcore']['webservice']['enabled'] === true) {
            // Nothing to do here, the API is already enabled
            $this->writeMessage('Webservice API was already enabled');
            return;
        }

        // Enable webservice and store settings
        $this->writeMessage('Enabling webservice API');
        $config['pimcore']['webservice']['enabled'] = true;
        File::put($configFile, Yaml::dump($config, 5));

        $this->writeMessage('Stored system settings');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // Don't disable the webservice API because it could have been enabled for other purposes as well.
    }
}
