<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220619103616 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mission DROP FOREIGN KEY FK_9067F23C25F06C53');
        $this->addSql('ALTER TABLE mission ADD CONSTRAINT FK_9067F23C25F06C53 FOREIGN KEY (adherent_id) REFERENCES adherent (id) ON DELETE SET NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mission DROP FOREIGN KEY FK_9067F23C25F06C53');
        $this->addSql('ALTER TABLE mission ADD CONSTRAINT FK_9067F23C25F06C53 FOREIGN KEY (adherent_id) REFERENCES adherent (id)');
    }
}
