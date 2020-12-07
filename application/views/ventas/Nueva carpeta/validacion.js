function campo_numeros(e) { 
    tecla = (document.all) ? e.keyCode : e.which; 
    if (tecla==8) return true; 
    patron = /\d/; // Solo acepta números 
    te = String.fromCharCode(tecla);
    return patron.test(te);
} 

function campo_letras(e) { 
    tecla = (document.all) ? e.keyCode : e.which; 
    if (tecla==8) return true; 
    patron = /\D/; // No acepta números 
    te = String.fromCharCode(tecla);
    return patron.test(te);
} 

function campo_num_let(e) { 
    tecla = (document.all) ? e.keyCode : e.which; 
    if (tecla==8) return true; 
    patron = /[A-Za-z0-9\s]/; // Acepta números y letras
    te = String.fromCharCode(tecla);
    return patron.test(te);
}

$(document).ready(function () {
	var emailreg = /^[a-zA-Z0-9_\.\-]+@[a-zA-Z0-9\-]+\.[a-zA-Z0-9\-\.]+$/;
        var num;
	$("#submit").click(function (){
		$(".error").remove();	
	
		if( $(".campo_nombre").val() == "" || $(".campo_nombre").val() == " Nombre ..." ){
			$(".campo_nombre").focus().after("<span class='error'>Digitar Nombre</span>");
			return false;
		}else if( $(".campo_telefono").val() == "" || $(".campo_telefono").val() == " Celular..." ){
			$(".campo_telefono").focus().after("<span class='error'>Digitar Celular</span>");
			return false;
		}else if(isNaN($(".campo_telefono").val())){
			$(".campo_telefono").focus().after("<span class='error'>Vuelva a Digitar su Numero de Celular</span>");
			return false;
		}else if( $(".campo_email").val() == "" || !emailreg.test($(".campo_email").val()) || $(".campo_email").val() == " Correo Electrónico ..."  ){
			$(".campo_email").focus().after("<span class='error'>Digitar su e-mail</span>");
			return false;
		}
	});

	$("#submit1").click(function (){
		$(".error").remove();	
	
		if( $(".campo_nombre1").val() == "" || $(".campo_nombre1").val() == " Nombre ..." ){
			$(".campo_nombre1").focus().after("<span class='error'>Digitar Nombre</span>");
			return false;
		}else if( $(".campo_telefono1").val() == "" || $(".campo_telefono1").val() == " Celular..." ){
			$(".campo_telefono1").focus().after("<span class='error'>Digitar Celular</span>");
			return false;
		}else if(isNaN($(".campo_telefono1").val())){
			$(".campo_telefono1").focus().after("<span class='error'>Vuelva a Digitar su Numero de Celular</span>");
			return false;
		}else if( $(".campo_email1").val() == "" || !emailreg.test($(".campo_email1").val()) || $(".campo_email1").val() == " Correo Electrónico ..."  ){
			$(".campo_email1").focus().after("<span class='error'>Digitar su e-mail</span>");
			return false;
		}
	});


       $(".campo_nombre, .campo_telefono, .campo_email, .campo_nombre1, .campo_telefono1, .campo_email1").keyup(function(){
		if( $(this).val() != "" ){
			$(".error").fadeOut();			
			return false;
		}		
	});

});