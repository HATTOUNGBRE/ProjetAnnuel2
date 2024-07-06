<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240629180615 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE historique_reservation ADD demande_reservation_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE historique_reservation ADD CONSTRAINT FK_9B66473373E3A33A FOREIGN KEY (demande_reservation_id) REFERENCES demande_reservation (id)');
        $this->addSql('CREATE INDEX IDX_9B66473373E3A33A ON historique_reservation (demande_reservation_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE historique_reservation DROP FOREIGN KEY FK_9B66473373E3A33A');
        $this->addSql('DROP INDEX IDX_9B66473373E3A33A ON historique_reservation');
        $this->addSql('ALTER TABLE historique_reservation DROP demande_reservation_id');
    }
}
