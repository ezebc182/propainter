<?php

/**
 * Created by PhpStorm.
 * User: Ezequiel
 * Date: 04/09/2016
 * Time: 01:00 AM
 */

//Load::lib("PHPMailer/PHPMailer.php");
include_once APP_PATH . "libs/PHPMailer/PHPMailer.php";
class Correo
{
    private function _initParams(){


                                       // Enable verbose debug output
        $mail = new PHPMailer();
        $mail->SMTPDebug = 2;
        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = 'mail.propainter.it';  // Specify main and backup SMTP servers
        //$mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = 'info@propainter.it';                 // SMTP username
        $mail->Password = 'propainter123!';                           // SMTP password
        //$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 25;
        $mail->isHTML(true);                                     // Set email format to HTML

        return $mail;
    }
    public function enviar($datos)
    {
        $mail = self::_initParams();

        $mail->setFrom('info@propainter.it', 'PRO PAINTER');
        //$mail->addAddress('info@propainter.com', 'INFO PRO PAINTER');     // Add a recipient
        $mail->addBCC('eebarcoch@gmail.com');               // Name is optional

        $mail->Subject = '[PROPAINTER-WEB] Nuovo email di '.$datos["nombre"];
        $mail->Body = $datos["mensaje"];
        $mail->AltBody = $datos["mensaje"];

        if (!$mail->send()) {
            return false;
        } else {
            return true;
        }
    }
}