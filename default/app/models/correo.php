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
    private function _initParams()
    {


        // Enable verbose debug output
        $mail = new PHPMailer();
        //$mail->SMTPDebug = 2;
        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = 'mail.propainter.it';  // Specify main and backup SMTP servers
        //$mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = 'non-risposta@propainter.it';                 // SMTP username
        $mail->Password = 'n0nr1p05t4';                           // SMTP password
        //$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 25;
        $mail->isHTML(true);                                     // Set email format to HTML

        return $mail;
    }
    public function inviareEmailAlClienti($mail,$datos)
    {
        //$mail = self::_initParams();

        $mail->setFrom('non-risposta@propainter.it', 'PRO PAINTER');
        //$mail->addAddress('info@propainter.com', 'INFO PRO PAINTER');     // Add a recipient
        $mail->addBCC($datos["email"]);               // Name is optional

        $mail->Subject = '[PROPAINTER-WEB]' . ucfirst($datos["nombre"]).' abbiamo ricevuto la tua email!';
        $mail->Body = "<h2>Gentile cliente <b>" . ucfirst($datos['nombre']) . "</b></h2>,<br>";
        $mail->Body .= "abbiamo ricevuto la tua email. <br>";
        $mail->Body .= "Noi risponderemo prontamente.   <br>";
        $mail->Body .= "Grazie mille <br>  ";
        $mail->Body .= "Attentamente<br>";
        $mail->Body .= "<a href='http://www.propainter.it/'>";
        $mail->Body .= "<img src='http://www.propainter.it/img/logos/logo-min.png'></a>";
        $mail->Body .= "Tel: +39.331.795.6936    Email info@propainter.it";
        $mail->AltBody = "Gentile cliente: Abbiamo ricevuto la tua email. Noi risponderemo prontamente. Grazie mille.
        Attentamente, PROPAINTER.";

        $mail->send();
    }
    public function enviar($datos)
    {
        $mail = self::_initParams();

        $mail->setFrom('non-risposta@propainter.it', 'PRO PAINTER');
        //$mail->addAddress('info@propainter.com', 'INFO PRO PAINTER');     // Add a recipient
        $mail->addBCC('eebarcoch@gmail.com');               // Name is optional

        $mail->Subject = '[PROPAINTER-WEB] Nuovo email di ' . ucfirst($datos["nombre"]);
        $mail->Body = $datos["mensaje"];
        $mail->AltBody = $datos["mensaje"];

        if (!$mail->send()) {
            self::inviareEmailAlClienti($mail,$datos);
            return false;
        } else {
            return true;
        }
    }


}