<?php

namespace Controllers;

use MVC\Router;
use Model\Usuario;
use Classes\Email;

class LoginController {

    public static function login(Router $router) {
        $alertas = [];
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $auth = new Usuario($_POST);
            $alertas = $auth->validarLogin();
            if (empty($alertas)) {
                $usuario = Usuario::where("email", $auth->email);
                if ($usuario) {
                    if ($usuario->comprobarPasswordYConfirmado($auth->password)) {
                        session_start();
                        $_SESSION["id"] = $usuario->id;
                        $_SESSION["nombre"] = $usuario->nombre . " " . $usuario->apellido;
                        $_SESSION["email"] = $usuario->email;
                        $_SESSION["logged"] = true;
                        $_SESSION["admin"] = $usuario->admin ?? null;
                        if ($usuario->admin === "1") {
                            header("Location: /admin");
                        }
                        else {
                            header("Location: /cita");
                        }
                    }
                }
                else
                    Usuario::setAlerta("error", "Usuario no encontrado");

                $alertas = Usuario::getAlertas();
            }
        }
        $router->render("auth/login", [
            "alertas" => $alertas
        ]);
    }

    public static function logout(Router $router) {
        session_start();
        session_destroy();
        header("Location: /");
    }

    public static function olvide(Router $router) {
        $alertas = [];
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $auth = new Usuario($_POST);
            $alertas = $auth->validarEmail();
            if (empty($alertas)) {
                $usuario = Usuario::where("email", $auth->email);
                if ($usuario && $usuario->confirmado === "1") {
                    $usuario->generarToken();
                    $usuario->guardar();
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    $email->enviarInstrucciones();  
                    Usuario::setAlerta("exito", "Revisa tu email");
                }
                else
                    Usuario::setAlerta("error", "Usuario no encontrado o no confirmado");
                $alertas = Usuario::getAlertas();
            }
        }
        $router->render("auth/olvide", [
            "alertas" => $alertas
        ]);
    }

    public static function recuperar(Router $router) {
        $alertas = [];
        $tokenError = false;
        $token = s($_GET["token"]);
        $usuario = Usuario::where('token', $token);

        if (empty($usuario)) {
            Usuario::setAlerta("error", "Token no valido");
            $tokenError = true;
        }
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $password = new Usuario($_POST);
            $alertas = $password->validarPassword();
            if (empty($alertas)) {
                $usuario->password = null;
                $usuario->password = $password->password;
                $usuario->hashPassword();
                $usuario->token = null;
                $resultado = $usuario->guardar();
                if ($resultado)
                    header("Location: /");
            }
        }
        $alertas = Usuario::getAlertas();
        $router->render("auth/recuperar-password", [
             "alertas" => $alertas,
             "tokenError" => $tokenError
        ]);
    }

    public static function crear(Router $router) {
        $usuario = new Usuario;
        $alertas = [];
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $usuario->sincronizar($_POST);
            $alertas = $usuario->validarCrear($usuario);

            if (empty($alertas)) {
                $resultado = $usuario->existeUsuario();
                if ($resultado->num_rows) {
                    $alertas = Usuario::getAlertas();
                }
                else {
                    $usuario->hashPassword();
                    $usuario->generarToken();
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    $email->enviarConfirmacion();
                    $resultado = $usuario->guardar();
                    if ($resultado) {
                        header("Location: /mensaje");
                    }
                }
            }

        }

        $router->render("auth/crear-cuenta", [
            "usuario" => $usuario,
            "alertas" => $alertas
        ]);
    }

    public static function confirmar(Router $router) {
        $alertas = [];
        $token = s($_GET["token"]);

        $usuario = Usuario::where("token", $token);
        if (empty($usuario)) {
            Usuario::setAlerta("error", "Token no valido.");
        }
        else {
            $usuario->confirmado = 1;
            $usuario->token = null;
            $usuario->guardar();
            Usuario::setAlerta("exito", "Cuenta confirmada correctamente.");
        }
        $alertas = Usuario::getAlertas();
        $router->render("auth/confirmar-cuenta", [
            "alertas" => $alertas
        ]);
    }

    public static function mensaje(Router $router) {
        $router->render("auth/mensaje", []);
    }

}