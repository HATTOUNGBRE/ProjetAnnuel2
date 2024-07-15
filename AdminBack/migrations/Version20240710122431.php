<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240710122431 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE check_voyageur DROP FOREIGN KEY FK_13CCBD3AB83297E7');
        $this->addSql('DROP INDEX IDX_13CCBD3AB83297E7 ON check_voyageur');
        $this->addSql('ALTER TABLE check_voyageur CHANGE reservation_id demande_reservation_id INT NOT NULL');
        $this->addSql('ALTER TABLE check_voyageur ADD CONSTRAINT FK_13CCBD3A73E3A33A FOREIGN KEY (demande_reservation_id) REFERENCES demande_reservation (id)');
        $this->addSql('CREATE INDEX IDX_13CCBD3A73E3A33A ON check_voyageur (demande_reservation_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE check_voyageur DROP FOREIGN KEY FK_13CCBD3A73E3A33A');
        $this->addSql('DROP INDEX IDX_13CCBD3A73E3A33A ON check_voyageur');
        $this->addSql('ALTER TABLE check_voyageur CHANGE demande_reservation_id reservation_id INT NOT NULL');
        $this->addSql('ALTER TABLE check_voyageur ADD CONSTRAINT FK_13CCBD3AB83297E7 FOREIGN KEY (reservation_id) REFERENCES reservation_voyageur (id)');
        $this->addSql('CREATE INDEX IDX_13CCBD3AB83297E7 ON check_voyageur (reservation_id)');
    }
}
