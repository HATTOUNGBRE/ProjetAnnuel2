<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240614143100 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Ajout des relations entre Prestation et User, et entre Reservation et User.';
    }

    public function up(Schema $schema): void
    {
       
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955C54C8C93');
        $this->addSql('DROP INDEX IDX_42C84955C54C8C93 ON reservation');
        $this->addSql('ALTER TABLE reservation DROP prestataire_id');
        $this->addSql('ALTER TABLE prestation DROP FOREIGN KEY FK_52AE5D214A76ED395');
        $this->addSql('DROP INDEX IDX_52AE5D214A76ED395 ON prestation');
        $this->addSql('ALTER TABLE prestation DROP user_id');
    }
}
