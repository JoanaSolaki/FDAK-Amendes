<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240528123736 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE fine (id INT AUTO_INCREMENT NOT NULL, reason VARCHAR(100) NOT NULL, amount INT NOT NULL, id_taxes VARCHAR(12) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE paidment (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, fine_id INT DEFAULT NULL, date DATE NOT NULL, card VARCHAR(20) NOT NULL, crypto VARCHAR(10) NOT NULL, exp_date DATE NOT NULL, INDEX IDX_8CBC53AEA76ED395 (user_id), UNIQUE INDEX UNIQ_8CBC53AEE90B2A0C (fine_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, name VARCHAR(80) NOT NULL, surname VARCHAR(80) NOT NULL, adress VARCHAR(255) NOT NULL, phone VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE paidment ADD CONSTRAINT FK_8CBC53AEA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE paidment ADD CONSTRAINT FK_8CBC53AEE90B2A0C FOREIGN KEY (fine_id) REFERENCES fine (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE paidment DROP FOREIGN KEY FK_8CBC53AEA76ED395');
        $this->addSql('ALTER TABLE paidment DROP FOREIGN KEY FK_8CBC53AEE90B2A0C');
        $this->addSql('DROP TABLE fine');
        $this->addSql('DROP TABLE paidment');
        $this->addSql('DROP TABLE user');
    }
}
