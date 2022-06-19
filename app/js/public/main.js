/*
 * @author Rafael Clares <rafael@clares.com>
 * @version 3.0 <11/2013>
 * www.clares.com.br
 */

var baseUri = $('base').attr('href').replace('/app/','');        
$(function(){
    $('head').append('<script src="js/jquery/jquery.placeholder.js" type="text/javascript"></script>');
    $('input[placeholder], textarea[placeholder]').placeholder(); 
    //force hide elements
    $('.hidden').hide(); 	
    //tootips
    $('.tips-top').tooltip({
        placement:'top'
    });    
    $('.tips-left').tooltip({
        placement:'left'
    });    
    $('.tips-bottom').tooltip({
        placement:'bottom'
    });    
    $('.tips-right').tooltip({
        placement:'right'
    }); 

    //setMenuActive
    var activeLink = window.location.href;
    $('#navmenu').find('a').each(function(){
        if($(this).attr('href') == activeLink){
            $(this).parent().addClass('active');
        }
    });
    //exibe o form após o carregamento
    window.onload = function(){
        $('.panel-busca').removeClass('hidden').fadeIn();
    }
    //altera faixa preços quando selecionado venda / locacao
    $('.valor_locacao').attr('name','valormax_aux').hide();
    $('#finalidade').live('change',function(e){
        e.preventDefault();
        if($('#finalidade option:selected').val() == 2 || $('#finalidade option:selected').val() == 4){
            $('.valor_venda').hide();
            $('.valor_locacao').show();
        }
        else{
            $('.valor_venda').show();
            $('.valor_locacao').hide();            
        }
    })    
    //busca por ref.
    $('#form-busca-ref').submit(function(e){
        if( $('#busca').val() == ""){
            $('#busca').focus();
            return false;
        }
    })
    $('#panel-busca form').attr('onSubmit','return false');
    $('#btn-busca').live('click',function(e){
        e.preventDefault();
        var finalidade = $('#finalidade option:selected').val();
        var tipo = $('#tipo option:selected').val();
        var dorms = $('#dorms option:selected').val();
        var cidade = $('#cidade option:selected').val();
        var bairro = $('#bairro option:selected').val();
        var valormax = $('#valormax option:selected').val();
        if(finalidade == 2){
          var valormax = $('.valor_locacao option:selected').val();
        }
        var url  = baseUri+'/index/buscaavancada/';
        url += 'finalidade/'+finalidade+'/';
        
        if(tipo >= 1){
            url += 'tipo/'+tipo+'/';
        }
        if(dorms >= 1){
            url += 'dorms/'+dorms+'/';
        }
        if(cidade >= 1){
            url += 'cidade/'+cidade+'/';
        }
        if(bairro >= 1){
            url += 'bairro/'+bairro+'/';
        }
        if(valormax >= 1){
            url += 'max/'+valormax+'/';
        }
        if(finalidade > 0){
            window.location = url;
        }
        else{
            var popcontent = '<p>Você deve informar o tipo de transação.<p>';
            $('#finalidade').popover({
                placement:'right',
                title:'Busca Refinada',
                html: true, 
                content:popcontent
            });    
            $('#finalidade').popover('show');           
        }
    })
    //voltar ao topo
    $('.go-top img').tooltip({
        placement:'top'
    }); 
    $('.go-top').live('click',function(){
        $('html, body').animate({
            scrollTop: $('#logo').offset().top
        }, 700);  
    })
    
    //envia form página contato
    $('#form-contato-home').attr('onSubmit','return false');
    $('#form-contato-home .btn').button();
    $('#form-contato-home').submit(function(e) {
        e.preventDefault();
        e.stopPropagation();
        if(valid == true){
            $('#form-contato-home .btn').button('loading');
            var url = baseUri + '/mailer/send/';
            var ar  = $(this).serialize();
            $.post(url,{
                ar:ar
            },function(data){
                if(data == 0){
                    $('#form-contato-home .btn').removeClass('btn-danger').addClass('btn-success');
                    $('#form-contato-home .btn').button('complete');
                    $('#form-contato-home').attr('onsubmit','return false');
                    $('#form-contato-home').find('*').val('');
                    $('#form-contato-home').find('*').removeClass('invalid').removeClass('valid');                    
                    setTimeout(function(){
                        $('#form-contato-home .btn').addClass('btn-danger').removeClass('btn-success');    
                        $('#form-contato-home .btn').html('Enviar Nova Mensagem');
                    },5000);
                }
            })
        }
        return false;
    })  
    //envia form página detalhes do imovel
    $('#contato-ref-im').attr('onSubmit','return false');
    $('#contato-ref-im').submit(function(e) {
        e.preventDefault();
        e.stopPropagation();
        if(valid == true){
            $('#contato-ref-im .btn').button('loading');
            var url = baseUri + '/mailer/sendFromRef/';
            var ar  = $(this).serialize();
            $.post(url,{
                ar:ar
            },function(data){
                if(data == 0){
                    $('#contato-ref-im .btn').removeClass('btn-danger').addClass('btn-success');
                    $('#contato-ref-im .btn').button('complete');
                    $('#contato-ref-im').attr('onsubmit','return false');
                    $('#contato-ref-im').find('.required').val('');
                    $('#contato-ref-im').find('*').removeClass('invalid').removeClass('valid');                    
                    setTimeout(function(){
                        $('#contato-ref-im .btn').addClass('btn-danger').removeClass('btn-success');    
                        $('#contato-ref-im .btn').html('Enviar Nova Mensagem');
                    },5000);
                }
            })
        }
        return false;
    })
    
    $('#cidade').live('change',function(){
        var cidade = $('#cidade option:selected').val();
        $('#bairro option').remove();
        $('<option>').val('0').text('Bairro...').appendTo('#bairro');
        //$('#bairro').selectpicker('refresh');  
        if(cidade >= 1){
            loadSub(cidade)
        }else{
            $('<option>').val('0').text('Bairro...').appendTo('#bairro');
            $('#bairro').attr('disabled','disabled'); 
        //$('#bairro').selectpicker('refresh');              
        }
    })
    //load sub cidade 
    loadSub = function (cat) {
        var url = baseUri+'/index/fillSub/'+cat+'/'
        $.getJSON(url, {
            cat:cat
        }, 
        function(data){
            $('#bairro option').remove();
            $('#bairro').removeAttr('disabled');
            //$('#bairro').prop('disabled',false);
            //$('#bairro').selectpicker('refresh');            
            if(data != 0){
                $('<option>').val('0').text('Todos').appendTo('#bairro');
                $(data).each(function (k,v) {
                    $('<option>').val(v.sub_id).text(v.sub_title).appendTo('#bairro'); 
                //$('#bairro').selectpicker('refresh'); 
                })
                $('#bairro').val(0)
            }
            else{
                $('<option>').val('0').text('Nenhum bairro cadastrado').appendTo('#bairro');
                $('#bairro').attr('disabled','disabled');
            }
        })    
    }
})

