<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240229135142 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE medecins (id INT AUTO_INCREMENT NOT NULL, medecins_id INT DEFAULT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, phonenumber INT NOT NULL, degree VARCHAR(255) NOT NULL, region VARCHAR(255) NOT NULL, rate INT NOT NULL, INDEX IDX_691272DDDA00906 (medecins_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE medicament (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, prix DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE medications (id INT AUTO_INCREMENT NOT NULL, quantite INT NOT NULL, prix DOUBLE PRECISION NOT NULL, name VARCHAR(255) NOT NULL, disponibilite TINYINT(1) NOT NULL, instruction_usage VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE patients (id INT AUTO_INCREMENT NOT NULL, rendezvous_id INT DEFAULT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, phonenumber INT NOT NULL, INDEX IDX_2CCC2E2C3345E0A3 (rendezvous_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE planning_medecins (id INT AUTO_INCREMENT NOT NULL, medecin_id INT DEFAULT NULL, jour_debut VARCHAR(255) NOT NULL, jour_fin VARCHAR(255) NOT NULL, heure_debut TIME NOT NULL, heure_fin TIME NOT NULL, disponibilite TINYINT(1) NOT NULL, nbr INT NOT NULL, INDEX IDX_5E1724704F31A84 (medecin_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE prescriptions (id INT AUTO_INCREMENT NOT NULL, patients_id INT DEFAULT NULL, medecins_id INT DEFAULT NULL, frequance VARCHAR(255) NOT NULL, date_debut DATE NOT NULL, date_fin DATE NOT NULL, INDEX IDX_E41E1AC3CEC3FD2F (patients_id), INDEX IDX_E41E1AC3DA00906 (medecins_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE prescriptions_medications (prescriptions_id INT NOT NULL, medications_id INT NOT NULL, INDEX IDX_20321581BF8A2A75 (prescriptions_id), INDEX IDX_20321581815BCB09 (medications_id), PRIMARY KEY(prescriptions_id, medications_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rendez_vousgg (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rendezvous (id INT AUTO_INCREMENT NOT NULL, medecins_id INT DEFAULT NULL, date DATETIME NOT NULL, raison VARCHAR(255) NOT NULL, nbr INT NOT NULL, INDEX IDX_C09A9BA8DA00906 (medecins_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rendezvouss (id INT AUTO_INCREMENT NOT NULL, medecins_id INT DEFAULT NULL, date DATE NOT NULL, raison VARCHAR(255) NOT NULL, INDEX IDX_C3C37E4ADA00906 (medecins_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE medecins ADD CONSTRAINT FK_691272DDDA00906 FOREIGN KEY (medecins_id) REFERENCES rendezvous (id)');
        $this->addSql('ALTER TABLE patients ADD CONSTRAINT FK_2CCC2E2C3345E0A3 FOREIGN KEY (rendezvous_id) REFERENCES rendezvous (id)');
        $this->addSql('ALTER TABLE planning_medecins ADD CONSTRAINT FK_5E1724704F31A84 FOREIGN KEY (medecin_id) REFERENCES medecins (id)');
        $this->addSql('ALTER TABLE prescriptions ADD CONSTRAINT FK_E41E1AC3CEC3FD2F FOREIGN KEY (patients_id) REFERENCES patients (id)');
        $this->addSql('ALTER TABLE prescriptions ADD CONSTRAINT FK_E41E1AC3DA00906 FOREIGN KEY (medecins_id) REFERENCES medecins (id)');
        $this->addSql('ALTER TABLE prescriptions_medications ADD CONSTRAINT FK_20321581BF8A2A75 FOREIGN KEY (prescriptions_id) REFERENCES prescriptions (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE prescriptions_medications ADD CONSTRAINT FK_20321581815BCB09 FOREIGN KEY (medications_id) REFERENCES medications (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE rendezvous ADD CONSTRAINT FK_C09A9BA8DA00906 FOREIGN KEY (medecins_id) REFERENCES medecins (id)');
        $this->addSql('ALTER TABLE rendezvouss ADD CONSTRAINT FK_C3C37E4ADA00906 FOREIGN KEY (medecins_id) REFERENCES medecins (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE medecins DROP FOREIGN KEY FK_691272DDDA00906');
        $this->addSql('ALTER TABLE patients DROP FOREIGN KEY FK_2CCC2E2C3345E0A3');
        $this->addSql('ALTER TABLE planning_medecins DROP FOREIGN KEY FK_5E1724704F31A84');
        $this->addSql('ALTER TABLE prescriptions DROP FOREIGN KEY FK_E41E1AC3CEC3FD2F');
        $this->addSql('ALTER TABLE prescriptions DROP FOREIGN KEY FK_E41E1AC3DA00906');
        $this->addSql('ALTER TABLE prescriptions_medications DROP FOREIGN KEY FK_20321581BF8A2A75');
        $this->addSql('ALTER TABLE prescriptions_medications DROP FOREIGN KEY FK_20321581815BCB09');
        $this->addSql('ALTER TABLE rendezvous DROP FOREIGN KEY FK_C09A9BA8DA00906');
        $this->addSql('ALTER TABLE rendezvouss DROP FOREIGN KEY FK_C3C37E4ADA00906');
        $this->addSql('DROP TABLE medecins');
        $this->addSql('DROP TABLE medicament');
        $this->addSql('DROP TABLE medications');
        $this->addSql('DROP TABLE patients');
        $this->addSql('DROP TABLE planning_medecins');
        $this->addSql('DROP TABLE prescriptions');
        $this->addSql('DROP TABLE prescriptions_medications');
        $this->addSql('DROP TABLE rendez_vousgg');
        $this->addSql('DROP TABLE rendezvous');
        $this->addSql('DROP TABLE rendezvouss');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
