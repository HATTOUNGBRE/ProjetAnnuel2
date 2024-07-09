<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240708195912 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE availability (id INT AUTO_INCREMENT NOT NULL, property_id INT NOT NULL, start_date DATETIME NOT NULL, end_date DATETIME NOT NULL, INDEX IDX_3FB7A2BF549213EC (property_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, active TINYINT(1) NOT NULL, updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category_user (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, active TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE demande_prestation (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, property_id INT NOT NULL, titre VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, date_deffet DATETIME NOT NULL, type VARCHAR(255) NOT NULL, statut VARCHAR(255) NOT NULL, INDEX IDX_A704850CA76ED395 (user_id), INDEX IDX_A704850C549213EC (property_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE demande_reservation (id INT AUTO_INCREMENT NOT NULL, property_id INT NOT NULL, date_arrivee DATE NOT NULL, date_depart DATE NOT NULL, guest_nb INT NOT NULL, status VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, surname VARCHAR(255) NOT NULL, voyageur_id INT NOT NULL, total_price DOUBLE PRECISION NOT NULL, active TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, reservation_number VARCHAR(10) NOT NULL, UNIQUE INDEX UNIQ_E3EF1621DE6156CF (reservation_number), INDEX IDX_E3EF1621549213EC (property_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE historique_reservation (id INT AUTO_INCREMENT NOT NULL, property_id INT NOT NULL, demande_reservation_id INT NOT NULL, date_arrivee DATE NOT NULL, date_depart DATE NOT NULL, guest_nb INT NOT NULL, status VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, name VARCHAR(255) NOT NULL, surname VARCHAR(255) NOT NULL, voyageur_id INT NOT NULL, total_price DOUBLE PRECISION NOT NULL, reservation_number VARCHAR(10) NOT NULL, UNIQUE INDEX UNIQ_9B664733DE6156CF (reservation_number), INDEX IDX_9B664733549213EC (property_id), INDEX IDX_9B66473373E3A33A (demande_reservation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE payment (id INT AUTO_INCREMENT NOT NULL, reservation_id INT NOT NULL, proprietor_id INT NOT NULL, date DATETIME NOT NULL, amount NUMERIC(10, 2) NOT NULL, method VARCHAR(255) NOT NULL, card_last4 VARCHAR(255) NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, INDEX IDX_6D28840DB83297E7 (reservation_id), INDEX IDX_6D28840D530C2AF4 (proprietor_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE prestataire (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, type VARCHAR(255) NOT NULL, tarif NUMERIC(10, 2) NOT NULL, verified TINYINT(1) NOT NULL, INDEX IDX_60A26480A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE prestation (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, titre VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, date_deffet DATETIME NOT NULL, date_de_fin DATETIME DEFAULT NULL, type VARCHAR(255) NOT NULL, date_de_creation DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', statut VARCHAR(255) NOT NULL, active TINYINT(1) NOT NULL, INDEX IDX_51C88FADA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE property (id INT AUTO_INCREMENT NOT NULL, category_id INT NOT NULL, proprio_id INT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, price DOUBLE PRECISION NOT NULL, image VARCHAR(255) DEFAULT NULL, updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', active TINYINT(1) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', max_persons INT NOT NULL, has_pool TINYINT(1) NOT NULL, area INT NOT NULL, has_balcony TINYINT(1) NOT NULL, commune VARCHAR(255) NOT NULL, INDEX IDX_8BF21CDE12469DE2 (category_id), INDEX IDX_8BF21CDE6B82600 (proprio_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reservation_voyageur (id INT AUTO_INCREMENT NOT NULL, property_id INT NOT NULL, date_arrivee DATE NOT NULL, date_depart DATE NOT NULL, guest_nb INT NOT NULL, reservation_number VARCHAR(10) NOT NULL, voyageur_id INT NOT NULL, total_price DOUBLE PRECISION NOT NULL, UNIQUE INDEX UNIQ_6387C040DE6156CF (reservation_number), INDEX IDX_6387C040549213EC (property_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE test (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ticket (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, surname VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, role VARCHAR(255) NOT NULL, question VARCHAR(255) NOT NULL, message LONGTEXT NOT NULL, created_at DATETIME NOT NULL, status VARCHAR(255) NOT NULL, ticket_number VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_97A0ADA3ECD2759F (ticket_number), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, category_user_id INT NOT NULL, name VARCHAR(255) NOT NULL, surname VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, is_verified TINYINT(1) DEFAULT 0 NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', image_profile VARCHAR(255) DEFAULT NULL, roles JSON NOT NULL COMMENT \'(DC2Type:json)\', INDEX IDX_8D93D64960B693EB (category_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE availability ADD CONSTRAINT FK_3FB7A2BF549213EC FOREIGN KEY (property_id) REFERENCES property (id)');
        $this->addSql('ALTER TABLE demande_prestation ADD CONSTRAINT FK_A704850CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE demande_prestation ADD CONSTRAINT FK_A704850C549213EC FOREIGN KEY (property_id) REFERENCES property (id)');
        $this->addSql('ALTER TABLE demande_reservation ADD CONSTRAINT FK_E3EF1621549213EC FOREIGN KEY (property_id) REFERENCES property (id)');
        $this->addSql('ALTER TABLE historique_reservation ADD CONSTRAINT FK_9B664733549213EC FOREIGN KEY (property_id) REFERENCES property (id)');
        $this->addSql('ALTER TABLE historique_reservation ADD CONSTRAINT FK_9B66473373E3A33A FOREIGN KEY (demande_reservation_id) REFERENCES demande_reservation (id)');
        $this->addSql('ALTER TABLE payment ADD CONSTRAINT FK_6D28840DB83297E7 FOREIGN KEY (reservation_id) REFERENCES reservation_voyageur (id)');
        $this->addSql('ALTER TABLE payment ADD CONSTRAINT FK_6D28840D530C2AF4 FOREIGN KEY (proprietor_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE prestataire ADD CONSTRAINT FK_60A26480A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE prestation ADD CONSTRAINT FK_51C88FADA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE property ADD CONSTRAINT FK_8BF21CDE12469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE property ADD CONSTRAINT FK_8BF21CDE6B82600 FOREIGN KEY (proprio_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE reservation_voyageur ADD CONSTRAINT FK_6387C040549213EC FOREIGN KEY (property_id) REFERENCES property (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64960B693EB FOREIGN KEY (category_user_id) REFERENCES category_user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE availability DROP FOREIGN KEY FK_3FB7A2BF549213EC');
        $this->addSql('ALTER TABLE demande_prestation DROP FOREIGN KEY FK_A704850CA76ED395');
        $this->addSql('ALTER TABLE demande_prestation DROP FOREIGN KEY FK_A704850C549213EC');
        $this->addSql('ALTER TABLE demande_reservation DROP FOREIGN KEY FK_E3EF1621549213EC');
        $this->addSql('ALTER TABLE historique_reservation DROP FOREIGN KEY FK_9B664733549213EC');
        $this->addSql('ALTER TABLE historique_reservation DROP FOREIGN KEY FK_9B66473373E3A33A');
        $this->addSql('ALTER TABLE payment DROP FOREIGN KEY FK_6D28840DB83297E7');
        $this->addSql('ALTER TABLE payment DROP FOREIGN KEY FK_6D28840D530C2AF4');
        $this->addSql('ALTER TABLE prestataire DROP FOREIGN KEY FK_60A26480A76ED395');
        $this->addSql('ALTER TABLE prestation DROP FOREIGN KEY FK_51C88FADA76ED395');
        $this->addSql('ALTER TABLE property DROP FOREIGN KEY FK_8BF21CDE12469DE2');
        $this->addSql('ALTER TABLE property DROP FOREIGN KEY FK_8BF21CDE6B82600');
        $this->addSql('ALTER TABLE reservation_voyageur DROP FOREIGN KEY FK_6387C040549213EC');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64960B693EB');
        $this->addSql('DROP TABLE availability');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE category_user');
        $this->addSql('DROP TABLE demande_prestation');
        $this->addSql('DROP TABLE demande_reservation');
        $this->addSql('DROP TABLE historique_reservation');
        $this->addSql('DROP TABLE payment');
        $this->addSql('DROP TABLE prestataire');
        $this->addSql('DROP TABLE prestation');
        $this->addSql('DROP TABLE property');
        $this->addSql('DROP TABLE reservation_voyageur');
        $this->addSql('DROP TABLE test');
        $this->addSql('DROP TABLE ticket');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
