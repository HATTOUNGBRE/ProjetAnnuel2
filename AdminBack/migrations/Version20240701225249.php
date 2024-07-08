<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240701225249 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE demande_reservation ADD reservation_number VARCHAR(10) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_E3EF1621DE6156CF ON demande_reservation (reservation_number)');
        $this->addSql('ALTER TABLE historique_reservation ADD reservation_number VARCHAR(10) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_9B664733DE6156CF ON historique_reservation (reservation_number)');
        $this->addSql('ALTER TABLE reservation_voyageur ADD reservation_number VARCHAR(10) NOT NULL, ADD voyageur_id INT NOT NULL, ADD total_price DOUBLE PRECISION NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6387C040DE6156CF ON reservation_voyageur (reservation_number)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_9B664733DE6156CF ON historique_reservation');
        $this->addSql('ALTER TABLE historique_reservation DROP reservation_number');
        $this->addSql('DROP INDEX UNIQ_E3EF1621DE6156CF ON demande_reservation');
        $this->addSql('ALTER TABLE demande_reservation DROP reservation_number');
        $this->addSql('DROP INDEX UNIQ_6387C040DE6156CF ON reservation_voyageur');
        $this->addSql('ALTER TABLE reservation_voyageur DROP reservation_number, DROP voyageur_id, DROP total_price');
    }
}
