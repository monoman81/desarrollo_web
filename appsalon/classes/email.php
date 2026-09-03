<?php

namespace Classes;
use PHPMailer\PHPMailer\PHPMailer;

class Email {

    public $email;
    public $nombre;
    public $token;
    
    public function __construct($email, $nombre, $token) {
        $this->email = $email;
        $this->nombre = $nombre;
        $this->token = $token;
    }

    public function enviarConfirmacion() {
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = "sandbox.smtp.mailtrap.io";
        $mail->SMTPAuth = true;
        $mail->Username = "20b83bee71a8da";
        $mail->Password = "8ae2eeb5e62f9e";
        $mail->SMTPSecure = "tls";
        $mail->Port = 2525;

        $mail->setFrom("cuentas@appsalon.com");
        $mail->addAddress("cuentas@appsalon.com", "AppSalon.com");
        $mail->Subject = "Confirmar tu cuenta";

        $mail->isHTML(true);
        $mail->CharSet = "UTF-8";

        $contenido = <<<MAIL
        <html>
        <body>
        <p>Hola <strong>$this->nombre</strong> has creado tu cuenta en App Salon, solo debes de confirmarla presionando el siguiente enlace: </p>
        <p>Presiona aqui: <a href="http://appsalon.test/confirmar-cuenta?token=$this->token">Confirmar tu Cuenta</a></p>
        <p>Si tu no creaste esta cuenta, puedes ignorar este mensaje</p>
        </body>
        </html>
        MAIL;
        // debug($contenido);
        $mail->Body = $contenido;
        $mail->send();
    }

    public function enviarInstrucciones() {
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = "sandbox.smtp.mailtrap.io";
        $mail->SMTPAuth = true;
        $mail->Username = "20b83bee71a8da";
        $mail->Password = "8ae2eeb5e62f9e";
        $mail->SMTPSecure = "tls";
        $mail->Port = 2525;

        $mail->setFrom("cuentas@appsalon.com");
        $mail->addAddress("cuentas@appsalon.com", "AppSalon.com");
        $mail->Subject = "Reestablece tu password";

        $mail->isHTML(true);
        $mail->CharSet = "UTF-8";

        $contenido = <<<MAIL
        <html>
        <body>
        <p>Hola <strong>$this->nombre</strong> para reestablecer tu password haz click en el siguiente enlace: </p>
        <p>Presiona aqui: <a href="http://appsalon.test/recuperar?token=$this->token">Reestablecer Password</a></p>
        <p>Si tu no solicitaste este cambio, puedes ignorar este mensaje</p>
        </body>
        </html>
        MAIL;
        // debug($contenido);
        $mail->Body = $contenido;
        $mail->send();
    }

}