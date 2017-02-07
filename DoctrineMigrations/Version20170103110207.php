<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170103110207 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE ext_translations (id INT AUTO_INCREMENT NOT NULL, locale VARCHAR(8) NOT NULL, object_class VARCHAR(255) NOT NULL, field VARCHAR(32) NOT NULL, foreign_key VARCHAR(64) NOT NULL, content LONGTEXT DEFAULT NULL, INDEX translations_lookup_idx (locale, object_class, foreign_key), UNIQUE INDEX lookup_unique_idx (locale, object_class, field, foreign_key), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categories (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_3AF346685E237E06 (name), UNIQUE INDEX UNIQ_3AF34668989D9B62 (slug), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category_translations (id INT AUTO_INCREMENT NOT NULL, object_id INT DEFAULT NULL, locale VARCHAR(8) NOT NULL, field VARCHAR(32) NOT NULL, content LONGTEXT DEFAULT NULL, INDEX IDX_1C60F915232D562B (object_id), UNIQUE INDEX UNIQ_1C60F9154180C698232D562B5BF54558 (locale, object_id, field), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE attachments (id INT AUTO_INCREMENT NOT NULL, file_id INT DEFAULT NULL, title VARCHAR(255) DEFAULT NULL, position INT NOT NULL, INDEX IDX_47C4FAD693CB796C (file_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE attachment_translations (id INT AUTO_INCREMENT NOT NULL, object_id INT DEFAULT NULL, locale VARCHAR(8) NOT NULL, field VARCHAR(32) NOT NULL, content LONGTEXT DEFAULT NULL, INDEX IDX_FC394778232D562B (object_id), UNIQUE INDEX UNIQ_FC3947784180C698232D562B5BF54558 (locale, object_id, field), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE files (id INT AUTO_INCREMENT NOT NULL, path VARCHAR(255) DEFAULT NULL, filename VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE images (id INT AUTO_INCREMENT NOT NULL, path VARCHAR(255) DEFAULT NULL, filename VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pages (id INT AUTO_INCREMENT NOT NULL, image_id INT DEFAULT NULL, gallery_id INT DEFAULT NULL, category_id INT DEFAULT NULL, slug VARCHAR(255) NOT NULL, body LONGTEXT DEFAULT NULL, alias VARCHAR(255) DEFAULT NULL, title VARCHAR(255) NOT NULL, teaser LONGTEXT DEFAULT NULL, published TINYINT(1) NOT NULL, page_type VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_2074E575989D9B62 (slug), INDEX IDX_2074E5753DA5256D (image_id), INDEX IDX_2074E5754E7AF8F (gallery_id), INDEX IDX_2074E57512469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE abstract_page_attachment (page_id INT NOT NULL, attachment_id INT NOT NULL, INDEX IDX_B871CA30C4663E4 (page_id), UNIQUE INDEX UNIQ_B871CA30464E68B (attachment_id), PRIMARY KEY(page_id, attachment_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pages_translations (id INT AUTO_INCREMENT NOT NULL, object_id INT DEFAULT NULL, locale VARCHAR(8) NOT NULL, field VARCHAR(32) NOT NULL, content LONGTEXT DEFAULT NULL, INDEX IDX_EAA721E0232D562B (object_id), UNIQUE INDEX UNIQ_EAA721E04180C698232D562B5BF54558 (locale, object_id, field), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE video (id INT AUTO_INCREMENT NOT NULL, video_id VARCHAR(128) NOT NULL, type VARCHAR(128) NOT NULL, url VARCHAR(128) NOT NULL, UNIQUE INDEX UNIQ_7CC7DA2CF47645AE (url), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE gallery (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE gallery_items (id INT AUTO_INCREMENT NOT NULL, gallery_id INT DEFAULT NULL, image_id INT DEFAULT NULL, video_id INT DEFAULT NULL, position INT NOT NULL, name TINYTEXT DEFAULT NULL, type VARCHAR(10) NOT NULL, INDEX IDX_583396E4E7AF8F (gallery_id), UNIQUE INDEX UNIQ_583396E3DA5256D (image_id), INDEX IDX_583396E29C1004E (video_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE gallery_items_translations (id INT AUTO_INCREMENT NOT NULL, object_id INT DEFAULT NULL, locale VARCHAR(8) NOT NULL, field VARCHAR(32) NOT NULL, content LONGTEXT DEFAULT NULL, INDEX IDX_E953FA8F232D562B (object_id), UNIQUE INDEX UNIQ_E953FA8F4180C698232D562B5BF54558 (locale, object_id, field), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tags (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(128) NOT NULL, UNIQUE INDEX unique_name_idx (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE news (id INT AUTO_INCREMENT NOT NULL, image_id INT DEFAULT NULL, gallery_id INT DEFAULT NULL, slug VARCHAR(255) NOT NULL, body LONGTEXT DEFAULT NULL, title VARCHAR(255) NOT NULL, teaser LONGTEXT DEFAULT NULL, created DATETIME NOT NULL, published TINYINT(1) NOT NULL, important TINYINT(1) NOT NULL, news_type VARCHAR(255) NOT NULL, start_date DATETIME DEFAULT NULL, end_date DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_1DD39950989D9B62 (slug), INDEX IDX_1DD399503DA5256D (image_id), INDEX IDX_1DD399504E7AF8F (gallery_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE news_tags (news_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_BA6162ADB5A459A0 (news_id), INDEX IDX_BA6162ADBAD26311 (tag_id), PRIMARY KEY(news_id, tag_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE abstract_news_attachment (page_id INT NOT NULL, attachment_id INT NOT NULL, INDEX IDX_5FEE2CD7C4663E4 (page_id), UNIQUE INDEX UNIQ_5FEE2CD7464E68B (attachment_id), PRIMARY KEY(page_id, attachment_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE news_translations (id INT AUTO_INCREMENT NOT NULL, object_id INT DEFAULT NULL, locale VARCHAR(8) NOT NULL, field VARCHAR(32) NOT NULL, content LONGTEXT DEFAULT NULL, INDEX IDX_20FDB330232D562B (object_id), UNIQUE INDEX UNIQ_20FDB3304180C698232D562B5BF54558 (locale, object_id, field), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE menu (id INT AUTO_INCREMENT NOT NULL, page_id INT DEFAULT NULL, root_id INT DEFAULT NULL, parent_id INT DEFAULT NULL, `label` VARCHAR(255) NOT NULL, path VARCHAR(255) DEFAULT NULL, external_url VARCHAR(255) DEFAULT NULL, slug VARCHAR(255) DEFAULT NULL, clickable TINYINT(1) NOT NULL, publish_date DATETIME DEFAULT NULL, lft INT NOT NULL, lvl INT NOT NULL, rgt INT NOT NULL, INDEX IDX_7D053A93C4663E4 (page_id), INDEX IDX_7D053A9379066886 (root_id), INDEX IDX_7D053A93727ACA70 (parent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE menu_translation (id INT AUTO_INCREMENT NOT NULL, object_id INT DEFAULT NULL, locale VARCHAR(8) NOT NULL, field VARCHAR(32) NOT NULL, content LONGTEXT DEFAULT NULL, INDEX IDX_DC955B23232D562B (object_id), UNIQUE INDEX UNIQ_DC955B234180C698232D562B5BF54558 (locale, object_id, field), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL, username_canonical VARCHAR(180) NOT NULL, email VARCHAR(180) NOT NULL, email_canonical VARCHAR(180) NOT NULL, enabled TINYINT(1) NOT NULL, salt VARCHAR(255) DEFAULT NULL, password VARCHAR(255) NOT NULL, last_login DATETIME DEFAULT NULL, confirmation_token VARCHAR(180) DEFAULT NULL, password_requested_at DATETIME DEFAULT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', UNIQUE INDEX UNIQ_1483A5E992FC23A8 (username_canonical), UNIQUE INDEX UNIQ_1483A5E9A0D96FBF (email_canonical), UNIQUE INDEX UNIQ_1483A5E9C05FB297 (confirmation_token), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE lexik_translation_file (id INT AUTO_INCREMENT NOT NULL, domain VARCHAR(255) NOT NULL, locale VARCHAR(10) NOT NULL, extention VARCHAR(10) NOT NULL, path VARCHAR(255) NOT NULL, hash VARCHAR(255) NOT NULL, UNIQUE INDEX hash_idx (hash), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE lexik_trans_unit (id INT AUTO_INCREMENT NOT NULL, key_name VARCHAR(255) NOT NULL, domain VARCHAR(255) NOT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, UNIQUE INDEX key_domain_idx (key_name, domain), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE lexik_trans_unit_translations (id INT AUTO_INCREMENT NOT NULL, file_id INT DEFAULT NULL, trans_unit_id INT DEFAULT NULL, locale VARCHAR(10) NOT NULL, content LONGTEXT NOT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_B0AA394493CB796C (file_id), INDEX IDX_B0AA3944C3C583C9 (trans_unit_id), UNIQUE INDEX trans_unit_locale_idx (trans_unit_id, locale), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE category_translations ADD CONSTRAINT FK_1C60F915232D562B FOREIGN KEY (object_id) REFERENCES categories (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE attachments ADD CONSTRAINT FK_47C4FAD693CB796C FOREIGN KEY (file_id) REFERENCES files (id)');
        $this->addSql('ALTER TABLE attachment_translations ADD CONSTRAINT FK_FC394778232D562B FOREIGN KEY (object_id) REFERENCES attachments (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE pages ADD CONSTRAINT FK_2074E5753DA5256D FOREIGN KEY (image_id) REFERENCES images (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE pages ADD CONSTRAINT FK_2074E5754E7AF8F FOREIGN KEY (gallery_id) REFERENCES gallery (id)');
        $this->addSql('ALTER TABLE pages ADD CONSTRAINT FK_2074E57512469DE2 FOREIGN KEY (category_id) REFERENCES categories (id)');
        $this->addSql('ALTER TABLE abstract_page_attachment ADD CONSTRAINT FK_B871CA30C4663E4 FOREIGN KEY (page_id) REFERENCES pages (id)');
        $this->addSql('ALTER TABLE abstract_page_attachment ADD CONSTRAINT FK_B871CA30464E68B FOREIGN KEY (attachment_id) REFERENCES attachments (id)');
        $this->addSql('ALTER TABLE pages_translations ADD CONSTRAINT FK_EAA721E0232D562B FOREIGN KEY (object_id) REFERENCES pages (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE gallery_items ADD CONSTRAINT FK_583396E4E7AF8F FOREIGN KEY (gallery_id) REFERENCES gallery (id)');
        $this->addSql('ALTER TABLE gallery_items ADD CONSTRAINT FK_583396E3DA5256D FOREIGN KEY (image_id) REFERENCES images (id)');
        $this->addSql('ALTER TABLE gallery_items ADD CONSTRAINT FK_583396E29C1004E FOREIGN KEY (video_id) REFERENCES video (id)');
        $this->addSql('ALTER TABLE gallery_items_translations ADD CONSTRAINT FK_E953FA8F232D562B FOREIGN KEY (object_id) REFERENCES gallery_items (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE news ADD CONSTRAINT FK_1DD399503DA5256D FOREIGN KEY (image_id) REFERENCES images (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE news ADD CONSTRAINT FK_1DD399504E7AF8F FOREIGN KEY (gallery_id) REFERENCES gallery (id)');
        $this->addSql('ALTER TABLE news_tags ADD CONSTRAINT FK_BA6162ADB5A459A0 FOREIGN KEY (news_id) REFERENCES news (id)');
        $this->addSql('ALTER TABLE news_tags ADD CONSTRAINT FK_BA6162ADBAD26311 FOREIGN KEY (tag_id) REFERENCES tags (id)');
        $this->addSql('ALTER TABLE abstract_news_attachment ADD CONSTRAINT FK_5FEE2CD7C4663E4 FOREIGN KEY (page_id) REFERENCES news (id)');
        $this->addSql('ALTER TABLE abstract_news_attachment ADD CONSTRAINT FK_5FEE2CD7464E68B FOREIGN KEY (attachment_id) REFERENCES attachments (id)');
        $this->addSql('ALTER TABLE news_translations ADD CONSTRAINT FK_20FDB330232D562B FOREIGN KEY (object_id) REFERENCES news (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE menu ADD CONSTRAINT FK_7D053A93C4663E4 FOREIGN KEY (page_id) REFERENCES pages (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE menu ADD CONSTRAINT FK_7D053A9379066886 FOREIGN KEY (root_id) REFERENCES menu (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE menu ADD CONSTRAINT FK_7D053A93727ACA70 FOREIGN KEY (parent_id) REFERENCES menu (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE menu_translation ADD CONSTRAINT FK_DC955B23232D562B FOREIGN KEY (object_id) REFERENCES menu (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE lexik_trans_unit_translations ADD CONSTRAINT FK_B0AA394493CB796C FOREIGN KEY (file_id) REFERENCES lexik_translation_file (id)');
        $this->addSql('ALTER TABLE lexik_trans_unit_translations ADD CONSTRAINT FK_B0AA3944C3C583C9 FOREIGN KEY (trans_unit_id) REFERENCES lexik_trans_unit (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE category_translations DROP FOREIGN KEY FK_1C60F915232D562B');
        $this->addSql('ALTER TABLE pages DROP FOREIGN KEY FK_2074E57512469DE2');
        $this->addSql('ALTER TABLE attachment_translations DROP FOREIGN KEY FK_FC394778232D562B');
        $this->addSql('ALTER TABLE abstract_page_attachment DROP FOREIGN KEY FK_B871CA30464E68B');
        $this->addSql('ALTER TABLE abstract_news_attachment DROP FOREIGN KEY FK_5FEE2CD7464E68B');
        $this->addSql('ALTER TABLE attachments DROP FOREIGN KEY FK_47C4FAD693CB796C');
        $this->addSql('ALTER TABLE pages DROP FOREIGN KEY FK_2074E5753DA5256D');
        $this->addSql('ALTER TABLE gallery_items DROP FOREIGN KEY FK_583396E3DA5256D');
        $this->addSql('ALTER TABLE news DROP FOREIGN KEY FK_1DD399503DA5256D');
        $this->addSql('ALTER TABLE abstract_page_attachment DROP FOREIGN KEY FK_B871CA30C4663E4');
        $this->addSql('ALTER TABLE pages_translations DROP FOREIGN KEY FK_EAA721E0232D562B');
        $this->addSql('ALTER TABLE menu DROP FOREIGN KEY FK_7D053A93C4663E4');
        $this->addSql('ALTER TABLE gallery_items DROP FOREIGN KEY FK_583396E29C1004E');
        $this->addSql('ALTER TABLE pages DROP FOREIGN KEY FK_2074E5754E7AF8F');
        $this->addSql('ALTER TABLE gallery_items DROP FOREIGN KEY FK_583396E4E7AF8F');
        $this->addSql('ALTER TABLE news DROP FOREIGN KEY FK_1DD399504E7AF8F');
        $this->addSql('ALTER TABLE gallery_items_translations DROP FOREIGN KEY FK_E953FA8F232D562B');
        $this->addSql('ALTER TABLE news_tags DROP FOREIGN KEY FK_BA6162ADBAD26311');
        $this->addSql('ALTER TABLE news_tags DROP FOREIGN KEY FK_BA6162ADB5A459A0');
        $this->addSql('ALTER TABLE abstract_news_attachment DROP FOREIGN KEY FK_5FEE2CD7C4663E4');
        $this->addSql('ALTER TABLE news_translations DROP FOREIGN KEY FK_20FDB330232D562B');
        $this->addSql('ALTER TABLE menu DROP FOREIGN KEY FK_7D053A9379066886');
        $this->addSql('ALTER TABLE menu DROP FOREIGN KEY FK_7D053A93727ACA70');
        $this->addSql('ALTER TABLE menu_translation DROP FOREIGN KEY FK_DC955B23232D562B');
        $this->addSql('ALTER TABLE lexik_trans_unit_translations DROP FOREIGN KEY FK_B0AA394493CB796C');
        $this->addSql('ALTER TABLE lexik_trans_unit_translations DROP FOREIGN KEY FK_B0AA3944C3C583C9');
        $this->addSql('DROP TABLE ext_translations');
        $this->addSql('DROP TABLE categories');
        $this->addSql('DROP TABLE category_translations');
        $this->addSql('DROP TABLE attachments');
        $this->addSql('DROP TABLE attachment_translations');
        $this->addSql('DROP TABLE files');
        $this->addSql('DROP TABLE images');
        $this->addSql('DROP TABLE pages');
        $this->addSql('DROP TABLE abstract_page_attachment');
        $this->addSql('DROP TABLE pages_translations');
        $this->addSql('DROP TABLE video');
        $this->addSql('DROP TABLE gallery');
        $this->addSql('DROP TABLE gallery_items');
        $this->addSql('DROP TABLE gallery_items_translations');
        $this->addSql('DROP TABLE tags');
        $this->addSql('DROP TABLE news');
        $this->addSql('DROP TABLE news_tags');
        $this->addSql('DROP TABLE abstract_news_attachment');
        $this->addSql('DROP TABLE news_translations');
        $this->addSql('DROP TABLE menu');
        $this->addSql('DROP TABLE menu_translation');
        $this->addSql('DROP TABLE users');
        $this->addSql('DROP TABLE lexik_translation_file');
        $this->addSql('DROP TABLE lexik_trans_unit');
        $this->addSql('DROP TABLE lexik_trans_unit_translations');
    }
}