<?php
//faz cache 
@header( "Cache-Control: max-age=604800" );

class Imovel extends PHPFrodo
{
    public $config = array( );
    public $config_cep = array( );
    public $menu;
    public $item_categoria = null;
    public $item_sub = null;
    public $item_url = null;
    public $item_id = null;
    public $item = null;
    public $f_foto = null;
    public $f_foto_big = null;
    public $cliente_uf = null;

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
                ->from( 'cliente' )
                ->execute();
        if ( $this->result() )
        {
            $this->cliente_uf = $this->data[0]['cliente_uf'];
            $this->assignAll();
        }

        if ( isset( $this->uri_segment[1] ) && isset( $this->uri_segment[2] ) && isset( $this->uri_segment[3] ) )
        {
            $this->item_categoria = $this->uri_segment[1];
            $this->item_sub = $this->uri_segment[2];
            $this->item_id = $this->uri_segment[3];
        }
    }

    public function welcome()
    {
        $this->tpl( 'public/detalhe.html' );
        if ( $this->item_id != null )
        {
            $this->select()
                    ->from( 'item' )
                    ->join( 'sub', 'item_sub = sub_id', 'INNER' )
                    ->join( 'tipo', 'item_tipo = tipo_id', 'INNER' )
                    ->join( 'categoria', 'item_categoria = categoria_id', 'INNER' )
                    ->where( "item_id = $this->item_id" )
                    ->execute();
            if ( $this->result() )
            {
                $i = ( object ) $this->data[0];
                $this->data[0]['bairro'] = strtolower( $this->data[0]['sub_title'] );
                if ( $this->data[0]['item_mapa'] == 2 && $this->data[0]['item_endereco'] != "" )
                {
                    if ( $this->data[0]['bairro'] == 'centro' )
                    {
                        $endereco_mapa = $this->data[0]['item_endereco']
                                . ", "
                                . $this->data[0]['categoria_title']
                                . ", Brasil";
                    }
                    else
                    {
                        $endereco_mapa = $this->data[0]['item_endereco']
                                . ", "
                                //. $this->data[0]['bairro']
                                . ", "
                                . $this->data[0]['categoria_title']
                                . ", Brasil";
                    }
                    $this->assign( 'textoLocalizacao', '' );
                    $localizacao = $this->data[0]['item_endereco']
                            . ", " . $this->data[0]['sub_title']
                            . " - "
                            . $this->data[0]['categoria_title'];
                    $this->assign( 'localizacao', $localizacao );
                }
                else
                {
                    $endereco_mapa = $this->data[0]['bairro']
                            . ", "
                            . $this->data[0]['categoria_title']
                            . ", Brasil";

                    $localizacao = $this->data[0]['categoria_title']
                            . " - "
                            . $this->data[0]['sub_title'];
                    $this->assign( 'textoLocalizacao', 'aproximada' );
                    $this->assign( 'localizacao', $localizacao );
                }

                //$endereco_mapa = "$i->sub_title, $i->categoria_title, $this->cliente_uf, Brasil";
                $this->addkey( 'endereco_mapa', $endereco_mapa );
                $this->addkey( 'item_bread_title', '', 'item_title' );
                $this->addkey( 'item_desc_meta', '', 'item_desc' );
                $this->encode( 'item_desc_meta', 'strip_tags' );
                $this->addkey( 'item_negocio', '', 'item_finalidade' );
                $this->preg( array( '/1/', '/2/', '/3/', '/4/' ), array( 'Venda', 'Locação', 'Locação e Venda', 'Temporada' ), 'item_negocio' );
                $this->item = $this->data[0];

                if ( $this->data[0]['item_preco'] == '0,00' || $this->data[0]['item_preco'] < 1 )
                {
                    $this->data[0]['item_preco'] = 'Consulte-nos';
                }
                else
                {
                    $this->data[0]['item_preco'] = 'R$ ' . number_format( $this->data[0]['item_preco'], 2, ',', '.' );
                }

                if ( $this->data[0]['item_preco_temp'] == '0,00' || $this->data[0]['item_preco_temp'] < 1 )
                {
                    $this->data[0]['item_preco_temp'] = 'Consulte-nos';
                }
                else
                {
                    $this->data[0]['item_preco_temp'] = 'R$ ' . number_format( $this->data[0]['item_preco_temp'], 2, ',', '.' );
                }

                if ( $this->data[0]['item_preco_locacao'] == '0,00' || $this->data[0]['item_preco_locacao'] < 1 )
                {
                    $this->data[0]['item_preco_locacao'] = 'Consulte-nos';
                }
                else
                {
                    $this->data[0]['item_preco_locacao'] = 'R$ ' . number_format( $this->data[0]['item_preco_locacao'], 2, ',', '.' );
                }

                if ( $this->data[0]['item_preco_condominio'] == '0,00' || $this->data[0]['item_preco_condominio'] < 1 )
                {
                    $this->assign( 'showHideCond', 'hide' );
                }
                else
                {
                    $this->data[0]['item_preco_condominio'] = 'R$ ' . number_format( $this->data[0]['item_preco_condominio'], 2, ',', '.' );
                }
                
                if ( $this->data[0]['item_preco_iptu'] == '0,00' || $this->data[0]['item_preco_iptu'] < 1 )
                {
                    $this->assign( 'showHideIptu', 'hide' );
                }
                else
                {
                    $this->data[0]['item_preco_iptu'] = 'R$ ' . number_format( $this->data[0]['item_preco_iptu'], 2, ',', '.' );
                }
                if ( $this->data[0]['item_finalidade'] == 1 )
                {
                    $this->assign( 'showHideLocacao', 'hide' );
                    $this->assign( 'showHideTemp', 'hide' );
                }
                
                if ( $this->data[0]['item_finalidade'] == 4 )
                {
                    $this->assign( 'showHideVenda', 'hide' );
                    $this->assign( 'showHideLocacao', 'hide' );
                    $this->assign( 'showHideCond', 'hide' );
                    $this->assign( 'showHideIptu', 'hide' );
                    $this->assign( 'showHideTemp', 'show' );
                }

                if ( $this->data[0]['item_finalidade'] == 2 )
                {
                    $this->assign( 'showHideVenda', 'hide' );
                    $this->assign( 'showHideCond', 'hide' );
                }

                if ( $this->data[0]['item_finalidade'] == 3 )
                {
                    $this->assign( 'showHideTemp', 'hide' );
                }              
                $this->assignAll();
            }
            $this->fillFoto();
            $this->fillTipo();
            $this->fillCategoria();
            $this->fillSimilares( $i );
            $this->render();
            $this->viewcount();
        }
    }

    public function fillSimilares( $i )
    {
        $this->select()
                ->from( 'item' )
                ->join( 'tipo', 'item_tipo = tipo_id', 'INNER' )
                ->join( 'sub', 'item_sub = sub_id', 'INNER' )
                ->join( 'categoria', 'sub_categoria = categoria_id', 'INNER' )
                ->where( "item_show = 1 and item_destaque = 1 and item_categoria = $i->item_categoria and item_tipo = $i->item_tipo and item_id <> $this->item_id" )
                ->groupby( 'item_id' )
                ->orderby( 'item_pos asc' )
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
                        $aux[$k]['item_preco'] = 'R$ ' . $aux[$k]['item_preco_locacao'];
                    }
                    else
                    {
                        $aux[$k]['item_preco'] = 'Consulte-nos';
                    }
                }
                if ( $aux[$k]['item_preco'] == '0,00' || $aux[$k]['item_preco'] < 1 )
                {
                    $aux[$k]['item_preco'] = 'Consulte-nos';
                }
                else
                {
                    $aux[$k]['item_preco'] = number_format( $aux[$k]['item_preco'], 2, ',', '.' );
                }

                if ( $aux[$k]['sub_title'] == "." || $aux[$k]['sub_title'] == "" )
                {
                    $aux[$k]['sub_title'] = "";
                    $aux[$k]['sub_url'] = "bairro";
                }

                $item = $aux[$k]['item_id'];
                $this->select()->from( 'foto' )->where( "foto_item = $item" )->paginate( 1 )->orderby( 'foto_pos asc' )->execute();
                if ( $this->result() )
                {
                    if ( $this->data[0]['foto_url'] == "" || empty( $this->data[0]['foto_url'] ) )
                    {
                        $this->data[0]['foto_url'] = 'empty';
                    }
                    $aux[$k]['foto_url'] = $this->data[0]['foto_url'];
                }
                else
                {
                    $aux[$k]['foto_url'] = 'empty';
                }
            }
            $this->data = $aux;

            $pat = array( '/1/', '/2/', '/3/', '/4/' );
            $rep = array( 'Venda', 'Locação', 'Locação e Venda', 'Temporada' );
            $this->preg( $pat, $rep, 'item_finalidade' );

            $this->encode( 'sub_title', 'ucwords' );

            $this->addkey( 'item_promo', '', 'item_vendido' );
            $this->preg( '/\.jpg/', '', 'foto_url' );

            $pat = array( '/1/', '/2/', '/3/', '/4/', '/5/', '/6/', '/7/', '/8/', '/9/', '/10/', '/11/', '/12/' );
            $rep = array( 'Vendido', 'Alugado', 'Lançamento', 'Pronto para morar', 'Em construção', 'Oportunidade', 'Financie', 'Decorado', 'Para renda', 'Alugue', 'Condomínio', 'Cobertura' );
            $this->preg( $pat, $rep, 'item_promo' );
            //$this->clonekey( 'item_dorm', array( 'item_suite', 'item_dorm' ), '+' );
            $this->fetch( 's', $this->data );
        }
        else
        {
            $this->assign( 'similares', 'hide' );
        }
    }

    public function viewcount()
    {
        $this->increment( 'item', 'item_views', 1, "item_id = $this->item_id" );
    }

    public function fillFoto()
    {
        $this->select()
                ->from( 'foto' )
                ->where( "foto_item = $this->item_id" )
                ->orderby( 'foto_pos asc' )
                ->execute();
        if ( $this->result() )
        {
            $this->addkey( 'foto_big', '', 'foto_url' );
            $this->preg( '/\.jpg/', '', 'foto_url' );
            $this->f_foto = $this->data[0]['foto_url'];
            $this->f_foto_big = $this->data[0]['foto_big'];
            $this->assign( 'f_foto', $this->f_foto );
            $this->assign( 'f_big', $this->f_foto_big );
            $this->assignAll();
            //unset($this->data[0]);
            $this->fetch( 'fg', $this->data );
        }
    }

    public function fillTipo()
    {
        $this->select()
                ->from( 'tipo' )
                ->join( 'item', 'item_tipo = tipo_id', 'INNER' )
                ->groupby( 'tipo_id' )
                ->orderby( 'tipo_title asc' )
                ->execute();
        if ( $this->result() )
        {
            $this->fetch( 'tpb', $this->data );
            $this->fetch( 'tpv', $this->data );
            $this->fetch( 'tpl', $this->data );            
        }
    }

    public function fillCategoria()
    {
        $this->select()
                ->from( 'categoria' )
                ->join( 'sub', 'sub_categoria = categoria_id', 'INNER' )
                ->join( 'item', 'item_categoria = categoria_id', 'INNER' )
                ->groupby( 'categoria_id' )
                ->orderby( 'categoria_title asc' )
                ->execute();
        if ( $this->result() )
        {
            $this->fetch( 'catb', $this->data );
        }
    }
}
/* end file */