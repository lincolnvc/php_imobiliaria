$(function(){
    
    //notify plugin
    $('head').append('<link href="js/jquery/notify/style.css" rel="stylesheet" type="text/css" />');
    $('head').append('<script src="js/jquery/notify/notify.js" type="text/javascript"></script>');

    $('dt').find('b').removeClass('icon-chevron-down');
    //$("dd").hide();
    /*
    $("dt a").live('click',function(e){
        e.preventDefault();
        $('dt').removeClass('active');
        if($(this).parent().find('b').hasClass('icon-chevron-down')){
            //$('dt').find('b').removeClass('icon-chevron-up').addClass('icon-chevron-down');
            $(this).parent().find('b').removeClass('icon-chevron-down').addClass('icon-chevron-up'); 
            //$(this).parent().addClass('active');
            $(this).parent().next().slideDown();              
        }
        else{
            $(this).parent().find('b').removeClass('icon-chevron-up').addClass('icon-chevron-down');
            //$('dd').slideUp();
            $(this).parent().next().slideUp();
        //$('dt').removeClass('active');            
        }
        $(this).parent().addClass('active');
        return false;            
    });
    */
   /*
    $('dd a').not('.active').mouseover(function(e){
        e.preventDefault();
        e.stopPropagation();
        $(this).animate({
            opacity: 0.8,
            paddingLeft: "20px"
        //,fontSize: "13px"
        }, 350 );
    }).mouseout(function(e){
        e.preventDefault();
        e.stopPropagation();
        $(this).animate({
            opacity: 1,
            paddingLeft: "8px"
        //,fontSize: "11px"
        }, 500 );        
    })
    */
    //tables
    $('.tabler b').tooltip({
        placement:'top'
    });
    $('.btn-action').live('click',function(){
        //$(this).button('loading');
        });
    $('.tips').tooltip({
        placement:'right'
    });  
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
//$('#dash').popover({placement:'right',title:'Dashboard',html: true, content:'Informações das últimas ocorrências do site'});
})

function popr(elm,title,msg,place) {
    $('#'+elm).popover({
        placement:place,
        title:title,
        html: true, 
        content: msg
    }); 
    $('#'+elm).popover('show');
}


function refreshTips() {
       
    $('.tips').tooltip('hide');
    $('.tips').removeData('tooltip'); 
    $('.tips').tooltip({
        placement:'right'
    });  
    $('.tips-top').tooltip('hide');
    $('.tips-top').removeData('tooltip'); 
    $('.tips-top').tooltip({
        placement:'top'
    });    
    $('.tips-left').tooltip('hide');
    $('.tips-left').removeData('tooltip'); 
    $('.tips-left').tooltip({
        placement:'left'
    });    
    $('.tips-bottom').tooltip('hide');
    $('.tips-bottom').removeData('tooltip'); 
    $('.tips-bottom').tooltip({
        placement:'bottom'
    });    
    $('.tips-right').tooltip('hide');
    $('.tips-right').removeData('tooltip'); 
    $('.tips-right').tooltip({
        placement:'right'
    });    
}
