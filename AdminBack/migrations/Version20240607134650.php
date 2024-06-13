<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Exception;

final class Version20240607134650 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Assign a default owner to products without an owner';
    }

    public function up(Schema $schema): void
    {
        

        // Attribuez le propriétaire par défaut aux produits sans propriétaire
        $this->addSql("UPDATE product SET proprio_id = 99999 WHERE proprio_id IS NULL");

        
    }

    public function down(Schema $schema): void
    {
        
    }
}
