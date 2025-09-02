<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250902205354 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE app_category (id BIGINT AUTO_INCREMENT NOT NULL, parent_id BIGINT DEFAULT NULL, name VARCHAR(200) NOT NULL, description LONGTEXT DEFAULT NULL, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, INDEX IDX_ECC796C727ACA70 (parent_id), INDEX category_name_idx (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE app_category_meta (id BIGINT AUTO_INCREMENT NOT NULL, category_id BIGINT NOT NULL, meta_key VARCHAR(255) NOT NULL, meta_value LONGTEXT DEFAULT NULL, INDEX IDX_C61453DE12469DE2 (category_id), INDEX category_meta_key_idx (meta_key), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE app_customer (id BIGINT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, email VARCHAR(180) DEFAULT NULL, phone VARCHAR(30) DEFAULT NULL, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, type VARCHAR(30) DEFAULT NULL, UNIQUE INDEX UNIQ_89B9EEA4E7927C74 (email), UNIQUE INDEX UNIQ_89B9EEA4444F97DD (phone), INDEX customer_name_email_phone_idx (name, email, phone), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE app_customer_meta (id BIGINT AUTO_INCREMENT NOT NULL, customer_id BIGINT NOT NULL, meta_key VARCHAR(255) NOT NULL, meta_value LONGTEXT DEFAULT NULL, INDEX IDX_191E8DEE9395C3F3 (customer_id), INDEX customer_meta_key_idx (meta_key), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE app_inventory_movement (id BIGINT AUTO_INCREMENT NOT NULL, product_id BIGINT NOT NULL, inventor_id BIGINT NOT NULL, type VARCHAR(30) NOT NULL, quantity DOUBLE PRECISION DEFAULT \'0\' NOT NULL, reason VARCHAR(100) DEFAULT NULL, notes LONGTEXT DEFAULT NULL, date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, INDEX IDX_2086E41E4584665A (product_id), INDEX IDX_2086E41E9CECD5D4 (inventor_id), INDEX inventory_movement_type_idx (type), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE app_inventory_movement_meta (id BIGINT AUTO_INCREMENT NOT NULL, inventory_movement_id BIGINT NOT NULL, meta_key VARCHAR(255) NOT NULL, meta_value LONGTEXT DEFAULT NULL, INDEX IDX_7C26D832566C4C68 (inventory_movement_id), INDEX inventory_movement_meta_key_idx (inventory_movement_id, meta_key), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE app_option (id BIGINT AUTO_INCREMENT NOT NULL, option_key VARCHAR(200) NOT NULL, option_value LONGTEXT DEFAULT NULL, UNIQUE INDEX UNIQ_BF856D303CEE7BEE (option_key), INDEX option_key_idx (option_key), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE app_order (id BIGINT AUTO_INCREMENT NOT NULL, customer_id BIGINT DEFAULT NULL, cashier_id BIGINT NOT NULL, code VARCHAR(50) NOT NULL, total_amount DOUBLE PRECISION NOT NULL, date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, UNIQUE INDEX UNIQ_23FA1E5577153098 (code), INDEX IDX_23FA1E559395C3F3 (customer_id), INDEX IDX_23FA1E552EDB0489 (cashier_id), INDEX order_code_date_idx (code, date), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE app_order_item (id BIGINT AUTO_INCREMENT NOT NULL, order_id BIGINT NOT NULL, product_id BIGINT DEFAULT NULL, name VARCHAR(255) NOT NULL, quantity DOUBLE PRECISION DEFAULT \'0\' NOT NULL, unit_price DOUBLE PRECISION DEFAULT \'0\' NOT NULL, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, INDEX IDX_4F1B4758D9F6D38 (order_id), INDEX IDX_4F1B4754584665A (product_id), INDEX order_item_name_idx (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE app_order_item_meta (id BIGINT AUTO_INCREMENT NOT NULL, order_item_id BIGINT NOT NULL, meta_key VARCHAR(255) NOT NULL, meta_value LONGTEXT DEFAULT NULL, INDEX IDX_F99F0EBFE415FB15 (order_item_id), INDEX order_item_meta_key_idx (meta_key), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE app_order_meta (id BIGINT AUTO_INCREMENT NOT NULL, order_id BIGINT NOT NULL, meta_key VARCHAR(255) NOT NULL, meta_value LONGTEXT DEFAULT NULL, INDEX IDX_CC18855E8D9F6D38 (order_id), INDEX order_meta_key_idx (meta_key), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE app_order_payment (id BIGINT AUTO_INCREMENT NOT NULL, order_id BIGINT NOT NULL, method VARCHAR(50) NOT NULL, amount DOUBLE PRECISION DEFAULT \'0\' NOT NULL, notes LONGTEXT DEFAULT NULL, date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, INDEX IDX_12F10EA8D9F6D38 (order_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE app_order_payment_meta (id BIGINT AUTO_INCREMENT NOT NULL, payment_id BIGINT NOT NULL, meta_key VARCHAR(255) NOT NULL, meta_value LONGTEXT DEFAULT NULL, INDEX IDX_92A0DC534C3A3BB (payment_id), INDEX payment_meta_key_idx (meta_key), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE app_product (id BIGINT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, sku VARCHAR(100) DEFAULT NULL, price DOUBLE PRECISION DEFAULT \'0\' NOT NULL, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, UNIQUE INDEX UNIQ_3E1784E0F9038C4 (sku), INDEX product_name_sku_idx (name, sku), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_category (product_id BIGINT NOT NULL, category_id BIGINT NOT NULL, INDEX IDX_CDFC73564584665A (product_id), INDEX IDX_CDFC735612469DE2 (category_id), PRIMARY KEY(product_id, category_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE app_product_meta (id BIGINT AUTO_INCREMENT NOT NULL, product_id BIGINT NOT NULL, meta_key VARCHAR(255) NOT NULL, meta_value LONGTEXT DEFAULT NULL, INDEX IDX_98942C154584665A (product_id), INDEX product_meta_key_idx (meta_key), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE app_user (id BIGINT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, email VARCHAR(180) DEFAULT NULL, phone VARCHAR(30) DEFAULT NULL, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, username VARCHAR(180) NOT NULL, password VARCHAR(255) NOT NULL, roles JSON NOT NULL, last_login DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_88BDF3E9E7927C74 (email), UNIQUE INDEX UNIQ_88BDF3E9444F97DD (phone), UNIQUE INDEX UNIQ_88BDF3E9F85E0677 (username), INDEX user_name_username_email_idx (name, username, email, phone), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE app_user_meta (id BIGINT AUTO_INCREMENT NOT NULL, user_id BIGINT NOT NULL, meta_key VARCHAR(255) NOT NULL, meta_value LONGTEXT DEFAULT NULL, INDEX IDX_51C07C9A76ED395 (user_id), INDEX user_meta_key_idx (meta_key), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE app_category ADD CONSTRAINT FK_ECC796C727ACA70 FOREIGN KEY (parent_id) REFERENCES app_category (id)');
        $this->addSql('ALTER TABLE app_category_meta ADD CONSTRAINT FK_C61453DE12469DE2 FOREIGN KEY (category_id) REFERENCES app_category (id)');
        $this->addSql('ALTER TABLE app_customer_meta ADD CONSTRAINT FK_191E8DEE9395C3F3 FOREIGN KEY (customer_id) REFERENCES app_customer (id)');
        $this->addSql('ALTER TABLE app_inventory_movement ADD CONSTRAINT FK_2086E41E4584665A FOREIGN KEY (product_id) REFERENCES app_product (id)');
        $this->addSql('ALTER TABLE app_inventory_movement ADD CONSTRAINT FK_2086E41E9CECD5D4 FOREIGN KEY (inventor_id) REFERENCES app_user (id)');
        $this->addSql('ALTER TABLE app_inventory_movement_meta ADD CONSTRAINT FK_7C26D832566C4C68 FOREIGN KEY (inventory_movement_id) REFERENCES app_inventory_movement (id)');
        $this->addSql('ALTER TABLE app_order ADD CONSTRAINT FK_23FA1E559395C3F3 FOREIGN KEY (customer_id) REFERENCES app_customer (id)');
        $this->addSql('ALTER TABLE app_order ADD CONSTRAINT FK_23FA1E552EDB0489 FOREIGN KEY (cashier_id) REFERENCES app_user (id)');
        $this->addSql('ALTER TABLE app_order_item ADD CONSTRAINT FK_4F1B4758D9F6D38 FOREIGN KEY (order_id) REFERENCES app_order (id)');
        $this->addSql('ALTER TABLE app_order_item ADD CONSTRAINT FK_4F1B4754584665A FOREIGN KEY (product_id) REFERENCES app_product (id)');
        $this->addSql('ALTER TABLE app_order_item_meta ADD CONSTRAINT FK_F99F0EBFE415FB15 FOREIGN KEY (order_item_id) REFERENCES app_order_item (id)');
        $this->addSql('ALTER TABLE app_order_meta ADD CONSTRAINT FK_CC18855E8D9F6D38 FOREIGN KEY (order_id) REFERENCES app_order (id)');
        $this->addSql('ALTER TABLE app_order_payment ADD CONSTRAINT FK_12F10EA8D9F6D38 FOREIGN KEY (order_id) REFERENCES app_order (id)');
        $this->addSql('ALTER TABLE app_order_payment_meta ADD CONSTRAINT FK_92A0DC534C3A3BB FOREIGN KEY (payment_id) REFERENCES app_order_payment (id)');
        $this->addSql('ALTER TABLE product_category ADD CONSTRAINT FK_CDFC73564584665A FOREIGN KEY (product_id) REFERENCES app_product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_category ADD CONSTRAINT FK_CDFC735612469DE2 FOREIGN KEY (category_id) REFERENCES app_category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE app_product_meta ADD CONSTRAINT FK_98942C154584665A FOREIGN KEY (product_id) REFERENCES app_product (id)');
        $this->addSql('ALTER TABLE app_user_meta ADD CONSTRAINT FK_51C07C9A76ED395 FOREIGN KEY (user_id) REFERENCES app_user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE app_category DROP FOREIGN KEY FK_ECC796C727ACA70');
        $this->addSql('ALTER TABLE app_category_meta DROP FOREIGN KEY FK_C61453DE12469DE2');
        $this->addSql('ALTER TABLE app_customer_meta DROP FOREIGN KEY FK_191E8DEE9395C3F3');
        $this->addSql('ALTER TABLE app_inventory_movement DROP FOREIGN KEY FK_2086E41E4584665A');
        $this->addSql('ALTER TABLE app_inventory_movement DROP FOREIGN KEY FK_2086E41E9CECD5D4');
        $this->addSql('ALTER TABLE app_inventory_movement_meta DROP FOREIGN KEY FK_7C26D832566C4C68');
        $this->addSql('ALTER TABLE app_order DROP FOREIGN KEY FK_23FA1E559395C3F3');
        $this->addSql('ALTER TABLE app_order DROP FOREIGN KEY FK_23FA1E552EDB0489');
        $this->addSql('ALTER TABLE app_order_item DROP FOREIGN KEY FK_4F1B4758D9F6D38');
        $this->addSql('ALTER TABLE app_order_item DROP FOREIGN KEY FK_4F1B4754584665A');
        $this->addSql('ALTER TABLE app_order_item_meta DROP FOREIGN KEY FK_F99F0EBFE415FB15');
        $this->addSql('ALTER TABLE app_order_meta DROP FOREIGN KEY FK_CC18855E8D9F6D38');
        $this->addSql('ALTER TABLE app_order_payment DROP FOREIGN KEY FK_12F10EA8D9F6D38');
        $this->addSql('ALTER TABLE app_order_payment_meta DROP FOREIGN KEY FK_92A0DC534C3A3BB');
        $this->addSql('ALTER TABLE product_category DROP FOREIGN KEY FK_CDFC73564584665A');
        $this->addSql('ALTER TABLE product_category DROP FOREIGN KEY FK_CDFC735612469DE2');
        $this->addSql('ALTER TABLE app_product_meta DROP FOREIGN KEY FK_98942C154584665A');
        $this->addSql('ALTER TABLE app_user_meta DROP FOREIGN KEY FK_51C07C9A76ED395');
        $this->addSql('DROP TABLE app_category');
        $this->addSql('DROP TABLE app_category_meta');
        $this->addSql('DROP TABLE app_customer');
        $this->addSql('DROP TABLE app_customer_meta');
        $this->addSql('DROP TABLE app_inventory_movement');
        $this->addSql('DROP TABLE app_inventory_movement_meta');
        $this->addSql('DROP TABLE app_option');
        $this->addSql('DROP TABLE app_order');
        $this->addSql('DROP TABLE app_order_item');
        $this->addSql('DROP TABLE app_order_item_meta');
        $this->addSql('DROP TABLE app_order_meta');
        $this->addSql('DROP TABLE app_order_payment');
        $this->addSql('DROP TABLE app_order_payment_meta');
        $this->addSql('DROP TABLE app_product');
        $this->addSql('DROP TABLE product_category');
        $this->addSql('DROP TABLE app_product_meta');
        $this->addSql('DROP TABLE app_user');
        $this->addSql('DROP TABLE app_user_meta');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
