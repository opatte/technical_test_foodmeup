<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170301023658 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE family (id INT AUTO_INCREMENT NOT NULL, uuid VARCHAR(255) NOT NULL, status VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, created_by VARCHAR(255) NOT NULL, updated_at DATETIME DEFAULT NULL, updated_by VARCHAR(255) DEFAULT NULL, origgpcd VARCHAR(255) NOT NULL, UNIQUE INDEX unique_family_uuid (uuid), UNIQUE INDEX unique_family_origgpcd (origgpcd), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE family_content (id INT AUTO_INCREMENT NOT NULL, id_family INT NOT NULL, uuid VARCHAR(255) NOT NULL, status VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, created_by VARCHAR(255) NOT NULL, updated_at DATETIME DEFAULT NULL, updated_by VARCHAR(255) DEFAULT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, lang VARCHAR(255) NOT NULL, INDEX IDX_93D1E41583D194B6 (id_family), UNIQUE INDEX unique_family_content_uuid (uuid), UNIQUE INDEX unique_family_content_slug (slug), UNIQUE INDEX unique_family_content_by_family_and_lang_and_name (id_family, lang, name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ingredient (id INT AUTO_INCREMENT NOT NULL, id_family INT NOT NULL, uuid VARCHAR(255) NOT NULL, status VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, created_by VARCHAR(255) NOT NULL, updated_at DATETIME DEFAULT NULL, updated_by VARCHAR(255) DEFAULT NULL, origfdcd INT NOT NULL, INDEX IDX_6BAF787083D194B6 (id_family), UNIQUE INDEX unique_ingredient_uuid (uuid), UNIQUE INDEX unique_ingredient_origfdcd (origfdcd), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ingredient_content (id INT AUTO_INCREMENT NOT NULL, id_ingredient INT NOT NULL, uuid VARCHAR(255) NOT NULL, status VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, created_by VARCHAR(255) NOT NULL, updated_at DATETIME DEFAULT NULL, updated_by VARCHAR(255) DEFAULT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, lang VARCHAR(255) NOT NULL, INDEX IDX_FFF23B6FCE25F8A7 (id_ingredient), UNIQUE INDEX unique_ingredient_content_uuid (uuid), UNIQUE INDEX unique_ingredient_content_slug (slug), UNIQUE INDEX unique_ingredient_content_by_ingredient_and_lang_and_name (id_ingredient, lang, name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE family_content ADD CONSTRAINT FK_93D1E41583D194B6 FOREIGN KEY (id_family) REFERENCES family (id)');
        $this->addSql('ALTER TABLE ingredient ADD CONSTRAINT FK_6BAF787083D194B6 FOREIGN KEY (id_family) REFERENCES family (id)');
        $this->addSql('ALTER TABLE ingredient_content ADD CONSTRAINT FK_FFF23B6FCE25F8A7 FOREIGN KEY (id_ingredient) REFERENCES ingredient (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE family_content DROP FOREIGN KEY FK_93D1E41583D194B6');
        $this->addSql('ALTER TABLE ingredient DROP FOREIGN KEY FK_6BAF787083D194B6');
        $this->addSql('ALTER TABLE ingredient_content DROP FOREIGN KEY FK_FFF23B6FCE25F8A7');
        $this->addSql('DROP TABLE family');
        $this->addSql('DROP TABLE family_content');
        $this->addSql('DROP TABLE ingredient');
        $this->addSql('DROP TABLE ingredient_content');
    }
}
