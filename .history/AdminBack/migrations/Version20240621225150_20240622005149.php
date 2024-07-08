<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240621225150 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE candidature DROP FOREIGN KEY FK_E33BD3B8B83297E7');
        $this->addSql('DROP INDEX IDX_E33BD3B8B83297E7 ON candidature');
        $this->addSql('ALTER TABLE candidature DROP reservation_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE candidature ADD reservation_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE candidature ADD CONSTRAINT FK_E33BD3B8B83297E7 FOREIGN KEY (reservation_id) REFERENCES reservation (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_E33BD3B8B83297E7 ON candidature (reservation_id)');
    }
}
