<?php

class Index extends PHPFrodo
{
    private $user_login;
    private $user_id;
    private $user_name;

    public function __construct()
    {
        parent::__construct();
        $sid = new Session;
        $sid->start();
        if ( !$sid->check() || $sid->getNode( 'user_id' ) <= 0 || strlen( $sid->getNode( 'user_login' ) ) <= 0 )
        {
            $this->redirect( "$this->baseUri/admin/login/logout/" );
            exit;
        }
        $this->user_login = (string) $sid->getNode( 'user_login' );
        $this->user_id = $sid->getNode( 'user_id' );
        $this->user_name = $sid->getNode( 'user_name' );
        $this->user_level = $sid->getNode( 'user_level' );
        if ( $this->user_level == 2 )
        {
            $this->assign( 'HideAccess', 'hide' );
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
        //sessao atendimento
        $this->select()->from( 'chatoperator' )->where( "vclogin = '$this->user_login'" )->execute();
        if ( isset( $this->data[0] ) )
        {
            $value = $this->user_login . "," . $this->data[0]['vcpassword'];
            @setcookie( 'webim_lite', $value, time() + 60 * 60 * 24 * 1000, "$this->baseUri/atd/" );
            $_SESSION["operator"] = $this->data[0];
        }else{
            $this->redirect( "$this->baseUri/admin/login/logout/" );
        }
    }

    public function welcome()
    {
        $this->tpl( 'admin/dashboard.html' );
        $this->render();
    }

    public function atdLogin()
    {
        //sessao atendimento
        $this->select()->from( 'chatoperator' )->where( "vclogin = '$this->user_login'" )->execute();
        $value = $this->user_login . "," . $this->data[0]['vcpassword'];
        @setcookie( 'webim_lite', $value, time() + 60 * 60 * 24 * 1000, "$this->baseUri/atd/" );
        $_SESSION["operator"] = $this->data[0];
    }

}
/*end file*/