<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240709172401 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE check_voyageur (id INT AUTO_INCREMENT NOT NULL, reservation_id INT NOT NULL, check_in DATETIME DEFAULT NULL, check_out DATETIME DEFAULT NULL, INDEX IDX_13CCBD3AB83297E7 (reservation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE check_voyageur ADD CONSTRAINT FK_13CCBD3AB83297E7 FOREIGN KEY (reservation_id) REFERENCES reservation_voyageur (id)');
        $this->addSql('ALTER TABLE payment CHANGE reservation_id reservation_id INT DEFAULT NULL, CHANGE proprietor_id proprietor_id INT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE check_voyageur DROP FOREIGN KEY FK_13CCBD3AB83297E7');
        $this->addSql('DROP TABLE check_voyageur');
        $this->addSql('ALTER TABLE payment CHANGE reservation_id reservation_id INT NOT NULL, CHANGE proprietor_id proprietor_id INT NOT NULL');
    }
}
