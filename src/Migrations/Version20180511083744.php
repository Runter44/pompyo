<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180511083744 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE inscription_evenement MODIFY id INT NOT NULL');
        $this->addSql('ALTER TABLE inscription_evenement DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE inscription_evenement DROP id');
        $this->addSql('ALTER TABLE inscription_evenement ADD PRIMARY KEY (utilisateur_id, evenement_id)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE inscription_evenement DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE inscription_evenement ADD id INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE inscription_evenement ADD PRIMARY KEY (id)');
    }
}
