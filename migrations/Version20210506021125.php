<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210506021125 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE coupon (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(50) NOT NULL, type VARCHAR(50) NOT NULL, value DOUBLE PRECISION DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE coupon_rule (id INT AUTO_INCREMENT NOT NULL, coupon_id INT NOT NULL, type VARCHAR(50) NOT NULL, value DOUBLE PRECISION DEFAULT NULL, INDEX IDX_6E670CE66C5951B (coupon_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `order` (id INT AUTO_INCREMENT NOT NULL, price DOUBLE PRECISION NOT NULL, discount DOUBLE PRECISION NOT NULL, total DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE order_coupon (id INT AUTO_INCREMENT NOT NULL, order_id INT NOT NULL, code VARCHAR(50) NOT NULL, price DOUBLE PRECISION NOT NULL, type VARCHAR(50) NOT NULL, INDEX IDX_A7302FD78D9F6D38 (order_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE order_coupon_rule (id INT AUTO_INCREMENT NOT NULL, coupon_id INT NOT NULL, type VARCHAR(50) NOT NULL, value DOUBLE PRECISION NOT NULL, INDEX IDX_1D540F4866C5951B (coupon_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE order_product (id INT AUTO_INCREMENT NOT NULL, order_id INT NOT NULL, code VARCHAR(50) NOT NULL, price DOUBLE PRECISION NOT NULL, INDEX IDX_2530ADE68D9F6D38 (order_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(50) NOT NULL, price DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE coupon_rule ADD CONSTRAINT FK_6E670CE66C5951B FOREIGN KEY (coupon_id) REFERENCES coupon (id)');
        $this->addSql('ALTER TABLE order_coupon ADD CONSTRAINT FK_A7302FD78D9F6D38 FOREIGN KEY (order_id) REFERENCES `order` (id)');
        $this->addSql('ALTER TABLE order_coupon_rule ADD CONSTRAINT FK_1D540F4866C5951B FOREIGN KEY (coupon_id) REFERENCES order_coupon (id)');
        $this->addSql('ALTER TABLE order_product ADD CONSTRAINT FK_2530ADE68D9F6D38 FOREIGN KEY (order_id) REFERENCES `order` (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE coupon_rule DROP FOREIGN KEY FK_6E670CE66C5951B');
        $this->addSql('ALTER TABLE order_coupon DROP FOREIGN KEY FK_A7302FD78D9F6D38');
        $this->addSql('ALTER TABLE order_product DROP FOREIGN KEY FK_2530ADE68D9F6D38');
        $this->addSql('ALTER TABLE order_coupon_rule DROP FOREIGN KEY FK_1D540F4866C5951B');
        $this->addSql('DROP TABLE coupon');
        $this->addSql('DROP TABLE coupon_rule');
        $this->addSql('DROP TABLE `order`');
        $this->addSql('DROP TABLE order_coupon');
        $this->addSql('DROP TABLE order_coupon_rule');
        $this->addSql('DROP TABLE order_product');
        $this->addSql('DROP TABLE product');
    }
}
