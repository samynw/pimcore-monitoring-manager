<?php

namespace Samynw\MonitoringManagerBundle\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Pimcore\Migrations\Migration\AbstractPimcoreMigration;
use Pimcore\Model\User;
use Pimcore\Tool\Authentication;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20210110154226 extends AbstractPimcoreMigration
{
    public function doesSqlMigrations(): bool
    {
        return false;
    }

    /**
     * Create a user for the monitoring calls
     *
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $user = User::getByName('monitoring');
        if ($user instanceof User) {
            $this->writeMessage('There already was a user "monitoring" defined.');
            $this->writeMessage('We assume this user was created manually for the same purpose.');
            $this->writeMessage('Please review the user and permissions and modify if needed.');
            $this->writeMessage('However, if this was an existing user for different purposes, you can create your own user for this bundle');
            return;
        }

        $this->writeMessage('Create user "monitoring"');
        $user = new User();
        $user->setName('monitoring');
        $user->setParentId(0);
        $user->setActive(true);

        // Generate a random password to active the user
        $passwordHash = Authentication::getPasswordHash($user->getUsername(), bin2hex(random_bytes(16)));
        $user->setPassword($passwordHash);

        // Generate a random API key
        $user->setApiKey(bin2hex(random_bytes(64))); // set an API key

        $user->save();
        $this->writeMessage('User "monitoring" was created');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
    }
}
