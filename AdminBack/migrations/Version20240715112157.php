<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240715112157 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE ticket');
        $this->addSql('ALTER TABLE check_voyageur ADD CONSTRAINT FK_13CCBD3A73E3A33A FOREIGN KEY (demande_reservation_id) REFERENCES demande_reservation (id)');
        $this->addSql('ALTER TABLE demande_reservation ADD CONSTRAINT FK_E3EF162162915402 FOREIGN KEY (voyageur_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_E3EF162162915402 ON demande_reservation (voyageur_id)');
        $this->addSql('ALTER TABLE payment CHANGE reservation_id reservation_id INT DEFAULT NULL, CHANGE proprietor_id proprietor_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE tickets CHANGE updated_at updated_at DATETIME DEFAULT NULL, CHANGE assigned_to assigned_to VARCHAR(255) DEFAULT NULL, CHANGE title question VARCHAR(255) NOT NULL, CHANGE description message LONGTEXT NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_54469DF4ECD2759F ON tickets (ticket_number)');
        $this->addSql('ALTER TABLE user ADD reset_token VARCHAR(64) DEFAULT NULL, ADD token_expiration DATETIME DEFAULT NULL, ADD is_suspended TINYINT(1) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ticket (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, surname VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, email VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, role VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, question VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, message LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, created_at DATETIME NOT NULL, status VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, ticket_number VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, UNIQUE INDEX UNIQ_97A0ADA3ECD2759F (ticket_number), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE check_voyageur DROP FOREIGN KEY FK_13CCBD3A73E3A33A');
        $this->addSql('ALTER TABLE demande_reservation DROP FOREIGN KEY FK_E3EF162162915402');
        $this->addSql('DROP INDEX IDX_E3EF162162915402 ON demande_reservation');
        $this->addSql('ALTER TABLE payment CHANGE reservation_id reservation_id INT NOT NULL, CHANGE proprietor_id proprietor_id INT NOT NULL');
        $this->addSql('DROP INDEX UNIQ_54469DF4ECD2759F ON tickets');
        $this->addSql('ALTER TABLE tickets CHANGE updated_at updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', CHANGE assigned_to assigned_to INT DEFAULT NULL, CHANGE question title VARCHAR(255) NOT NULL, CHANGE message description LONGTEXT NOT NULL');
        $this->addSql('ALTER TABLE user DROP reset_token, DROP token_expiration, DROP is_suspended');
    }
}
