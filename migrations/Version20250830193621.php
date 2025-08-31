<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250830193621 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE app_option (
              id BIGINT AUTO_INCREMENT NOT NULL,
              option_key VARCHAR(200) NOT NULL,
              option_value LONGTEXT DEFAULT NULL,
              UNIQUE INDEX UNIQ_BF856D303CEE7BEE (option_key),
              INDEX option_key_idx (option_key),
              PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE category (
              id BIGINT AUTO_INCREMENT NOT NULL,
              parent_id BIGINT DEFAULT NULL,
              name VARCHAR(200) NOT NULL,
              description LONGTEXT DEFAULT NULL,
              created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
              updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON
              UPDATE
                CURRENT_TIMESTAMP,
                INDEX IDX_64C19C1727ACA70 (parent_id),
                INDEX category_name_idx (name),
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE category_meta (
              id BIGINT AUTO_INCREMENT NOT NULL,
              category_id BIGINT NOT NULL,
              meta_key VARCHAR(255) NOT NULL,
              meta_value LONGTEXT DEFAULT NULL,
              INDEX IDX_5C696E7212469DE2 (category_id),
              INDEX category_meta_key_idx (meta_key),
              PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE customer (
              id BIGINT AUTO_INCREMENT NOT NULL,
              name VARCHAR(100) NOT NULL,
              email VARCHAR(180) DEFAULT NULL,
              phone VARCHAR(30) DEFAULT NULL,
              created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
              updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON
              UPDATE
                CURRENT_TIMESTAMP,
                type VARCHAR(30) DEFAULT NULL,
                UNIQUE INDEX UNIQ_81398E09E7927C74 (email),
                UNIQUE INDEX UNIQ_81398E09444F97DD (phone),
                INDEX customer_name_email_phone_idx (name, email, phone),
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE customer_meta (
              id BIGINT AUTO_INCREMENT NOT NULL,
              customer_id BIGINT NOT NULL,
              meta_key VARCHAR(255) NOT NULL,
              meta_value LONGTEXT DEFAULT NULL,
              INDEX IDX_8363B0429395C3F3 (customer_id),
              INDEX customer_meta_key_idx (meta_key),
              PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE inventory_movement (
              id BIGINT AUTO_INCREMENT NOT NULL,
              product_id BIGINT NOT NULL,
              inventor_id BIGINT NOT NULL,
              type VARCHAR(30) NOT NULL,
              quantity DOUBLE PRECISION DEFAULT '0' NOT NULL,
              reason VARCHAR(100) DEFAULT NULL,
              notes LONGTEXT DEFAULT NULL,
              date DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)',
              created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
              updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON
              UPDATE
                CURRENT_TIMESTAMP,
                INDEX IDX_40972F664584665A (product_id),
                INDEX IDX_40972F669CECD5D4 (inventor_id),
                INDEX inventory_movement_type_idx (type),
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE inventory_movement_meta (
              id BIGINT AUTO_INCREMENT NOT NULL,
              inventory_movement_id BIGINT NOT NULL,
              meta_key VARCHAR(255) NOT NULL,
              meta_value LONGTEXT DEFAULT NULL,
              INDEX IDX_6CD1B8DB566C4C68 (inventory_movement_id),
              INDEX inventory_movement_meta_key_idx (inventory_movement_id, meta_key),
              PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE `order` (
              id BIGINT AUTO_INCREMENT NOT NULL,
              customer_id BIGINT DEFAULT NULL,
              cashier_id BIGINT NOT NULL,
              code VARCHAR(50) NOT NULL,
              total_amount DOUBLE PRECISION NOT NULL,
              date DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)',
              created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
              updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON
              UPDATE
                CURRENT_TIMESTAMP,
                UNIQUE INDEX UNIQ_F529939877153098 (code),
                INDEX IDX_F52993989395C3F3 (customer_id),
                INDEX IDX_F52993982EDB0489 (cashier_id),
                INDEX order_code_date_idx (code, date),
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE order_item (
              id BIGINT AUTO_INCREMENT NOT NULL,
              order_id BIGINT NOT NULL,
              product_id BIGINT DEFAULT NULL,
              name VARCHAR(255) NOT NULL,
              quantity DOUBLE PRECISION DEFAULT '0' NOT NULL,
              unit_price DOUBLE PRECISION DEFAULT '0' NOT NULL,
              created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
              updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON
              UPDATE
                CURRENT_TIMESTAMP,
                INDEX IDX_52EA1F098D9F6D38 (order_id),
                INDEX IDX_52EA1F094584665A (product_id),
                INDEX order_item_name_idx (name),
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE order_item_meta (
              id BIGINT AUTO_INCREMENT NOT NULL,
              order_item_id BIGINT NOT NULL,
              meta_key VARCHAR(255) NOT NULL,
              meta_value LONGTEXT DEFAULT NULL,
              INDEX IDX_A3452B36E415FB15 (order_item_id),
              INDEX order_item_meta_key_idx (meta_key),
              PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE order_meta (
              id BIGINT AUTO_INCREMENT NOT NULL,
              order_id BIGINT NOT NULL,
              meta_key VARCHAR(255) NOT NULL,
              meta_value LONGTEXT DEFAULT NULL,
              INDEX IDX_9A032E228D9F6D38 (order_id),
              INDEX order_meta_key_idx (meta_key),
              PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE order_payment (
              id BIGINT AUTO_INCREMENT NOT NULL,
              order_id BIGINT NOT NULL,
              method VARCHAR(50) NOT NULL,
              amount DOUBLE PRECISION DEFAULT '0' NOT NULL,
              notes LONGTEXT DEFAULT NULL,
              date DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)',
              created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
              updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON
              UPDATE
                CURRENT_TIMESTAMP,
                INDEX IDX_9B522D468D9F6D38 (order_id),
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE order_payment_meta (
              id BIGINT AUTO_INCREMENT NOT NULL,
              payment_id BIGINT NOT NULL,
              meta_key VARCHAR(255) NOT NULL,
              meta_value LONGTEXT DEFAULT NULL,
              INDEX IDX_F2B1172B4C3A3BB (payment_id),
              INDEX payment_meta_key_idx (meta_key),
              PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE product (
              id BIGINT AUTO_INCREMENT NOT NULL,
              name VARCHAR(255) NOT NULL,
              sku VARCHAR(100) DEFAULT NULL,
              price DOUBLE PRECISION DEFAULT '0' NOT NULL,
              created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
              updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON
              UPDATE
                CURRENT_TIMESTAMP,
                UNIQUE INDEX UNIQ_D34A04ADF9038C4 (sku),
                INDEX product_name_sku_idx (name, sku),
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE product_category (
              product_id BIGINT NOT NULL,
              category_id BIGINT NOT NULL,
              INDEX IDX_CDFC73564584665A (product_id),
              INDEX IDX_CDFC735612469DE2 (category_id),
              PRIMARY KEY(product_id, category_id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE product_meta (
              id BIGINT AUTO_INCREMENT NOT NULL,
              product_id BIGINT NOT NULL,
              meta_key VARCHAR(255) NOT NULL,
              meta_value LONGTEXT DEFAULT NULL,
              INDEX IDX_5A1A36944584665A (product_id),
              INDEX product_meta_key_idx (meta_key),
              PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE user (
              id BIGINT AUTO_INCREMENT NOT NULL,
              name VARCHAR(100) NOT NULL,
              username VARCHAR(180) NOT NULL,
              email VARCHAR(180) NOT NULL,
              phone VARCHAR(30) DEFAULT NULL,
              password VARCHAR(255) NOT NULL,
              roles JSON NOT NULL,
              last_login DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
              created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
              updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON
              UPDATE
                CURRENT_TIMESTAMP,
                UNIQUE INDEX UNIQ_8D93D649F85E0677 (username),
                UNIQUE INDEX UNIQ_8D93D649E7927C74 (email),
                UNIQUE INDEX UNIQ_8D93D649444F97DD (phone),
                INDEX user_name_username_email_idx (name, username, email, phone),
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE user_meta (
              id BIGINT AUTO_INCREMENT NOT NULL,
              user_id BIGINT NOT NULL,
              meta_key VARCHAR(255) NOT NULL,
              meta_value LONGTEXT DEFAULT NULL,
              INDEX IDX_AD7358FCA76ED395 (user_id),
              INDEX user_meta_key_idx (meta_key),
              PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE messenger_messages (
              id BIGINT AUTO_INCREMENT NOT NULL,
              body LONGTEXT NOT NULL,
              headers LONGTEXT NOT NULL,
              queue_name VARCHAR(190) NOT NULL,
              created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)',
              available_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)',
              delivered_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
              INDEX IDX_75EA56E0FB7336F0 (queue_name),
              INDEX IDX_75EA56E0E3BD61CE (available_at),
              INDEX IDX_75EA56E016BA31DB (delivered_at),
              PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE
              category
            ADD
              CONSTRAINT FK_64C19C1727ACA70 FOREIGN KEY (parent_id) REFERENCES category (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE
              category_meta
            ADD
              CONSTRAINT FK_5C696E7212469DE2 FOREIGN KEY (category_id) REFERENCES category (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE
              customer_meta
            ADD
              CONSTRAINT FK_8363B0429395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE
              inventory_movement
            ADD
              CONSTRAINT FK_40972F664584665A FOREIGN KEY (product_id) REFERENCES product (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE
              inventory_movement
            ADD
              CONSTRAINT FK_40972F669CECD5D4 FOREIGN KEY (inventor_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE
              inventory_movement_meta
            ADD
              CONSTRAINT FK_6CD1B8DB566C4C68 FOREIGN KEY (inventory_movement_id) REFERENCES inventory_movement (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE
              `order`
            ADD
              CONSTRAINT FK_F52993989395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE
              `order`
            ADD
              CONSTRAINT FK_F52993982EDB0489 FOREIGN KEY (cashier_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE
              order_item
            ADD
              CONSTRAINT FK_52EA1F098D9F6D38 FOREIGN KEY (order_id) REFERENCES `order` (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE
              order_item
            ADD
              CONSTRAINT FK_52EA1F094584665A FOREIGN KEY (product_id) REFERENCES product (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE
              order_item_meta
            ADD
              CONSTRAINT FK_A3452B36E415FB15 FOREIGN KEY (order_item_id) REFERENCES order_item (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE
              order_meta
            ADD
              CONSTRAINT FK_9A032E228D9F6D38 FOREIGN KEY (order_id) REFERENCES `order` (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE
              order_payment
            ADD
              CONSTRAINT FK_9B522D468D9F6D38 FOREIGN KEY (order_id) REFERENCES `order` (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE
              order_payment_meta
            ADD
              CONSTRAINT FK_F2B1172B4C3A3BB FOREIGN KEY (payment_id) REFERENCES order_payment (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE
              product_category
            ADD
              CONSTRAINT FK_CDFC73564584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE
              product_category
            ADD
              CONSTRAINT FK_CDFC735612469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE
              product_meta
            ADD
              CONSTRAINT FK_5A1A36944584665A FOREIGN KEY (product_id) REFERENCES product (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE
              user_meta
            ADD
              CONSTRAINT FK_AD7358FCA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE category DROP FOREIGN KEY FK_64C19C1727ACA70');
        $this->addSql('ALTER TABLE category_meta DROP FOREIGN KEY FK_5C696E7212469DE2');
        $this->addSql('ALTER TABLE customer_meta DROP FOREIGN KEY FK_8363B0429395C3F3');
        $this->addSql('ALTER TABLE inventory_movement DROP FOREIGN KEY FK_40972F664584665A');
        $this->addSql('ALTER TABLE inventory_movement DROP FOREIGN KEY FK_40972F669CECD5D4');
        $this->addSql('ALTER TABLE inventory_movement_meta DROP FOREIGN KEY FK_6CD1B8DB566C4C68');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F52993989395C3F3');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F52993982EDB0489');
        $this->addSql('ALTER TABLE order_item DROP FOREIGN KEY FK_52EA1F098D9F6D38');
        $this->addSql('ALTER TABLE order_item DROP FOREIGN KEY FK_52EA1F094584665A');
        $this->addSql('ALTER TABLE order_item_meta DROP FOREIGN KEY FK_A3452B36E415FB15');
        $this->addSql('ALTER TABLE order_meta DROP FOREIGN KEY FK_9A032E228D9F6D38');
        $this->addSql('ALTER TABLE order_payment DROP FOREIGN KEY FK_9B522D468D9F6D38');
        $this->addSql('ALTER TABLE order_payment_meta DROP FOREIGN KEY FK_F2B1172B4C3A3BB');
        $this->addSql('ALTER TABLE product_category DROP FOREIGN KEY FK_CDFC73564584665A');
        $this->addSql('ALTER TABLE product_category DROP FOREIGN KEY FK_CDFC735612469DE2');
        $this->addSql('ALTER TABLE product_meta DROP FOREIGN KEY FK_5A1A36944584665A');
        $this->addSql('ALTER TABLE user_meta DROP FOREIGN KEY FK_AD7358FCA76ED395');
        $this->addSql('DROP TABLE app_option');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE category_meta');
        $this->addSql('DROP TABLE customer');
        $this->addSql('DROP TABLE customer_meta');
        $this->addSql('DROP TABLE inventory_movement');
        $this->addSql('DROP TABLE inventory_movement_meta');
        $this->addSql('DROP TABLE `order`');
        $this->addSql('DROP TABLE order_item');
        $this->addSql('DROP TABLE order_item_meta');
        $this->addSql('DROP TABLE order_meta');
        $this->addSql('DROP TABLE order_payment');
        $this->addSql('DROP TABLE order_payment_meta');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE product_category');
        $this->addSql('DROP TABLE product_meta');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_meta');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
