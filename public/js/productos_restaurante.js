$(document).ready(function(){
	$("#s_categorias_prducto").on('change',function(){
		consultar_tamanos_categoria();
	});
	$("#s_categorias_prducto").chosen();
	$("#s_tamanos_producto").chosen();
	$("#s_tamanos_producto").on('change',function(){
		remover_columnas_tamanos();
		agregar_columnas_tamano($(this).val());
	});
	$("#btn_ing_base").on('click',function(){
		agregar_fila_tabla("tb_ing_base");
	});
});

function consultar_tamanos_categoria(){
	$.ajax({
		type: 'post',
		url: url_consulta_tamanos_categoria,
		data:{categoria:$("#s_categorias_prducto").val()},
		dataType: "json",
		beforeSend: function(){
			$("#div_consulta_ajax").html('<div><img src="'+url_base_imagen+'public/img/loader_gif.gif"/></div>');
		},
		success:function(result){
			$("#div_consulta_ajax").html('');
			$('#s_tamanos_producto').find('option').remove();
			//$("#s_tamanos_producto").append('<option value="">Seleccione</option>');
			$.each(result,function(index,value){
				$("#s_tamanos_producto").append('<option value="'+value.idtamanos_productos+'">'+value.nombre_tamano+'</option>');
			});
			$("#s_tamanos_producto").trigger("chosen:updated");
		}
	});
}

function agregar_columnas_tamano(tamano){
	if(tamano != null){
		$.each(tamano,function(index,value){
			var texto_tamano = $("#s_tamanos_producto option[value='"+value+"']").text();
			//var columna_en_base = $("#tb_ing_base thead tr").find('#th_base_'+value);
			
			if(!$("#tb_ing_base #th_base_"+value).length){
				$("#tb_ing_base thead tr").append('<th class="th_tamano" id="th_base_'+value+'">Cantidad '+texto_tamano+'</th>');
				$.each($("#tb_ing_base tbody tr"),function(index_column,column){
					$("#tb_ing_base tbody tr").append('<td class="th_tamano"><input type"number" name="t_cantidad_tamano['+index_column+']['+value+']"></td>');	
				});
			}
			if(!$("#tb_ing_adicion #th_base_"+value).length){
				$("#tb_ing_adicion thead tr").append('<th class="th_tamano" cl id="th_base_'+value+'">Cantidad '+texto_tamano+'</th>');
				$("#tb_ing_adicion tbody tr").append('<td class="th_tamano"><input type"number" name="t_cantidad_tamano['+value+']"></td>');
			}
			if(!$("#tb_ing_salsa #th_base_"+value).length){
				$("#tb_ing_salsa thead tr").append('<th class="th_tamano" id="th_base_'+value+'">Cantidad '+texto_tamano+'</th>');
				$("#tb_ing_salsa tbody tr").append('<td class="th_tamano"><input type"number" name="t_cantidad_tamano['+value+']"></td>');
			}
			if(!$("#tb_ing_insumo #th_base_"+value).length){
				$("#tb_ing_insumo thead tr").append('<th class="th_tamano" id="th_base_'+value+'">Cantidad '+texto_tamano+'</th>');
				$("#tb_ing_insumo tbody tr").append('<td class="th_tamano"><input type"number" name="t_cantidad_tamano['+value+']"></td>');
			}

		});
	}
	
	
}

function agregar_fila_tabla(id_tabla){
	var rowO = $("table#"+id_tabla+" tbody tr").last();
    var rowN = rowO.clone(true);
    $("table#"+id_tabla+" tbody").append(rowN);
    var posicion = Number($(rowN).find(".s_ingrediente").attr("data-posicion"))+1;
    $("table#"+id_tabla+" #detalle tr").each(function(i, e){            
           
        $(this).find(":input").each(function(j, o){
            var index = parseInt($(this).attr("name").replace(/[^\d]/g, '').replace(/^\s+|\s+$/g,""));
            if(typeof $(this).attr("name") !== "undefined" ){
                $(this).attr("name", $(this).attr("name").replace(index, i));
                if($(this).hasClass('s_ingrediente')){
                    $(this).attr("data-posicion", $(this).attr("data-posicion").replace(index, i));
                }
            }
        });
    }); 
}

function remover_columnas_tamanos(){
	$(".th_tamano").remove();
}