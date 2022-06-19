<?php
//@header( "Cache-Control: max-age=604800" );
@header( "Cache-Control: no-cache, must-revalidate" );
@header( "Expires: Mon, 26 Jul 1997 05:00:00 GMT" );

class Mapa extends Index
{

    public function __construct()
    {
        parent:: __construct();
    }

    public function welcome()
    {
        $this->tpl( 'public/mapas.html' );
        if ( isset( $this->uri_segment[1] ) )
        {
            $onload = " panTo(" . $this->uri_segment[1] . ")";
            $this->assign( 'panTo', $onload );
        }
        $this->lat = ( string ) $this->cliente['cliente_lat'];
        $this->lon = ( string ) $this->cliente['cliente_lon'];
        $this->assign( 'init_lat', $this->lat );
        $this->assign( 'init_lon', $this->lon );
        $this->fillTipoM();
        $this->render();
    }

    public function pontos()
    {
        $this->select()
                ->from( 'item' )
                ->join( 'tipo', 'item_tipo = tipo_id', 'INNER' )
                ->join( 'sub', 'item_sub = sub_id', 'INNER' )
                ->join( 'categoria', 'sub_categoria = categoria_id', 'INNER' )
                ->where( 'item_show = 1 and item_destaque = 1' )
                ->groupby( 'item_id' )
                ->orderby( 'item_pos asc' )
                ->execute();
        if ( $this->result() )
        {
            $aux = $this->data;
            foreach ( $aux as $k => $v )
            {
                unset( $aux[$k]['item_desc'] );
                if ( $aux[$k]['item_finalidade'] == 2 )
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
                elseif ( $aux[$k]['item_finalidade'] == 3 )
                {
                    if ( $aux[$k]['item_preco_locacao'] >= 1 )
                    {
                        $aux[$k]['item_preco'] = $aux[$k]['item_preco_locacao'];
                    }
                }
                elseif ( $aux[$k]['item_finalidade'] == 4 )
                {
                    if ( $aux[$k]['item_preco_temp'] >= 1 )
                    {
                        $aux[$k]['item_preco'] = $aux[$k]['item_preco_temp'];
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
                if ( $aux[$k]['item_area'] == 0 )
                {
                    $aux[$k]['item_area'] = "";
                    $aux[$k]['item_ar_visible'] = "hider";
                }
                if ( $aux[$k]['item_vaga'] == 0 )
                {
                    $aux[$k]['item_vaga'] = "";
                    $aux[$k]['item_vg_visible'] = "hider";
                }
                if ( $aux[$k]['item_dorm'] == 0 )
                {
                    $aux[$k]['item_dorm'] = "";
                    $aux[$k]['item_dm_visible'] = "hider";
                }
                $item = $aux[$k]['item_id'];
                $this->select()->from( 'foto' )->where( "foto_item = $item" )->paginate( 1 )->orderby( 'foto_pos asc' )->execute();
                if ( $this->result() )
                {
                    if ( $this->data[0]['foto_url'] == "" || empty( $this->data[0]['foto_url'] ) )
                    {
                        $this->data[0]['foto_url'] = 'empty';
                    }
                    $aux[$k]['ori_f_url'] = $this->data[0]['foto_url'];
                    $aux[$k]['foto_url'] = $this->data[0]['foto_url'];
                }
                else
                {
                    $aux[$k]['ori_f_url'] = 'empty';
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

            $pat = array( '/0/', '/1/', '/2/', '/3/', '/4/', '/5/', '/6/', '/7/', '/8/', '/9/', '/10/', '/11/', '/12/' );
            $rep = array( '', 'Vendido', 'Alugado', 'Lançamento', 'Pronto para morar', 'Em construção', 'Oportunidade', 'Financie', 'Decorado', 'Para renda', 'Alugue', 'Condomínio', 'Cobertura' );
            $this->preg( $pat, $rep, 'item_promo' );

            $this->clonekey( 'item_dorm', array( 'item_suite', 'item_dorm' ), '+' );
            $this->toJson( $this->data );
            echo $this->jsonData;
        }
    }
    
    public function fillTipoM()
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
}
