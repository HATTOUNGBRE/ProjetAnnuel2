<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240622133713 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C849559E45C554');
        $this->addSql('DROP INDEX IDX_42C849559E45C554 ON reservation');
        $this->addSql('ALTER TABLE reservation ADD prestataire_id INT DEFAULT NULL, DROP prestation_id');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955BE3DB2B7 FOREIGN KEY (prestataire_id) REFERENCES prestataire (id)');
        $this->addSql('CREATE INDEX IDX_42C84955BE3DB2B7 ON reservation (prestataire_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955BE3DB2B7');
        $this->addSql('DROP INDEX IDX_42C84955BE3DB2B7 ON reservation');
        $this->addSql('ALTER TABLE reservation ADD prestation_id INT NOT NULL, DROP prestataire_id');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C849559E45C554 FOREIGN KEY (prestation_id) REFERENCES prestation (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_42C849559E45C554 ON reservation (prestation_id)');
    }
}
