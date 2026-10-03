<?php

namespace Controllers;

use Model\Usuario;
use Classes\Email;
use MVC\Router;

class LoginController
{
    public static function login(Router $router)
    {
        session_start();
        if (isset($_SESSION['logged']) && $_SESSION['logged']) {
            header('Location: /dashboard');
        }
        $alertas = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $auth = new Usuario($_POST);
            $alertas = $auth->validarLogin();
            if (empty($alertas)) {
                $auth = Usuario::where('email', $auth->email);
                if (!$auth || !$auth->confirmado) {
                    Usuario::setAlerta('error', 'El usuario no existe o no se ha confirmado aun');
                } else {
                    if (!password_verify($_POST['password'], $auth->password)) {
                        Usuario::setAlerta('error', 'El password es incorrecto');
                    } else {
                        Usuario::setAlerta('success', 'El usuario se ha logueado exitosamente');
                        $_SESSION['logged'] = true;
                        $_SESSION['id'] = $auth->id;
                        $_SESSION['email'] = $auth->email;
                        $_SESSION['name'] = $auth->nombre;
                        header('Location: /dashboard');
                    }
                }
                $alertas = Usuario::getAlertas();
            }
        }
        $router->render('auth/login', [
            'titulo' => 'Iniciar Sesion',
            'alertas' => $alertas,
        ]);
    }

    public static function logout(Router $router)
    {
        session_start();
        $_SESSION = [];
        session_destroy();
        header('Location: /');
    }

    public static function crear(Router $router)
    {
        $usuario = new Usuario();
        $alertas = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario->sincronizar($_POST);
            $alertas = $usuario->validarNuevaCuenta();
            if (empty($alertas)) {
                $existeUsuario = Usuario::where('email', $usuario->email);
                if ($existeUsuario) {
                    Usuario::setAlerta('error', 'El usuario ya esta registrado');
                    $alertas = Usuario::getAlertas();
                } else {
                    $usuario->hashPassword();
                    unset($usuario->password2);
                    $usuario->crearToken();
                    $resultado = $usuario->guardar();
                    if ($resultado) {
                        $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                        $email->enviarConfirmacion();
                        header('Location: /mensaje');
                    }
                }
            }
        }
        $router->render('auth/crear', [
            'titulo' => 'Crear Usuario',
            'usuario' => $usuario,
            'alertas' => $alertas,
        ]);
    }

    public static function olvide(Router $router)
    {
        $alertas = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario = new Usuario($_POST);
            $alertas = $usuario->validarEmail();
            if (empty($alertas)) {
                $usuario = Usuario::where('email', $usuario->email);
                if (!$usuario || !$usuario->confirmado) {
                    Usuario::setAlerta('error', 'El usuario no existe o no esta confirmado');
                } else {
                    $usuario->crearToken();
                    unset($usuario->password2);
                    $usuario->guardar();
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    $email->enviarResetPassword();
                    Usuario::setAlerta(
                        'success',
                        'Hemos enviado las instrucciones a tu email para recuperar tu password',
                    );
                }
            }
        }
        $alertas = Usuario::getAlertas();
        $router->render('auth/olvide', [
            'titulo' => 'Recuperar Password',
            'alertas' => $alertas,
        ]);
    }

    public static function reestablecer(Router $router)
    {
        $mostrar = true;
        $token = s($_GET['token']);
        if (!$token) {
            header('Location: /');
        }
        $usuario = Usuario::where('token', $token);
        if (empty($usuario)) {
            Usuario::setAlerta('error', 'Token no valido');
            $mostrar = false;
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario->sincronizar($_POST);
            $alertas = $usuario->validarPassword();
            if (empty($alertas)) {
                $usuario->hashPassword();
                unset($usuario->password2);
                $usuario->token = '';
                $resultado = $usuario->guardar();
                if ($resultado) {
                    header('Location: /');
                }
            }
        } else {
            $alertas = Usuario::getAlertas();
        }
        $router->render('auth/reestablecer', [
            'titulo' => 'Reestablece tu Password',
            'alertas' => $alertas,
            'mostrar' => $mostrar,
        ]);
    }

    public static function mensaje(Router $router)
    {
        $router->render('auth/mensaje', [
            'titulo' => 'Cuenta creada exitosamente',
        ]);
    }

    public static function confirmar(Router $router)
    {
        $token = s($_GET['token']);
        if (!$token) {
            header('Location: /');
        }
        $usuario = Usuario::where('token', $token);
        if (empty($usuario)) {
            Usuario::setAlerta('error', 'Token invalido');
        } else {
            $usuario->confirmado = 1;
            $usuario->token = '';
            unset($usuario->password2);
            $usuario->guardar();
            Usuario::setAlerta('success', 'Se ha confirmado tu cuenta. Ya puedes iniciar sesion');
        }
        $alertas = Usuario::getAlertas();
        $router->render('auth/confirmar', [
            'titulo' => 'Confirma tu cuenta UpTask',
            'alertas' => $alertas,
        ]);
    }
}
