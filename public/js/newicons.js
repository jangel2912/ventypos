//Para cambiar las imagenes de los iconos de acciones en las tablas 
$(document).on('mouseover', '.acciones', function () {   
    img = $(this).find('img.iconacciones').attr('data-cambiar');
    //console.log("aacambio=" + img);
    $(this).find('img.iconacciones').attr("src", img);
});
$(document).on('mouseout', '.acciones', function () {
    img = $(this).find('img.iconacciones').attr('data-original');
    //console.log("aaoriginal=" + img);
    $(this).find('img.iconacciones').attr("src", img);
});

$(document).tooltip({ 
    selector: "[data-tooltip=true]", container: "body" 
})

$(document).on('click', '#tipo-busqueda', function () {    
    $("#tipo-busqueda li img.barraventa").each(function () {
        img = $(this).attr('data-original');      
        $(this).attr("src", img);        
    });

    imgactive = $(this).find('.active');
    console.log(imgactive);
    img2=imgactive.find('img.barraventa').attr('data-cambiar');
    console.log(img2);
    imgactive.find('img.barraventa').attr("src", img2);
    

});
/*
$(document).on('click', '#tipo-busqueda li', function () {     
   
    img = $(this).find('img.barraventa').attr('data-cambiar');    
    $(this).find('img.barraventa').attr("src", img);

});*/



































/*******jose */