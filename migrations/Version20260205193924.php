<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260205193924 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE grupo_usuario (grupo_id INT NOT NULL, usuario_id INT NOT NULL, INDEX IDX_7D6C3EFA9C833003 (grupo_id), INDEX IDX_7D6C3EFADB38439E (usuario_id), PRIMARY KEY (grupo_id, usuario_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE tarea_usuario (tarea_id INT NOT NULL, usuario_id INT NOT NULL, INDEX IDX_2F594F5F6D5BDFE1 (tarea_id), INDEX IDX_2F594F5FDB38439E (usuario_id), PRIMARY KEY (tarea_id, usuario_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE grupo_usuario ADD CONSTRAINT FK_7D6C3EFA9C833003 FOREIGN KEY (grupo_id) REFERENCES grupo (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE grupo_usuario ADD CONSTRAINT FK_7D6C3EFADB38439E FOREIGN KEY (usuario_id) REFERENCES usuario (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tarea_usuario ADD CONSTRAINT FK_2F594F5F6D5BDFE1 FOREIGN KEY (tarea_id) REFERENCES tarea (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tarea_usuario ADD CONSTRAINT FK_2F594F5FDB38439E FOREIGN KEY (usuario_id) REFERENCES usuario (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE mensaje ADD grupo_id INT NOT NULL, ADD autor_id INT NOT NULL');
        $this->addSql('ALTER TABLE mensaje ADD CONSTRAINT FK_9B631D019C833003 FOREIGN KEY (grupo_id) REFERENCES grupo (id)');
        $this->addSql('ALTER TABLE mensaje ADD CONSTRAINT FK_9B631D0114D45BBE FOREIGN KEY (autor_id) REFERENCES usuario (id)');
        $this->addSql('CREATE INDEX IDX_9B631D019C833003 ON mensaje (grupo_id)');
        $this->addSql('CREATE INDEX IDX_9B631D0114D45BBE ON mensaje (autor_id)');
        $this->addSql('ALTER TABLE tarea ADD categoria_id INT NOT NULL, ADD grupo_id INT NOT NULL');
        $this->addSql('ALTER TABLE tarea ADD CONSTRAINT FK_3CA053663397707A FOREIGN KEY (categoria_id) REFERENCES categoria (id)');
        $this->addSql('ALTER TABLE tarea ADD CONSTRAINT FK_3CA053669C833003 FOREIGN KEY (grupo_id) REFERENCES grupo (id)');
        $this->addSql('CREATE INDEX IDX_3CA053663397707A ON tarea (categoria_id)');
        $this->addSql('CREATE INDEX IDX_3CA053669C833003 ON tarea (grupo_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE grupo_usuario DROP FOREIGN KEY FK_7D6C3EFA9C833003');
        $this->addSql('ALTER TABLE grupo_usuario DROP FOREIGN KEY FK_7D6C3EFADB38439E');
        $this->addSql('ALTER TABLE tarea_usuario DROP FOREIGN KEY FK_2F594F5F6D5BDFE1');
        $this->addSql('ALTER TABLE tarea_usuario DROP FOREIGN KEY FK_2F594F5FDB38439E');
        $this->addSql('DROP TABLE grupo_usuario');
        $this->addSql('DROP TABLE tarea_usuario');
        $this->addSql('ALTER TABLE mensaje DROP FOREIGN KEY FK_9B631D019C833003');
        $this->addSql('ALTER TABLE mensaje DROP FOREIGN KEY FK_9B631D0114D45BBE');
        $this->addSql('DROP INDEX IDX_9B631D019C833003 ON mensaje');
        $this->addSql('DROP INDEX IDX_9B631D0114D45BBE ON mensaje');
        $this->addSql('ALTER TABLE mensaje DROP grupo_id, DROP autor_id');
        $this->addSql('ALTER TABLE tarea DROP FOREIGN KEY FK_3CA053663397707A');
        $this->addSql('ALTER TABLE tarea DROP FOREIGN KEY FK_3CA053669C833003');
        $this->addSql('DROP INDEX IDX_3CA053663397707A ON tarea');
        $this->addSql('DROP INDEX IDX_3CA053669C833003 ON tarea');
        $this->addSql('ALTER TABLE tarea DROP categoria_id, DROP grupo_id');
    }
}
