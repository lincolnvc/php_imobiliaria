var baseUri = $('base').attr('href').replace('/app/','');
function mapInit(lat,lon) {          
    initMap(lat,lon); 
    /*LAYOUT ADJUST*/
    $('#map').height(  $(window).height() - ( $('#top_wrap').height()   ) - 20);
    if($(window).height() <= 900){
        $('#map').height(450)    
    }
    $(window).resize(function() {
        $('#map').height(  $(window).height() - ( $('#top_wrap').height()   ) - 20);
        if($(window).height() <= 900){
            $('#map').height(450)    
        }        
        if(map){
            map.setCenter(lat, lng);
        }
    })
    /*END LAYOUT ADJUST*/
    /*ZOOM CONTROL*/
    var zoomAtual = 8;
    $('#zoomIn').live('click', function() {
        zoomAtual++;
        if(zoomAtual<21){
            var posZoom = zoomAtual*10;
            $("#zoom_slider").animate({
                marginLeft: posZoom
            }, 150);
        }else{
            zoomAtual = 21;
        }
        map.zoomIn(1);
    });
    $('#zoomOut').live('click', function() {
        zoomAtual--;
        if(zoomAtual>0){
            var posZoom = zoomAtual*10;
            $("#zoom_slider").animate({
                marginLeft: posZoom
            }, 150);
        }else{
            zoomAtual = 0;
        }
        map.zoomOut(1);
    });    
    /*END ZOOM CONTROL*/
    getMarkers();
}

function getMarkers() {    
    var url = baseUri + '/mapa/pontos/'
    $.post(url,{},function(data){
        if(data != 'null'){
            var data = $.parseJSON(data);
            $.each(data.rs,function(k,v){
                addMarker(v)
            })
            setTimeout(function(){
                markerClusterer = new MarkerClusterer(map.map, mymarkers,{
                    maxZoom: 12,
                    gridSize: 30
                });
            },1500)            
        }
    })
}
