$(function(){
    $('head').append('<link rel="stylesheet" href="js/jquery/fancy/jquery.fancybox.css"  media="screen" type="text/css"/>');
    $('head').append('<script src="js/jquery/fancy/jquery.fancybox.js" type="text/javascript"></script>');
    $(".various").fancybox({
        maxWidth	: 580,
        maxHeight	: 600,
        fitToView	: false,
        width	: '650',
        height	: '500',
        autoSize	: false,
        closeClick	: false,
        openEffect	: 'elastic',
        closeEffect	: 'none'
    });        
})