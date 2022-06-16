<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220615234342 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE rank (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE product ADD rank_id INT NOT NULL, DROP level');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD7616678F FOREIGN KEY (rank_id) REFERENCES rank (id)');
        $this->addSql('CREATE INDEX IDX_D34A04AD7616678F ON product (rank_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD7616678F');
        $this->addSql('DROP TABLE rank');
        $this->addSql('DROP INDEX IDX_D34A04AD7616678F ON product');
        $this->addSql('ALTER TABLE product ADD level VARCHAR(255) NOT NULL, DROP rank_id');
    }
}
