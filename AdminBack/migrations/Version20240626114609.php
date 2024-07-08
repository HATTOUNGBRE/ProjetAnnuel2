<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240626114609 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE prestation (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, titre VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, date_deffet DATETIME NOT NULL, date_de_fin DATETIME NOT NULL, type VARCHAR(255) NOT NULL, date_de_creation DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_51C88FADA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE prestation ADD CONSTRAINT FK_51C88FADA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE demande_prestation DROP date_de_fin');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE prestation DROP FOREIGN KEY FK_51C88FADA76ED395');
        $this->addSql('DROP TABLE prestation');
        $this->addSql('ALTER TABLE demande_prestation ADD date_de_fin DATETIME NOT NULL');
    }
}
