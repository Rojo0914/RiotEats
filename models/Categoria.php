<?php

// Clase Categoría (Hamburguesa)

class Categoria {
    private ?int $id; // ?int es para decir que puede ser NULL
    private string $nombre;
    private string $slug;
    private ?string $descripcion;
    private bool $activa;

    public function __construct(string $nombre, string $slug, ?string $descripcion = null, bool $activa = true, ?int $id = null) {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->slug = $slug;
        $this->descripcion = $descripcion;
        $this->activa = $activa;
    }

    // GETTERS: Necesarios para que CategoriaDAO pueda leer las propiedades privadas
    public function getId(): ?int {
        return $this->id;
    }
    public function getNombre(): string {
        return $this->nombre;
    }
    public function getSlug(): string {
        return $this->slug;
    }
    public function getDescripcion(): ?string {
        return $this->descripcion;
    }
    public function isActiva(): bool {
        return $this->activa;
    }

    // SETTERS
    public function setNombre(string $nombre): void {
        $this->nombre = $nombre;
    }

    public function setDescripcion(?string $descripcion): void {
        $this->descripcion = $descripcion;
    }

    public function setActiva(bool $activa): void {
        $this->activa = $activa;
    }

}