<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240707130338 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE payment ADD proprietor_id INT NOT NULL');
        $this->addSql('ALTER TABLE payment ADD CONSTRAINT FK_6D28840D530C2AF4 FOREIGN KEY (proprietor_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_6D28840D530C2AF4 ON payment (proprietor_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE payment DROP FOREIGN KEY FK_6D28840D530C2AF4');
        $this->addSql('DROP INDEX IDX_6D28840D530C2AF4 ON payment');
        $this->addSql('ALTER TABLE payment DROP proprietor_id');
    }
}
