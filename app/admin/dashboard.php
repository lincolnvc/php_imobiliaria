<?php

class Dashboard extends PHPFrodo
{

    private $user_login;
    private $user_id;
    private $user_name;

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
        $this->select()
                ->from( 'config' )
                ->execute();
        if ( $this->result() )
        {
            $this->config = ( object ) $this->data[0];
            $this->assignAll();
        }        
        $this->user_login = $sid->getNode( 'user_login' );
        $this->user_id = $sid->getNode( 'user_id' );
        $this->user_name = $sid->getNode( 'user_name' );
        $this->user_level = $sid->getNode( 'user_level' );
        if ( $this->user_level == 2 )
        {
            $this->assign( 'HideAccess', 'hide' );
        }
        $this->assign( 'user_name', $this->user_name );
        //login atendimento
        $atd = new Index;
        $atd->atdLogin();
    }

    public function welcome()
    {
        $this->tpl( 'admin/dashboard.html' );
        $this->render();
    }
    public function itens()
    {
		$this->pagebase = "$this->baseUri/admin/dashboard/itens";
        $this->tpl( 'admin/dashboard_item.html' );
        $this->select()
                ->from( 'item' )
                ->join( 'sub', 'sub_id = item_sub', 'INNER' )
                ->join( 'categoria', 'sub_categoria = categoria_id', 'INNER' )
                ->join( 'user', 'item_user = user_id', 'INNER' )
                ->paginate( 20 )
                ->orderby( 'item_views desc' )
                ->execute();
        if ( $this->result() )
        {
            $this->money( 'item_preco' );
            $this->money( 'item_desconto' );
            $this->fetch( 'rs', $this->data );
        }
        $this->render();
    }

}

/*end file*/