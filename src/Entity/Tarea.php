<?php

namespace App\Entity;

use App\Enum\EstadoTarea;
use App\Enum\Prioridad;
use App\Repository\TareaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TareaRepository::class)]
class Tarea
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nombre_tarea = null;

    #[ORM\Column(length: 255)]
    private ?string $descripcion = null;

    #[ORM\Column]
    private ?\DateTime $fecha_publicacion = null;

    #[ORM\Column]
    private ?\DateTime $fecha_vencimiento = null;

    #[ORM\Column]
    private ?bool $ia = null;

    #[ORM\Column(enumType: EstadoTarea::class)]
    private ?EstadoTarea $estado = null;

    #[ORM\Column(enumType: Prioridad::class)]
    private ?Prioridad $prioridad = null;

    /**
     * @var Collection<int, Usuario>
     */
    #[ORM\ManyToMany(targetEntity: Usuario::class, inversedBy: 'tareas')]
    private Collection $usuarios;

    #[ORM\ManyToOne(inversedBy: 'tareas')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Categoria $categoria = null;

    #[ORM\ManyToOne(inversedBy: 'tareas')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Grupo $grupo = null;

    public function __construct()
    {
        $this->usuarios = new ArrayCollection();
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

    public function getNombreTarea(): ?string
    {
        return $this->nombre_tarea;
    }

    public function setNombreTarea(string $nombre_tarea): static
    {
        $this->nombre_tarea = $nombre_tarea;

        return $this;
    }

    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setDescripcion(string $descripcion): static
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    public function getFechaPublicacion(): ?\DateTime
    {
        return $this->fecha_publicacion;
    }

    public function setFechaPublicacion(\DateTime $fecha_publicacion): static
    {
        $this->fecha_publicacion = $fecha_publicacion;

        return $this;
    }

    public function getFechaVencimiento(): ?\DateTime
    {
        return $this->fecha_vencimiento;
    }

    public function setFechaVencimiento(\DateTime $fecha_vencimiento): static
    {
        $this->fecha_vencimiento = $fecha_vencimiento;

        return $this;
    }

    public function isIa(): ?bool
    {
        return $this->ia;
    }

    public function setIa(bool $ia): static
    {
        $this->ia = $ia;

        return $this;
    }

    public function getEstado(): ?EstadoTarea
    {
        return $this->estado;
    }

    public function setEstado(EstadoTarea $estado): static
    {
        $this->estado = $estado;

        return $this;
    }

    public function getPrioridad(): ?Prioridad
    {
        return $this->prioridad;
    }

    public function setPrioridad(Prioridad $prioridad): static
    {
        $this->prioridad = $prioridad;

        return $this;
    }

    /**
     * @return Collection<int, Usuario>
     */
    public function getUsuarios(): Collection
    {
        return $this->usuarios;
    }

    public function addUsuario(Usuario $usuario): static
    {
        if (!$this->usuarios->contains($usuario)) {
            $this->usuarios->add($usuario);
        }

        return $this;
    }

    public function removeUsuario(Usuario $usuario): static
    {
        $this->usuarios->removeElement($usuario);

        return $this;
    }

    public function getCategoria(): ?Categoria
    {
        return $this->categoria;
    }

    public function setCategoria(?Categoria $categoria): static
    {
        $this->categoria = $categoria;

        return $this;
    }

    public function getGrupo(): ?Grupo
    {
        return $this->grupo;
    }

    public function setGrupo(?Grupo $grupo): static
    {
        $this->grupo = $grupo;

        return $this;
    }
}
