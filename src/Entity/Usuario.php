<?php

namespace App\Entity;

use App\Repository\UsuarioRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UsuarioRepository::class)]
class Usuario
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 20)]
    private ?string $nombre_usuario = null;

    #[ORM\Column(length: 50)]
    private ?string $correo = null;

    #[ORM\Column(length: 255)]
    private ?string $password_hash = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $fecha_creacion = null;

    /**
     * @var Collection<int, Tarea>
     */
    #[ORM\ManyToMany(targetEntity: Tarea::class, mappedBy: 'usuarios')]
    private Collection $tareas;

    /**
     * @var Collection<int, Mensaje>
     */
    #[ORM\OneToMany(targetEntity: Mensaje::class, mappedBy: 'autor')]
    private Collection $mensajes;

    /**
     * @var Collection<int, Grupo>
     */
    #[ORM\ManyToMany(targetEntity: Grupo::class, mappedBy: 'usuarios')]
    private Collection $grupos;

    /**
     * @var Collection<int, Contacto>
     */
    #[ORM\OneToMany(targetEntity: Contacto::class, mappedBy: 'usuarioSolicitante')]
    private Collection $solicitudesEnviadas;

    /**
     * @var Collection<int, Contacto>
     */
    #[ORM\OneToMany(targetEntity: Contacto::class, mappedBy: 'usuarioReceptor')]
    private Collection $solicitudesRecibidas;

    public function __construct()
    {
        $this->tareas = new ArrayCollection();
        $this->mensajes = new ArrayCollection();
        $this->grupos = new ArrayCollection();
        $this->solicitudesEnviadas = new ArrayCollection();
        $this->solicitudesRecibidas = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(string $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getNombreUsuario(): ?string
    {
        return $this->nombre_usuario;
    }

    public function setNombreUsuario(string $nombre_usuario): static
    {
        $this->nombre_usuario = $nombre_usuario;

        return $this;
    }

    public function getCorreo(): ?string
    {
        return $this->correo;
    }

    public function setCorreo(string $correo): static
    {
        $this->correo = $correo;

        return $this;
    }

    public function getPasswordHash(): ?string
    {
        return $this->password_hash;
    }

    public function setPasswordHash(string $password_hash): static
    {
        $this->password_hash = $password_hash;

        return $this;
    }

    public function getFechaCreacion(): ?\DateTime
    {
        return $this->fecha_creacion;
    }

    public function setFechaCreacion(\DateTime $fecha_creacion): static
    {
        $this->fecha_creacion = $fecha_creacion;

        return $this;
    }

    /**
     * @return Collection<int, Tarea>
     */
    public function getTareas(): Collection
    {
        return $this->tareas;
    }

    public function addTarea(Tarea $tarea): static
    {
        if (!$this->tareas->contains($tarea)) {
            $this->tareas->add($tarea);
            $tarea->addUsuario($this);
        }

        return $this;
    }

    public function removeTarea(Tarea $tarea): static
    {
        if ($this->tareas->removeElement($tarea)) {
            $tarea->removeUsuario($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Mensaje>
     */
    public function getMensajes(): Collection
    {
        return $this->mensajes;
    }

    public function addMensaje(Mensaje $mensaje): static
    {
        if (!$this->mensajes->contains($mensaje)) {
            $this->mensajes->add($mensaje);
            $mensaje->setAutor($this);
        }

        return $this;
    }

    public function removeMensaje(Mensaje $mensaje): static
    {
        if ($this->mensajes->removeElement($mensaje)) {
            // set the owning side to null (unless already changed)
            if ($mensaje->getAutor() === $this) {
                $mensaje->setAutor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Grupo>
     */
    public function getGrupos(): Collection
    {
        return $this->grupos;
    }

    public function addGrupo(Grupo $grupo): static
    {
        if (!$this->grupos->contains($grupo)) {
            $this->grupos->add($grupo);
            $grupo->addUsuario($this);
        }

        return $this;
    }

    public function removeGrupo(Grupo $grupo): static
    {
        if ($this->grupos->removeElement($grupo)) {
            $grupo->removeUsuario($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Contacto>
     */
    public function getSolicitudesEnviadas(): Collection
    {
        return $this->solicitudesEnviadas;
    }

    public function addSolicitudesEnviada(Contacto $solicitudesEnviada): static
    {
        if (!$this->solicitudesEnviadas->contains($solicitudesEnviada)) {
            $this->solicitudesEnviadas->add($solicitudesEnviada);
            $solicitudesEnviada->setUsuarioSolicitante($this);
        }

        return $this;
    }

    public function removeSolicitudesEnviada(Contacto $solicitudesEnviada): static
    {
        if ($this->solicitudesEnviadas->removeElement($solicitudesEnviada)) {
            // set the owning side to null (unless already changed)
            if ($solicitudesEnviada->getUsuarioSolicitante() === $this) {
                $solicitudesEnviada->setUsuarioSolicitante(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Contacto>
     */
    public function getSolicitudesRecibidas(): Collection
    {
        return $this->solicitudesRecibidas;
    }

    public function addSolicitudesRecibida(Contacto $solicitudesRecibida): static
    {
        if (!$this->solicitudesRecibidas->contains($solicitudesRecibida)) {
            $this->solicitudesRecibidas->add($solicitudesRecibida);
            $solicitudesRecibida->setUsuarioReceptor($this);
        }

        return $this;
    }

    public function removeSolicitudesRecibida(Contacto $solicitudesRecibida): static
    {
        if ($this->solicitudesRecibidas->removeElement($solicitudesRecibida)) {
            // set the owning side to null (unless already changed)
            if ($solicitudesRecibida->getUsuarioReceptor() === $this) {
                $solicitudesRecibida->setUsuarioReceptor(null);
            }
        }

        return $this;
    }
}
