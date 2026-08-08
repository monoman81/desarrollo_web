<?php

namespace Model;

class Admin extends ActiveRecord {
    protected static $tabla = "usuarios";
    protected static $columnas = ["id", "email", "password"];

    public $id;
    public $email;
    public $password;

    public function __construct($args = []) {
        $this->id = $args["id"] ?? null;
        $this->email = $args["email"] ?? "";
        $this->password = $args["password"] ?? "";
    }

    public function Validar() {
        if (!$this->email)
            self::$errores[] = "Email es obligatorio";
        if (!$this->password)
            self::$errores[] = "Password es obligatorio";
        return self::$errores;
    }

    public function existeUsuario() {
        $query = "SELECT * FROM ".self::$tabla." WHERE email='$this->email' LIMIT 1";
        $resultado = self::$db->query($query);
        if (!$resultado->num_rows) {
            self::$errores[] = "El usuario no existe";
            return null;
        }
        return $resultado;
    }

    public function verificarPassword($resultado) {
        $usuario = $resultado->fetch_object();
        if (!password_verify($this->password, $usuario->password)) {
            self::$errores[] = "El password es incorrecto";
            return null;
        }
        return $usuario;
    }

    public function autenticar($usuario) {
        session_start();
        $_SESSION["usuario"] = $usuario->email;
        $_SESSION["logged"] = true;
        header("Location: /admin");
    }

}