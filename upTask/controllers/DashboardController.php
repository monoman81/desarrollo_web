<?php

namespace Controllers;

use Model\Usuario;
use MVC\Router;

class DashboardController
{

    public static function index(Router $router): void
    {
        session_start();
        isAuth();
        $router->render('/dashboard/index', [
            'titulo' => 'Proyectos',
        ]);
    }

    public static function crear_proyecto(Router $router): void
    {
        session_start();
        isAuth();
        $router->render('/dashboard/crear-proyecto', [
            'titulo' => 'Crear Proyecto',
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