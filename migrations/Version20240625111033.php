<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240625111033 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_user_networks ALTER user_id TYPE UUID');
        $this->addSql('COMMENT ON COLUMN user_user_networks.user_id IS NULL');
        $this->addSql('ALTER TABLE user_users ADD name_first_name VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE user_users ADD name_last_name VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE user_users DROP name_first');
        $this->addSql('ALTER TABLE user_users DROP name_last');
        $this->addSql('ALTER TABLE user_users ALTER id TYPE UUID');
        $this->addSql('ALTER TABLE user_users ALTER email TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE user_users ALTER new_email TYPE VARCHAR(255)');
        $this->addSql('COMMENT ON COLUMN user_users.id IS NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE user_user_networks ALTER user_id TYPE UUID');
        $this->addSql('COMMENT ON COLUMN user_user_networks.user_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE user_users ADD name_first VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE user_users ADD name_last VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE user_users DROP name_first_name');
        $this->addSql('ALTER TABLE user_users DROP name_last_name');
        $this->addSql('ALTER TABLE user_users ALTER id TYPE UUID');
        $this->addSql('ALTER TABLE user_users ALTER email TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE user_users ALTER new_email TYPE VARCHAR(255)');
        $this->addSql('COMMENT ON COLUMN user_users.id IS \'(DC2Type:uuid)\'');
    }
}
