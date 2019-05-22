<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190212192352 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE commentaires_user');
        $this->addSql('ALTER TABLE commentaires ADD autorId INT DEFAULT NULL');
        $this->addSql('ALTER TABLE commentaires ADD CONSTRAINT FK_D9BEC0C4B8444834 FOREIGN KEY (autorId) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_D9BEC0C4B8444834 ON commentaires (autorId)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE commentaires_user (commentaires_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_2554AB0317C4B2B0 (commentaires_id), INDEX IDX_2554AB03A76ED395 (user_id), PRIMARY KEY(commentaires_id, user_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE commentaires_user ADD CONSTRAINT FK_2554AB0317C4B2B0 FOREIGN KEY (commentaires_id) REFERENCES commentaires (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE commentaires_user ADD CONSTRAINT FK_2554AB03A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE commentaires DROP FOREIGN KEY FK_D9BEC0C4B8444834');
        $this->addSql('DROP INDEX IDX_D9BEC0C4B8444834 ON commentaires');
        $this->addSql('ALTER TABLE commentaires DROP autorId');
    }
}
