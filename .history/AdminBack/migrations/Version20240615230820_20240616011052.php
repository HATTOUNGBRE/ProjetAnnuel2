<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240615230820 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add user_id column to reservation table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE reservation ADD user_id INT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE reservation DROP user_id');
    }
}
