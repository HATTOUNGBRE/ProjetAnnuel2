<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240622133246 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE candidature DROP FOREIGN KEY FK_E33BD3B8A76ED395');
        $this->addSql('ALTER TABLE reservation_prestataire DROP FOREIGN KEY FK_ACCEB9DCB83297E7');
        $this->addSql('ALTER TABLE reservation_prestataire DROP FOREIGN KEY FK_ACCEB9DCBE3DB2B7');
        $this->addSql('DROP TABLE candidature');
        $this->addSql('DROP TABLE reservation_prestataire');
        $this->addSql('ALTER TABLE reservation ADD candidatures_id INT DEFAULT NULL, DROP prestataire_id');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C8495593767DAA FOREIGN KEY (candidatures_id) REFERENCES prestataire (id)');
        $this->addSql('CREATE INDEX IDX_42C8495593767DAA ON reservation (candidatures_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE candidature (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, validated TINYINT(1) NOT NULL, INDEX IDX_E33BD3B8A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE reservation_prestataire (reservation_id INT NOT NULL, prestataire_id INT NOT NULL, INDEX IDX_ACCEB9DCB83297E7 (reservation_id), INDEX IDX_ACCEB9DCBE3DB2B7 (prestataire_id), PRIMARY KEY(reservation_id, prestataire_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE candidature ADD CONSTRAINT FK_E33BD3B8A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE reservation_prestataire ADD CONSTRAINT FK_ACCEB9DCB83297E7 FOREIGN KEY (reservation_id) REFERENCES reservation (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reservation_prestataire ADD CONSTRAINT FK_ACCEB9DCBE3DB2B7 FOREIGN KEY (prestataire_id) REFERENCES prestataire (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C8495593767DAA');
        $this->addSql('DROP INDEX IDX_42C8495593767DAA ON reservation');
        $this->addSql('ALTER TABLE reservation ADD prestataire_id INT NOT NULL, DROP candidatures_id');
    }
}
