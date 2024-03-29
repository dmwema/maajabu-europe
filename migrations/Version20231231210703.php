<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231231210703 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE registration ADD video_path VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE user ADD firstname VARCHAR(255) NOT NULL, ADD lastname VARCHAR(255) NOT NULL, ADD birthday DATE NOT NULL, ADD address VARCHAR(255) NOT NULL, ADD phone_number VARCHAR(255) NOT NULL, ADD bio VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE registration DROP video_path');
        $this->addSql('ALTER TABLE `user` DROP firstname, DROP lastname, DROP birthday, DROP address, DROP phone_number, DROP bio');
    }
}
