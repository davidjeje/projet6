<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190102202819 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE commentaires ADD figureId INT DEFAULT NULL');
        $this->addSql('ALTER TABLE commentaires ADD CONSTRAINT FK_D9BEC0C4AD051FEF FOREIGN KEY (figureId) REFERENCES tricks (id)');
        $this->addSql('CREATE INDEX IDX_D9BEC0C4AD051FEF ON commentaires (figureId)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE commentaires DROP FOREIGN KEY FK_D9BEC0C4AD051FEF');
        $this->addSql('DROP INDEX IDX_D9BEC0C4AD051FEF ON commentaires');
        $this->addSql('ALTER TABLE commentaires DROP figureId');
    }
}
