<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190318122957 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE commentaires CHANGE autorId autorId INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user DROP commentaireId');
        $this->addSql('ALTER TABLE tricks DROP commentairesId, DROP seconde video, DROP troisieme video, DROP seconde image');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE commentaires CHANGE autorId autorId INT DEFAULT 1');
        $this->addSql('ALTER TABLE tricks ADD commentairesId INT DEFAULT NULL, ADD seconde video VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, ADD troisieme video VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, ADD seconde image VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE user ADD commentaireId INT NOT NULL');
    }
}
