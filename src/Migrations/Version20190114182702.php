<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190114182702 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE tricks_user (tricks_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_6BF763A03B153154 (tricks_id), INDEX IDX_6BF763A0A76ED395 (user_id), PRIMARY KEY(tricks_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE tricks_user ADD CONSTRAINT FK_6BF763A03B153154 FOREIGN KEY (tricks_id) REFERENCES tricks (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tricks_user ADD CONSTRAINT FK_6BF763A0A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON user (email)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE tricks_user');
        $this->addSql('DROP INDEX UNIQ_8D93D649E7927C74 ON user');
    }
}
