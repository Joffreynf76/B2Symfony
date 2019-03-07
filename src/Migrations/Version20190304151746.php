<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190304151746 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP INDEX IDX_E65135D02C87042F');
        $this->addSql('CREATE TEMPORARY TABLE __temp__incidents AS SELECT id, trash_id, date, email, picture, reference, city, address, latitude, longitude, altitude, description FROM incidents');
        $this->addSql('DROP TABLE incidents');
        $this->addSql('CREATE TABLE incidents (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, trash_id INTEGER DEFAULT NULL, date DATE NOT NULL, email VARCHAR(255) NOT NULL COLLATE BINARY, picture VARCHAR(255) DEFAULT NULL COLLATE BINARY, reference VARCHAR(20) NOT NULL COLLATE BINARY, city VARCHAR(255) NOT NULL COLLATE BINARY, address CLOB NOT NULL COLLATE BINARY, latitude DOUBLE PRECISION DEFAULT NULL, longitude DOUBLE PRECISION DEFAULT NULL, altitude DOUBLE PRECISION DEFAULT NULL, description CLOB NOT NULL COLLATE BINARY, CONSTRAINT FK_E65135D02C87042F FOREIGN KEY (trash_id) REFERENCES trashs (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO incidents (id, trash_id, date, email, picture, reference, city, address, latitude, longitude, altitude, description) SELECT id, trash_id, date, email, picture, reference, city, address, latitude, longitude, altitude, description FROM __temp__incidents');
        $this->addSql('DROP TABLE __temp__incidents');
        $this->addSql('CREATE INDEX IDX_E65135D02C87042F ON incidents (trash_id)');
        $this->addSql('ALTER TABLE users ADD COLUMN googleAuthenticatorSecret VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP INDEX IDX_E65135D02C87042F');
        $this->addSql('CREATE TEMPORARY TABLE __temp__incidents AS SELECT id, trash_id, date, email, picture, reference, city, address, latitude, longitude, altitude, description FROM incidents');
        $this->addSql('DROP TABLE incidents');
        $this->addSql('CREATE TABLE incidents (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, trash_id INTEGER DEFAULT NULL, date DATE NOT NULL, email VARCHAR(255) NOT NULL, picture VARCHAR(255) DEFAULT NULL, reference VARCHAR(20) NOT NULL, city VARCHAR(255) NOT NULL, address CLOB NOT NULL, latitude DOUBLE PRECISION DEFAULT NULL, longitude DOUBLE PRECISION DEFAULT NULL, altitude DOUBLE PRECISION DEFAULT NULL, description CLOB NOT NULL)');
        $this->addSql('INSERT INTO incidents (id, trash_id, date, email, picture, reference, city, address, latitude, longitude, altitude, description) SELECT id, trash_id, date, email, picture, reference, city, address, latitude, longitude, altitude, description FROM __temp__incidents');
        $this->addSql('DROP TABLE __temp__incidents');
        $this->addSql('CREATE INDEX IDX_E65135D02C87042F ON incidents (trash_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__users AS SELECT id, name, firstname, email, password, city, roles, date_creation, last_login, active, secret_code, is_double_auth FROM users');
        $this->addSql('DROP TABLE users');
        $this->addSql('CREATE TABLE users (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(100) NOT NULL, firstname VARCHAR(100) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(100) NOT NULL, city VARCHAR(255) DEFAULT NULL, roles CLOB NOT NULL --(DC2Type:array)
        , date_creation DATE NOT NULL, last_login DATE DEFAULT NULL, active BOOLEAN NOT NULL, secret_code VARCHAR(255) DEFAULT NULL, is_double_auth BOOLEAN NOT NULL)');
        $this->addSql('INSERT INTO users (id, name, firstname, email, password, city, roles, date_creation, last_login, active, secret_code, is_double_auth) SELECT id, name, firstname, email, password, city, roles, date_creation, last_login, active, secret_code, is_double_auth FROM __temp__users');
        $this->addSql('DROP TABLE __temp__users');
    }
}
