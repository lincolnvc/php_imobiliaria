<?php

class Smtpc extends PHPFrodo
{

    public $user_login;
    public $user_id;
    public $user_name;
    public $frete_param;

    public function __construct()
    {
        parent::__construct();
        $sid = new Session;
        $sid->start();
        if ( !$sid->check() || $sid->getNode( 'user_id' ) <= 0 )
        {
            $this->redirect( "$this->baseUri/admin/login/logout/" );
            exit;
        }
        $this->user_login = $sid->getNode( 'user_login' );
        $this->user_id = $sid->getNode( 'user_id' );
        $this->user_name = $sid->getNode( 'user_name' );
        $this->user_level = $sid->getNode( 'user_level' );
        if ( $this->user_level == 2 )
        {
            $this->redirect( "$this->baseUri/admin/" );
        }
        $this->assign( 'user_name', $this->user_name );
        $this->select()
                ->from( 'config' )
                ->execute();
        if ( $this->result() )
        {
            $this->config = ( object ) $this->data[0];
            $this->assignAll();
        }
        if ( isset( $this->uri_segment ) && in_array( 'process-ok', $this->uri_segment ) )
        {
            $this->assign( 'msgOnload', 'notify("<h1>Procedimento realizado com sucesso</h1>")' );
        }
    }

    public function welcome()
    {
        $this->tpl( 'admin/smtp.html' );
        $this->select()
                ->from( 'smtp' )
                ->execute();
        if ( $this->result() )
        {
            $this->assignAll();
        }
        $this->render();
    }

    public function atualizar()
    {
        if ( $this->postIsValid( array( 'smtp_host' => 'string', 'smtp_username' => 'string' ) ) )
        {
            if ( trim( $this->postGetValue( 'smtp_password' ) ) == "" )
            {
                $this->postIndexDrop( 'smtp_password' );
            }
            $this->update( 'smtp' )->set()->execute();

            //chat update
            $email = $this->postGetValue( 'smtp_username' );
            $title = $this->postGetValue( 'smtp_fromname' );
            $host = $this->baseUri;
            //atualiza url chat
            $this->update( 'chatconfig' )
                    ->set( array( 'vcvalue' ), array( "$host/" ) )
                    ->where( "id = 4" )
                    ->execute();

            //atualiza title chat
            $this->update( 'chatconfig' )
                    ->set( array( 'vcvalue' ), array( "$title" ) )
                    ->where( "id = 3" )
                    ->execute();

            //atualiza title chat
            $this->update( 'chatconfig' )
                    ->set( array( 'vcvalue' ), array( "$email" ) )
                    ->where( "id = 13" )
                    ->execute();

            $this->redirect( "$this->baseUri/admin/smtpc/process-ok/" );
        }
    }

    public function test()
    {

        parse_str( $_POST['dados'], $post );
        $this->post2Query( $post );
        if ( trim( $this->postGetValue( 'smtp_password' ) ) == "" )
        {
            $this->postIndexDrop( 'smtp_password' );
        }
        $this->postValueChange( 'smtp_fromname', utf8_decode( $this->postGetValue( 'smtp_fromname' ) ) );

        $this->update( 'smtp' )->set()->execute();
        $this->select()->from( 'smtp' )->execute();
        if ( $this->result() )
        {
            $m = ( object ) $this->data[0];
            $this->helper( 'mail' );
            global $mail;
            $mail->Port = $m->smtp_port;
            $mail->Host = "$m->smtp_host";
            $mail->Username = $m->smtp_username;
            $mail->Password = $m->smtp_password;
            $mail->From = $m->smtp_username;
            $mail->FromName = utf8_decode( $m->smtp_fromname );
            $mail->Subject = "Teste Envio";
            if ( strlen( $m->smtp_bcc ) >= 1 )
            {
                $mail->AddBCC( $m->smtp_bcc );
            }
            $mail->AddAddress( $m->smtp_username );
            $mail->AddReplyTo( $m->smtp_replyto );
            $mail->Body = "asdas";
            if ( $mail->Send() )
            {
                echo 0;
            }
            else
            {
                echo "Erro: $mail->ErrorInfo <br/> Provaveis causas: <br> - E-mail, Senha, Porta ou Servidor SMTP incorretos.";
            }
        }
        else
        {
            echo "Configuração incompleta, verifique os campos!";
        }
    }

}

/*end file*/