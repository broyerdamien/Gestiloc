<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231207091544 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE lodger ADD property_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE lodger ADD CONSTRAINT FK_8ACBBC1D549213EC FOREIGN KEY (property_id) REFERENCES property (id)');
        $this->addSql('CREATE INDEX IDX_8ACBBC1D549213EC ON lodger (property_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE lodger DROP FOREIGN KEY FK_8ACBBC1D549213EC');
        $this->addSql('DROP INDEX IDX_8ACBBC1D549213EC ON lodger');
        $this->addSql('ALTER TABLE lodger DROP property_id');
    }
}
