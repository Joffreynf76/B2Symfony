<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190301102817 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('CREATE TABLE incidents (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, trash_id INTEGER DEFAULT NULL, date DATE NOT NULL, email VARCHAR(255) NOT NULL, picture VARCHAR(255) DEFAULT NULL, reference VARCHAR(20) NOT NULL, city VARCHAR(255) NOT NULL, address CLOB NOT NULL, latitude DOUBLE PRECISION DEFAULT NULL, longitude DOUBLE PRECISION DEFAULT NULL, altitude DOUBLE PRECISION DEFAULT NULL, description CLOB NOT NULL)');
        $this->addSql('CREATE INDEX IDX_E65135D02C87042F ON incidents (trash_id)');
        $this->addSql('CREATE TABLE users (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(100) NOT NULL, firstname VARCHAR(100) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(100) NOT NULL, city VARCHAR(255) DEFAULT NULL, roles CLOB NOT NULL --(DC2Type:array)
        , date_creation DATE NOT NULL, last_login DATE DEFAULT NULL, active BOOLEAN NOT NULL, secret_code VARCHAR(255) DEFAULT NULL, is_double_auth BOOLEAN NOT NULL)');
        $this->addSql('CREATE TABLE trashs (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, city VARCHAR(255) NOT NULL, address CLOB NOT NULL, insee_code VARCHAR(10) NOT NULL, latitude DOUBLE PRECISION NOT NULL, longitude DOUBLE PRECISION NOT NULL, altitude DOUBLE PRECISION NOT NULL, reference VARCHAR(50) NOT NULL, capacity_max INTEGER NOT NULL, actual_capacity INTEGER DEFAULT NULL)');
        $this->addSql('CREATE TABLE message (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, sender INTEGER NOT NULL, receiver INTEGER NOT NULL, date DATE NOT NULL, object VARCHAR(255) NOT NULL, content CLOB NOT NULL, status BOOLEAN NOT NULL)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP TABLE incidents');
        $this->addSql('DROP TABLE users');
        $this->addSql('DROP TABLE trashs');
        $this->addSql('DROP TABLE message');
    }
}
