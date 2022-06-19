<?php

class Item extends PHPFrodo
{
    public $user_login;
    public $user_id;
    public $user_name;
    public $user_level;
    public $user_email;
    public $msgError;
    public $categoria_id;
    public $tipo_id;
    public $categoria_title;
    public $sub_id;
    public $sub_title;
    public $item_id;
    public $item_ref;
    public $item_new_ref;
    public $item_sub;
    public $item_preco;
    public $item_keywords;
    public $item_desc;
    public $item_show;
    public $item_oferta;
    public $item_url;

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
        $this->select()
                ->from( 'cliente' )
                ->execute();
        if ( $this->result() )
        {
            $this->cliente = ( object ) $this->data[0];
            $this->assignAll();
        }
        if ( isset( $this->uri_segment ) && in_array( 'process-ok', $this->uri_segment ) )
        {
            $this->assign( 'msgOnload', 'notify("<h1>Procedimento realizado com sucesso</h1>")' );
        }else{
            $this->assign( 'msgOnload', '' );
        }
        //login atendimento
        $atd = new Index;
        $atd->atdLogin();
    }

    public function welcome()
    {
        $this->pagebase = "$this->baseUri/admin/item";
        $this->tpl( 'admin/item.html' );
        $this->select()
                ->from( 'item' )
                ->join( 'sub', 'sub_id = item_sub', 'INNER' )
                ->join( 'categoria', 'sub_categoria = categoria_id', 'INNER' );
        if ( $this->user_level == 2 )
        {
            $this->where( "item_user = $this->user_id" );
        }
        $this->paginate( 15 )
                ->orderby( 'item_id desc' )
                ->execute();
        if ( $this->result() )
        {

            $aux = $this->data;
            foreach ( $aux as $k => $v )
            {
                if ( $aux[$k]['item_finalidade'] == 2 || $aux[$k]['item_finalidade'] == 3 || $aux[$k]['item_finalidade'] == 4 )
                {
                    if ( $aux[$k]['item_preco_locacao'] >= 1 )
                    {
                        $aux[$k]['item_preco'] = $aux[$k]['item_preco_locacao'];
                    }
                    else
                    {
                        $aux[$k]['item_preco'] = '0,00';
                    }
                }
            }
            $this->data = $aux;
            $this->money( 'item_preco' );
            $this->money( 'item_preco_locacao' );
            $this->money( 'item_preco_iptu' );
            $this->money( 'item_preco_condominio' );
            $this->preg( array( '/1/', '/2/', '/3/', '/4/' ), array( 'Venda', 'Locação', 'Locação e Venda', 'Temporada' ), 'item_finalidade' );
            $this->preg( array( '/0/', '/1/' ), array( 'Não', 'Sim' ), 'item_show' );
            $this->fetch( 'rs', $this->data );
            $this->assign( 'item_qtde', $this->getTotalItem() );
        }
        $this->render();
    }

    public function getDono( $dono )
    {
        $this->select()->from( 'dono' )->where( "dono_id = $dono" )->execute();
        if ( $this->result() )
        {
            return array( $this->data[0]['dono_nome'], $this->data[0]['dono_telefone1'] );
        }
        else
        {
            return ' &nbsp;';
        }
    }

    public function getTotalItem()
    {
        $this->select()->from( 'item' );
        if ( $this->user_level == 2 )
        {
            $this->where( "item_user = $this->user_id" );
        }
        $this->execute();
        if ( $this->result() )
        {
            return count( $this->data );
        }
        else
        {
            return 0;
        }
    }

    public function busca()
    {
        //$this->pagebase = "$this->baseUri/admin/item";
        $item_ref = "";
        if ( isset( $_POST['busca'] ) )
        {
            $item_ref = $_POST['busca'];
        }

        if ( in_array( 'print', $this->uri_segment ) )
        {
            $this->tpl( 'admin/item_busca_print.html' );
        }
        else
        {
            $this->tpl( 'admin/item_busca.html' );
            if ( isset( $_SESSION['item_ref'] ) )
            {
                unset( $_SESSION['item_ref'] );
            }
        }
        if ( isset( $_SESSION['item_ref'] ) )
        {
            $item_ref = $_SESSION['item_ref'];
        }

        if ( $item_ref != "" )
        {
            $this->select()
                    ->from( 'item' )
                    ->join( 'sub', 'sub_id = item_sub', 'INNER' )
                    ->join( 'categoria', 'sub_categoria = categoria_id', 'INNER' )
                    ->join( 'tipo', 'item_tipo = tipo_id', 'INNER' );
            if ( $this->user_level == 2 )
            {
                $this->where( "item_ref  = '$item_ref' AND item_user = $this->user_id" );
            }
            else
            {
                $this->where( "item_ref  = '$item_ref'" );
            }
            $this->orderby( 'item_ref asc' )
                    ->execute();
            if ( $this->result() )
            {
                $aux = $this->data;
                foreach ( $aux as $k => $v )
                {
                    if ( $aux[$k]['item_finalidade'] == 2 || $aux[$k]['item_finalidade'] == 3 || $aux[$k]['item_finalidade'] == 4 )
                    {
                        if ( $aux[$k]['item_preco_locacao'] >= 1 )
                        {
                            $aux[$k]['item_preco'] = $aux[$k]['item_preco_locacao'];
                        }
                        else
                        {
                            $aux[$k]['item_preco'] = 'Consulte-nos';
                        }
                    }
                    if ( $aux[$k]['item_preco'] == '0,00' || $aux[$k]['item_preco'] <= 0 )
                    {
                        $aux[$k]['item_preco'] = 'Consulte-nos';
                    }
                    else
                    {
                        $aux[$k]['item_preco'] = number_format( $aux[$k]['item_preco'], 2, ',', '.' );
                    }

                    $aux[$k]['dono_nome'] = " &nbsp;";
                    $aux[$k]['dono_fone'] = " &nbsp;";
                    if ( $aux[$k]['item_dono'] >= 1 )
                    {
                        $dono = $this->getDono( $aux[$k]['item_dono'] );
                        $aux[$k]['dono_nome'] = $dono[0];
                        $aux[$k]['dono_fone'] = $dono[1];
                    }
                }
                $_SESSION['item_ref'] = $item_ref;
                $this->data = $aux;
                $pat = array( '/1/', '/2/', '/3/', '/4/' );
                $rep = array( 'Venda', 'Locação', 'Locação e Venda', 'Temporada' );
                $this->preg( $pat, $rep, 'item_finalidade' );
                $this->assign( 'item_qtde', count( $this->data ) );
                $this->fetch( 'rs', $this->data );
                $this->assign( 'print_btn', 'show' );
            }
            else
            {
                $this->assign( 'print_btn', 'hide' );
                $this->assign( 'showHide', "hide" );
                $this->assign( 'msg_busca', '<h5 class="alert">Nenhum item encontrado.</h5>' );
            }
        }
        else
        {
            $this->assign( 'print_btn', 'hide' );
            $this->assign( 'showHide', "hide" );
            $this->assign( 'msg_busca', '' );
        }
        $this->fillTipo();
        $this->fillCategoria();
        $this->assign( 'busca', "$item_ref" );
        $this->render();
    }

    public function buscaavancada()
    {

        if ( in_array( 'print', $this->uri_segment ) )
        {
            $this->tpl( 'admin/item_busca_print.html' );
        }
        else
        {
            $this->tpl( 'admin/item_busca.html' );
        }

        $cond = "";
        $load = "";
        $loc = null;
        if ( in_array( 'finalidade', $this->uri_segment ) )
        {
            $k = array_keys( $this->uri_segment, 'finalidade' );
            if ( $k )
            {
                $finalidade = $this->uri_segment[$k[0] + 1];
                if ( $finalidade > 0 )
                {
                    if ( $finalidade == 2 )
                    {
                        $loc = 3;
                    }
                }
            }
            unset( $k );
        }

        if ( in_array( 'tipo', $this->uri_segment ) )
        {
            $k = array_keys( $this->uri_segment, 'tipo' );
            if ( $k )
            {
                $tipo = $this->uri_segment[$k[0] + 1];
                if ( $tipo > 0 )
                {
                    $cond .= "item_tipo = $tipo AND ";
                }
                $load .= "$('#tipo').val('$tipo');\n";
            }
            unset( $k );
        }

        if ( in_array( 'dorms', $this->uri_segment ) )
        {
            $k = array_keys( $this->uri_segment, 'dorms' );
            if ( $k )
            {
                $dorm = $this->uri_segment[$k[0] + 1];
                if ( $dorm > 0 )
                {
                    $cond .= "item_dorm = $dorm AND ";
                }
                $load .= "$('#dorms').val('$dorm');\n";
            }
            unset( $k );
        }

        if ( in_array( 'suites', $this->uri_segment ) )
        {
            $k = array_keys( $this->uri_segment, 'suites' );
            if ( $k )
            {
                $suite = $this->uri_segment[$k[0] + 1];
                if ( $suite > 0 )
                {
                    $cond .= "item_suite = $suite AND ";
                }
                $load .= "$('#suites').val('$dorm');\n";
            }
            unset( $k );
        }

        if ( in_array( 'bairro', $this->uri_segment ) )
        {
            $k = array_keys( $this->uri_segment, 'bairro' );
            if ( $k )
            {
                $bairro = $this->uri_segment[$k[0] + 1];
                if ( $bairro > 0 )
                {
                    $cond .= "item_sub = $bairro  AND ";
                }
                $load .= "$('#bairro').val('$bairro');\n";
            }
            unset( $k );
        }
        if ( in_array( 'cidade', $this->uri_segment ) )
        {
            $k = array_keys( $this->uri_segment, 'cidade' );
            if ( $k )
            {
                $cidade = $this->uri_segment[$k[0] + 1];
                if ( $cidade > 0 )
                {
                    $cond .= "item_categoria = $cidade  AND ";
                }
                $load .= "$('#cidade').val('$cidade');\n";
                $load .= "loadSub('$cidade');\n";
            }
            unset( $k );
        }

        //if ( $loc == 1 )
        //{
        if ( in_array( 'min', $this->uri_segment ) )
        {
            $k = array_keys( $this->uri_segment, 'min' );
            if ( $k )
            {
                $preco = $this->uri_segment[$k[0] + 1];
                if ( $preco > 0 )
                {
                    $cond .= "item_preco >= $preco AND ";
                }
                $load .= "$('#valormin').val('$preco');\n";
            }
            unset( $k );
        }
        if ( in_array( 'max', $this->uri_segment ) )
        {
            $k = array_keys( $this->uri_segment, 'max' );
            if ( $k )
            {
                $preco = $this->uri_segment[$k[0] + 1];
                if ( $preco > 0 )
                {
                    $cond .= "item_preco <= $preco  AND ";
                }
                $load .= "$('#valormax').val('$preco');\n";
            }
            unset( $k );
        }
        //}

        if ( in_array( 'finalidade', $this->uri_segment ) )
        {
            $k = array_keys( $this->uri_segment, 'finalidade' );
            if ( $k )
            {
                $finalidade = $this->uri_segment[$k[0] + 1];
                if ( $finalidade > 0 )
                {
                    $cond .= "item_finalidade = $finalidade";
                }
                $load .= "$('#finalidade').val('$finalidade');\n";
            }
            unset( $k );
        }

        $load .="$('.bot-panel').click();\n\r\t\t";

        if ( in_array( 'imref', $this->uri_segment ) )
        {
            $k = array_keys( $this->uri_segment, 'imref' );
            if ( $k )
            {
                if ( isset( $this->uri_segment[$k[0] + 1] ) && !empty( $this->uri_segment[$k[0] + 1] ) )
                {
                    $imref = trim( $this->uri_segment[$k[0] + 1] );
                    if ( trim( $imref ) != "" )
                    {
                        $cond = "item_ref = '$imref'";
                        $load .= "$('#imref').val('$imref');\n";
                    }
                }
            }
            unset( $k );
        }
        $this->assign( 'load', $load );
        $this->select()
                ->from( 'item' )
                ->join( 'sub', 'item_sub = sub_id', 'INNER' )
                ->join( 'categoria', 'item_categoria = categoria_id', 'INNER' )
                ->join( 'tipo', 'item_tipo = tipo_id', 'INNER' )
                ->where( "$cond" )
                ->groupby( 'item_id' )
                ->orderby( 'item_id desc' )
                ->execute();
        if ( $this->result() )
        {
            $aux = $this->data;
            foreach ( $aux as $k => $v )
            {
                if ( $aux[$k]['item_finalidade'] == 2 || $aux[$k]['item_finalidade'] == 3 || $aux[$k]['item_finalidade'] == 4 )
                {
                    if ( $aux[$k]['item_preco_locacao'] >= 1 )
                    {
                        $aux[$k]['item_preco'] = $aux[$k]['item_preco_locacao'];
                    }
                }
                if ( $aux[$k]['item_preco'] == '0,00' || $aux[$k]['item_preco'] <= 0 )
                {
                    $aux[$k]['item_preco'] = $aux[$k]['item_preco'];
                }
                else
                {
                    $aux[$k]['item_preco'] = number_format( $aux[$k]['item_preco'], 2, ',', '.' );
                }
                $aux[$k]['dono_nome'] = " &nbsp;";
                $aux[$k]['dono_fone'] = " &nbsp;";
                if ( $aux[$k]['item_dono'] >= 1 )
                {
                    $dono = $this->getDono( $aux[$k]['item_dono'] );
                    $aux[$k]['dono_nome'] = $dono[0];
                    $aux[$k]['dono_fone'] = $dono[1];
                }
            }
            $this->data = $aux;
            $pat = array( '/1/', '/2/', '/3/', '/4/' );
            $rep = array( 'Venda', 'Locação', 'Locação e Venda', 'Temporada' );
            $this->preg( $pat, $rep, 'item_finalidade' );
            $this->fetch( 'rs', $this->data );
            $this->assign( 'print_btn', '' );
            $this->assign( 'msg_busca', '' );
        }
        else
        {
            $this->assign( 'print_btn', 'hide' );
            $this->assign( 'msg_busca', '<h5>Nenhum imóvel econtrado com os critérios selecionados!</h5>' );
        }

        $this->assign( 'busca', "" );
        if ( isset( $this->data[0] ) )
        {
            $this->assign( 'item_qtde', count( $this->data ) );
        }
        else
        {
            $this->assign( 'item_qtde', '0' );
        }
        $this->fillTipo( 1 );
        $this->fillCategoria();
        $this->render();
    }

    public function editar()
    {
        if ( isset( $this->uri_segment[2] ) )
        {
            $this->item_id = $this->uri_segment[2];
            $this->tpl( 'admin/item_editar.html' );
            $this->select()
                    ->from( 'item' )
                    ->join( 'sub', 'sub_id = item_sub', 'INNER' )
                    ->join( 'categoria', 'categoria_id = sub_categoria', 'INNER' );
            if ( $this->user_level == 2 )
            {
                $this->where( "item_id = $this->item_id AND item_user = $this->user_id" );
            }
            else
            {
                $this->where( "item_id = $this->item_id" );
            }
            $this->execute();
            if ( $this->result() )
            {
                $this->money( 'item_preco' );
                $this->money( 'item_preco_locacao' );
                $this->money( 'item_preco_temp' );
                $this->money( 'item_preco_iptu' );
                $this->money( 'item_preco_condominio' );
                $this->assignAll();
                $this->helper( 'redactor' );
                //echo $this->data[0]['item_desc'];exit;
                $editor = editor( $this->data[0]['item_desc'], 'item_desc', '350px', '90%' );
                $this->assign( 'editor', $editor );
                $this->fillCategoria();
            }
            else
            {
                $this->redirect( "$this->baseUri/admin/item/" );
            }
            if ( isset( $this->uri_segment[3] ) )
            {
                $tab = $this->uri_segment[3];
                $tab = "$('#myTab a[href=\"#$tab\"]').tab('show')";
                $this->assign( 'loadTab', $tab );
            }
            else
            {
                $this->assign( 'loadTab', '' );
                $this->assign( 'msgOnload', '' );
            }
            //fill fotos
            $this->fillFotos();
            $this->fillTipo();
            $this->fillDono();
            $this->render();
        }
    }

    public function posicao()
    {
        $this->tpl( 'admin/item_destaque.html' );
        $this->select()
                ->from( 'item' )
                ->where( 'item_show = 1 and item_destaque = 1' )
                ->orderby( 'item_pos asc' )
                ->execute();
        if ( $this->result() )
        {
            $aux = $this->data;
            foreach ( $aux as $k => $v )
            {
                $item = $aux[$k]['item_id'];
                $this->select()->from( 'foto' )->where( "foto_item = $item" )->paginate( 1 )->orderby( 'foto_pos asc' )->execute();
                if ( $this->result() )
                {
                    $aux[$k]['foto_url'] = $this->data[0]['foto_url'];
                }
            }
            $this->data = $aux;
            $this->preg( '/\.jpg/', '', 'foto_url' );
            $this->fetch( 'ft', $this->data );
        }
        $this->render();
    }

    public function updateItemPos()
    {
        $item = $_POST['item'];
        parse_str( $item, $arr );
        foreach ( $arr['li'] as $pos => $item_id )
        {
            $this->update( 'item' )
                    ->set( array( 'item_pos' ), array( "$pos" ) )
                    ->where( "item_id = $item_id" )
                    ->execute();
            echo $this->query . "\n";
        }
    }

    public function novo()
    {
        $this->tpl( 'admin/item_novo.html' );
        $this->fillCategoria();
        $this->fillTipo();
        $this->helper( 'redactor' );
        $editor = editor( '', 'item_desc', '350px', '90%' );
        $this->assign( 'editor', $editor );
        //fill donos
        $this->fillDono();
        $this->render();
    }

    public function fillFotos()
    {
        $this->select()
                ->from( 'foto' )
                ->where( "foto_item = $this->item_id" )
                ->orderby( 'foto_pos asc' )
                ->execute();
        if ( $this->result() )
        {
            $this->preg( '/\.jpg/', '', 'foto_url' );
            $this->fetch( 'ft', $this->data );
        }
        else
        {
            $this->assign( 'fotoControl', 'hide' );
        }
    }

    public function fillCategoria()
    {
        $this->select()
                ->from( 'categoria' )
                ->orderby( 'categoria_title asc' )
                ->execute();
        if ( $this->result() )
        {
            $this->fetch( 'combo', $this->data );
        }
    }

    public function fillDono()
    {
        $this->select()->from( 'dono' )->orderby( 'dono_nome asc' )->execute();
        if ( $this->result() )
        {
            $this->fetch( 'dono', $this->data );
        }
    }

    public function fillTipo()
    {
        $this->select()
                ->from( 'tipo' )
                ->orderby( 'tipo_title asc' )
                ->execute();
        if ( $this->result() )
        {
            $this->fetch( 'tp', $this->data );
        }
    }

    public function fillSubCategoria()
    {
        if ( isset( $this->uri_segment[2] ) )
        {
            $this->categoria_id = $this->uri_segment[2];
            $this->select( 'sub_id,sub_title' )
                    ->from( 'sub' )
                    ->where( "sub_categoria = $this->categoria_id" )
                    ->orderby( 'sub_title asc' )
                    ->execute();
            if ( $this->result() )
            {
                @header( 'Content-Type: text/html; charset=iso-8859-1' );
                echo $this->toJson();
            }
            else
            {
                echo 0;
            }
        }
    }

    public function incluir()
    {
        if ( $this->postIsValid( array(
                    //'item_ref' => 'string',
                    'item_categoria' => 'string',
                    'item_sub' => 'string'
                ) ) )
        {
            $this->categoria_id = $this->postGetValue( 'item_categoria' );
            $this->sub_id = $this->postGetValue( 'item_sub' );
            $this->tipo_id = $this->postGetValue( 'item_tipo' );
            $this->item_endereco = $this->postGetValue( 'item_endereco' );

            $termo_busca = $this->getTermos();
            $this->postIndexAdd( 'item_busca', $termo_busca );

            $this->item_endereco_uf = ( string ) $this->cliente->cliente_uf;
            ;
            if ( trim( $this->item_endereco ) != "" )
            {
                $this->item_endereco = "$this->item_endereco, $this->bairro, $this->cidade, $this->item_endereco_uf";
            }
            else
            {
                $this->item_endereco = "$this->bairro, $this->cidade, $this->item_endereco_uf";
            }
            $latlon = $this->getLatLon( $this->item_endereco );
            if ( $latlon['lat'] != '' )
            {
                $this->postIndexAdd( 'item_lat', trim( $latlon['lat'] ) );
                $this->postIndexAdd( 'item_lon', trim( $latlon['lon'] ) );
            }

            $this->postIndexAdd( 'item_user', $this->user_id );
            $this->postIndexDrop( 'upload' );
            $this->postValueChange( 'item_preco', preg_replace( array( '/\./', '/\,/' ), array( '', '.' ), $this->postGetValue( 'item_preco' ) ) );
            $this->postValueChange( 'item_preco_locacao', preg_replace( array( '/\./', '/\,/' ), array( '', '.' ), $this->postGetValue( 'item_preco_locacao' ) ) );
            $this->postValueChange( 'item_preco_temp', preg_replace( array( '/\./', '/\,/' ), array( '', '.' ), $this->postGetValue( 'item_preco_temp' ) ) );
            $this->postValueChange( 'item_preco_iptu', preg_replace( array( '/\./', '/\,/' ), array( '', '.' ), $this->postGetValue( 'item_preco_iptu' ) ) );
            $this->postValueChange( 'item_preco_condominio', preg_replace( array( '/\./', '/\,/' ), array( '', '.' ), $this->postGetValue( 'item_preco_condominio' ) ) );
            $this->insert( 'item' )->fields()->values()->execute();
            $item = mysql_insert_id();
            $this->item_id = mysql_insert_id();
            //$this->item_ref = "$this->item_new_ref" . "00" . "$this->item_id";
            //$this->generateRef();


            $this->redirect( "$this->baseUri/admin/item/editar/$item/fotos/" );
        }
        else
        {
            $this->msgError = $this->response;
            $this->pageError();
        }
    }

    public function generateRef()
    {
        $this->item_ref = strtoupper( $this->item_ref );
        $this->update( 'item' )
                ->set( array( 'item_ref' ), array( "$this->item_ref" ) )
                ->where( "item_id = $this->item_id" )
                ->execute();
    }

    public function atualizar()
    {
        if ( isset( $this->uri_segment[2] ) )
        {
            if ( $this->postIsValid( array(
                        //'item_ref' => 'string',
                        'item_categoria' => 'string',
                        'item_sub' => 'string'
                    ) ) )
            {
                $this->categoria_id = $this->postGetValue( 'item_categoria' );
                $this->sub_id = $this->postGetValue( 'item_sub' );
                $this->tipo_id = $this->postGetValue( 'item_tipo' );
                $this->item_endereco = $this->postGetValue( 'item_endereco' );

                $termo_busca = $this->getTermos();
                $this->postIndexAdd( 'item_busca', $termo_busca );

                $this->item_endereco_uf = ( string ) $this->cliente->cliente_uf;
                if ( trim( $this->item_endereco ) != "" )
                {
                    //$this->item_endereco = "$this->item_endereco, $this->bairro, $this->cidade, $this->item_endereco_uf";
                    $this->item_endereco = "$this->item_endereco, $this->cidade, $this->item_endereco_uf";
                }
                else
                {
                    $this->item_endereco = "$this->bairro, $this->cidade, $this->item_endereco_uf";
                }

                $latlon = $this->getLatLon( "$this->item_endereco, Brasil" );
                if ( $latlon['lat'] != '' )
                {
                    $this->postIndexAdd( 'item_lat', trim( $latlon['lat'] ) );
                    $this->postIndexAdd( 'item_lon', trim( $latlon['lon'] ) );
                }

                $this->postIndexDrop( 'upload' );
                $this->item_id = $this->uri_segment[2];
                $this->postValueChange( 'item_preco', preg_replace( array( '/\./', '/\,/' ), array( '', '.' ), $this->postGetValue( 'item_preco' ) ) );
                $this->postValueChange( 'item_preco_locacao', preg_replace( array( '/\./', '/\,/' ), array( '', '.' ), $this->postGetValue( 'item_preco_locacao' ) ) );
                $this->postValueChange( 'item_preco_temp', preg_replace( array( '/\./', '/\,/' ), array( '', '.' ), $this->postGetValue( 'item_preco_temp' ) ) );
                $this->postValueChange( 'item_preco_iptu', preg_replace( array( '/\./', '/\,/' ), array( '', '.' ), $this->postGetValue( 'item_preco_iptu' ) ) );
                $this->postValueChange( 'item_preco_condominio', preg_replace( array( '/\./', '/\,/' ), array( '', '.' ), $this->postGetValue( 'item_preco_condominio' ) ) );

                //$this->item_ref = strtoupper( "$this->item_new_ref" . "00" . "$this->item_id" );
                //$this->postIndexAdd( 'item_ref', $this->item_ref );

                $this->update( 'item' )->set();
                if ( $this->user_level == 2 )
                {
                    $this->where( "item_id = $this->item_id AND item_user = $this->user_id" );
                }
                else
                {
                    $this->where( "item_id = $this->item_id" );
                }
                //echo $this->query;exit;
                $this->execute();
                $this->redirect( "$this->baseUri/admin/item/editar/$this->item_id/process-ok/" );
            }
        }
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

    function getLatLonCep( $zip )
    {
        $url = "http://maps.googleapis.com/maps/api/geocode/json?address=" . urlencode( $zip ) . "&sensor=false";
        $result_string = file_get_contents( $url );
        $result = json_decode( $result_string, true );
        $result1[] = $result['results'][0];
        $result2[] = $result1[0]['geometry'];
        $result3[] = $result2[0]['location'];
        return array( 'lat' => $result3[0]['lat'], 'lon' => $result3[0]['lng'] );
    }

    public function getTermos()
    {

        $term = "";

        $this->select()->from( 'tipo' )->where( "tipo_id = $this->tipo_id" )->execute();
        $term .= $this->data[0]['tipo_title'] . " ";

        $this->item_new_ref = $this->urlmodr( substr( $this->data[0]['tipo_title'], 0, 3 ) );

        $this->select()->from( 'categoria' )->where( "categoria_id = $this->categoria_id" )->execute();
        $term .= $this->data[0]['categoria_title'] . " ";
        $this->cidade = $this->data[0]['categoria_title'];

        $this->select()->from( 'sub' )->where( "sub_id = $this->sub_id" )->execute();
        $term .= $this->data[0]['sub_title'];

        $this->bairro = $this->data[0]['sub_title'];
        return $term;
    }

    public function remover()
    {
        if ( isset( $this->uri_segment[2] ) )
        {
            $this->item_id = $this->uri_segment[2];
            $this->removeFotos();
            $this->delete()->from( 'item' );
            if ( $this->user_level == 2 )
            {
                $this->where( "item_id = $this->item_id AND item_user = $this->user_id" );
            }
            else
            {
                $this->where( "item_id = $this->item_id" );
            }
            $this->execute();
            $this->redirect( "$this->baseUri/admin/item/process-ok/" );
        }
    }

    public function massremove()
    {

        if ( isset( $_POST['check'] ) && !empty( $_POST['check'] ) )
        {
            $i = $_POST['check'];
            foreach ( $i as $k => $v )
            {
                $this->item_id = $v;
                $this->removeFotos();
                $this->delete()->from( 'item' );
                if ( $this->user_level == 2 )
                {
                    $this->where( "item_id = $this->item_id AND item_user = $this->user_id" );
                }
                else
                {
                    $this->where( "item_id = $this->item_id" );
                }
                $this->execute();
            }
        }
        $this->redirect( "$this->baseUri/admin/item/process-ok/" );
    }

    public function removeFotos()
    {
        $this->select()
                ->from( 'foto' )
                ->where( "foto_item = $this->item_id" )
                ->execute();
        if ( $this->result() )
        {
            foreach ( $this->data as $f )
            {
                $f = ( object ) $f;
                $file = "app/fotos/$f->foto_url";
                if ( file_exists( $file ) )
                {
                    @unlink( $file );
                }
            }
        }
    }

    public function removeUniqFoto()
    {
        if ( isset( $this->uri_segment[2] ) )
        {
            $foto_id = $this->uri_segment[2];
        }
        elseif ( isset( $_POST['foto_id'] ) && !empty( $_POST['foto_id'] ) )
        {
            $foto_id = $_POST['foto_id'];
        }
        if ( isset( $foto_id ) )
        {
            $this->select()
                    ->from( 'foto' )
                    ->where( "foto_id = $foto_id" )
                    ->execute();
            if ( $this->result() )
            {
                $f = ( object ) $this->data[0];
                $file = "app/fotos/$f->foto_url";
                if ( file_exists( $file ) )
                {
                    @unlink( $file );
                    echo "$file removido";
                }
                $this->delete()->from( 'foto' )->where( "foto_id = $foto_id" )->execute();
            }
            else
            {
                echo 'error';
            }
        }
    }

    public function updateFotoPos()
    {
        $item = $_POST['item'];
        parse_str( $item, $arr );
        foreach ( $arr['li'] as $pos => $foto_id )
        {
            $this->update( 'foto' )
                    ->set( array( 'foto_pos' ), array( "$pos" ) )
                    ->where( "foto_id = $foto_id" )
                    ->execute();
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
