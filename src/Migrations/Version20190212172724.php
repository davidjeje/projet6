<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190212172724 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE commentaires_user (commentaires_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_2554AB0317C4B2B0 (commentaires_id), INDEX IDX_2554AB03A76ED395 (user_id), PRIMARY KEY(commentaires_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE commentaires_user ADD CONSTRAINT FK_2554AB0317C4B2B0 FOREIGN KEY (commentaires_id) REFERENCES commentaires (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE commentaires_user ADD CONSTRAINT FK_2554AB03A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('DROP INDEX name ON tricks');
        $this->addSql('ALTER TABLE tricks ADD video VARCHAR(255) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_E1D902C15E237E06 ON tricks (name)');
        $this->addSql('ALTER TABLE user CHANGE token token VARCHAR(255) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D6495F37A13B ON user (token)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE commentaires_user');
        $this->addSql('DROP INDEX UNIQ_E1D902C15E237E06 ON tricks');
        $this->addSql('ALTER TABLE tricks DROP video');
        $this->addSql('CREATE UNIQUE INDEX name ON tricks (id, name)');
        $this->addSql('DROP INDEX UNIQ_8D93D6495F37A13B ON user');
        $this->addSql('ALTER TABLE user CHANGE token token VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci');
    }
}
