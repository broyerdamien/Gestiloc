<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241209135122 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE avis_echeance (id INT AUTO_INCREMENT NOT NULL, location_id INT NOT NULL, date_debut DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', date_fin DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', payment_status VARCHAR(20) NOT NULL, amount DOUBLE PRECISION NOT NULL, remaining_amount DOUBLE PRECISION DEFAULT NULL, excess_amount DOUBLE PRECISION DEFAULT NULL, INDEX IDX_A9912F9E64D218E (location_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE location (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(255) DEFAULT NULL, depot INT DEFAULT NULL, start_date DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', end_date DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', etat TINYINT(1) NOT NULL, loyer DOUBLE PRECISION DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE location_property (location_id INT NOT NULL, property_id INT NOT NULL, INDEX IDX_5AA73E3C64D218E (location_id), INDEX IDX_5AA73E3C549213EC (property_id), PRIMARY KEY(location_id, property_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE location_lodger (location_id INT NOT NULL, lodger_id INT NOT NULL, INDEX IDX_C1B9341F64D218E (location_id), INDEX IDX_C1B9341F36790F15 (lodger_id), PRIMARY KEY(location_id, lodger_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE lodger (id INT AUTO_INCREMENT NOT NULL, property_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, firstname VARCHAR(255) NOT NULL, address VARCHAR(255) DEFAULT NULL, phone VARCHAR(20) DEFAULT NULL, mail VARCHAR(50) DEFAULT NULL, date_of_birth DATE DEFAULT NULL COMMENT \'(DC2Type:date_immutable)\', job VARCHAR(150) DEFAULT NULL, salary DOUBLE PRECISION DEFAULT NULL, sex VARCHAR(50) DEFAULT NULL, INDEX IDX_8ACBBC1D549213EC (property_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE payment (id INT AUTO_INCREMENT NOT NULL, avis_echeance_id INT DEFAULT NULL, amount DOUBLE PRECISION DEFAULT NULL, payment_date DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_6D28840D4D8CBDC (avis_echeance_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE property (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, address VARCHAR(255) NOT NULL, building VARCHAR(255) DEFAULT NULL, etage INT DEFAULT NULL, numero INT DEFAULT NULL, country VARCHAR(255) NOT NULL, post_code INT NOT NULL, area DOUBLE PRECISION DEFAULT NULL, number_of_parts INT NOT NULL, bedroom INT DEFAULT NULL, bathroom INT DEFAULT NULL, rental_charges DOUBLE PRECISION DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE avis_echeance ADD CONSTRAINT FK_A9912F9E64D218E FOREIGN KEY (location_id) REFERENCES location (id)');
        $this->addSql('ALTER TABLE location_property ADD CONSTRAINT FK_5AA73E3C64D218E FOREIGN KEY (location_id) REFERENCES location (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE location_property ADD CONSTRAINT FK_5AA73E3C549213EC FOREIGN KEY (property_id) REFERENCES property (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE location_lodger ADD CONSTRAINT FK_C1B9341F64D218E FOREIGN KEY (location_id) REFERENCES location (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE location_lodger ADD CONSTRAINT FK_C1B9341F36790F15 FOREIGN KEY (lodger_id) REFERENCES lodger (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE lodger ADD CONSTRAINT FK_8ACBBC1D549213EC FOREIGN KEY (property_id) REFERENCES property (id)');
        $this->addSql('ALTER TABLE payment ADD CONSTRAINT FK_6D28840D4D8CBDC FOREIGN KEY (avis_echeance_id) REFERENCES avis_echeance (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE avis_echeance DROP FOREIGN KEY FK_A9912F9E64D218E');
        $this->addSql('ALTER TABLE location_property DROP FOREIGN KEY FK_5AA73E3C64D218E');
        $this->addSql('ALTER TABLE location_property DROP FOREIGN KEY FK_5AA73E3C549213EC');
        $this->addSql('ALTER TABLE location_lodger DROP FOREIGN KEY FK_C1B9341F64D218E');
        $this->addSql('ALTER TABLE location_lodger DROP FOREIGN KEY FK_C1B9341F36790F15');
        $this->addSql('ALTER TABLE lodger DROP FOREIGN KEY FK_8ACBBC1D549213EC');
        $this->addSql('ALTER TABLE payment DROP FOREIGN KEY FK_6D28840D4D8CBDC');
        $this->addSql('DROP TABLE avis_echeance');
        $this->addSql('DROP TABLE location');
        $this->addSql('DROP TABLE location_property');
        $this->addSql('DROP TABLE location_lodger');
        $this->addSql('DROP TABLE lodger');
        $this->addSql('DROP TABLE payment');
        $this->addSql('DROP TABLE property');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
