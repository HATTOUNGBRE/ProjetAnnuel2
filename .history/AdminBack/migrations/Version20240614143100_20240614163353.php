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
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reservation ADD titre VARCHAR(255) NOT NULL');

        // Assurez-vous que toutes les réservations ont un prestation_id non nul
        $this->addSql('UPDATE reservation SET prestation_id = (SELECT id FROM prestation LIMIT 1) WHERE prestation_id IS NULL');

        // Assurez-vous que toutes les prestations référencées existent dans la table prestation
        $this->addSql('DELETE FROM reservation WHERE prestation_id NOT IN (SELECT id FROM prestation)');

        // Maintenant, nous pouvons rendre la colonne prestation_id non nulle
        $this->addSql('ALTER TABLE reservation CHANGE prestation_id prestation_id INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reservation DROP titre, CHANGE prestation_id prestation_id INT DEFAULT NULL');
    }
}
