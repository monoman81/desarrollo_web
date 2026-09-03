<?php

namespace Controllers;
use Model\Servicio;
use Model\Cita;
use Model\CitaServicio;

class APIController {

    public static function index() {
        $servicios = Servicio::all();
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($servicios);
        exit;
    }

    public static function guardar() {
        $citaId = 0;
        $cita = new Cita($_POST);
        $resultado = $cita->guardar();
        
        if ($resultado["resultado"]) {
            $citaId = $resultado["id"];
            $idServicios = explode(",", $_POST["servicios"]);
            foreach($idServicios as $idServicio) {
                $args = [
                    "citaId" => $citaId,
                    "servicioId" => $idServicio
                ];
                $citaServicio = new CitaServicio($args);
                $resultado = $citaServicio->guardar();
                if (!$resultado["resultado"]) break;
            }
        }
        
        $respuesta = [
            "success" => $resultado["resultado"],
            "id" => $citaId
        ];
        
        header('Content-Type: application/json; charset=utf-8');
        http_response_code(200);
        echo json_encode($respuesta);
        exit();
    }


}