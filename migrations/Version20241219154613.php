<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241219154613 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE article (id SERIAL NOT NULL, libelle VARCHAR(255) NOT NULL, qte_stock INT NOT NULL, prix INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE client (id SERIAL NOT NULL, surname VARCHAR(255) NOT NULL, telephone VARCHAR(50) NOT NULL, adresse VARCHAR(50) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C7440455E7769B0F ON client (surname)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C7440455450FF010 ON client (telephone)');
        $this->addSql('CREATE TABLE detail_article_dette (id SERIAL NOT NULL, article_id INT NOT NULL, dette_id INT DEFAULT NULL, qte INT NOT NULL, total INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_640FA4E27294869C ON detail_article_dette (article_id)');
        $this->addSql('CREATE INDEX IDX_640FA4E2E11400A1 ON detail_article_dette (dette_id)');
        $this->addSql('CREATE TABLE detail_dette_request (id SERIAL NOT NULL, article_id INT NOT NULL, dette_request_id INT DEFAULT NULL, qte INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_6C3C825A7294869C ON detail_dette_request (article_id)');
        $this->addSql('CREATE INDEX IDX_6C3C825A3BFB958 ON detail_dette_request (dette_request_id)');
        $this->addSql('CREATE TABLE dette (id SERIAL NOT NULL, client_id INT NOT NULL, data DATE NOT NULL, montant INT NOT NULL, montant_verser INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_831BC80819EB6921 ON dette (client_id)');
        $this->addSql('CREATE TABLE dette_request (id SERIAL NOT NULL, client_id INT NOT NULL, date DATE DEFAULT NULL, montant INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_FC9F9CA119EB6921 ON dette_request (client_id)');
        $this->addSql('CREATE TABLE payement (id SERIAL NOT NULL, dette_id INT NOT NULL, montant INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_B20A7885E11400A1 ON payement (dette_id)');
        $this->addSql('CREATE TABLE "user" (id SERIAL NOT NULL, client_id INT DEFAULT NULL, login VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, nom VARCHAR(50) NOT NULL, prenom VARCHAR(255) NOT NULL, is_active BOOLEAN DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D64919EB6921 ON "user" (client_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_login ON "user" (login)');
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
        $this->addSql('ALTER TABLE detail_article_dette ADD CONSTRAINT FK_640FA4E27294869C FOREIGN KEY (article_id) REFERENCES article (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE detail_article_dette ADD CONSTRAINT FK_640FA4E2E11400A1 FOREIGN KEY (dette_id) REFERENCES dette (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE detail_dette_request ADD CONSTRAINT FK_6C3C825A7294869C FOREIGN KEY (article_id) REFERENCES article (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE detail_dette_request ADD CONSTRAINT FK_6C3C825A3BFB958 FOREIGN KEY (dette_request_id) REFERENCES dette_request (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE dette ADD CONSTRAINT FK_831BC80819EB6921 FOREIGN KEY (client_id) REFERENCES client (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE dette_request ADD CONSTRAINT FK_FC9F9CA119EB6921 FOREIGN KEY (client_id) REFERENCES client (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE payement ADD CONSTRAINT FK_B20A7885E11400A1 FOREIGN KEY (dette_id) REFERENCES dette (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "user" ADD CONSTRAINT FK_8D93D64919EB6921 FOREIGN KEY (client_id) REFERENCES client (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE detail_article_dette DROP CONSTRAINT FK_640FA4E27294869C');
        $this->addSql('ALTER TABLE detail_article_dette DROP CONSTRAINT FK_640FA4E2E11400A1');
        $this->addSql('ALTER TABLE detail_dette_request DROP CONSTRAINT FK_6C3C825A7294869C');
        $this->addSql('ALTER TABLE detail_dette_request DROP CONSTRAINT FK_6C3C825A3BFB958');
        $this->addSql('ALTER TABLE dette DROP CONSTRAINT FK_831BC80819EB6921');
        $this->addSql('ALTER TABLE dette_request DROP CONSTRAINT FK_FC9F9CA119EB6921');
        $this->addSql('ALTER TABLE payement DROP CONSTRAINT FK_B20A7885E11400A1');
        $this->addSql('ALTER TABLE "user" DROP CONSTRAINT FK_8D93D64919EB6921');
        $this->addSql('DROP TABLE article');
        $this->addSql('DROP TABLE client');
        $this->addSql('DROP TABLE detail_article_dette');
        $this->addSql('DROP TABLE detail_dette_request');
        $this->addSql('DROP TABLE dette');
        $this->addSql('DROP TABLE dette_request');
        $this->addSql('DROP TABLE payement');
        $this->addSql('DROP TABLE "user"');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
