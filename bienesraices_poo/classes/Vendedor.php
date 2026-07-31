<?php

namespace App;

class Vendedor extends ActiveRecord {

    protected static $tabla = "vendedores";
    protected static $columnas = ["id", "nombre", "apellido", "telefono"];

    public $id;
    public $nombre;
    public $apellido;
    public $telefono;

    public function __construct($args = []) {
        $this->id = $args["id"] ?? null;
        $this->nombre = $args["nombre"] ?? "";
        $this->apellido = $args["apellido"] ?? "";
        $this->telefono = $args["telefono"] ?? "";
    }

    public function Validar() {
        if (!$this->nombre)
            self::$errores[] = "Debes de anadir un nombre";
        if (!$this->apellido)
            self::$errores[] = "Debes de anadir un apellido";
        if (!$this->telefono)
            self::$errores[] = "Debes de anadir un telefono";
        if (!preg_match('/[0-9]{10}/', $this->telefono))
            self::$errores[] = "Debes de anadir un telefono valido";
        
        return self::$errores;
    }

}