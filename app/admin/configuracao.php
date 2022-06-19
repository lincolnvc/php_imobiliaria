<?php

class Configuracao extends PHPFrodo
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
        $this->tpl( 'admin/configuracao.html' );
        $this->select()
                ->from( 'config' )
                ->execute();
        if ( $this->result() )
        {
            $this->helper( 'redactor' );
            $editor = editor( $this->data[0]['config_site_about'], 'config_site_about', '350px', '90%' );
            $this->assign( 'editor', $editor );
            $this->assignAll();
        }
        $this->render();
    }

    public function atualizar()
    {
        if ( $this->postIsValid( array( 'config_site_title' => 'string' ) ) )
        {
            $title = addslashes( strip_tags( $this->postGetValue( 'config_site_title' ) ) );
            $this->postValueChange( 'config_site_title', $title );
            $this->update( 'config' )->set()->execute();
            $this->redirect( "$this->baseUri/admin/configuracao/process-ok/" );
        }
    }
}
/*end file*/