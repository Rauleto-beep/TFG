<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260207095908 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE contacto (id INT AUTO_INCREMENT NOT NULL, estado_solicitud VARCHAR(255) NOT NULL, fecha_amistad DATETIME NOT NULL, usuario_solicitante_id INT DEFAULT NULL, usuario_receptor_id INT DEFAULT NULL, INDEX IDX_2741493C6DF5464A (usuario_solicitante_id), INDEX IDX_2741493C467F8F (usuario_receptor_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE contacto ADD CONSTRAINT FK_2741493C6DF5464A FOREIGN KEY (usuario_solicitante_id) REFERENCES usuario (id)');
        $this->addSql('ALTER TABLE contacto ADD CONSTRAINT FK_2741493C467F8F FOREIGN KEY (usuario_receptor_id) REFERENCES usuario (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE contacto DROP FOREIGN KEY FK_2741493C6DF5464A');
        $this->addSql('ALTER TABLE contacto DROP FOREIGN KEY FK_2741493C467F8F');
        $this->addSql('DROP TABLE contacto');
    }
}
