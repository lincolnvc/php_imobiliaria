<?php

class Usuario extends PHPFrodo
{

    public $user_login;
    public $user_id;
    public $user_name;
    public $msgError;

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
        $this->user_id = (int) $sid->getNode( 'user_id' );
        $this->user_name = $sid->getNode( 'user_name' );
        $this->user_level = $sid->getNode( 'user_level' );
		
        if ( isset( $this->uri_segment ) && in_array( 'process-ok', $this->uri_segment ) )
        {
            $this->assign( 'msgOnload', 'notify("<h1>Procedimento realizado com sucesso</h1>")' );
        }
        if ( isset( $this->uri_segment ) && in_array( 'permissao-n', $this->uri_segment ) )
        {
            $this->assign( 'msgOnload', 'notify("<h1>Usuário Admin não pode ser excluído</h1>")' );
        } 
		
		if ( !in_array('me',$this->uri_segment) && $this->user_level == 2)
        {
		    $this->redirect( "$this->baseUri/admin/" );
        }
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
        $this->pagebase = "$this->baseUri/admin/usuario";
        $this->tpl( 'admin/user.html' );
        $this->select()
                ->from( 'user' )
                ->paginate( 15 )
                ->orderby( 'user_name asc' )
                ->execute();
        if ( $this->result() )
        {
            $this->preg(array('/1/','/2/'),array('Administrador','Corretor'),'user_level');
            $this->fetch( 'rs', $this->data );
            $this->assign( 'user_qtde', count( $this->data ) );
        }
        $this->render();
    }

    public function editar()
    {
        if ( isset( $this->uri_segment[2] ) && $this->uri_segment[1] != 'me')
        {
            $this->user_id = $this->uri_segment[2];
        }
        $this->tpl( 'admin/user_editar.html' );
        $this->select()
                ->from( 'user' )
                ->where( "user_id = $this->user_id" )
                ->execute();
        if ( $this->result() )
        {
            $this->assignAll();
            if ( $this->data[0]['user_login'] == 'admin' && $this->user_login != 'admin' )
            {
                $this->tpl( 'admin/inativo.html' );
                $this->assign( 'msg', 'O usuário admin só pode ser alterado por ele mesmo!' );
                $this->render();
                exit;
            }
        }
        $this->render();
    }

    public function me()
    {
        $this->editar();
    }

    public function novo()
    {
        $this->tpl( 'admin/user_novo.html' );
        $this->render();
    }

    public function incluir()
    {
        if ( $this->postIsValid( array( 'user_login' => 'string', 'user_password' => 'string' ) ) )
        {
            $this->postIndexDrop( 'user_passwordr' );
            $this->postValueChange( 'user_password', md5( $this->postGetValue( 'user_password' ) ) );
            $this->insert( 'user' )->fields()->values()->execute();

            //inclui user chat
            $login = $this->postGetValue( 'user_login' );
            $level = $this->postGetValue( 'user_level' );
            $email = $this->postGetValue( 'user_email' );
            $nome = $this->postGetValue( 'user_name' );
            $pass = $this->postGetValue( 'user_password' ) ;
            $this->post_fields = array( );
            $this->post_values = array( );
            $permAdmin = "65535";
            $permOp = "65520";
            $perm = $permOp;
            if ( $level == 1 )
            {
                $perm = $permAdmin;
            }
            $this->insert( 'chatoperator' )
                    ->fields( array( 'vclogin', 'vclocalename', 'vccommonname', 'iperm', 'vcemail', 'vcpassword' ) )
                    ->values( array( "$login", "$nome", "$nome", "$perm", "$email", "$pass" ) )
                    ->execute();
            $this->redirect( "$this->baseUri/admin/usuario/process-ok/" );
        }
        else
        {
            $this->msgError = $this->response;
            $this->pageError();
        }
    }

    public function atualizar()
    {
        if ( isset( $this->uri_segment[2] ) )
        {
            if ( $this->postIsValid( array( 'user_email' => 'string' ) ) )
            {
                $this->user_id = $this->uri_segment[2];
                $this->postIndexDrop( 'user_passwordr' );

                if ( !$this->postGetValue( 'user_password' ) )
                {
                    $this->postIndexDrop( 'user_password' );
                }
                else
                {
                    $this->postValueChange( 'user_password', md5( $this->postGetValue( 'user_password' ) ) );
                    $pass =  $this->postGetValue( 'user_password' );
                }

                $this->update( 'user' )->set()->where( "user_id = $this->user_id" )->execute();

                //atualiza user chat
                $login = $this->postGetValue( 'user_login' );
                $level = $this->postGetValue( 'user_level' );
                $email = $this->postGetValue( 'user_email' );
                $nome = $this->postGetValue( 'user_name' );
                $this->post_fields = array( );
                $this->post_values = array( );
                $permAdmin = "65535";
                $permOp = "65520";
                $perm = $permOp;
                if ( $level == 1 )
                {
                    $perm = $permAdmin;
                }
                if ( isset( $pass ) )
                {
                    $f = array( 'vclogin', 'vclocalename', 'vccommonname', 'iperm', 'vcemail', 'vcpassword' );
                    $v = array( "$login", "$nome", "$nome", "$perm", "$email", "$pass" );
                }
                else
                {
                    $f = array( 'vclogin', 'vclocalename', 'vccommonname', 'iperm', 'vcemail' );
                    $v = array( "$login", "$nome", "$nome", "$perm", "$email" );
                }
                $this->update( 'chatoperator' )
                        ->set( $f, $v )
                        ->where( "vclogin = '$login'" )
                        ->execute();

			if ( in_array('me',$this->uri_segment) ){
					$this->redirect( "$this->baseUri/admin/usuario/me/process-ok/" );
				}else{
					$this->redirect( "$this->baseUri/admin/index/process-ok/" );
				}
            }
        }
    }

    public function remover()
    {
        if ( isset( $this->uri_segment[2] ) )
        {
            $this->user_id = $this->uri_segment[2];
            if ( $this->user_id != 1 )
            {
                $this->select()->from( 'user' )->where( "user_id = $this->user_id" )->execute();
                if ( $this->result() )
                {
                    //fix
                    if ( $this->data[0]['user_login'] == 'admin' && $this->user_login != 'admin' )
                    {
                        $this->tpl( 'admin/inativo.html' );
                        $this->assign( 'msg', 'O usuário admin não pode ser removido!' );
                        $this->render();
                        exit;
                    }
                    $login = $this->data[0]['user_login'];
                    $this->delete()->from( 'chatoperator' )->where( "vclogin = '$login'" )->execute();
                }
                $this->delete()->from( 'user' )->where( "user_id = $this->user_id" )->execute();
                $this->redirect( "$this->baseUri/admin/usuario/process-ok/" );
            }
            else
            {
                $this->redirect( "$this->baseUri/admin/usuario/permissao-n/" );
            }
        }
    }

    public function pageError()
    {
        $this->tpl( 'admin/error.html' );
        $this->assign( 'msgError', $this->msgError );
        $this->render();
    }

}

/*end file*/