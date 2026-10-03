<?php

namespace Classes;

use PHPMailer\PHPMailer\PHPMailer;

class Email
{
    protected $email;
    protected $nombre;
    protected $token;

    public function __construct($email, $nombre, $token)
    {
        $this->email = $email;
        $this->nombre = $nombre;
        $this->token = $token;
    }

    public function configure(): PHPMailer
    {
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->SMTPAuth = true;
        $mail->Host = $_ENV['EMAIL_HOST'];
        $mail->Username = $_ENV['EMAIL_USERNAME'];
        $mail->Password = $_ENV['EMAIL_PASSWORD'];
        $mail->SMTPSecure = $_ENV['EMAIL_SMTPSECURE'];
        $mail->Port = $_ENV['EMAIL_PORT'];
        $mail->setFrom('cuentas@uptask.com');
        $mail->addAddress('cuentas@uptask.com', 'upTask.com');
        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';
        return $mail;
    }

    public function enviarConfirmacion(): void
    {
        $mail = $this->configure();
        $mail->Subject = 'Confirma tu cuenta';
        $contenido = <<<MAIL
            <html>
            <body>
            <p><strong>Hola {$this->nombre}</strong>. Has creado tu cuenta en UpTask, solo debes confirmarla en el siguiente enlace</p>
            <p>Presiona aqui: <a href="{$_ENV['APP_URL']}/confirmar?token={$this->token}">Confirmar tu Cuenta</a></p>
            <p>Si tu no creaste esta cuenta, puedes ignorar este mensaje</p>
            </body>
            </html>
            MAIL;
        $mail->Body = $contenido;
        $mail->send();
    }

    public function enviarResetPassword(): void
    {
        $mail = $this->configure();
        $mail->Subject = 'Reestablece tu password';
        $contenido = <<<MAIL
            <html>
            <body>
            <p><strong>Hola {$this->nombre}</strong>. Parece que has olvidado tu password. Sigue el siguiente enlace para resetearlo.</p>
            <p>Presiona aqui: <a href="{$_ENV['APP_URL']}/reestablecer?token={$this->token}">Resetea tu Password</a></p>
            <p>Si tu no solicitaste el cambio, puedes ignorar este mensaje</p>
            </body>
            </html>
            MAIL;
        $mail->Body = $contenido;
        $mail->send();
    }

}