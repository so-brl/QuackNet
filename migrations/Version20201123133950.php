<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201123133950 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE comment');
        $this->addSql('ALTER TABLE quack ADD parent_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE quack ADD CONSTRAINT FK_83D44F6F727ACA70 FOREIGN KEY (parent_id) REFERENCES quack (id)');
        $this->addSql('CREATE INDEX IDX_83D44F6F727ACA70 ON quack (parent_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE comment (id INT AUTO_INCREMENT NOT NULL, quack_id INT DEFAULT NULL, author_id INT NOT NULL, content LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, created_at DATETIME NOT NULL, INDEX IDX_9474526CF675F31B (author_id), INDEX IDX_9474526CD3950CA9 (quack_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CD3950CA9 FOREIGN KEY (quack_id) REFERENCES quack (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CF675F31B FOREIGN KEY (author_id) REFERENCES duck (id)');
        $this->addSql('ALTER TABLE quack DROP FOREIGN KEY FK_83D44F6F727ACA70');
        $this->addSql('DROP INDEX IDX_83D44F6F727ACA70 ON quack');
        $this->addSql('ALTER TABLE quack DROP parent_id');
    }
}
