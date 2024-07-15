<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240709224423 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE tickets (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, status VARCHAR(50) NOT NULL, priority VARCHAR(50) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', ticket_number VARCHAR(255) NOT NULL, assigned_to INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ticket_category_mappings DROP FOREIGN KEY ticket_category_mappings_ibfk_1');
        $this->addSql('ALTER TABLE ticket_category_mappings DROP FOREIGN KEY ticket_category_mappings_ibfk_2');
        $this->addSql('ALTER TABLE ticket_attachments DROP FOREIGN KEY ticket_attachments_ibfk_1');
        $this->addSql('ALTER TABLE ticket_comments DROP FOREIGN KEY ticket_comments_ibfk_1');
        $this->addSql('ALTER TABLE ticket_comments DROP FOREIGN KEY ticket_comments_ibfk_2');
        $this->addSql('DROP TABLE ticket_category_mappings');
        $this->addSql('DROP TABLE ticket_attachments');
        $this->addSql('DROP TABLE ticket_comments');
        $this->addSql('DROP TABLE ticket_categories');
        $this->addSql('ALTER TABLE ticket DROP assigned_to');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ticket_category_mappings (ticket_id INT NOT NULL, category_id INT NOT NULL, INDEX category_id (category_id), INDEX IDX_28E95E36700047D2 (ticket_id), PRIMARY KEY(ticket_id, category_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE ticket_attachments (id INT AUTO_INCREMENT NOT NULL, ticket_id INT NOT NULL, file_path VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, uploaded_at DATETIME DEFAULT CURRENT_TIMESTAMP, INDEX ticket_id (ticket_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE ticket_comments (id INT AUTO_INCREMENT NOT NULL, ticket_id INT NOT NULL, user_id INT NOT NULL, comment TEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, created_at DATETIME DEFAULT CURRENT_TIMESTAMP, INDEX user_id (user_id), INDEX ticket_id (ticket_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE ticket_categories (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, description TEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_general_ci`, UNIQUE INDEX name (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE ticket_category_mappings ADD CONSTRAINT ticket_category_mappings_ibfk_1 FOREIGN KEY (ticket_id) REFERENCES tickets (id)');
        $this->addSql('ALTER TABLE ticket_category_mappings ADD CONSTRAINT ticket_category_mappings_ibfk_2 FOREIGN KEY (category_id) REFERENCES ticket_categories (id)');
        $this->addSql('ALTER TABLE ticket_attachments ADD CONSTRAINT ticket_attachments_ibfk_1 FOREIGN KEY (ticket_id) REFERENCES tickets (id)');
        $this->addSql('ALTER TABLE ticket_comments ADD CONSTRAINT ticket_comments_ibfk_1 FOREIGN KEY (ticket_id) REFERENCES tickets (id)');
        $this->addSql('ALTER TABLE ticket_comments ADD CONSTRAINT ticket_comments_ibfk_2 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('DROP TABLE tickets');
        $this->addSql('ALTER TABLE ticket ADD assigned_to VARCHAR(255) DEFAULT NULL');
    }
}
