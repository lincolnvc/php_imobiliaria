$(function(){
    //baseUri
    $('head').append('<script src="js/default/baseuri.js" type="text/javascript"></script>');
    //stupidtable
    $(".table").stupidtable();
    
    //editar
    $('.edit').live('click',function(){
        var id = $(this).attr('id');
        //var url = baseUri +'/admin/proprietario/editar/'+id+'/';
        //window.location = url;
        
        var id = $(this).attr('id');
        var a = $(this).attr('a');
        var b = $(this).attr('b');
        var c = $(this).attr('c');
        var d = $(this).attr('d');
        var e = $(this).attr('e');
        
        $('#add-proprietario').find('b').html('Editar Proprietário');
        $('#f-proprietario').find('.icon-plus-sign').removeClass('icon-plus-sign').addClass('icon-edit');
        $('#collapseOne').collapse('show');
        $('#dono_nome').val(a);
        $('#dono_telefone1').val(b);
        $('#dono_telefone2').val(c);
        $('#dono_telefone3').val(d);
        $('#dono_email').val(e);
        $('#btn-add').html('Atualizar');
        $('#f-proprietario').attr('action',$('#f-proprietario').attr('action').replace('/incluir/','/atualizar/'+id+'/'));
        $('#plano_nome').removeClass('invalid');
        
    })
    //cancel
    $('.cancel').live('click',function(){
        $('#collapseOne').collapse('hide'); 
        $('#add-plano').find('b').html('Cadastrar Novo Proprietário');
        $('#f-proprietario').attr('action',$('#f-proprietario').attr('action').replace('/atualizar/','/incluir/'));
        $('#btn-add').html('Cadastrar');
        $('#plano_title').val('');
        $('#add-plano').find('.icon-edit').removeClass('icon-edit').addClass('icon-plus-sign');
        $('#plano_title').removeClass('invalid');
    })
    //remove
    $('.remove').live('click',function(){
        var id = $(this).attr('id');
        $('#modal-remove').modal('show');
        var url = baseUri +'/admin/proprietario/remover/'+id+'/';
        $('#btn-remove').attr('href',url);
    })        
})
function valida()
{
    if($.trim($('#dono_nome').val()) == "")
    {
        $('#dono_nome').addClass('invalid');
        $('#dono_nome').focus();
        //$('#plano_title').popover({placement:'top',title:'Campo Requerido',html: true, content:'Você precisa selecionar uma plano!'});
        return false;
    }
    else
    {
        $('#dono_nome').removeClass('invalid');
        return true;
    }
}

