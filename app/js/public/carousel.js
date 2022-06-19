		
$("#mycarousel").show();
$("#mycarousel").carouFredSel({
    circular: false,
    infinite: false,
    responsive: true,
    auto: false,
    prev: {	
        button: "#mycarousel_prev",
        key: "left"
    },
    next:{ 
        button: "#mycarousel_next",
        key: "right"
    },
    scroll:{
        items :6
    },
    items:{
        visible:6,
        width:'185'
    }
    ,
    pagination: "#mycarousel_pag"
});
