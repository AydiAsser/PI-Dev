<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240305125732 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE planning_medecins DROP FOREIGN KEY FK_5E1724704F31A84');
        $this->addSql('DROP INDEX IDX_5E1724704F31A84 ON planning_medecins');
        $this->addSql('ALTER TABLE planning_medecins DROP medecin_id, DROP nbr');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE planning_medecins ADD medecin_id INT DEFAULT NULL, ADD nbr INT NOT NULL');
        $this->addSql('ALTER TABLE planning_medecins ADD CONSTRAINT FK_5E1724704F31A84 FOREIGN KEY (medecin_id) REFERENCES medecins (id)');
        $this->addSql('CREATE INDEX IDX_5E1724704F31A84 ON planning_medecins (medecin_id)');
    }
}
