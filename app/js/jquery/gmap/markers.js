var baseUri = $('base').attr('href').replace('/app/','');
var markers;
var markerx;
var mymarkers = [];
var address;
var lat;
var lng;
var routes;
var routesLL = [];
var statLoop = 1;
var posY;
var posX;
var map;
var cid = 0;
function initMap(lat,lng)
{
    //lat = -23.5385287; //altere a latitude e logintude conforme sua cidade
    //lng = -46.3108648;
    var myStyles =[{
        featureType: "poi.business",
        elementType: "labels",
        stylers: [
        {
            visibility: "off"
        }]
    }];
    map = new GMaps({
        div: 'map',
        lat: lat,
        lng: lng,
        scrollwheel: false,
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        streetViewControl: false,
        disableDefaultUI: true,
        styles: myStyles,
        zoom: 8
    })
    map.setCenter(lat, lng);        
}


function addMarker(p)
{
    var htmlr = '<div class="infowindow">';
                
    p.title =  p.tipo_title + ' para ' + p.item_finalidade + ' em '  + p.categoria_title;
    htmlr += '<div>'; 
    htmlr += '<h1>'+ p.tipo_title + ' para ' + p.item_finalidade + ' em '  + p.categoria_title + '</h1>';
    htmlr += '</div>';
               
    htmlr += '<div class="mywell">';

    htmlr += '<div class="foto">';
    htmlr += ' <img src="'+baseUri+'/thumb/'+p.foto_url+'/150/140/crop/" />';
    htmlr += '</div>';

    htmlr += '<div class="desc" >';
    htmlr += '<div class="text-left">';
    htmlr += '<p>Código: <b>'+ p.item_ref +'</b></p>';
    htmlr += '<p>Bairro: <b>'+ p.sub_title +'</b></p>';
    htmlr += '<p>Dormitórios: <b>'+ p.item_dorm +'</b></p>';
    htmlr += '<p>Vagas: <b>'+ p.item_vaga +'</b> ';
    htmlr += 'WC: <b>'+ p.item_wc +'</b></p>';
    htmlr += '<p>Área Total: <b>'+ p.item_area +'</b></p>';
    htmlr += '<p><b>R$ '+ p.item_preco +'</b></p>';
    htmlr += '<br />';
    htmlr += '<input type="hidden" id="pic_'+p.item_id+'" value="'+baseUri+'/thumb/'+p.foto_url+'/150/140/crop/">';
    htmlr += '<p class="text-center">';
    htmlr += '<a target="_blank" href="'+baseUri+'/imovel/'+p.categoria_url+'/'+p.sub_url+'/'+p.item_id+'/" class="btn btn-xs btn-primary">Ver Detalhes</a>&nbsp;';
    //htmlr += '<button  id="'+p.title+'" name="'+baseUri+'/thumb/'+p.foto_url+'/150/140/crop/" onclick="fbshare(this.id,this.name,'+p.item_id+')" class="btn btn-xs btn-primary">Compartilhar</button>';
    htmlr += '</p>';
    htmlr += '</div>';
    htmlr += '</div>';
    
    htmlr += '</div>';
                
    //htmlr += '<p style="display:inline-block; border:0px solid blue; margin:0px !important; padding:0px !important;">'
    //htmlr += '<button class="btn btn-xs btn-primary" onclick="fbshare(' + p_id + ')"><b class="icon-share icon-white"></b> compartilhar</button> &nbsp;'
    //htmlr += '</p>'
    
    htmlr += '</div>'

    var marker = map.addMarker({
        lat: p.item_lat,
        lng: p.item_lon,
        icon: "images/icons/marker.png",
        id: p.item_id,
        title: p.title,
        content: htmlr,                    
        click: function(){
            map.setCenter(p.item_lat, p.item_lon);
            var zoomAtual = 11;
            var posZoom = zoomAtual*10;
            if($("#zoom_slider")){
                $("#zoom_slider").animate({
                    marginLeft: posZoom
                }, 150);            
            }
        },                    
        infoWindow: {
            content: htmlr
        },
        close: function(){
        //map.cleanRoute();
        }                   
    }); 
    mymarkers.push(marker);

  
}
function getAddr(address) {
    GMaps.geocode({
        address: address,
        callback: function(results, status) {            
            if (status == 'OK') {
                var latlng = results[0].geometry.location;
                lat = latlng.lat();
                lng = latlng.lng();   
                map.setCenter(lat, lng);
                map.setZoom(12);
            }
        }
    })
}
function closeAllInfoWindow() {
    if(map.markers){
        markers  = mymarkers;
        $.each(markers,function(i,item){
            if(mymarkers[i].infoWindow){
                mymarkers[i].infoWindow.close();
            }
        })    
    }    
}

function panTo(id)
{
    setTimeout(function(){
        if(map.markers){
            markers  = mymarkers;
            $.each(markers,function(i,item){
                mid = markers[i].id
                if(markers[i].id == id){
                    var m = mymarkers[i];
                    //var n = m.getPosition();
                    map.setZoom(13); 
                    //map.panTo(m.getPosition()); 
                    map.setCenter(m.position.pb,m.position.qb); 
                    //console.log(m.position.qb,m.position.pb)
                    mymarkers[i].infoWindow.open(map, mymarkers[i])
                }
            })    
        }
    },500)
}


function fbshare(title,pic,id) 
{
    var obj = {
        method: 'feed',
        link: baseUri+'/mapa/'+id+'/',
        picture: pic,
        name: window.title,
        caption: title,
        description: 'Ótima oportunidade'
    };
    function callback(response) {
        if(response){
            var postid = response['post_id'];
        }
    }
    FB.ui(obj, callback);
}