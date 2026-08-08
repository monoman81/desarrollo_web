<?php

namespace Controllers;
use MVC\Router;
use Model\Propiedad;
use PHPMailer\PHPMailer\PHPMailer;

class PaginaController {

     public static function index(Router $router) {
        $propiedades = Propiedad::get(3);
        $router->render("public/index", [
            "propiedades" => $propiedades,
            "inicio" => true
        ]);
    }

    public static function nosotros(Router $router) {
        $router->render("public/nosotros", []);
    }

    public static function propiedades(Router $router) {
        $propiedades = Propiedad::all();
        $router->render("public/propiedades", [
            "propiedades" => $propiedades
        ]);
    }

    public static function propiedad(Router $router) {
        $id = validarORedireccionar("/");
        $propiedad = Propiedad::find($id);
        $router->render("public/propiedad", [
            "propiedad" => $propiedad,
            "found" => $propiedad ? true : false
        ]);
    }

    public static function blog(Router $router) {
        $router->render("public/blog", []);
    }

    public static function entrada(Router $router) {
        $router->render("public/entrada", []);
    }

    public static function contacto(Router $router) {
        $mensaje = null;
        if ($_SERVER["REQUEST_METHOD"] === "POST") {

            $respuestas = $_POST["contacto"];

            $mail = new PHPMailer();
            $mail->isSMTP();
            $mail->Host = "sandbox.smtp.mailtrap.io";
            $mail->SMTPAuth = true;
            $mail->Username = "20b83bee71a8da";
            $mail->Password = "8ae2eeb5e62f9e";
            $mail->SMTPSecure = "tls";
            $mail->Port = 2525;

            $mail->setFrom("admin@bienesraices.com");
            $mail->addAddress("admin@bienesraices.com", "BienesRaices.com");
            $mail->Subject = "Tienes un nuevo mensaje";
            
            $mail->isHTML(true);
            $mail->CharSet = "UTF-8";

            // $contenido = "<html><p>Tienes un nuevo mensaje</p></html>";

            $contenido = <<<MAIL
            <html>
            <body>
            <p>Tienes un nuevo mensaje</p>
            <p>Nombre: {$respuestas['nombre']}</p>
            <p>Email: {$respuestas['email']}</p>
            <p>Mensaje: {$respuestas['mensaje']}</p>
            </body>
            </html>
            MAIL;
            // debug($contenido);
            $mail->Body = $contenido;
            $mail->AltBody = "Esto es texto alternativo sin HTML";
            if ($mail->send()) {
                $mensaje = "Mensaje enviado correctamente";
            }
            else {
                $mensaje = "El mensaje no se pudo enviar";  
            }

        }
        $router->render("public/contacto", [
            "mensaje" => $mensaje
        ]);
    }

    

    


}