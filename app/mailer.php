<?php
class Mailer extends PHPFrodo
{

    public $config = array( );
    public $menu;
    public $smtp;

    public function __construct()
    {
        parent:: __construct();
        $this->select()
                ->from( 'config' )
                ->execute();
        if ( $this->result() )
        {
            $this->config = ( object ) $this->data[0];
            $this->assignAll();
        }
        $this->select()
                ->from( 'smtp' )
                ->execute();
        if ( $this->result() )
        {
            $this->smtp = $this->data[0];
        }
    }

    public function send()
    {
        parse_str( $_POST['ar'], $post );
        $this->post2Query( $post );
        $nome = $this->postGetValue('nome');
        $email = $this->postGetValue('email');
        $fone = $this->postGetValue('fone');
        $mensagem = $this->postGetValue('mensagem');
        $data = date('d/m/Y h:i');
        
        $this->helper( 'mail' );
        global $mail;
        $m = (object) $this->smtp;
        $mail->Port = $m->smtp_port;
        $mail->Host = "$m->smtp_host";
        $mail->Username = $m->smtp_username;
        $mail->Password = $m->smtp_password;
        $mail->From = $m->smtp_username;
        $mail->FromName = $m->smtp_fromname;
        $mail->Subject = "Contato Via Site";
        $mail->AddBCC( $m->smtp_bcc );
        $mail->AddAddress( $m->smtp_username );
        $mail->AddReplyTo( $email );
        $body = "<b>Data da Mensagem: </b> $data <br />";
        $body .= "<b>Nome:</b> $nome <br />";
        $body .= "<b>E-mail:</b> $email <br />";
        $body .= "<b>Telefone: </b>$fone <br />";
        $body .= "<b>Mensagem: </b>$mensagem <br />";
        $mail->Body = nl2br($body);
        if ( $mail->Send() )
        {
            echo 0;
        }
        else
        {
            echo "Erro: $mail->ErrorInfo <br/> Provaveis causas: <br> - E-mail, Senha, Porta ou Servidor SMTP incorretos.";
        }
    }

    public function sendFromRef()
    {
        parse_str( $_POST['ar'], $post );
        $this->post2Query( $post );
        $nome = $this->postGetValue('nome');
        $ref = $this->postGetValue('ref');
        $email = $this->postGetValue('email');
        $fone = $this->postGetValue('fone');
        $mensagem = $this->postGetValue('mensagem');
        $data = date('d/m/Y h:i');
        
        $this->helper( 'mail' );
        global $mail;
        $m = (object) $this->smtp;
        $mail->Port = $m->smtp_port;
        $mail->Host = "$m->smtp_host";
        $mail->Username = $m->smtp_username;
        $mail->Password = $m->smtp_password;
        $mail->From = $m->smtp_username;
        $mail->FromName = $m->smtp_fromname;
        $mail->Subject = "Contato Via Site - Imóvel [$ref]";
        $mail->AddBCC( $m->smtp_bcc );
        $mail->AddAddress( $m->smtp_username );
        $mail->AddReplyTo( $email );
        $body = "<b>Data da Mensagem: </b> $data <br />";
        $body .= "<b>Ref.:</b> $ref <br />";
        $body .= "<b>Nome:</b> $nome <br />";
        $body .= "<b>E-mail:</b> $email <br />";
        $body .= "<b>Telefone: </b>$fone <br />";
        $body .= "<b>Mensagem: </b>$mensagem <br />";
        
        $mail->Body = nl2br( utf8_decode($body) );
        if ( $mail->Send() )
        {
            echo 0;
        }
        else
        {
            //echo "Erro: $mail->ErrorInfo <br/> Provaveis causas: <br> - E-mail, Senha, Porta ou Servidor SMTP incorretos.";
            echo "Ocorreu um problema  ao tentar enviar a mensagem, por favor tente novamente mais tarde!";
        }
    }

}

/*end file*/