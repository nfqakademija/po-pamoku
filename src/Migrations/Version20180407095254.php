<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180407095254 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE activity_subcategory');
        $this->addSql('ALTER TABLE activity ADD subcategory_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE activity ADD CONSTRAINT FK_AC74095A5DC6FE57 FOREIGN KEY (subcategory_id) REFERENCES subcategory (id)');
        $this->addSql('CREATE INDEX IDX_AC74095A5DC6FE57 ON activity (subcategory_id)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE activity_subcategory (activity_id INT NOT NULL, subcategory_id INT NOT NULL, INDEX IDX_352CE4481C06096 (activity_id), INDEX IDX_352CE445DC6FE57 (subcategory_id), PRIMARY KEY(activity_id, subcategory_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE activity_subcategory ADD CONSTRAINT FK_352CE445DC6FE57 FOREIGN KEY (subcategory_id) REFERENCES subcategory (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE activity_subcategory ADD CONSTRAINT FK_352CE4481C06096 FOREIGN KEY (activity_id) REFERENCES activity (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE activity DROP FOREIGN KEY FK_AC74095A5DC6FE57');
        $this->addSql('DROP INDEX IDX_AC74095A5DC6FE57 ON activity');
        $this->addSql('ALTER TABLE activity DROP subcategory_id');
    }
}
