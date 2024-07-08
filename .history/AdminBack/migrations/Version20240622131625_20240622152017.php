<?php
// migrations/Version20240622131625.php
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240622131625 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add prestataire_id to disponibilite';
    }

    public function up(Schema $schema): void
    {
        // Ensure that there is at least one prestataire to reference
        $this->addSql('INSERT INTO prestataire (user_id, type, tarif, verified) VALUES (100004, \'temp\', 0, 0)');

        // Get the ID of the new prestataire
        $prestataireId = $this->connection->lastInsertId();

        // Add the prestataire_id column with a default value
        $this->addSql('ALTER TABLE disponibilite ADD prestataire_id INT DEFAULT ' . $prestataireId);
        
        // Add the foreign key constraint
        $this->addSql('ALTER TABLE disponibilite ADD CONSTRAINT FK_42C84955BE3DB2B7 FOREIGN KEY (prestataire_id) REFERENCES prestataire (id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE disponibilite DROP FOREIGN KEY FK_42C84955BE3DB2B7');
        $this->addSql('ALTER TABLE disponibilite DROP COLUMN prestataire_id');
    }
}
