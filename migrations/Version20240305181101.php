<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240305181101 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE prescri (id INT AUTO_INCREMENT NOT NULL, medicament_id INT DEFAULT NULL, patient_id INT DEFAULT NULL, quantite INT NOT NULL, date_debut DATE NOT NULL, date_fin DATE NOT NULL, INDEX IDX_7C40E321AB0D61F7 (medicament_id), INDEX IDX_7C40E3216B899279 (patient_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE prescri ADD CONSTRAINT FK_7C40E321AB0D61F7 FOREIGN KEY (medicament_id) REFERENCES medicament (id)');
        $this->addSql('ALTER TABLE prescri ADD CONSTRAINT FK_7C40E3216B899279 FOREIGN KEY (patient_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE prescriptions DROP FOREIGN KEY FK_E41E1AC3CEC3FD2F');
        $this->addSql('ALTER TABLE prescriptions DROP FOREIGN KEY FK_E41E1AC3DA00906');
        $this->addSql('DROP INDEX IDX_E41E1AC3CEC3FD2F ON prescriptions');
        $this->addSql('DROP INDEX IDX_E41E1AC3DA00906 ON prescriptions');
        $this->addSql('ALTER TABLE prescriptions DROP patients_id, DROP medecins_id, DROP frequance');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE prescri DROP FOREIGN KEY FK_7C40E321AB0D61F7');
        $this->addSql('ALTER TABLE prescri DROP FOREIGN KEY FK_7C40E3216B899279');
        $this->addSql('DROP TABLE prescri');
        $this->addSql('ALTER TABLE prescriptions ADD patients_id INT DEFAULT NULL, ADD medecins_id INT DEFAULT NULL, ADD frequance VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE prescriptions ADD CONSTRAINT FK_E41E1AC3CEC3FD2F FOREIGN KEY (patients_id) REFERENCES patients (id)');
        $this->addSql('ALTER TABLE prescriptions ADD CONSTRAINT FK_E41E1AC3DA00906 FOREIGN KEY (medecins_id) REFERENCES medecins (id)');
        $this->addSql('CREATE INDEX IDX_E41E1AC3CEC3FD2F ON prescriptions (patients_id)');
        $this->addSql('CREATE INDEX IDX_E41E1AC3DA00906 ON prescriptions (medecins_id)');
    }
}
