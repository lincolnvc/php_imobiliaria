<?php
//faz cache 
//@header( "Cache-Control: max-age=604800" );

class Index extends PHPFrodo
{
    public $config = array( );
    public $menu;

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
            $this->cliente = $this->data[0];
            $this->assignAll();
        }

        $this->select()
                ->from( 'smtp' )
                ->execute();
        if ( $this->result() )
        {
            $this->smtp = $this->data[0];
            $this->assignAll();
        }
    }

    public function welcome()
    {
        $this->tpl( 'public/index.html' );
        $this->select()
                ->from( 'item' )
                ->join( 'tipo', 'item_tipo = tipo_id', 'INNER' )
                ->join( 'sub', 'item_sub = sub_id', 'INNER' )
                ->join( 'categoria', 'sub_categoria = categoria_id', 'INNER' )
                ->join( 'foto', 'foto_item = item_id and foto.foto_pos = ( SELECT MIN( foto_pos ) FROM foto where foto_item = item_id)', 'LEFT' )
                ->where( 'item_show = 1 and item_destaque = 1' )
                ->paginate( 15 )
                ->groupby( 'item_id' )
                ->orderby( 'item_pos asc' )
                ->execute();
        if ( $this->result() )
        {
            $aux = $this->data;
            $keys = '';
            $content = '';
            $cities = array( );
            foreach ( $aux as $k => $v )
            {
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
                if ( $aux[$k]['item_vaga'] == 0 )
                {
                    $aux[$k]['item_vaga'] = "0";
                }
                if ( $aux[$k]['item_wc'] == 0 )
                {
                    $aux[$k]['item_wc'] = "0";
                }
                if ( $aux[$k]['item_dorm'] == 0 )
                {
                    $aux[$k]['item_dorm'] = "0";
                }
                if ( !isset( $aux[$k]['foto_url'] ) || $aux[$k]['foto_url'] == "" )
                {
                    $aux[$k]['foto_url'] = "empty";
                }
                if ( isset( $aux[$k]['item_vendido'] ) )
                {
                    if ( $aux[$k]['item_vendido'] <= 9 )
                    {
                        $pat = array( '/0/', '/1/', '/2/', '/3/', '/4/', '/5/', '/6/', '/7/', '/8/', '/9/' );
                        $rep = array( '', 'Vendido', 'Alugado', 'Lançamento', 'Pronto para morar', 'Em construção', 'Oportunidade', 'Financie', 'Decorado', 'Para renda' );
                    }
                    else
                    {
                        $pat = array( '/10/', '/11/', '/12/' );
                        $rep = array( 'Alugue', 'Condomínio', 'Cobertura' );
                    }
                    $aux[$k]['item_vendido'] = preg_replace( $pat, $rep, $aux[$k]['item_vendido'] );
                }

                if ( $aux[$k]['sub_title'] == "." || $aux[$k]['sub_title'] == "" )
                {
                    $aux[$k]['sub_title'] = "*";
                    $aux[$k]['sub_url'] = "bairro";
                }

                if ( $aux[$k]['item_dorm'] >= 1 )
                {
                    $keys .= $aux[$k]['tipo_title'] . " com " . $aux[$k]['item_dorm'] . " dormitórios em " . $aux[$k]['categoria_title'] . ", ";
                }
                else
                {
                    $keys .= $aux[$k]['tipo_title'] . " em " . $aux[$k]['categoria_title'] . ", ";
                }
                if ( !in_array( $aux[$k]['categoria_title'], $cities ) )
                {
                    $cities[] = $aux[$k]['categoria_title'];
                    $content .= "imóveis em " . $aux[$k]['categoria_title'] . ", ";
                }
            }
            $this->assign( 'content', $content );
            $this->assign( 'keys', $keys );

            $this->data = $aux;
            $pat = array( '/1/', '/2/', '/3/', '/4/' );
            $rep = array( 'Venda', 'Locação', 'Locação e Venda', 'Temporada' );
            $this->preg( $pat, $rep, 'item_finalidade' );

            if ( !isset( $this->uri_segment[2] ) )
            {
                $this->assign( 'categoria_active', 'hider' );
            }
            $this->encode( 'sub_title', 'ucwords' );
            $this->addkey( 'item_promo', '', 'item_vendido' );
            $this->preg( '/\.jpg/', '', 'foto_url' );
            //$this->clonekey( 'item_dorm', array( 'item_suite', 'item_dorm' ), '+' );//soma suite + dorms
            $this->fetch( 'i', $this->data );
        }
        $this->fillSlideShow();
        $this->fillTipo();
        $this->fillCategoria();
        $this->render();
    }

    public function fillSlideShow()
    {
        $this->select()
                ->from( 'item' )
                ->join( 'tipo', 'item_tipo = tipo_id', 'INNER' )
                ->join( 'sub', 'item_sub = sub_id', 'INNER' )
                ->join( 'categoria', 'sub_categoria = categoria_id', 'INNER' )
                ->join( 'foto', 'foto_item = item_id and foto.foto_pos = ( SELECT MAX( foto_pos ) FROM foto where foto_item = item_id)', 'LEFT' )
                ->where( 'item_show = 1 and item_slide = 1' )
                ->groupby( 'item_id' )
                ->orderby( 'item_pos asc' )
                ->limit( 0,7 )
                ->execute();
        if ( $this->result() )
        {
            $aux = $this->data;
            foreach ( $aux as $k => $v )
            {
                if ( $aux[$k]['sub_title'] == "." || $aux[$k]['sub_title'] == "" )
                {
                    $aux[$k]['sub_title'] = "*";
                    $aux[$k]['sub_url'] = "bairro";
                }
                if ( $aux[$k]['foto_url'] == "" || empty( $aux[$k]['foto_url'] ) )
                {
                    $aux[$k]['foto_url'] = 'empty';
                }
            }
            $this->data = $aux;
            $this->encode( 'sub_title', 'ucwords' );
            $this->money( 'item_preco' );
            $this->money( 'item_preco_locacao' );
            $this->preg( array( '/1/', '/2/', '/3/', '/4/' ), array( 'Venda', 'Locação', 'Locação e Venda', 'Temporada' ), 'item_finalidade' );
            $this->clonekey( 'foto_path', array( 'foto_url' ) );
            $this->preg( '/\.jpg/', '', 'foto_url' );
            shuffle($this->data);
            $this->fetch( 'sl', $this->data );
        }
        else
        {
            $this->assign( 'no-slide', 'hidden' );
        }
    }

    public function fillTipo( $ori = null )
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
            $this->fetch( 'seo_city', $this->data );
        }
    }

    public function fillSub()
    {
        if ( isset( $this->uri_segment[2] ) )
        {
            $this->categoria_id = $this->uri_segment[2];
        }
        $this->select()
                ->from( 'sub' )
                ->where( "sub_categoria = $this->categoria_id" )
                ->groupby( 'sub_id' )
                ->orderby( 'sub_title asc' )
                ->execute();
        if ( $this->result() )
        {
            $this->encode( 'sub_title', 'utf8_encode' );
            @header( 'Content-Type: text/html; charset=iso-8859-1' );
            echo json_encode( $this->data );
        }
        else
        {
            echo 0;
        }
    }

    public function buscaavancada()
    {
        $this->tpl( 'public/busca.html' );
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
                $load .= "setTimeout(function(){\n";
                $load .= "$('#bairro').val('$bairro')\n";
                $load .= "},1000)\n";
            }
            unset( $k );
        }

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

        if ( in_array( 'finalidade', $this->uri_segment ) )
        {
            $k = array_keys( $this->uri_segment, 'finalidade' );
            if ( $k )
            {
                $finalidade = $this->uri_segment[$k[0] + 1];
                if ( $finalidade > 0 )
                {
                    $cond .= "item_finalidade = $finalidade ";
                    if ( $finalidade == 2 )
                    {
                        $load .= "$('.valor_venda').hide();\n";
                        $load .= "$('.valor_locacao').show();\n";
                    }
                }
                $load .= "$('#finalidade').val('$finalidade');\n";
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
                    if ( $finalidade == 2 )
                    {
                        $cond .= " AND item_preco_locacao <= $preco ";
                        $load .= "$('.valor_locacao').val('$preco');\n";
                    }
                    else
                    {
                        $cond .= " AND item_preco <= $preco ";
                        $load .= "$('.valor_venda').val('$preco');\n";
                    }
                }
            }
            unset( $k );
        }

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

        if ( isset( $finalidade ) && $finalidade == 2 || isset( $finalidade ) && $finalidade == 1 )
        {
            $cond .= " AND item_show = 1 OR " . preg_replace( array( '/item_finalidade \= 1/', '/item_finalidade \= 2/' ), array( 'item_finalidade  = 3', 'item_finalidade  = 3' ), $cond );
        }

        $this->assign( 'load', $load );
        $this->select()
                ->from( 'item' )
                ->join( 'sub', 'item_sub = sub_id', 'INNER' )
                ->join( 'categoria', 'item_categoria = categoria_id', 'INNER' )
                ->join( 'tipo', 'item_tipo = tipo_id', 'INNER' )
                ->join( 'foto', 'foto_item = item_id and foto.foto_pos = ( SELECT MIN( foto_pos ) FROM foto where foto_item = item_id)', 'LEFT' )
                ->where( "$cond AND item_show = 1" )
                ->groupby( 'item_id' )
                ->orderby( 'item_pos asc' )
                ->execute();
        if ( $this->result() )
        {
            $aux = $this->data;
            foreach ( $aux as $k => $v )
            {
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
                if ( $aux[$k]['item_vaga'] == 0 )
                {
                    $aux[$k]['item_vaga'] = "0";
                }
                if ( $aux[$k]['item_wc'] == 0 )
                {
                    $aux[$k]['item_wc'] = "0";
                }
                if ( $aux[$k]['item_dorm'] == 0 )
                {
                    $aux[$k]['item_dorm'] = "0";
                }
                if ( !isset( $aux[$k]['foto_url'] ) || $aux[$k]['foto_url'] == "" )
                {
                    $aux[$k]['foto_url'] = "empty";
                }

                if ( isset( $aux[$k]['item_vendido'] ) )
                {
                    if ( $aux[$k]['item_vendido'] <= 9 )
                    {
                        $pat = array( '/0/', '/1/', '/2/', '/3/', '/4/', '/5/', '/6/', '/7/', '/8/', '/9/' );
                        $rep = array( '', 'Vendido', 'Alugado', 'Lançamento', 'Pronto para morar', 'Em construção', 'Oportunidade', 'Financie', 'Decorado', 'Para renda' );
                    }
                    else
                    {
                        $pat = array( '/10/', '/11/', '/12/' );
                        $rep = array( 'Alugue', 'Condomínio', 'Cobertura' );
                    }
                    $aux[$k]['item_vendido'] = preg_replace( $pat, $rep, $aux[$k]['item_vendido'] );
                }

                if ( $aux[$k]['sub_title'] == "." || $aux[$k]['sub_title'] == "" )
                {
                    $aux[$k]['sub_title'] = "*";
                    $aux[$k]['sub_url'] = "bairro-nao-informado";
                }
                if ( $aux[$k]['foto_url'] == "" || empty( $aux[$k]['foto_url'] ) )
                {
                    $aux[$k]['foto_url'] = 'empty';
                }
            }
            $this->data = $aux;
            $pat = array( '/1/', '/2/', '/3/', '/4/' );
            $rep = array( 'Venda', 'Locação', 'Locação e Venda', 'Temporada' );
            $this->preg( $pat, $rep, 'item_finalidade' );
            if ( !isset( $this->uri_segment[2] ) )
            {
                $this->assign( 'categoria_active', 'hider' );
            }
            $this->encode( 'sub_title', 'ucwords' );
            $this->addkey( 'item_promo', '', 'item_vendido' );
            $this->preg( '/\.jpg/', '', 'foto_url' );
            //$this->clonekey( 'item_dorm', array( 'item_suite', 'item_dorm' ), '+' ); //soma suite + dorms
            $this->fetch( 'i', $this->data );
            $this->assign( 'noResult', '' );
        }
        else
        {
            $this->assign( 'noResult', '<h3>;( Nenhum imóvel encontrado!</h3><Br /><Br /><Br /><Br />' );
        }
        $this->assign( 'busca', "" );
        $this->fillTipo( 1 );
        $this->fillCategoria();
        $this->render();
    }

    public function busca()
    {
        $this->tpl( 'public/busca.html' );
        if ( isset( $_POST['busca'] ) && !empty( $_POST['busca'] ) )
        {
            $busca = preg_replace( array( '/\s+/' ), array( ' ' ), $_POST['busca'] );
            $term = preg_replace( array( '/\s+/', '/\s+(em)/' ), array( ' ', '' ), $_POST['busca'] );
            $parts = explode( " ", $term );
            $part1 = $parts[0];
            $condG = ' AND item_show = 1';
            $cond = "item_busca like '%$term%' $condG OR ";
            $cond .= "item_ref like'%$term%' $condG OR ";
            $cond .= "tipo_title like'%$term%' $condG OR ";
            $cond .= "categoria_title like'%$term%' $condG OR ";
            //$cond .= "item_busca like '%$part1%' $condG OR ";
            $cond .= "sub_title like'%$term%' ";
            if ( @is_nan( $busca ) )
            {
                $cond = "item_ref = '$busca'";
            }
            $this->select()
                    ->from( 'item' )
                    ->join( 'sub', 'item_sub = sub_id', 'INNER' )
                    ->join( 'categoria', 'sub_categoria = categoria_id', 'INNER' )
                    ->join( 'tipo', 'item_tipo = tipo_id', 'INNER' )
                    ->join( 'foto', 'foto_item = item_id and foto.foto_pos = ( SELECT MIN( foto_pos ) FROM foto where foto_item = item_id)', 'LEFT' )
                    ->where( "$cond" )
                    ->groupby( 'item_id' )
                    ->orderby( 'item_id desc' )
                    ->execute();
            if ( $this->result() )
            {
                $aux = $this->data;
                foreach ( $aux as $k => $v )
                {
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
                    if ( $aux[$k]['item_vaga'] == 0 )
                    {
                        $aux[$k]['item_vaga'] = "0";
                    }
                    if ( $aux[$k]['item_wc'] == 0 )
                    {
                        $aux[$k]['item_wc'] = "0";
                    }
                    if ( $aux[$k]['item_dorm'] == 0 )
                    {
                        $aux[$k]['item_dorm'] = "0";
                    }
                    if ( !isset( $aux[$k]['foto_url'] ) || $aux[$k]['foto_url'] == "" )
                    {
                        $aux[$k]['foto_url'] = "empty";
                    }

                    if ( isset( $aux[$k]['item_vendido'] ) )
                    {
                        if ( $aux[$k]['item_vendido'] <= 9 )
                        {
                            $pat = array( '/0/', '/1/', '/2/', '/3/', '/4/', '/5/', '/6/', '/7/', '/8/', '/9/' );
                            $rep = array( '', 'Vendido', 'Alugado', 'Lançamento', 'Pronto para morar', 'Em construção', 'Oportunidade', 'Financie', 'Decorado', 'Para renda' );
                        }
                        else
                        {
                            $pat = array( '/10/', '/11/', '/12/' );
                            $rep = array( 'Alugue', 'Condomínio', 'Cobertura' );
                        }
                        $aux[$k]['item_vendido'] = preg_replace( $pat, $rep, $aux[$k]['item_vendido'] );
                    }
                    if ( $aux[$k]['sub_title'] == "." || $aux[$k]['sub_title'] == "" )
                    {
                        $aux[$k]['sub_title'] = "*";
                        $aux[$k]['sub_url'] = "bairro-nao-informado";
                    }
                    if ( $aux[$k]['foto_url'] == "" || empty( $aux[$k]['foto_url'] ) )
                    {
                        $aux[$k]['foto_url'] = 'empty';
                    }
                }
                $this->data = $aux;
                $pat = array( '/1/', '/2/', '/3/', '/4/' );
                $rep = array( 'Venda', 'Locação', 'Locação e Venda', 'Temporada' );
                $this->preg( $pat, $rep, 'item_finalidade' );
                if ( !isset( $this->uri_segment[2] ) )
                {
                    $this->assign( 'categoria_active', 'hider' );
                }
                $this->assign( 'noResult', '' );
                $this->encode( 'sub_title', 'ucwords' );
                $this->addkey( 'item_promo', '', 'item_vendido' );
                $this->preg( '/\.jpg/', '', 'foto_url' );
                //$this->clonekey( 'item_dorm', array( 'item_suite', 'item_dorm' ), '+' );
                $this->fetch( 'i', $this->data );
            }
            else
            {
                $this->assign( 'noResult', '<h3>;( Nenhum imóvel encontrado!</h3><Br /><Br /><Br /><Br />' );
            }
        }
        else
        {
            $this->redirect( "$this->baseUri/" );
        }
        $this->fillTipo( 1 );
        $this->fillCategoria();
        $this->assign( 'busca', "$busca" );
        $this->render();
    }
}
/*end file*/
