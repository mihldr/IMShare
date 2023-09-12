<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230912200210 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE `key` (id INT AUTO_INCREMENT NOT NULL, generated_by_id INT NOT NULL, secret VARCHAR(255) NOT NULL, is_active TINYINT(1) NOT NULL, generated_at DATETIME NOT NULL, allow_images TINYINT(1) NOT NULL, allow_videos TINYINT(1) NOT NULL, allow_documents TINYINT(1) NOT NULL, allow_executables TINYINT(1) NOT NULL, INDEX IDX_8A90ABA91BDD81B (generated_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE `key` ADD CONSTRAINT FK_8A90ABA91BDD81B FOREIGN KEY (generated_by_id) REFERENCES `user` (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `key` DROP FOREIGN KEY FK_8A90ABA91BDD81B');
        $this->addSql('DROP TABLE `key`');
    }
}
