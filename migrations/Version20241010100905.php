<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241010100905 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE product_format_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE product_to_product_format_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE review_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE product_format (id INT NOT NULL, title VARCHAR(255) NOT NULL, description TEXT NOT NULL, comment VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE product_to_product_format (id INT NOT NULL, product_id INT NOT NULL, product_format_id INT NOT NULL, price NUMERIC(10, 2) NOT NULL, discount_percent INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_6A11ADFC4584665A ON product_to_product_format (product_id)');
        $this->addSql('CREATE INDEX IDX_6A11ADFCB8894088 ON product_to_product_format (product_format_id)');
        $this->addSql('CREATE TABLE review (id INT NOT NULL, product_id INT NOT NULL, rating INT NOT NULL, content TEXT NOT NULL, author VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_794381C64584665A ON review (product_id)');
        $this->addSql('COMMENT ON COLUMN review.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE product_to_product_format ADD CONSTRAINT FK_6A11ADFC4584665A FOREIGN KEY (product_id) REFERENCES product (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE product_to_product_format ADD CONSTRAINT FK_6A11ADFCB8894088 FOREIGN KEY (product_format_id) REFERENCES product_format (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C64584665A FOREIGN KEY (product_id) REFERENCES product (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE product ADD isbn VARCHAR(13) NOT NULL');
        $this->addSql('ALTER TABLE product ADD description TEXT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE product_format_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE product_to_product_format_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE review_id_seq CASCADE');
        $this->addSql('ALTER TABLE product_to_product_format DROP CONSTRAINT FK_6A11ADFC4584665A');
        $this->addSql('ALTER TABLE product_to_product_format DROP CONSTRAINT FK_6A11ADFCB8894088');
        $this->addSql('ALTER TABLE review DROP CONSTRAINT FK_794381C64584665A');
        $this->addSql('DROP TABLE product_format');
        $this->addSql('DROP TABLE product_to_product_format');
        $this->addSql('DROP TABLE review');
        $this->addSql('ALTER TABLE product DROP isbn');
        $this->addSql('ALTER TABLE product DROP description');
    }
}
