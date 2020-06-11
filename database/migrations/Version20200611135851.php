<?php

namespace Database\Migrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema as Schema;

class Version20200611135851 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE donations (id INT AUTO_INCREMENT NOT NULL, event_id INT DEFAULT NULL, username VARCHAR(50) NOT NULL, email VARCHAR(100) DEFAULT NULL, message VARCHAR(255) DEFAULT NULL, amount NUMERIC(10, 2) NOT NULL, status VARCHAR(16) NOT NULL, source VARCHAR(16) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_CDE9896271F7E88B (event_id), INDEX created_at_idx (created_at), INDEX status_idx (status), INDEX source_idx (source), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messages (id INT AUTO_INCREMENT NOT NULL, event_id INT DEFAULT NULL, user_id VARCHAR(50) DEFAULT NULL, donation_id INT DEFAULT NULL, message VARCHAR(255) NOT NULL, organizer TINYINT(1) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_DB021E9671F7E88B (event_id), INDEX IDX_DB021E96A76ED395 (user_id), INDEX IDX_DB021E964DC1279C (donation_id), INDEX created_at_idx (created_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE donations ADD CONSTRAINT FK_CDE9896271F7E88B FOREIGN KEY (event_id) REFERENCES events (id)');
        $this->addSql('ALTER TABLE messages ADD CONSTRAINT FK_DB021E9671F7E88B FOREIGN KEY (event_id) REFERENCES events (id)');
        $this->addSql('ALTER TABLE messages ADD CONSTRAINT FK_DB021E96A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE messages ADD CONSTRAINT FK_DB021E964DC1279C FOREIGN KEY (donation_id) REFERENCES donations (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE messages DROP FOREIGN KEY FK_DB021E964DC1279C');
        $this->addSql('DROP TABLE donations');
        $this->addSql('DROP TABLE messages');
    }
}
