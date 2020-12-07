//===========================================================





var comandaObj = null;

var comandaFirstRead = 0;



var ultimaNotifComanda = null;





//===========================================================



function runComanda(){

    setInterval(function(){

        consultarNotificacion();

    },10000)

}



function consultarNotificacion(){

   

    $.ajax({

        url: $siteUrl + '/comanda/getNotificacionServer/',            

        async: false,

        dataType: 'json',            

        success: function (data) {



            if( ultimaNotifComanda != data[0].notificacion){

                ultimaNotifComanda = data[0].notificacion;



                if( $("#comandaSidebar").hasClass( "in" ) ){

                    getComandaData("");

                }else{

                    $("#comanda").addClass("comandaAlert");

                    getComandaData("no");

                }                                                                                                



            }



        },

        error: function (jqXHR, textStatus, errorThrown) {

            //alert(errorThrown);

        }

    });



}



function clearComanda(){

    $("#comandaL").html("");

    $("#contComBtnAsignados").html("");

    $("#contComBtnNoAsignados").html("");

}



function agregarComanda(id,nombre){

    if( comandaFirstRead != 0){

        var addObj = {"id":id,"nombre":nombre}

        comandaObj.espera.push(addObj);

    }

}



function actualizarComandaEstados(data){

    if( comandaFirstRead != 0){

        //Eliminamos de la lista de detalles

        for (var i = 0; i < comandaObj.detalles.length; i++) {

            var obj = comandaObj.detalles[i];

            for (var j = 0; j < data.detalles.length; j++) {

                obj2 = data.detalles[j];

                if( obj.factura == obj2.factura ){

                    comandaObj.detalles[i]["estado"] = obj2.estado;

                }

            }



        }                                   

    }

}



function cambiarNombreComanda(id,nombre){

    if( comandaFirstRead != 0){

        //Eliminamos de la lista de detalles

        for (var i = 0; i < comandaObj.detalles.length; i++) {

            var obj = comandaObj.detalles[i];

            if( obj.factura == id ){                                                    

                comandaObj.detalles[i]["nombre"] = nombre;

            }

        }

        //Eliminamos de la lista de facturas en espera

        for (var i = 0; i < comandaObj.espera.length; i++) {

            var obj = comandaObj.espera[i];

            if( obj.id == id ){                                                    

                comandaObj.espera[i]["nombre"] = nombre;

            }

        }



    }

}



function eliminarComanda(id){

    if( comandaFirstRead != 0){

        //Eliminamos de la lista de detalles

        for (var i = 0; i < comandaObj.detalles.length; i++) {

            var obj = comandaObj.detalles[i];

            if( obj.factura == id ){                                                    

                comandaObj.detalles.splice( i, 1);

            }

        }

        //Eliminamos de la lista de facturas en espera

        for (var i = 0; i < comandaObj.espera.length; i++) {

            var obj = comandaObj.espera[i];

            if( obj.id == id ){                                                    

                comandaObj.espera.splice( i, 1);

            }

        }



    }

}



function renderComandaAsign(idComan,tipo, domItem){

    var userElment = $(".comUserActive");





    if( userElment.length > 0 ){



        var idUser = $(userElment).attr("id");



        if( tipo == "delete" ){                                            

            for (var i = 0; i < comandaObj.detalles.length; i++) {

                var obj = comandaObj.detalles[i];

                if( obj.factura == idComan ){                                                    

                    comandaObj.detalles.splice( i, 1);



                    var addObj = {"id":obj.factura,"nombre":obj.nombre}

                    comandaObj.espera.push(addObj);



                    $(domItem).parent().detach();



                    var html = "<div class='btnCom'> <div class='btn 2 btnComandaFact' onclick='renderComandaAsign("+ obj.factura +", \"add\",this)'>"+ obj.nombre +"</div> </div>";

                    $("#contComBtnNoAsignados").append( html );



                    var cantidad = $(userElment).find(".cantidadComandaAsig").html();

                    $(userElment).find(".cantidadComandaAsig").html( parseInt(cantidad)-1 );





                }

            }

        }



        if( tipo == "add" ){                                            

            for (var i = 0; i < comandaObj.espera.length; i++) {

                var obj = comandaObj.espera[i];

                if( obj.id == idComan ){                                                    

                    comandaObj.espera.splice( i, 1);



                    var addObj = {"estado":"0","factura":obj.id,"id":idUser,"nombre":obj.nombre}

                    comandaObj.detalles.push(addObj);



                    $(domItem).parent().detach();



                    var html = "<div class='btnCom'> <div class='btn 2 btnComandaFact' onclick='renderComandaAsign("+ obj.id +", \"delete\",this)'>"+ obj.nombre +"</div> </div>";

                    $("#contComBtnAsignados").append( html );



                    var cantidad = $(userElment).find(".cantidadComandaAsig").html();

                    $(userElment).find(".cantidadComandaAsig").html( parseInt(cantidad)+1 );



                }

            }

        }



    }else{

        alert("Seleccione un usuario")

    }



}



