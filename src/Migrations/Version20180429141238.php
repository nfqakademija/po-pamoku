<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180429141238 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE activity (id INT AUTO_INCREMENT NOT NULL, location_id INT DEFAULT NULL, subcategory_id INT DEFAULT NULL, user_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, price_from DOUBLE PRECISION NOT NULL, price_to DOUBLE PRECISION NOT NULL, age_from INT NOT NULL, age_to INT NOT NULL, path_to_logo VARCHAR(255) NOT NULL, INDEX IDX_AC74095A64D218E (location_id), INDEX IDX_AC74095A5DC6FE57 (subcategory_id), UNIQUE INDEX UNIQ_AC74095AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE activity_timetable (activity_id INT NOT NULL, timetable_id INT NOT NULL, INDEX IDX_E1A9D1C781C06096 (activity_id), INDEX IDX_E1A9D1C7CC306847 (timetable_id), PRIMARY KEY(activity_id, timetable_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE city (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE location (id INT AUTO_INCREMENT NOT NULL, city_id INT DEFAULT NULL, street VARCHAR(255) NOT NULL, house VARCHAR(255) NOT NULL, apartment INT DEFAULT NULL, postcode VARCHAR(255) DEFAULT NULL, INDEX IDX_5E9E89CB8BAC62AF (city_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE subcategory (id INT AUTO_INCREMENT NOT NULL, category_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_DDCA44812469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE timetable (id INT AUTO_INCREMENT NOT NULL, weekday_id INT DEFAULT NULL, time_from TIME NOT NULL, time_to TIME NOT NULL, INDEX IDX_6B1F67048516439 (weekday_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(150) NOT NULL, password VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, surname VARCHAR(255) NOT NULL, phone_number VARCHAR(255) NOT NULL, role VARCHAR(255) NOT NULL, is_blocked TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE weekday (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE activity ADD CONSTRAINT FK_AC74095A64D218E FOREIGN KEY (location_id) REFERENCES location (id)');
        $this->addSql('ALTER TABLE activity ADD CONSTRAINT FK_AC74095A5DC6FE57 FOREIGN KEY (subcategory_id) REFERENCES subcategory (id)');
        $this->addSql('ALTER TABLE activity ADD CONSTRAINT FK_AC74095AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE activity_timetable ADD CONSTRAINT FK_E1A9D1C781C06096 FOREIGN KEY (activity_id) REFERENCES activity (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE activity_timetable ADD CONSTRAINT FK_E1A9D1C7CC306847 FOREIGN KEY (timetable_id) REFERENCES timetable (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE location ADD CONSTRAINT FK_5E9E89CB8BAC62AF FOREIGN KEY (city_id) REFERENCES city (id)');
        $this->addSql('ALTER TABLE subcategory ADD CONSTRAINT FK_DDCA44812469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE timetable ADD CONSTRAINT FK_6B1F67048516439 FOREIGN KEY (weekday_id) REFERENCES weekday (id)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE activity_timetable DROP FOREIGN KEY FK_E1A9D1C781C06096');
        $this->addSql('ALTER TABLE subcategory DROP FOREIGN KEY FK_DDCA44812469DE2');
        $this->addSql('ALTER TABLE location DROP FOREIGN KEY FK_5E9E89CB8BAC62AF');
        $this->addSql('ALTER TABLE activity DROP FOREIGN KEY FK_AC74095A64D218E');
        $this->addSql('ALTER TABLE activity DROP FOREIGN KEY FK_AC74095A5DC6FE57');
        $this->addSql('ALTER TABLE activity_timetable DROP FOREIGN KEY FK_E1A9D1C7CC306847');
        $this->addSql('ALTER TABLE activity DROP FOREIGN KEY FK_AC74095AA76ED395');
        $this->addSql('ALTER TABLE timetable DROP FOREIGN KEY FK_6B1F67048516439');
        $this->addSql('DROP TABLE activity');
        $this->addSql('DROP TABLE activity_timetable');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE city');
        $this->addSql('DROP TABLE location');
        $this->addSql('DROP TABLE subcategory');
        $this->addSql('DROP TABLE timetable');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE weekday');
    }
}
