<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241213174650 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE broker_company (id SERIAL NOT NULL, city_id INT NOT NULL, company_name VARCHAR(30) NOT NULL, company_phone_number VARCHAR(10) NOT NULL, company_email VARCHAR(40) NOT NULL, company_address VARCHAR(50) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_F8DD0AB58BAC62AF ON broker_company (city_id)');
        $this->addSql('CREATE TABLE city (id SERIAL NOT NULL, city_name VARCHAR(30) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE neighborhood (id SERIAL NOT NULL, city_id INT NOT NULL, neighborhood_name VARCHAR(30) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_FEF1E9EE8BAC62AF ON neighborhood (city_id)');
        $this->addSql('CREATE TABLE real_estate (id SERIAL NOT NULL, type_id INT NOT NULL, city_id INT NOT NULL, neighborhood_id INT NOT NULL, agent_id INT NOT NULL, owner_id INT NOT NULL, estate_name VARCHAR(40) NOT NULL, estate_area DOUBLE PRECISION NOT NULL, estate_floor INT NOT NULL, is_furnished BOOLEAN NOT NULL, estate_price DOUBLE PRECISION NOT NULL, estate_address VARCHAR(50) NOT NULL, date_built_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, date_added_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, is_active BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_DE4E97A8C54C8C93 ON real_estate (type_id)');
        $this->addSql('CREATE INDEX IDX_DE4E97A88BAC62AF ON real_estate (city_id)');
        $this->addSql('CREATE INDEX IDX_DE4E97A8803BB24B ON real_estate (neighborhood_id)');
        $this->addSql('CREATE INDEX IDX_DE4E97A83414710B ON real_estate (agent_id)');
        $this->addSql('CREATE INDEX IDX_DE4E97A87E3C61F9 ON real_estate (owner_id)');
        $this->addSql('COMMENT ON COLUMN real_estate.date_built_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN real_estate.date_added_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE real_estate_agent (id SERIAL NOT NULL, web_user_id INT NOT NULL, city_id INT NOT NULL, company_id INT DEFAULT NULL, agent_name VARCHAR(40) NOT NULL, agent_phone_number VARCHAR(10) NOT NULL, agent_email VARCHAR(40) NOT NULL, agent_commission DOUBLE PRECISION NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C6062FB64FC17D0B ON real_estate_agent (web_user_id)');
        $this->addSql('CREATE INDEX IDX_C6062FB68BAC62AF ON real_estate_agent (city_id)');
        $this->addSql('CREATE INDEX IDX_C6062FB6979B1AD6 ON real_estate_agent (company_id)');
        $this->addSql('CREATE TABLE real_estate_images (id SERIAL NOT NULL, real_estate_id INT NOT NULL, image_path VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_4C43CA991E4EB97C ON real_estate_images (real_estate_id)');
        $this->addSql('CREATE TABLE real_estate_owner (id SERIAL NOT NULL, city_id INT DEFAULT NULL, web_user_id INT NOT NULL, owner_name VARCHAR(40) NOT NULL, owner_phone_number VARCHAR(10) NOT NULL, owner_email VARCHAR(40) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_2FED55578BAC62AF ON real_estate_owner (city_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2FED55574FC17D0B ON real_estate_owner (web_user_id)');
        $this->addSql('CREATE TABLE real_estate_type (id SERIAL NOT NULL, type_name VARCHAR(30) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE web_user (id SERIAL NOT NULL, username VARCHAR(20) NOT NULL, password VARCHAR(50) NOT NULL, is_admin BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE messenger_messages (id BIGSERIAL NOT NULL, body TEXT NOT NULL, headers TEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, available_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, delivered_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)');
        $this->addSql('CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)');
        $this->addSql('CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)');
        $this->addSql('COMMENT ON COLUMN messenger_messages.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN messenger_messages.available_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN messenger_messages.delivered_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE OR REPLACE FUNCTION notify_messenger_messages() RETURNS TRIGGER AS $$
            BEGIN
                PERFORM pg_notify(\'messenger_messages\', NEW.queue_name::text);
                RETURN NEW;
            END;
        $$ LANGUAGE plpgsql;');
        $this->addSql('DROP TRIGGER IF EXISTS notify_trigger ON messenger_messages;');
        $this->addSql('CREATE TRIGGER notify_trigger AFTER INSERT OR UPDATE ON messenger_messages FOR EACH ROW EXECUTE PROCEDURE notify_messenger_messages();');
        $this->addSql('ALTER TABLE broker_company ADD CONSTRAINT FK_F8DD0AB58BAC62AF FOREIGN KEY (city_id) REFERENCES city (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE neighborhood ADD CONSTRAINT FK_FEF1E9EE8BAC62AF FOREIGN KEY (city_id) REFERENCES city (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE real_estate ADD CONSTRAINT FK_DE4E97A8C54C8C93 FOREIGN KEY (type_id) REFERENCES real_estate_type (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE real_estate ADD CONSTRAINT FK_DE4E97A88BAC62AF FOREIGN KEY (city_id) REFERENCES city (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE real_estate ADD CONSTRAINT FK_DE4E97A8803BB24B FOREIGN KEY (neighborhood_id) REFERENCES neighborhood (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE real_estate ADD CONSTRAINT FK_DE4E97A83414710B FOREIGN KEY (agent_id) REFERENCES real_estate_agent (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE real_estate ADD CONSTRAINT FK_DE4E97A87E3C61F9 FOREIGN KEY (owner_id) REFERENCES real_estate_owner (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE real_estate_agent ADD CONSTRAINT FK_C6062FB64FC17D0B FOREIGN KEY (web_user_id) REFERENCES web_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE real_estate_agent ADD CONSTRAINT FK_C6062FB68BAC62AF FOREIGN KEY (city_id) REFERENCES city (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE real_estate_agent ADD CONSTRAINT FK_C6062FB6979B1AD6 FOREIGN KEY (company_id) REFERENCES broker_company (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE real_estate_images ADD CONSTRAINT FK_4C43CA991E4EB97C FOREIGN KEY (real_estate_id) REFERENCES real_estate (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE real_estate_owner ADD CONSTRAINT FK_2FED55578BAC62AF FOREIGN KEY (city_id) REFERENCES city (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE real_estate_owner ADD CONSTRAINT FK_2FED55574FC17D0B FOREIGN KEY (web_user_id) REFERENCES web_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE broker_company DROP CONSTRAINT FK_F8DD0AB58BAC62AF');
        $this->addSql('ALTER TABLE neighborhood DROP CONSTRAINT FK_FEF1E9EE8BAC62AF');
        $this->addSql('ALTER TABLE real_estate DROP CONSTRAINT FK_DE4E97A8C54C8C93');
        $this->addSql('ALTER TABLE real_estate DROP CONSTRAINT FK_DE4E97A88BAC62AF');
        $this->addSql('ALTER TABLE real_estate DROP CONSTRAINT FK_DE4E97A8803BB24B');
        $this->addSql('ALTER TABLE real_estate DROP CONSTRAINT FK_DE4E97A83414710B');
        $this->addSql('ALTER TABLE real_estate DROP CONSTRAINT FK_DE4E97A87E3C61F9');
        $this->addSql('ALTER TABLE real_estate_agent DROP CONSTRAINT FK_C6062FB64FC17D0B');
        $this->addSql('ALTER TABLE real_estate_agent DROP CONSTRAINT FK_C6062FB68BAC62AF');
        $this->addSql('ALTER TABLE real_estate_agent DROP CONSTRAINT FK_C6062FB6979B1AD6');
        $this->addSql('ALTER TABLE real_estate_images DROP CONSTRAINT FK_4C43CA991E4EB97C');
        $this->addSql('ALTER TABLE real_estate_owner DROP CONSTRAINT FK_2FED55578BAC62AF');
        $this->addSql('ALTER TABLE real_estate_owner DROP CONSTRAINT FK_2FED55574FC17D0B');
        $this->addSql('DROP TABLE broker_company');
        $this->addSql('DROP TABLE city');
        $this->addSql('DROP TABLE neighborhood');
        $this->addSql('DROP TABLE real_estate');
        $this->addSql('DROP TABLE real_estate_agent');
        $this->addSql('DROP TABLE real_estate_images');
        $this->addSql('DROP TABLE real_estate_owner');
        $this->addSql('DROP TABLE real_estate_type');
        $this->addSql('DROP TABLE web_user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
