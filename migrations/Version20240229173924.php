<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240229173924 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE prescriptions ADD medicament_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE prescriptions ADD CONSTRAINT FK_E41E1AC3AB0D61F7 FOREIGN KEY (medicament_id) REFERENCES medicament (id)');
        $this->addSql('CREATE INDEX IDX_E41E1AC3AB0D61F7 ON prescriptions (medicament_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE prescriptions DROP FOREIGN KEY FK_E41E1AC3AB0D61F7');
        $this->addSql('DROP INDEX IDX_E41E1AC3AB0D61F7 ON prescriptions');
        $this->addSql('ALTER TABLE prescriptions DROP medicament_id');
    }
}
