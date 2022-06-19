<?php

class Site extends PHPFrodo
{
    public $login = null;
    public $user_login;
    public $user_id;
    public $user_name;
    public $cliente_id;
    public $cliente_cpf;
    public $cliente_nome;
    public $cliente_email;

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
            $this->redirect( "$this->baseUri/admin/" );
        }
        $this->assign( 'user_name', $this->user_name );
        if ( isset( $this->uri_segment ) && in_array( 'process-ok', $this->uri_segment ) )
        {
            $this->assign( 'msgOnload', 'notify("<h1>Procedimento realizado com sucesso</h1>")' );
        }
    }

    public function welcome()
    {
        $this->contato();
    }

    public function contato()
    {
        $this->tpl( 'admin/contato.html' );
        $this->select()
                ->from( 'cliente' )
                ->execute();
        if ( $this->result() )
        {
            $this->config = ( object ) $this->data[0];
            $this->assignAll();
        }
        $this->render();
    }

    public function atualizar()
    {
        $valid = array(
            'cliente_telefone1' => 'string',
            'cliente_cep' => 'string',
            'cliente_rua' => 'string',
            'cliente_num' => 'string',
            'cliente_bairro' => 'string',
            'cliente_cidade' => 'string',
            'cliente_uf' => 'string',
        );
        if ( $this->postIsValid( $valid ) )
        {

            $this->postValueChange( 'cliente_uf', strtoupper( $this->postGetValue( 'cliente_uf' ) ) );
            
            $address =   $this->postGetValue( 'cliente_bairro' ) . ", "
                       . $this->postGetValue( 'cliente_cidade' ) . ", "
                       . $this->postGetValue( 'cliente_uf' );
            
            $latlon = $this->getLatLon( "$address, Brasil" );
            if ( $latlon['lat'] != '' )
            {
                $this->postIndexAdd( 'cliente_lat', trim( $latlon['lat'] ) );
                $this->postIndexAdd( 'cliente_lon', trim( $latlon['lon'] ) );
            }
            
            $this->update( 'cliente' )->set()->where( "cliente_id = 1" )->execute();
            $this->redirect( "$this->baseUri/admin/site/contato/process-ok/" );
        }
        else
        {
            $this->pageError();
        }
    }

    public function pageError()
    {
        echo $this->response . "<br>";
        echo mysql_error();
    }

    public function getLatLon( $address )
    {
        $address = urlencode( utf8_encode( $address ) );
        $url = "http://maps.google.com/maps/api/geocode/json?address=$address&sensor=false&region=Brazil";
        $json = @file_get_contents( $url );
        $json = json_decode( $json );
        //$this->printr($json);exit;
        if ( isset( $json->status ) && $json->status == "OK" )
        {
            $lat = $json->results[0]->geometry->location->lat;
            $lon = $json->results[0]->geometry->location->lng;
            return array( 'lat' => $lat, 'lon' => $lon );
        }
        else
        {
            return array( 'lat' => '', 'lon' => '' );
        }
    }
}
/*end file*/