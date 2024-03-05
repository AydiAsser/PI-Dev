<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240305124750 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE calendar DROP FOREIGN KEY FK_6EA9A14665D8A12E');
        $this->addSql('DROP INDEX IDX_6EA9A14665D8A12E ON calendar');
        $this->addSql('ALTER TABLE calendar DROP medecins_id_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE calendar ADD medecins_id_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE calendar ADD CONSTRAINT FK_6EA9A14665D8A12E FOREIGN KEY (medecins_id_id) REFERENCES medecins (id)');
        $this->addSql('CREATE INDEX IDX_6EA9A14665D8A12E ON calendar (medecins_id_id)');
    }
}
