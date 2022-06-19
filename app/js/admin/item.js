(function($){
    $.fn.myfilter = function() { 
        $(this).keydown(function (e) { 
            if (e.shiftKey || e.ctrlKey || e.altKey) { // if shift, ctrl or alt keys held down 
                e.preventDefault();         // Prevent character input 
            } else { 
                var n = e.keyCode; 
                if (!((n == 8)              // backspace 
                    || (n == 46)                // delete 
                    || (n >= 35 && n <= 40)     // arrow keys/home/end 
                    || (n >= 48 && n <= 57)     // numbers on keyboard 
                    || (n >= 96 && n <= 105))   // number on keypad 
                ) { 
                    e.preventDefault();     // Prevent character input 
                } 
            } 
        }); 
    }
})(jQuery);

var donoStart = "";
$(function(){
    //baseUri
    $('head').append('<script src="js/default/baseuri.js" type="text/javascript"></script>');
    //formata preco
    $('head').append('<script src="js/jquery/jquery.price.js" type="text/javascript"></script>');
    $('.valor').priceFormat({
        prefix: ''
    });
    $('#item_area').myfilter();
    //stupidtable
    $(".table").stupidtable();   
    //button submit
    $('#btn-add').live('click',function(){
        $('#f-item').submit();
    })
    //editar item
    $('.edit').live('click',function(){
        var id = $(this).attr('id');
        window.location = baseUri+'/admin/item/editar/'+id+'/';
    })
    //cancel
    $('.cancel').live('click',function(){
        $('#sub_categoria').val('');
        $('#collapseOne').collapse('hide'); 
        $('#add-categoria').find('b').html('Cadastrar Nova Subcategoria');
        $('#f-categoria').attr('action',$('#f-categoria').attr('action').replace('/atualizar/','/incluir/'));
        $('#btn-add').html('Cadastrar');
        $('#sub_title').val('');
        $('#add-categoria').find('.icon-edit').removeClass('icon-edit').addClass('icon-plus-sign');
        $('#sub_title').removeClass('invalid');
    })
    //remover item
    $('.remove').live('click',function(){
        var id = $(this).attr('id');
        $('#modal-remove').modal('show');
        var url = baseUri +'/admin/item/remover/'+id+'/';
        $('#btn-remove').attr('href',url);
    })      
    //event change
    $('<option>').val('').text('Antes, selecione uma cidade').appendTo('#item_sub');
    $('#item_sub').attr('disabled','disabled');
    $('#item_categoria').live('change',function(){
        if($('#item_categoria option:selected').val() != ""){
            $('<option>').val('').text('carregando bairros...').appendTo('#item_sub');
            var cat = $('#item_categoria option:selected').val();
            var url = baseUri+'/admin/item/fillSubCategoria/'+cat+'/'
            $.getJSON(url, {
                cat:cat
            }, 
            function(data){
                $('#item_sub option').remove();
                $('#item_sub').removeAttr('disabled');
                if(data != 0){
                    $(data.rs).each(function (k,v) {
                        $('<option>').val(v.sub_id).text(v.sub_title).appendTo('#item_sub'); 
                    })
                }
                else{
                    $('<option>').val('').text('Nenhum bairro cadastrado').appendTo('#item_sub');
                    $('#item_sub').attr('disabled','disabled');
                }
            })
        }
        else{
            $('#item_sub option').remove();
            $('<option>').val('').text('Antes, selecione uma cidade').appendTo('#item_sub');
            $('#item_sub').attr('disabled','disabled');
        }
    })
    //mensagens input tips
    var popcontent = '<p>Cadastre apenas o código do imóvel. Exemplo: <b> 123</b>';
    $('#item_ref').popover({
        placement:'right',
        title:'Código interno do  Imóvel',
        html: true, 
        content:popcontent
    });
    var popcontent3 = '<p>Esta opção determina se o Imóvel aparecerá ou não no site.<p>';
    $('#item_show').popover({
        placement:'right',
        title:'Imóvel Ativo',
        html: true, 
        content:popcontent3
    });
    var popcontent4 = '<p>Rótulo mp Imóvel / vendido / alugado / outros /  aparecem com uma tarja diferenciada no site.<p>';
    $('#item_vendido').popover({
        placement:'right',
        title:'Imóvel Vendido / Alugado',
        html: true, 
        content:popcontent4
    });
    var popcontent5 = '<p>Imóveis em destaque aparecem na página inicial do site. Se definido como "não", aparecerá apenas nas buscas!<p>';
    $('#item_destaque').popover({
        placement:'right',
        title:'Imóvel em Destaque',
        html: true, 
        content:popcontent5
    });
    var popcontent6 = '<p>Imóveis em slideshow aparecem no slideshow da página principal.<p>';
    $('#item_slide').popover({
        placement:'right',
        title:'Imóvel em Slideshow',
        html: true, 
        content:popcontent6
    });
    var popcontent7 = '<p>Se o valor for 0,00 aparecerá "consulte-nos" no site.<p>';
    $('#item_preco').popover({
        placement:'right',
        title:'Valor do Imóvel',
        html: true, 
        content:popcontent7
    });
    var popcontent8 = '<p>Cadastre o endereço se desejar exibi-lo no mapa de detalhes.<p>';
    $('#item_endereco').popover({
        placement:'right',
        title:'Endereço do Imóvel',
        html: true, 
        content:popcontent8
    });
    var popcontent9 = '<p>Exibir o endereço exato do imóvel no mapa de detalhes.<p>';
    $('#item_mapa').popover({
        placement:'right',
        title:'Endereço exato do Imóvel',
        html: true, 
        content:popcontent9
    });
    
    //load sub categoria item editar
    loadSub = function (cat,sub) {
        //console.log(cat)
        var url = baseUri+'/admin/item/fillSubCategoria/'+cat+'/'
        $.getJSON(url, {
            cat:cat
        }, 
        function(data){
            $('#item_sub option').remove();
            $('#item_sub').removeAttr('disabled');
            if(data != 0){
                $(data.rs).each(function (k,v) {
                    $('<option>').val(v.sub_id).text(v.sub_title).appendTo('#item_sub'); 
                })
                $('#item_sub').val(sub);
            }
            else{
                $('<option>').val('').text('Nenhuma subcategoria cadastrada').appendTo('#item_sub');
                $('#item_sub').attr('disabled','disabled');
            }
        })    
    }

    //make sortable
    $( "#photo-gallery-ul" ).sortable({
        opacity: 0.8,
        placeholder: "ui-state-highlight",
        cursor: "move",
        stop: function(){
            var sorted = $(this).sortable('serialize');
            var url = baseUri+'/admin/item/updateFotoPos/'
            $.post(url,{
                item:sorted
            },function(data){
                //console.log(data)
                notify('<h1>Nova posição gravada</h1>');
            })
        }
    });
    //make selectable
    $( "#photo-gallery-ul" ).selectable({
        start: function() {
        },
        stop: function() {
        }
    });
    
    
    //make sortable item posicao
    $( "#photo-gallery-ul-pos" ).sortable({
        opacity: 0.8,
        placeholder: "ui-state-highlight",
        cursor: "move",
        stop: function(){
            var sorted = $(this).sortable('serialize');
            var url = baseUri+'/admin/item/updateItemPos/'
            $.post(url,{
                item:sorted
            },function(data){
                console.log(data)
                notify('<h1>Nova posição gravada</h1>');
            })
        }
    });    
    
    //remove foto
    $('#btn-remove-foto').live('click',function(e){                    
        e.preventDefault();
        if($( "#photo-gallery-ul .ui-selected" ).length >= 1){
            $( "#photo-gallery-ul .ui-selected" ).each(function() {
                if($(this).attr('id')){   
                    var foto_id = $(this).attr('id').replace('li_','');
                    var url = baseUri+'/admin/item/removeUniqFoto/'+foto_id+'/'
                    $(this).remove();
                    //$(this).effect('clip',function(){});
                    $.post(url,{
                        foto_id:foto_id
                    },function(data){
                        //console.log($( '#photo-gallery-ul li' ).length)                     
                        //oculta controles
                        if( ( $( '#photo-gallery-ul li' ).length - 1) <= 0){
                            $('#foto-control').hide();
                        }    
                        
                    })                    
                }
            });   
            var sorted = $("#photo-gallery-ul").sortable('serialize');
            var url = baseUri+'/admin/item/updateFotoPos/'
            $.post(url,{
                item:sorted
            },function(data){
                //console.log(data)
                })            
        }
        else{
            notify('<h1>Nenhuma foto selecionada!</h1>');
        }
    })
    //cancela selecao
    $('#btn-remove-cancel').live('click',function(e){                    
        e.preventDefault();
        $( "#photo-gallery-ul .ui-selected" ).each(function() {
            if($(this).attr('id')){
                $(this).removeClass('ui-selected');
            }
        });    
        notify('<h1>Seleçao cancelada</h1>');
    })
    //seleciona todas 
    $('#btn-remove-all').live('click',function(e){                    
        e.preventDefault();
        $( "#photo-gallery-ul li" ).each(function() {
            if($(this).attr('id')){
                $(this).addClass('ui-selected');
            }
        });                    
        notify('<h1>Todas as fotos selecionadas</h1>');
    })
    //seleciona um com duplo clique
    $( "#photo-gallery-ul li" ).live('dblclick',function(){
        //console.log( $(this).attr('id').replace('li_','')  );
        if($(this).hasClass('ui-selected')){
            $(this).removeClass('ui-selected');
        //$(this).tooltip('hide');
        //$(this).removeData('tooltip');
        //$(this).attr('title','duplo clique para selecionar');                        
        }
        else{
            $(this).addClass('ui-selected');
        //$(this).tooltip('hide');
        //$(this).removeData('tooltip');
        //$(this).attr('title','duplo clique para desmarcar');
        //$(this).tooltip({placement:'top'});
        }
    })
    //disable selection selectable
    $( "ul,li" ).disableSelection();  
    //oculta controles
    if( $( '#photo-gallery-ul li' ).length <= 0){
        $('#foto-control').hide();
    }    
    /*
    $('#item_vendido').live('change',function(){
        var iv = $('#item_vendido option:selected').val();
        if(iv == 'x'){
            $('#item_vendido').remove();
            $('<input/>')
            .attr('id','item_vendido')
            .attr('name','item_vendido')
            .addClass('name','item_vendido')
            .val('Rótulo Personalizado')
            .appendTo($('#rotulo'));
        }
        if($('#item_vendido').val() == ''){
            $('#item_vendido').remove();
        }
    })
    */
   
    //cadastrar proprietario
    $('#item_dono').live('change',function(e){
        e.preventDefault();
        var dono = $('#item_dono option:selected').val();
        if(dono == 'n'){
            $('#modal-dono').modal('show');
            
            $('#btn-cadastra').live('click',function(){
                var n = $.trim ( $('#dono_nome').val() );
                var t = $.trim ( $('#dono_telefone1').val() );                
                var url = baseUri +'/admin/proprietario/incluirRapido/';
                $.post(url,{
                    dono_nome: n,
                    dono_telefone1: t
                },function(data){
                    if(data != 1){                        
                        $('#modal-dono').modal('hide');
                        var url = baseUri +'/admin/proprietario/fillList/';
                        $.post(url,{},function(list){
                            if(data != 1){
                                var list = $.parseJSON(list);   
                            
                                $('#item_dono option').remove();                            
                                $('<option/>')
                                .val("")
                                .text("Selecione um propritário...")
                                .appendTo($('#item_dono'))
                                
                                $(list.rs).each(function(k,v){
                                    $('<option/>')
                                    .val(v.dono_id)
                                    .text(v.dono_nome)
                                    .appendTo($('#item_dono'))
                                })
                            
                                $('<option/>')
                                .val("n")
                                .text("Cadastrar novo propritário...")
                                .appendTo($('#item_dono'))
                            
                                $('#item_dono').val(data)
                            }
                        })
                    }
                })
            });            
        }
    })
})

