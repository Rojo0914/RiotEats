<?php
class Usuario {
    private $id;
    private $nombre;
    private $email;
    private $password;
    private $telefono;
    private $direccion;
    private $fecha_nacimiento;
    private $rol;
    private $fecha_registro;

    public function getId() { return $this->id; }
    public function getNombre() { return $this->nombre; }
    public function getEmail() { return $this->email; }
    public function getPassword() { return $this->password; }
    public function getTelefono() { return $this->telefono; }
    public function getDireccion() { return $this->direccion; }
    public function getFechaNacimiento() { return $this->fecha_nacimiento; }
    public function getRol() { return $this->rol; }
    public function getFechaRegistro() { return $this->fecha_registro; }

    public function esAdmin() {
        return $this->rol === 'admin';
    }
}
?>