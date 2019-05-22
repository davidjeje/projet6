<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190318125559 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE tricks ADD seconde_image VARCHAR(255) NOT NULL, ADD seconde_video VARCHAR(255) NOT NULL, ADD troisieme_video VARCHAR(255) NOT NULL, DROP secondeVideo, DROP troisiemeVideo, DROP secondeImage');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE tricks ADD secondeVideo VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, ADD troisiemeVideo VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, ADD secondeImage VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, DROP seconde_image, DROP seconde_video, DROP troisieme_video');
    }
}
