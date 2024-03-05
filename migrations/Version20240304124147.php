<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240304124147 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE rendezvouss DROP FOREIGN KEY FK_C3C37E4ADA00906');
        $this->addSql('DROP INDEX IDX_C3C37E4ADA00906 ON rendezvouss');
        $this->addSql('ALTER TABLE rendezvouss DROP medecins_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE rendezvouss ADD medecins_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE rendezvouss ADD CONSTRAINT FK_C3C37E4ADA00906 FOREIGN KEY (medecins_id) REFERENCES medecins (id)');
        $this->addSql('CREATE INDEX IDX_C3C37E4ADA00906 ON rendezvouss (medecins_id)');
    }
}
