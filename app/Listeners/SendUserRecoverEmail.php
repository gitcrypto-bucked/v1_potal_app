<?php

namespace App\Listeners;

use App\Events\UserRecovered;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use PHPMailer\PHPMailer\PHPMailer;

class SendUserRecoverEmail
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(UserRecovered $event): void
    {
        $user = $event->user;
        $name = $user['name'];
        $email = $user['email'];
        $user_token = $user['user_token'];
        $url =$user['url'];
        try
        {
            $this->sendRecoverPasswordEmail($email, $url, $name, $user_token);
        }
        catch (\Exception $e)
        {
            Log::info($e->getMessage());
        }
    }


    /**@retorna o corpo do email em html */
    protected  function getPasswordRecoverTemplate( $url, $name, $user_token):string
    {
        return '  <!DOCTYPE html>
            <html>
          <head>
          <meta charset="UTF-8">
          <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
          <style>

        @import "https://fonts.cdnfonts.com/css/poppins";

          body
          {
            font-family: Poppins, sans-serif;
            font-size: 15px !important;
          }
        #customers {
          font-family: Poppins, sans-serif;
          border-collapse: collapse;
          width: 100%;
        }

        #customers td, #customers th {
          border: none ;
          padding: 8px;
        }

        #customers tr:nth-child(even){background-color: #F8F8F8;}

        #customers tr{background-color: #F8F8F8;}

        #customers th {
          padding-top: 20px;
          padding-bottom: 20px;
          text-align: left;
          background-color: #cfdadf ;
          color: black;
        }

        table tr th
        {
            font-size:14px;
        }

        th
        {
            width:100%;
        }



        .logo {
            width: 125px;
        }

        .button {
          font: bold 11px Arial;
          text-decoration: none;
          background-color:#0E86D4;
          color: white !important;
          padding: 12px 16px 12px 16px;
          border-radius:5px;
        }
        </style>
        </head>
        <body>


        <table id="customers">
          <tr>
            <th><img src="cid:logoimg" class="logo"  alt="PHPMailer"></th>

          </tr>
          <tr>
            <td></td>


          </tr>
          <tr>
            <td>Caro(a) '.$name.', recebemos com sucesso seu pedido de recuperação de senha!</td>


          </tr>
          <tr>
               <td>Para logar-se no portal informe o token: '.$user_token.'</td>
          </tr>
          <tr>
            <td>Clique no botão abaixo, para cadastrar uma nova senha.</td>

          </tr>
          <tr>
            <td></td>

          </tr>
          <tr>
           <td><a href="'.$url.'" target="_blank" class="button">Criar Senha</a></td>

          </tr>
           <tr>
            <td></td>

          </tr>
          <tr>
            <td>Att</td>

          </tr>

          <tr>
            <td>LowCost</td>

          </tr>
        </table>

        </body>
        </html>
        ';
    }

    private function sendRecoverPasswordEmail($to, $url, $name, $user_token):bool
    {
        $headers = 'FROM: '.self::from;
        $message = self::getPasswordRecoverTemplate( $url, $name, $user_token);

        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->SMTPDebug = 0;
        $mail->Host = self::smpt;
        $mail->Port = self::port;
        $mail->SMTPSecure = 'tls'; //important
        $mail->SMTPAuth = true;
        $mail->Username = self::from;
        $mail->Password = self::password;

        $mail->setFrom(self::from, 'Portal LowCost');
        $mail->addReplyTo($to, $name);
        $mail->addAddress($to, $name);
        $mail->AddEmbeddedImage(dirname(getcwd()).'/public/logo/logo.png', 'logoimg', 'logo.jpg');

        $mail->Subject = 'Cadastro de usuarios';
        $mail->Body = $message;
        $mail->IsHTML(true);
        if (!$mail->send()) {
//            echo 'Mailer Error: ' . $mail->ErrorInfo;
            return false;
        } else {
//            echo 'The email message was sent.';
            return true;
        }
    }

    /** @var string configurações do servidor de email */
    private const smpt='smtp.office365.com';
    private const from = 'pedro.henrique@lowcost.com.br';
    private const password ='9002Fast@';
    private const port =587;
}
