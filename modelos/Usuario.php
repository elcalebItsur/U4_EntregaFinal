<?php
class Usuario {
    public $id;
    public $nombre;
    public $email;
    public $password;
    public $telefono;
    public $genero;
    public $fecha_nacimiento;
    public $tipo;
    public $nombre_tienda;
    public $rfc;
    public $direccion;

    public function __construct($data) {
        $this->nombre = $data['nombre'] ?? '';
        $this->email = $data['email'] ?? '';
        $this->password = $data['password'] ?? '';
        $this->telefono = $data['telefono'] ?? '';
        $this->genero = $data['genero'] ?? '';
        $this->fecha_nacimiento = $data['fecha_nacimiento'] ?? '';
        $this->tipo = $data['tipo'] ?? '';
        $this->nombre_tienda = $data['nombre_tienda'] ?? '';
        $this->rfc = $data['rfc'] ?? '';
        $this->direccion = $data['direccion'] ?? '';
    }
}
?>
