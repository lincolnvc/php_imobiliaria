$(function(){
    //baseUri
    $('head').append('<script src="js/default/baseuri.js" type="text/javascript"></script>');
    //formata preco
    $('head').append('<script src="js/jquery/jquery.price.js" type="text/javascript"></script>');
    $('#item_preco').priceFormat({
        prefix: ''
    });
    $('#item_desconto').priceFormat({
        prefix: ''
    });
    //stupidtable
    $(".table").stupidtable();   
    //button submit
    $('#btn-add').live('click',function(){
        $('#f-item').submit();
    })
    //editar item
    $('.edit').live('click',function(){
        var id = $(this).attr('id');
        window.location = baseUri+'/admin/cliente/editar/'+id+'/';
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
        var url = baseUri +'/admin/cliente/remover/'+id+'/';
        $('#btn-remove').attr('href',url);
    })      

})
