<?php

namespace Model;

class Propiedad extends ActiveRecord {

    protected static $tabla = "propiedades";
    protected static $columnas = ["id", "titulo", "precio", "imagen", "descripcion", "habitaciones", "wc", "estacionamiento", "creado", "vendedor_id"];

    public $id;
    public $titulo;
    public $precio;
    public $imagen;
    public $descripcion;
    public $habitaciones;
    public $wc;
    public $estacionamiento;
    public $creado;
    public $vendedor_id;

    public function __construct($args = []) {
        $this->id = $args["id"] ?? null;
        $this->titulo = $args["titulo"] ?? "";
        $this->precio = $args["precio"] ?? "";
        $this->imagen = $args["imagen"] ?? "";
        $this->descripcion = $args["descripcion"] ?? "";
        $this->habitaciones = $args["habitaciones"] ?? "";
        $this->wc = $args["wc"] ?? "";
        $this->estacionamiento = $args["estacionamiento"] ?? "";
        $this->creado = date("Y/m/d");
        $this->vendedor_id = $args["vendedor_id"] ?? null;
    }

    public function Validar() {
        if (!$this->titulo)
            self::$errores[] = "Debes de anadir un titulo";
        if (!$this->precio)
            self::$errores[] = "Debes de anadir un precio";
        if (!$this->descripcion)
            self::$errores[] = "Debes de anadir una descripcion";
        if (!$this->habitaciones)
            self::$errores[] = "Debes de anadir el numero de habitaciones. Usar 0 si no tiene.";
        if (!$this->wc)
            self::$errores[] = "Debes de anadir el numero de banos. Usar 0 si no tiene.";
        if (!$this->estacionamiento)
            self::$errores[] = "Debes de anadir el numero de espacios de estacionamiento disponibles. Usar 0 si no tiene.";
        if (!$this->vendedor_id)
            self::$errores[] = "Debes de escoger un vendedor.";
        if (!$this->imagen)
            self::$errores[] = "La imagen de la propiedad es obligatoria.";
        
        return self::$errores;
    }
    

}