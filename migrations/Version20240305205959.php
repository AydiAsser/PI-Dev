<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240305205959 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE medicament (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, prix DOUBLE PRECISION NOT NULL, categorie VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE panier (id INT AUTO_INCREMENT NOT NULL, client_id INT DEFAULT NULL, medi_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, prix DOUBLE PRECISION NOT NULL, description VARCHAR(255) NOT NULL, categorie VARCHAR(255) NOT NULL, quantite INT NOT NULL, INDEX IDX_24CC0DF219EB6921 (client_id), INDEX IDX_24CC0DF29CA81994 (medi_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE prescri (id INT AUTO_INCREMENT NOT NULL, medicament_id INT DEFAULT NULL, patient_id INT DEFAULT NULL, quantite INT NOT NULL, date_debut DATE NOT NULL, date_fin DATE NOT NULL, INDEX IDX_7C40E321AB0D61F7 (medicament_id), INDEX IDX_7C40E3216B899279 (patient_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE panier ADD CONSTRAINT FK_24CC0DF219EB6921 FOREIGN KEY (client_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE panier ADD CONSTRAINT FK_24CC0DF29CA81994 FOREIGN KEY (medi_id) REFERENCES medicament (id)');
        $this->addSql('ALTER TABLE prescri ADD CONSTRAINT FK_7C40E321AB0D61F7 FOREIGN KEY (medicament_id) REFERENCES medicament (id)');
        $this->addSql('ALTER TABLE prescri ADD CONSTRAINT FK_7C40E3216B899279 FOREIGN KEY (patient_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE panier DROP FOREIGN KEY FK_24CC0DF219EB6921');
        $this->addSql('ALTER TABLE panier DROP FOREIGN KEY FK_24CC0DF29CA81994');
        $this->addSql('ALTER TABLE prescri DROP FOREIGN KEY FK_7C40E321AB0D61F7');
        $this->addSql('ALTER TABLE prescri DROP FOREIGN KEY FK_7C40E3216B899279');
        $this->addSql('DROP TABLE medicament');
        $this->addSql('DROP TABLE panier');
        $this->addSql('DROP TABLE prescri');
    }
}
