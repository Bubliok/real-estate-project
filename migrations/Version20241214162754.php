<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241214162754 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE real_estate_owner DROP CONSTRAINT fk_2fed55574fc17d0b');
        $this->addSql('ALTER TABLE real_estate_agent DROP CONSTRAINT fk_c6062fb64fc17d0b');
        $this->addSql('DROP SEQUENCE web_user_id_seq CASCADE');
        $this->addSql('CREATE TABLE "user" (id SERIAL NOT NULL, username VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_USERNAME ON "user" (username)');
        $this->addSql('DROP TABLE web_user');
        $this->addSql('ALTER TABLE real_estate ALTER type_id DROP NOT NULL');
        $this->addSql('DROP INDEX uniq_c6062fb64fc17d0b');
        $this->addSql('ALTER TABLE real_estate_agent ALTER city_id DROP NOT NULL');
        $this->addSql('ALTER TABLE real_estate_agent RENAME COLUMN web_user_id TO user_id');
        $this->addSql('ALTER TABLE real_estate_agent ADD CONSTRAINT FK_C6062FB6A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C6062FB6A76ED395 ON real_estate_agent (user_id)');
        $this->addSql('ALTER TABLE real_estate_images ALTER real_estate_id DROP NOT NULL');
        $this->addSql('DROP INDEX uniq_2fed55574fc17d0b');
        $this->addSql('ALTER TABLE real_estate_owner RENAME COLUMN web_user_id TO user_id');
        $this->addSql('ALTER TABLE real_estate_owner ADD CONSTRAINT FK_2FED5557A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2FED5557A76ED395 ON real_estate_owner (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE real_estate_agent DROP CONSTRAINT FK_C6062FB6A76ED395');
        $this->addSql('ALTER TABLE real_estate_owner DROP CONSTRAINT FK_2FED5557A76ED395');
        $this->addSql('CREATE SEQUENCE web_user_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE web_user (id SERIAL NOT NULL, username VARCHAR(20) NOT NULL, password VARCHAR(50) NOT NULL, is_admin BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('DROP TABLE "user"');
        $this->addSql('DROP INDEX UNIQ_2FED5557A76ED395');
        $this->addSql('ALTER TABLE real_estate_owner RENAME COLUMN user_id TO web_user_id');
        $this->addSql('ALTER TABLE real_estate_owner ADD CONSTRAINT fk_2fed55574fc17d0b FOREIGN KEY (web_user_id) REFERENCES web_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX uniq_2fed55574fc17d0b ON real_estate_owner (web_user_id)');
        $this->addSql('ALTER TABLE real_estate ALTER type_id SET NOT NULL');
        $this->addSql('DROP INDEX UNIQ_C6062FB6A76ED395');
        $this->addSql('ALTER TABLE real_estate_agent ALTER city_id SET NOT NULL');
        $this->addSql('ALTER TABLE real_estate_agent RENAME COLUMN user_id TO web_user_id');
        $this->addSql('ALTER TABLE real_estate_agent ADD CONSTRAINT fk_c6062fb64fc17d0b FOREIGN KEY (web_user_id) REFERENCES web_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX uniq_c6062fb64fc17d0b ON real_estate_agent (web_user_id)');
        $this->addSql('ALTER TABLE real_estate_images ALTER real_estate_id SET NOT NULL');
    }
}
