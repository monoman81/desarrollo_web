<?php

namespace Controllers;
use MVC\Router;
use Model\Usuario;
use Classes\Email;

class LoginController {

    public static function login(Router $router) {
        $router->render("auth/login", []);
    }

    public static function logout(Router $router) {
        echo "desde logout";
    }

    public static function olvide(Router $router) {
        $router->render("auth/olvide", []);
    }

    public static function recuperar(Router $router) {
        echo "desde recuperar";
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