<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201124141657 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE quack DROP FOREIGN KEY FK_83D44F6F727ACA70');
        $this->addSql('DROP INDEX IDX_83D44F6F727ACA70 ON quack');
        $this->addSql('ALTER TABLE quack CHANGE parent_id comments_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE quack ADD CONSTRAINT FK_83D44F6F63379586 FOREIGN KEY (comments_id) REFERENCES quack (id)');
        $this->addSql('CREATE INDEX IDX_83D44F6F63379586 ON quack (comments_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE quack DROP FOREIGN KEY FK_83D44F6F63379586');
        $this->addSql('DROP INDEX IDX_83D44F6F63379586 ON quack');
        $this->addSql('ALTER TABLE quack CHANGE comments_id parent_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE quack ADD CONSTRAINT FK_83D44F6F727ACA70 FOREIGN KEY (parent_id) REFERENCES quack (id)');
        $this->addSql('CREATE INDEX IDX_83D44F6F727ACA70 ON quack (parent_id)');
    }
}