function checkDonoAndClose() {
    $('#modal-dono').modal('hide');
    if(donoStart == "" || donoStart == "n"){
        $('#item_dono').val('');    
    }else{
        $('#item_dono').val(donoStart);
    }
}
function valida(){
    /*
    if($.trim($('#item_ref').val()) == ""){
        $('#item_ref').addClass('invalid');
        $('#myTab a[href="#dados"]').tab('show');   
        $('#item_ref').focus();
        return false;
    }
    else{
        $('#item_ref').removeClass('invalid');
    }  
    */
    if($.trim($('#item_tipo').val()) == ""){
        $('#item_tipo').addClass('invalid');
        $('#myTab a[href="#dados"]').tab('show');   
        $('#item_tipo').focus();
        return false;
    }
    else{
        $('#item_tipo').removeClass('invalid');
    }   
    
    if($('#item_categoria option:selected').val() == ""){
        $('#item_categoria').addClass('invalid');
        $('#myTab a[href="#dados"]').tab('show');   
        $('#item_categoria').focus();
        return false;        
    }
    else{
        $('#item_categoria').removeClass('invalid');
    }
    if($('#item_sub option:selected').val() == ""){
        $('#item_sub').addClass('invalid');
        $('#myTab a[href="#dados"]').tab('show');   
        $('#item_sub').focus();
        return false;        
    }
    else{
        $('#item_sub').removeClass('invalid');
    }
    if($.trim($('#item_preco').val()) == ""){
        $('#item_preco').addClass('invalid');
        $('#myTab a[href="#precos"]').tab('show');   
        $('#item_preco').focus();
        return false;
    }
    else{
        $('#item_preco').removeClass('invalid');
    }   
	
	
//$('#myTab a[href="#desc"]').tab('show');   
}

//reload binds
function reloadFotoBind() { 
    //oculta controles
    if( $( "#photo-gallery-ul li" ).length <= 0){
        $('#foto-control').hide();
    }        
    if( $( "#photo-gallery-ul li" ).length >= 1){
        $('#foto-control').show();
    }
}

function goTo(url) {
    window.location = url;
}




