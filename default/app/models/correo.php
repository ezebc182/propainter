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

    public function inviareEmailAlClienti($datos)
    {
        $mail = self::_initParams();

        $mail->setFrom('non-risposta@propainter.it', 'Non risposta | PRO PAINTER');
        //$mail->addBCC('info@propainter.com', 'info | PRO PAINTER');     // Add a recipient
        $mail->addAddress($datos["email"], $datos["nombre"]);
        // Name is optional

        $mail->Subject = '[PROPAINTER-WEB] ' . ucfirst($datos["nombre"]) . ' abbiamo ricevuto la tua email!';
        $mail->Body = "<h3>Gentile cliente:</h3>";
        $mail->Body .= "<p>Grazie per il vostro messaggio, verrete ricontattati al piú presto da un nostro responsabile</p> <br>";
        $mail->Body .= "Attentamente<br>";
        $mail->Body .= "<p><b>Massimiliano Edoardo Saurin</b></p>";
        $mail->Body .= "<p>+39.331.795.6936 - maxsaurin@propainter.it | info@propainter.it</p>";
        $mail->Body .= "<a href='http://www.propainter.it/'>
        <img src='http://www.propainter.it/img/logos/logo-min.png'></a>";


        $mail->AltBody = "Gentile cliente: Abbiamo ricevuto la tua email. Noi risponderemo prontamente. Grazie mille.
        Attentamente, PROPAINTER.";

        $mail->send();
    }

    public function enviar($datos)
    {
        $mail = self::_initParams();

        $mail->setFrom('non-risposta@propainter.it', 'Non risposta | PRO PAINTER');
        $mail->addAddress('info@propainter.com', 'info | PRO PAINTER');     // Add a recipient
        //$mail->addBCC('eebarcoch@gmail.com');               // Name is optional

        $mail->Subject = '[PROPAINTER-WEB] Nuovo email di ' . ucfirst($datos["nombre"]);
        $mail->Body = "<h3>Informazione del cliente: </h3><br/>";
        $mail->Body .="<p><b>Nome: </b>".$datos["nombre"]. "</p>";
        $mail->Body .="<p><b>Email: </b>".$datos["email"]. "</p>";
        $mail->Body .="<p><b>Messaggio: </b>".$datos["mensaje"]. "</p>";

        $mail->AltBody ="Nome:".$datos["nombre"];
        $mail->AltBody .="Email:".$datos["email"];
        $mail->AltBody .="Messaggio:".$datos["mensaje"];
        self::inviareEmailAlClienti($datos);

        if (!$mail->send()) {

            return false;
        } else {
            return true;
        }
    }


}