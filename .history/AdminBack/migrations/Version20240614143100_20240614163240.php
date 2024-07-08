<?
declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240614143100 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Ajoute un titre aux réservations et rend la colonne prestation_id non nulle';
    }

    public function up(Schema $schema): void
    {
        // Assurez-vous que toutes les prestations ont un ID non nul
        $this->addSql('UPDATE reservation SET prestation_id = 1 WHERE prestation_id IS NULL');
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reservation ADD titre VARCHAR(255) NOT NULL, CHANGE prestation_id prestation_id INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reservation DROP titre, CHANGE prestation_id prestation_id INT DEFAULT NULL');
    }
}
