<?php

namespace App\Entity;

use App\Repository\ReparacionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReparacionRepository::class)]
class Reparacion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $nroOrdenManual = null;

    #[ORM\Column(length: 255)]
    private ?string $agente = null;

    #[ORM\Column(length: 255)]
    private ?string $estado = null;

    #[ORM\Column(length: 255)]
    private ?string $equipo = null;

    #[ORM\Column(length: 255)]
    private ?string $departamentoArea = null;

    #[ORM\Column(length: 255)]
    private ?string $asignadoA = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $fechaInicio = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $fechaFin = null;

    #[ORM\Column(length: 255)]
    private ?string $notificado = null;

    #[ORM\Column]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column(length: 255)]
    private ?string $carga = null;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNroOrdenManual(): ?string
    {
        return $this->nroOrdenManual;
    }

    public function setNroOrdenManual(?string $nroOrdenManual): self
    {
        $this->nroOrdenManual = $nroOrdenManual;

        return $this;
    }

    public function getAgente(): ?string
    {
        return $this->agente;
    }

    public function setAgente(string $agente): self
    {
        $this->agente = $agente;

        return $this;
    }

    public function getEstado(): ?string
    {
        return $this->estado;
    }

    public function setEstado(string $estado): self
    {
        $this->estado = $estado;

        return $this;
    }

    public function getEquipo(): ?string
    {
        return $this->equipo;
    }

    public function setEquipo(string $equipo): self
    {
        $this->equipo = $equipo;

        return $this;
    }

    public function getDepartamentoArea(): ?string
    {
        return $this->departamentoArea;
    }

    public function setDepartamentoArea(string $departamentoArea): self
    {
        $this->departamentoArea = $departamentoArea;

        return $this;
    }

    public function getAsignadoA(): ?string
    {
        return $this->asignadoA;
    }

    public function setAsignadoA(string $asignadoA): self
    {
        $this->asignadoA = $asignadoA;

        return $this;
    }

    public function getFechaInicio(): ?\DateTimeInterface
    {
        return $this->fechaInicio;
    }

    public function setFechaInicio(\DateTimeInterface $fechaInicio): self
    {
        $this->fechaInicio = $fechaInicio;

        return $this;
    }

    public function getFechaFin(): ?\DateTimeInterface
    {
        return $this->fechaFin;
    }

    public function setFechaFin(?\DateTimeInterface $fechaFin): self
    {
        $this->fechaFin = $fechaFin;

        return $this;
    }

    public function getNotificado(): ?string
    {
        return $this->notificado;
    }

    public function setNotificado(string $notificado): self
    {
        $this->notificado = $notificado;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getCarga(): ?string
    {
        return $this->carga;
    }

    public function setCarga(string $carga): self
    {
        $this->carga = $carga;

        return $this;
    }
}
