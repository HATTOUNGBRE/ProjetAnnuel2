<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240627165728 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE demande_prestation ADD property_id INT NOT NULL');
        $this->addSql('ALTER TABLE demande_prestation ADD CONSTRAINT FK_A704850C549213EC FOREIGN KEY (property_id) REFERENCES property (id)');
        $this->addSql('CREATE INDEX IDX_A704850C549213EC ON demande_prestation (property_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE demande_prestation DROP FOREIGN KEY FK_A704850C549213EC');
        $this->addSql('DROP INDEX IDX_A704850C549213EC ON demande_prestation');
        $this->addSql('ALTER TABLE demande_prestation DROP property_id');
    }
}
