<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220620233139 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE category CHANGE path path_category VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE product CHANGE path path_product VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE sub_category CHANGE path path_sub_category VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE category CHANGE path_category path VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE product CHANGE path_product path VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE sub_category CHANGE path_sub_category path VARCHAR(255) NOT NULL');
    }
}
