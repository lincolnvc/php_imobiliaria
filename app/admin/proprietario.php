<?php

class Proprietario extends PHPFrodo
{
    private $user_login;
    private $user_id;
    private $user_name;
    private $dono_id;

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
        if ( isset( $_SESSION['expired'] ) )
        {
            $this->redirect( "$this->baseUri/admin/" );
            exit;
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
        $this->select()
                ->from( 'config' )
                ->execute();
        if ( $this->result() )
        {
            $this->config = ( object ) $this->data[0];
            $this->assignAll();
        }
    }

    public function welcome()
    {
        $this->tpl( 'admin/proprietario.html' );
        $this->pagebase = "$this->baseUri/admin/proprietario";
        $this->select()->from( 'dono' )->paginate( 14 );
        if ( $this->user_level == 2 )
        {
            $this->where( "dono_user = $this->user_id" );
        }
        $this->orderby( 'dono_nome asc' )->execute();
        if ( $this->result() )
        {
            $this->fetch( 'rs', $this->data );
        }
        $this->assign( 'dono_qtde', $this->numrows );
        $this->render();
    }

    public function imoveis()
    {
        $this->pagebase = "$this->baseUri/admin/proprietario/imoveis";
        $this->tpl( 'admin/proprietario_imoveis.html' );
        $this->select()
                ->from( 'dono' )
                ->join( 'item', 'item_dono = dono_id', 'INNER' )
                ->join( 'categoria', 'item_categoria = categoria_id', 'INNER' )
                ->join( 'sub', 'item_sub = sub_id', 'INNER' )
                ->join( 'tipo', 'item_tipo = tipo_id', 'INNER' )
                ->join( 'user', 'item_user = user_id', 'INNER' )
                ->paginate( 14 );
        if ( $this->user_level == 2 )
        {
            $this->where( "dono_user = $this->user_id" );
        }
        $this->orderby( 'dono_nome asc' )->execute();
        if ( $this->result() )
        {
            $this->fetch( 'rs', $this->data );
        }
        $this->assign( 'dono_qtde', $this->numrows );
        $this->render();
    }

    public function incluir()
    {
        if ( $this->postIsValid( array( 'dono_nome' => 'string', 'dono_telefone1' => 'string' ) ) )
        {
            $this->postIndexAdd( 'dono_user', $this->user_id );
            $this->insert( 'dono' )->fields()->values()->execute();
            $this->redirect( "$this->baseUri/admin/proprietario/" );
        }
    }

    public function atualizar()
    {
        if ( isset( $this->uri_segment[2] ) )
        {
            if ( $this->postIsValid( array( 'dono_nome' => 'string' ) ) )
            {
                $this->dono_id = $this->uri_segment[2];
                $this->postValueChange( 'dono_nome', ucfirst( $this->postGetValue( 'dono_nome' ) ) );
                $this->update( 'dono' )
                        ->set()
                        ->where( "dono_id = $this->dono_id" )
                        ->execute();
                $this->redirect( "$this->baseUri/admin/proprietario/process-ok/" );
            }
        }
    }

    public function remover()
    {
        if ( isset( $this->uri_segment[2] ) )
        {
            $this->dono_id = $this->uri_segment[2];
            $this->select()->from( 'item' )->join( 'dono', 'dono_id = item_dono', 'INNER' );
            if ( $this->user_level == 2 )
            {
                $this->where( "item_dono = $this->dono_id AND dono_user = $this->user_id" );
            }
            else
            {
                $this->where( "dono_user = $this->user_id" );
            }
            $this->execute();
            if ( $this->result() )
            {
                $this->update( 'item' )->set( array( 'item_dono' ), array( '0' ) )->where( "item_dono = $this->dono_id" )->execute();
            }
            $this->delete()->from( 'dono' )
                    ->where( "dono_id = $this->dono_id" )
                    ->execute();
            $this->redirect( "$this->baseUri/admin/proprietario/" );
        }
    }

    public function fillList()
    {
        $this->select()->from( 'dono' );
        if ( $this->user_level == 2 )
        {
            $this->where( "dono_user = $this->user_id" );
        }
        $this->orderby( 'dono_nome asc' )->execute();
        if ( $this->result() )
        {
            $this->toJson();
            echo $this->jsonData;
        }
        else
        {
            echo 1;
        }
    }

    public function incluirRapido()
    {
        if ( $this->postIsValid( array( 'dono_nome' => 'string', 'dono_telefone1' => 'string' ) ) )
        {
            $this->postIndexAdd( 'dono_user', $this->user_id );
            $this->insert( 'dono' )->fields()->values()->execute();
            echo mysql_insert_id();
        }
        else
        {
            echo 1;
        }
    }
}
/*end file*/