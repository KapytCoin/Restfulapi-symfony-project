<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241011132654 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product DROP authors');
        $this->addSql('ALTER TABLE product DROP meap');
        $this->addSql('ALTER TABLE product DROP isbn');
        $this->addSql('ALTER TABLE product_format ADD discount_percent INT NOT NULL');
        $this->addSql('ALTER TABLE product_format ADD price NUMERIC(10, 2) NOT NULL');
        $this->addSql('ALTER TABLE product_to_product_format DROP CONSTRAINT fk_6a11adfcb8894088');
        $this->addSql('DROP INDEX idx_6a11adfcb8894088');
        $this->addSql('ALTER TABLE product_to_product_format RENAME COLUMN product_format_id TO format_id');
        $this->addSql('ALTER TABLE product_to_product_format ADD CONSTRAINT FK_6A11ADFCD629F605 FOREIGN KEY (format_id) REFERENCES product_format (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_6A11ADFCD629F605 ON product_to_product_format (format_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE product ADD authors TEXT NOT NULL');
        $this->addSql('ALTER TABLE product ADD meap BOOLEAN DEFAULT false NOT NULL');
        $this->addSql('ALTER TABLE product ADD isbn VARCHAR(13) NOT NULL');
        $this->addSql('COMMENT ON COLUMN product.authors IS \'(DC2Type:simple_array)\'');
        $this->addSql('ALTER TABLE product_format DROP discount_percent');
        $this->addSql('ALTER TABLE product_format DROP price');
        $this->addSql('ALTER TABLE product_to_product_format DROP CONSTRAINT FK_6A11ADFCD629F605');
        $this->addSql('DROP INDEX IDX_6A11ADFCD629F605');
        $this->addSql('ALTER TABLE product_to_product_format RENAME COLUMN format_id TO product_format_id');
        $this->addSql('ALTER TABLE product_to_product_format ADD CONSTRAINT fk_6a11adfcb8894088 FOREIGN KEY (product_format_id) REFERENCES product_format (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_6a11adfcb8894088 ON product_to_product_format (product_format_id)');
    }
}
