<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201120122102 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE quack DROP FOREIGN KEY FK_83D44F6FC6E59929');
        $this->addSql('DROP INDEX FK_83D44F6FC6E59929 ON quack');
        $this->addSql('ALTER TABLE quack DROP autheur_id');
        $this->addSql('ALTER TABLE quack ADD CONSTRAINT FK_83D44F6F60BB6FE6 FOREIGN KEY (auteur_id) REFERENCES duck (id)');
        $this->addSql('CREATE INDEX IDX_83D44F6F60BB6FE6 ON quack (auteur_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE quack DROP FOREIGN KEY FK_83D44F6F60BB6FE6');
        $this->addSql('DROP INDEX IDX_83D44F6F60BB6FE6 ON quack');
        $this->addSql('ALTER TABLE quack ADD autheur_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE quack ADD CONSTRAINT FK_83D44F6FC6E59929 FOREIGN KEY (autheur_id) REFERENCES duck (id)');
        $this->addSql('CREATE INDEX FK_83D44F6FC6E59929 ON quack (autheur_id)');
    }
}
