<?php

namespace App\Entity;

use App\Enum\EstadoTarea;
use App\Repository\ContactoRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ContactoRepository::class)]
class Contacto
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'solicitudesEnviadas')]
    private ?Usuario $usuarioSolicitante = null;

    #[ORM\ManyToOne(inversedBy: 'solicitudesRecibidas')]
    private ?Usuario $usuarioReceptor = null;

    #[ORM\Column(enumType: EstadoTarea::class)]
    private ?EstadoTarea $estadoSolicitud = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $fecha_amistad = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsuarioSolicitante(): ?Usuario
    {
        return $this->usuarioSolicitante;
    }

    public function setUsuarioSolicitante(?Usuario $usuarioSolicitante): static
    {
        $this->usuarioSolicitante = $usuarioSolicitante;

        return $this;
    }

    public function getUsuarioReceptor(): ?Usuario
    {
        return $this->usuarioReceptor;
    }

    public function setUsuarioReceptor(?Usuario $usuarioReceptor): static
    {
        $this->usuarioReceptor = $usuarioReceptor;

        return $this;
    }

    public function getEstadoSolicitud(): ?EstadoTarea
    {
        return $this->estadoSolicitud;
    }

    public function setEstadoSolicitud(EstadoTarea $estadoSolicitud): static
    {
        $this->estadoSolicitud = $estadoSolicitud;

        return $this;
    }

    public function getFechaAmistad(): ?\DateTimeImmutable
    {
        return $this->fecha_amistad;
    }

    public function setFechaAmistad(\DateTimeImmutable $fecha_amistad): static
    {
        $this->fecha_amistad = $fecha_amistad;

        return $this;
    }
}
