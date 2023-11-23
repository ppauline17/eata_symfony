<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231123130010 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE teammate ADD picture VARCHAR(255) DEFAULT NULL, ADD description VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE teammate ADD CONSTRAINT FK_C06EEBAE12469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('CREATE INDEX IDX_C06EEBAE12469DE2 ON teammate (category_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE teammate DROP FOREIGN KEY FK_C06EEBAE12469DE2');
        $this->addSql('DROP INDEX IDX_C06EEBAE12469DE2 ON teammate');
        $this->addSql('ALTER TABLE teammate DROP picture, DROP description');
    }
}
