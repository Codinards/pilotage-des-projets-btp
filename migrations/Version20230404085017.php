<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230404085017 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE company (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, address VARCHAR(255) DEFAULT NULL, phone_number VARCHAR(255) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL)');
        $this->addSql('CREATE TABLE project (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, type_id INTEGER NOT NULL, company_id INTEGER NOT NULL, sector_id INTEGER NOT NULL, name VARCHAR(255) NOT NULL, cost DOUBLE PRECISION NOT NULL, start_at DATETIME NOT NULL, end_at DATETIME DEFAULT NULL, is_active BOOLEAN NOT NULL, close_contract VARCHAR(255) DEFAULT NULL, presented_at DATETIME DEFAULT NULL --(DC2Type:datetime_immutable)
        , number INTEGER DEFAULT NULL)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2FB3D0EE96901F54 ON project (number)');
        $this->addSql('CREATE INDEX IDX_2FB3D0EEC54C8C93 ON project (type_id)');
        $this->addSql('CREATE INDEX IDX_2FB3D0EE979B1AD6 ON project (company_id)');
        $this->addSql('CREATE INDEX IDX_2FB3D0EEDE95C867 ON project (sector_id)');
        $this->addSql('CREATE TABLE project_type (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE TABLE role (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(15) NOT NULL, title VARCHAR(25) NOT NULL)');
        $this->addSql('CREATE TABLE sector (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL)');
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, role_id INTEGER NOT NULL, pseudo VARCHAR(180) NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
        , password VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, is_locked BOOLEAN NOT NULL)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D64986CC499D ON user (pseudo)');
        $this->addSql('CREATE INDEX IDX_8D93D649D60322AC ON user (role_id)');
        $this->addSql('CREATE TABLE messenger_messages (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, body CLOB NOT NULL, headers CLOB NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL)');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)');
        $this->addSql('CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)');
        $this->addSql('CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE company');
        $this->addSql('DROP TABLE project');
        $this->addSql('DROP TABLE project_type');
        $this->addSql('DROP TABLE role');
        $this->addSql('DROP TABLE sector');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