function renderComandaUserClick(idUser,element){



    //-----------------------

    //  Facturas Asignadas

    //-----------------------

    if( $(element).hasClass( "comUserActive" ) ){



        $(".comandaUsu").removeClass("comUserActive");                                        



        $(element).removeClass("comUserActive");

        $("#contComBtnAsignados").html( "" );



    }else{



        $(".comandaUsu").removeClass("comUserActive");



        $(element).addClass("comUserActive");



        $("#contComBtnAsignados").html( "" );

        var html = "";

        for (var i = 0; i < comandaObj.detalles.length; i++) {

            var tmpObj = comandaObj.detalles[i];

            if( tmpObj.id == idUser ){



                var comandaOk = "";

                var onclick = "onclick='renderComandaAsign("+ tmpObj.factura +", \"delete\",this)'";

                if( tmpObj.estado == 3 ){

                    comandaOk = "style='background-color:#ffa02f !important; cursor:default'";

                    onclick = "";

                }



                html += "<div class='btnCom'> <div class='btn 2 btnComandaFact' "+comandaOk+" "+onclick+">"+ tmpObj.nombre +"</div> </div>";

            }

        }                                    

        $("#contComBtnAsignados").html( html );

    }

}



function renderizarComanda(){



    $("#contComBtnAsignados").html( "" );



    //-----------------------

    //  Usuarios

    //-----------------------

    $("#comandaL").html( "" );

    var html = "";

    for (var i = 0; i < comandaObj.usuarios.length; i++) {

        var tmpObj = comandaObj.usuarios[i];



        var cantidadCom = 0;

        var comandaOk = "";

        for( var j = 0; j < comandaObj.detalles.length; j++) {

            var tmpObjDetalle = comandaObj.detalles[j];



            if( tmpObjDetalle.estado == 3 && tmpObjDetalle.id == tmpObj.id ){

                comandaOk = "style='display:block;'";

            }                                            



            if( tmpObj.id == tmpObjDetalle.id ){

                cantidadCom++;

            }

        }                                         



        html += "<div class='comandaUsu' id='"+ tmpObj.id +"' onclick='renderComandaUserClick("+ tmpObj.id +",this)'> <span id='nComanda"+ tmpObj.id +"' class='cantidadComandaAsig'>" + cantidadCom + "</span>"+ tmpObj.nombre +" <div class='comandaOk' "+comandaOk+" ></div></div>";

    }





    $("#comandaL").html( html );





    //-----------------------

    //  Facturas no asignadas

    //-----------------------

    $("#contComBtnNoAsignados").html( "" );

    var html = "";

    for (var i = 0; i < comandaObj.espera.length; i++) {

        var tmpObj = comandaObj.espera[i];

        html += "<div class='btnCom'> <div class='btn 2 btnComandaFact' onclick='renderComandaAsign("+ tmpObj.id +",\"add\",this)'>"+ tmpObj.nombre +"</div> </div>";                                        

    }                                    

    $("#contComBtnNoAsignados").html( html );



}



function enviarComanda(){





    $.ajax({

        url: $siteUrl + '/comanda/enviarComanda/',            

        data: { "detalle": comandaObj.detalles },

        type: "POST",

        dataType: 'text',            

        success: function (data) {

            cerrarComanda();

        },

        error: function (jqXHR, textStatus, errorThrown) {

            alert(errorThrown);

        }

    });



}



function getComandaData(render){

    

    $.ajax({

        url: $siteUrl + '/comanda/getData/',            

        dataType: 'json',            

        success: function (data) {



            if( comandaFirstRead == 0){

                comandaObj = data;    

                comandaFirstRead = 1;

                if( render != "no" ){

                    renderizarComanda();    

                }

            }else{

                actualizarComandaEstados(data);

                

                // Actualizamos nuevos usuarios conectados

                comandaObj.usuarios = data.usuarios

                

                if( render != "no" ){

                    renderizarComanda();    

                }



            }



        },

        error: function (jqXHR, textStatus, errorThrown) {

            alert(errorThrown);

        }

    });



}



function cerrarComanda(){

    $('#comandaSidebar').modal('hide');

}   



function cerrarImprimirComanda(){

    cerrarComanda();

    setTimeout(function(){

        imprimirComanda();

    },600)



}





