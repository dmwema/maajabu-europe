<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240103110250 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE country ADD emoji_name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE user ADD origin_id INT DEFAULT NULL, ADD residence_id INT DEFAULT NULL, DROP bio');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64956A273CC FOREIGN KEY (origin_id) REFERENCES country (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6498B225FBD FOREIGN KEY (residence_id) REFERENCES country (id)');
        $this->addSql('CREATE INDEX IDX_8D93D64956A273CC ON user (origin_id)');
        $this->addSql('CREATE INDEX IDX_8D93D6498B225FBD ON user (residence_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `user` DROP FOREIGN KEY FK_8D93D64956A273CC');
        $this->addSql('ALTER TABLE `user` DROP FOREIGN KEY FK_8D93D6498B225FBD');
        $this->addSql('DROP INDEX IDX_8D93D64956A273CC ON `user`');
        $this->addSql('DROP INDEX IDX_8D93D6498B225FBD ON `user`');
        $this->addSql('ALTER TABLE `user` ADD bio VARCHAR(255) DEFAULT NULL, DROP origin_id, DROP residence_id');
        $this->addSql('ALTER TABLE country DROP emoji_name');
    }
}
