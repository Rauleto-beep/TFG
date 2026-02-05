<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260205190544 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE categoria (id INT AUTO_INCREMENT NOT NULL, nombre_categoria VARCHAR(255) NOT NULL, color VARCHAR(255) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE grupo (id INT AUTO_INCREMENT NOT NULL, nombre_grupo VARCHAR(255) NOT NULL, fecha_creacion DATE NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE mensaje (id INT AUTO_INCREMENT NOT NULL, contenido VARCHAR(255) NOT NULL, fecha_envio DATETIME NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE tarea (id INT AUTO_INCREMENT NOT NULL, nombre_tarea VARCHAR(255) NOT NULL, descripcion VARCHAR(255) NOT NULL, fecha_publicacion DATETIME NOT NULL, fecha_vencimiento DATETIME NOT NULL, ia TINYINT NOT NULL, estado VARCHAR(255) NOT NULL, prioridad VARCHAR(255) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE usuario (id INT AUTO_INCREMENT NOT NULL, nombre_usuario VARCHAR(20) NOT NULL, correo VARCHAR(50) NOT NULL, password_hash VARCHAR(255) NOT NULL, fecha_creacion DATE NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE categoria');
        $this->addSql('DROP TABLE grupo');
        $this->addSql('DROP TABLE mensaje');
        $this->addSql('DROP TABLE tarea');
        $this->addSql('DROP TABLE usuario');
    }
}
