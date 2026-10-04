<?php

namespace Controllers;

use Model\Proyecto;
use Model\Usuario;
use MVC\Router;

class DashboardController
{

    public static function index(Router $router): void
    {
        session_start();
        isAuth();

        $proyectos = Proyecto::belongsTo('propietario_id', $_SESSION['id']);
        $router->render('/dashboard/index', [
            'titulo' => 'Proyectos',
            'proyectos' => $proyectos,
        ]);
    }

    public static function crear_proyecto(Router $router): void
    {
        session_start();
        isAuth();
        $alertas = [];
        $proyecto = new Proyecto();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $proyecto->sincronizar($_POST);
            $alertas = $proyecto->validar();
            if (empty($alertas)) {
                $proyecto->url = md5(uniqid(rand(), true));
                $proyecto->propietario_id = $_SESSION['id'];
                $resultado = $proyecto->guardar();
                if ($resultado) {
                    header("Location: /proyecto?id=$proyecto->url");
                }
            }
        }

        $router->render('/dashboard/crear-proyecto', [
            'titulo' => 'Crear Proyecto',
            'alertas' => $alertas,
            'proyecto' => $proyecto,
        ]);
    }

    public static function proyecto(Router $router): void
    {
        session_start();
        isAuth();
        $token = $_GET['id'];
        if (!$token) {
            header('Location: /dashboard');
        }
        $proyecto = Proyecto::where('url', $token);
        if (!$proyecto || $proyecto->propietario_id != $_SESSION['id']) {
            header('Location: /dashboard');
        }
        $router->render('/dashboard/proyecto', [
            'titulo' => $proyecto->proyecto,
            'proyecto' => $proyecto,
        ]);
    }

    public static function perfil(Router $router): void
    {
        session_start();
        isAuth();
        $router->render('/dashboard/perfil', [
            'titulo' => 'Perfil',
        ]);
    }

}