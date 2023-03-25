<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230324080331 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE permissions_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE roles_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE statement_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE "user_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE permissions (id INT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE roles (id INT NOT NULL, parent_role_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_B63E2EC7A44B56EA ON roles (parent_role_id)');
        $this->addSql('CREATE TABLE roles_permissions (roles_id INT NOT NULL, permissions_id INT NOT NULL, PRIMARY KEY(roles_id, permissions_id))');
        $this->addSql('CREATE INDEX IDX_CEC2E04338C751C4 ON roles_permissions (roles_id)');
        $this->addSql('CREATE INDEX IDX_CEC2E0439C3E4F87 ON roles_permissions (permissions_id)');
        $this->addSql('CREATE TABLE statement (id INT NOT NULL, author_id INT NOT NULL, name VARCHAR(255) DEFAULT NULL, number INT NOT NULL, file_url VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_C0DB5176F675F31B ON statement (author_id)');
        $this->addSql('CREATE TABLE "user" (id INT NOT NULL, name VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, username VARCHAR(255) NOT NULL, password VARCHAR(64) NOT NULL, email VARCHAR(255) NOT NULL, is_active BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649F85E0677 ON "user" (username)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON "user" (email)');
        $this->addSql('CREATE TABLE user_roles (user_id INT NOT NULL, roles_id INT NOT NULL, PRIMARY KEY(user_id, roles_id))');
        $this->addSql('CREATE INDEX IDX_54FCD59FA76ED395 ON user_roles (user_id)');
        $this->addSql('CREATE INDEX IDX_54FCD59F38C751C4 ON user_roles (roles_id)');
        $this->addSql('ALTER TABLE roles ADD CONSTRAINT FK_B63E2EC7A44B56EA FOREIGN KEY (parent_role_id) REFERENCES roles (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE roles_permissions ADD CONSTRAINT FK_CEC2E04338C751C4 FOREIGN KEY (roles_id) REFERENCES roles (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE roles_permissions ADD CONSTRAINT FK_CEC2E0439C3E4F87 FOREIGN KEY (permissions_id) REFERENCES permissions (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE statement ADD CONSTRAINT FK_C0DB5176F675F31B FOREIGN KEY (author_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_roles ADD CONSTRAINT FK_54FCD59FA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_roles ADD CONSTRAINT FK_54FCD59F38C751C4 FOREIGN KEY (roles_id) REFERENCES roles (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE permissions_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE roles_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE statement_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE "user_id_seq" CASCADE');
        $this->addSql('ALTER TABLE roles DROP CONSTRAINT FK_B63E2EC7A44B56EA');
        $this->addSql('ALTER TABLE roles_permissions DROP CONSTRAINT FK_CEC2E04338C751C4');
        $this->addSql('ALTER TABLE roles_permissions DROP CONSTRAINT FK_CEC2E0439C3E4F87');
        $this->addSql('ALTER TABLE statement DROP CONSTRAINT FK_C0DB5176F675F31B');
        $this->addSql('ALTER TABLE user_roles DROP CONSTRAINT FK_54FCD59FA76ED395');
        $this->addSql('ALTER TABLE user_roles DROP CONSTRAINT FK_54FCD59F38C751C4');
        $this->addSql('DROP TABLE permissions');
        $this->addSql('DROP TABLE roles');
        $this->addSql('DROP TABLE roles_permissions');
        $this->addSql('DROP TABLE statement');
        $this->addSql('DROP TABLE "user"');
        $this->addSql('DROP TABLE user_roles');
    }
}
