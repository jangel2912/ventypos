<style>
    #cargando {
        background-color: #fff;
        position:absolute;
        opacity: 0.2;
        filter: alpha(opacity=20); /* For IE8 and earlier */
    }
</style>
<div class="page-header">

    <div class="icon">

        <span class="ico-box"></span>

    </div>

    <h1><?php echo custom_lang("Productos", "Productos");?><small><?php echo $this->config->item('site_title');?></small></h1>

</div>

<?php echo form_open_multipart("productos/atributos", array("id" =>"validate"));?>
    <!-- Bootrstrap modal form -->
    <div id="fModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h3 id="myModalLabel">Cantidad en almacenes</h3>
        </div>
        <div style="padding: 5% 10%;">
            <table width="100%">
                <thead>
                    <tr>
                        <th>Almacen</th>
                        <th>Cantidad</th>
                    </tr>
                </thead>
                <tbody id="list_almacenes">
                    <?php foreach ($data['almacenes'] as $key => $value) { ?>
                    <tr>
                        <td><?php echo $value->nombre ?></td>
                        <td align="right"><input type="text" name="cantidad" data-almacen="<?php echo $value->id ?>" value="0"></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>                   
        <div class="modal-footer">
            <button type="button" onclick="guardar_almacen(this)" class="btn btn-success">Guardar</button> 
            <button class="btn btn-warning" data-dismiss="modal" aria-hidden="true">Cancelar</button>            
        </div>
    </div>
    <div class="row-fluid">
        <div class="span12">
            <div class="input-append file">
                <input type="file" name="imagen_principal" style="display: none"/>
                <input type="text"/>
                <button type="button" class="btn"><i class="icon-folder-open icon-white"></i></button>
            </div>
        </div>
    </div>

    <div class="row-fluid">
        <div class="span3">
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre">
            <br><br>
        </div>
        <div class="span4">
            <label for="descripcion">Descripción:</label>
            <input type="text" id="descripcion" name="descripcion">
        </div>
        <div class="span2">
            <label for="impuesto">Impuesto:</label>
            <input type="text" id="impuesto" name="impuesto">
        </div>
        <div class="span3">
            <label for="categoria_atributos">Categoria/Atributos</label>
            <select id="categoria_atributos" name="categoria_atributos" class="select" style="width: 100%;">
                <option>Escoge una categoria...</option>
                <?php foreach ($data['atributos_categoria']['aaData'] as $key => $value) { ?>
                    <option value="<?php echo $value[1] ?>"><?php echo $value[0] ?></option>
                <?php } ?>
            </select>
        </div>
    </div>

    <div class="row-fluid">
        <div class="span3 group-select2">
            <label for="marca_principal">Marca: &nbsp; ( Activar <input type="checkbox" data-hide='["select2#marca_principal", "clear.group-marca"]' class="active-select" checked> )</label>
            <select id="marca_principal" name="marca_principal" class="select" style="width: 100%;">
                <option>Escoge una marca...</option>
                <?php foreach ($data['marcas'] as $value) { ?>
                    <option value="<?php echo $value->id ?>"><?php echo $value->nombre ?></option>
                <?php } ?>
            </select>
            <br><br>
        </div>
        <div class="span3 group-select2">
            <label for="proveedor_principal">Proveedor: &nbsp; ( Activar <input type="checkbox" data-hide='["select2#proveedor_principal", "clear.group-proveedor"]' class="active-select" checked> )</label>
            <select id="proveedor_principal" name="proveedor_principal" class="select" style="width: 100%;">
                <option>Escoge un tipo...</option>
                <?php foreach ($data['proveedores'] as $value) { ?>
                    <option value="<?php echo $value->id_proveedor ?>"><?php echo $value->nombre_comercial ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="span3 group-select2">
            <label for="linea">Lineas: &nbsp; ( Activar <input type="checkbox" data-hide='["select2#linea"]' class="active-select" checked> )</label>
            <select id="linea" name="linea" class="select" style="width: 100%;">
                <option>Escoge un tipo...</option>
                <?php foreach ($data['lineas'] as $value) { ?>
                    <option value="<?php echo $value->id ?>"><?php echo $value->nombre ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="span3 group-select2">
            <label for="tipo_material">Tipos de materiales: &nbsp; ( Activar <input type="checkbox" data-hide='["select2#tipo_material"]' class="active-select" checked> )</label>
            <select id="tipo_material" name="tipo_material" class="select" style="width: 100%;">
                <option>Escoge un tipo...</option>
                <?php foreach ($data['tipos_materiales'] as $value) { ?>
                    <option value="<?php echo $value->id ?>"><?php echo $value->nombre ?></option>
                <?php } ?>
            </select>
        </div>
    </div>

    <div class="row-fluid">
        <div class="span12">
            <div class="well">
            	<input type="hidden" id="fila_seleccionada" value="0">
                <table width="100%" id="detalle_producto">
                    <thead>
                        <tr>
                            <th class="group-marca">Marca</th>
                            <th class="group-proveedor">Proveedor</th>
                            <th>Código</th>
                            <th>Precio/Compra</th>
                            <th>Precio/Venta</th>
                            <th>Imágen</th>
                            <th>Activo</th>
                            <th>Tienda</th>
                            <th>Almacenes</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr onclick="seleccion_fila(this)">
                            <td class="group-marca">
					            <select id="marca" name="marca[]" class="select" style="width: 100%;">
					                <?php foreach ($data['marcas'] as $value) { ?>
					                    <option value="<?php echo $value->id ?>"><?php echo $value->nombre ?></option>
					                <?php } ?>
					            </select>
                            </td>
                            <td class="group-proveedor">
					            <select id="proveedor" name="proveedor[]" class="select" style="width: 100%;">
					                <?php foreach ($data['proveedores'] as $value) { ?>
					                    <option value="<?php echo $value->id_proveedor ?>"><?php echo $value->nombre_comercial ?></option>
					                <?php } ?>
					            </select>
                            </td>
                            <td><input type="text" name="codigo[]"></td>
                            <td><input type="text" name="precio_compra[]"></td>
                            <td><input type="text" name="precio_venta[]"></td>
                            <td align="center">
                                <div class="input-append file">
                                    <input type="file" name="imagenes[]" style="display: none"/>
                                    <input type="text" style="display: none"/>
                                    <button type="button" class="btn"><i class="icon-folder-open icon-white"></i></button>
                                </div>
                            </td>
                            <td align="center"><input type="checkbox" name="activo[]" checked="checked" value="1"/></td>
                            <td align="center"><input type="checkbox" name="tienda[]" checked="checked" value="1"/></td>
                            <td align="center"><a href="#fModal" class="btn almacenes" role="button" data-toggle="modal">Seleccionar</a></td>
                            <td align="center"><a href="javascript: void(0)" class="btn btn-success agregar_datos" onclick="agregar_datos(this)">Agregar</a></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="row-fluid">
        <div class="span12">
            <hr>
            <button type="submit" class="btn btn-success">Guardar</button>
        </div>
    </div>
</form>

<script>
	localStorage.setItem('origin_thead', '');
	localStorage.setItem('origin_tbody', '');
	localStorage.setItem('active_data', '{}');

    $(function(){
    	$('.active-select').click(function (e) {
    		var selector_id, data = JSON.parse(localStorage.active_data), active_data = [],
    			checked = ( $(this).attr('checked') == 'checked' ) ? 'block' : 'none',
    			data_hide = JSON.parse($(this).attr('data-hide'));
    		
    		for (var i in data_hide) {
    			if( data_hide[i].search("select2") > -1 ) {
    				selector_id = data_hide[i].split('select2');
    				$(selector_id[1]).closest('.group-select2').find('.select2-container').css('display', checked);
    			} else if( data_hide[i].search("clear") > -1 ) {
    				selector_id = data_hide[i].split('clear');

    				if(checked == 'none') {
	    				$(selector_id[1]).each(function (index, elem) {
	    					active_data.push($(elem).html());
	    				});
	    				$(selector_id[1]).html('');

	    				data[selector_id[1]] = active_data;

	    				localStorage.active_data = JSON.stringify(data);
    				} else {
    					active_data = JSON.parse(localStorage.active_data);

    					$(selector_id[1]).each(function (index, elem) {
    						$(elem).html(active_data[selector_id[1]][index]);
    					});
    				}

    				$(selector_id[1]).closest('.group-select2').find('.select2-container').css('display', checked);
    			} else $(data_hide[i]).css('display', checked);
    		};
    	});
    	$('#marca_principal')
    		.on('change', function(e){
    			var valor = e.val;
    			$("#marca").select2("val", valor);
    		});
    	$('#proveedor_principal')
    		.on('change', function(e){
    			var valor = e.val;
    			$("#proveedor").select2("val", valor);
    		});
        $('#categoria_atributos')
            .on('change', function(e){
                var valor = e.val;

                $("#categoria_atributos").prop("disabled", true);
                $("#categoria_atributos").parent().find('.select2-container.select').hide();
                $("#categoria_atributos").parent().append('<span><a href="javascript: void(0)" onclick="resetear(this)">'+e.target.selectedOptions[0].innerText+' &nbsp; <b class="text-error">&lt;Quitar&gt;</b></a></span>');

                if ( localStorage.origin_thead ) $('#detalle_producto > thead > tr').html(localStorage.origin_thead); else localStorage.setItem('origin_thead', $('#detalle_producto > thead > tr:first-child').html());
                if ( localStorage.origin_tbody ) $('#detalle_producto > tbody > tr').html(localStorage.origin_tbody); else localStorage.setItem('origin_tbody', $('#detalle_producto > tbody > tr:first-child').html());

                $.ajax({
                    url: "<?php echo site_url("atributo_categorias/get_ajax_atributos_categorias");?>/"+valor+"/categoria_id",
                    beforeSend: function() {
                        $('#detalle_producto').parent().show();
                        $('#detalle_producto').parent().prepend($('<div id="cargando" style="width: '+$('#detalle_producto').width()+'px; height: '+$('#detalle_producto').height()+'px; " align="center"></div>').append($('<img>').attr('src', 'data:image/gif;base64,R0lGODlhQABAAKUAAAQCBISChMTCxERCRKSipOTi5GRmZCQiJJSSlNTS1LSytPTy9DQyNFRSVHR2dIyKjMzKzExKTKyqrOzq7GxubCwqLJyanNza3Ly6vPz6/Dw6PBwaHFxeXHx'+'+'+'fISGhMTGxERGRKSmpOTm5GxqbCQmJJSWlNTW1LS2tPT29DQ2NFRWVHx6fIyOjMzOzExOTKyurOzu7HRydCwuLJyenNze3Ly'+'+'+'vPz'+'+'+'/Dw'+'+'+'PBweHP///wAAAAAAAAAAAAAAAAAAAAAAACH/C05FVFNDQVBFMi4wAwEAAAAh'+'+'+'QQICQAAACwAAAAAQABAAAAG/sCccEgsGo9CjGEkQDqf0Kjz44q4XAmpdstlVasWrnh8fHwjJbK6eOlQLAun2Yp2wmIaTnZ9RFEMgAhyZ2lIBgwyDCAZfEYmgJB7Rg90LoVGNQyaiCaNRTAjHAaiAYxGHoRHGRGImikwnkUEkKMYZalGFpsyKQ'+'+'+'xnyO0MXFFc1WXQxMavJoDsL9EJ5CiBJO4RCuavDIv0EUZK6KQBcWVyDkJiCmaKjbeRRC0Br5ElF/nHLspH'+'+'+''+'+'+'T8hD1zBFRoG2dg0YZChBDUgCQuBWmctizcmkBiE0MUpBzIgICCic0PHQIoOAjklnTbAmR4MFCiQcKhiBQx0sQEhEUNmyQIalI/okAHYJ6'+'+'+'ODOyIJg4ko9QeECIwiT3xAc0KkTBJIAK4ICDYDgwhElBlZAiOgkw4kIvLoZOaGBKlUSJ7VmzdohxIRvKySQjYLiAQcjF1S4dfv3yIIZWEfKDXCC7N4tC3PAWDF4AwCdKp4hMcFCbtCRDyAU9ZTBAonBAHBsSFFDSgYBQBV/7mAB6hoYNyrrJFFitJQFEhZnPfFrhNvLGyhoFlPAAt0OCGzzgSHDrYtOjRI8GAkQ2gyd3KBlwIDAtycUKTxIj/WYdL/38OOTKfBBgP37'+'+'+'O1j78dCnn8DMbRmQgkuEViCgQha0MR7HADg4IMQQjhDCC5VaKCFLnXXzwgR/nb4oAwUXiiihRq'+'+'+'Y4CHHvI04ooKwtcgihHOkEMB'+'+'+'dWoH3z9/UcLBSrJ5'+'+'+'OPQOZgHnv9ZGBCDUPykUENFySpRgEYKKDARr/QIGUNIngCgwAKvHCCAjW0R8Z4L0ipwAfLcYFCC2ZKWeZ'+'+'+'jbTwZZdzJrDeEzZcMKeXXkopgJNcLElnl3SeQIMWIkTZppmGQmMDDYrOaWYNd5X1gZl8nuClCY6pQRYKCWhKZ5lSeoTEpYOa'+'+'+'cF6NZgAqCotLEgEDB9IOmgLSNjqJZZHWAkmlXhCKeWhRohQQ6ov9FjEsYUSa4RZbSL5hKCkYtBeBnoOKusnX56QgJjouCmlV0LQAIEJ2Qm04KwJmJ4AZxFrdpsmERnAcOcQMKQqrRBsmikJmXOeEJkRKMDwqhSo0pnlEGyW'+'+'+'QKcNOypQInvidAuP0T0'+'+'+'2VPOQjQp5SVvmdDpFKm2W'+'+'+'ZHOc76r79XCDuw0ZorADHOfRrprPeoBCwAo3FTCjM8H7ZZ8/vnNwlzgzzLCXNOZgg9MbvoMAomEic7O0RI7cLLhkTjHrCwkYk8DHTOVj88bxKSkpxxuKSnQOqyR48xgQ1nNDCvTVLqem7RKAAwQloBumzv4KTkcCofBceRQFthqz4FjTUwOs7QQAAIfkECAkAAAAsAAAAAEAAQACFBAIEhIKExMLEREJE5OLkpKKkZGJkJCIk1NLUVFJU9PL0tLK0FBIUlJKUdHJ0NDI0zMrMTEpM7OrsrKqs3NrcXFpc/Pr8vLq8HBocnJqcfHp8DA4MjIqMbGpsPD48BAYEhIaExMbEREZE5ObkpKakZGZkLCos1NbUVFZU9Pb0tLa0FBYUlJaUdHZ0NDY0zM7MTE5M7O7srK6s3N7cXF5c/P78vL68HB4cnJ6cfH58////AAAAAAAAAAAAAAAAAAAABv5AnXBILBqPwpAmB0E6n9CoE2EoWSnSrHaLK1UNk614fMR9SwWyukhggSYpZ8brJTljAZQDuz6mQDmBdkhzX4NHGhGKNBZ9RjM5ARoBOXxGhXVIAhEwMIqWjkJ4S4ENNUeYBodEFgaenDAKoUULgZI5IWVnaUYknLAss0UKlLYgskVmdLxEMQmenhUxwkUCkaQql3SqRhy/ntnUrBy2kSNFqas6J98RHafiRCfFgcxC6UUO7S/xRjiSt04QUVbF3gVonAI4sjAijpMRtyJxgKcDn5AUFRAmIPBkBAKHR0YUyJBBAEgjtSIKGHKBBYmRNoYUaIfDiYQcLh6IEHhkAv6LnxlwIKBIRAGgSDlYNHKSosQ3GieHWMDh4YHVBzQIkWSRgSsJjkZCSGqAYOmTGhcMcLpwxAaMq1c9IFHBtW7XDCqQSW2gwqyUFCwcPCoBF24LJCno3rWbIYTfqFpOGs1Z2KqDaU5mkFi8uACohROqVn4AI1eUGi9wcK4rw6'+'+'+'aGChGP/BAAvITBTZWlxSmobKLHJjHSJCxmITrNQpEwC3Bsw'+'+'+'FAlw/OyJhFUa4WRYgkCAaKgUMHLYdccfer7z582QkIFjPvj37GeYztHAwvz79'+'+'+'zlWzlggQ4UM/v4B'+'+'+'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'+'+'+'C3tAoYGrVuZ9pX9n/fv4KVISEC'+'+'+'vPnyxsM1IIGAvfv28Clci0GYsGfvD1pE0M9/v/8WLdSQXH2wzPQPCf0l'+'+'+'F8EL2RAYIHfIbiggvq9QB89KQyjYYaA/swWzwoUThiBKCGUwEIJKJ6YooklpCdNA/C9JyMF94Vn44045rBdK901NN2OfVzQAgU9kkGAgw1Y5ssIDjjQAk6NuIFVkWKcEIEDODQpgj9kvEaYgY14gKUKTeKggge5ZTFDBaNhBaQYCyjQ5Jhz2jAClZEhCYtvLrIxwwg2zEmnAwrcA9ZghKVAlzhrRDOECw'+'+'+'QaWaZTTKQZhGI7gkXNiW8iURwERhRwgSSkjkmBla5VcOlQ1xYg5JPXMCBChtswNAsLZRp6gCjANVnDsC4'+'+'+'cQJtNa6AQ6IXQBDoGPKgMRIyrEAWQ4lQCjEChZM8IIFJAzhgbG10hARDWSCUKMR0g5Nm4NEGFI0hAUAxAuACEMsgAO4G/wqRAjSNoJoMASZIO'+'+'+'8RIxQKwC1OhvePBgaOoTA8tJLBAj4ivLdDEiSw6oMA0s8BAv42oAnG1zRA6YQEMfr8RAiHHzDBjB4FywkKdTFccR6vfzyBh2w2sorkDBxRMoEG7ECvijE8wnNFhtxs8qB3bsBADfc4HMfjwR9dQ5Er0xECrXu7GEnf1TjsNMdO9GCsR2oS4YEvPWLBLw4IxGCDDeAMHaOOdANNd9jPA3AC4CPwcHAUBa'+'+'+'BQlZxhxPEAAh'+'+'+'QQICQAAACwAAAAAQABAAIUEAgSEgoTEwsREQkSkoqTk4uRkYmQkIiSUkpTU0tS0srT08vR0cnQ0MjRUVlQUEhSMiozMysxMSkysqqzs6uxsamwsKiycmpzc2ty8urz8'+'+'+'vx8enw8OjwMCgxcXlyEhoTExsRERkSkpqTk5uRkZmQkJiSUlpTU1tS0trT09vR0dnQ0NjRcWlwcGhyMjozMzsxMTkysrqzs7uxsbmwsLiycnpzc3ty8vrz8/vx8fnw8PjwMDgz///8AAAAAAAAAAAAG/kCecEgsGo/Cwu02Qjqf0KhzpKgqZNKsdpuIVWOnrXh8fH1RCbK6KBMoXhqnWYFSpJGpzCVGWSM1dV4RcgpeYE4KJhcmBDh'+'+'+'RhRWVU1ldFV3RjaKm32PRCkxKKEKN0hmhmFGGgSLizUpnkVzhSgYlXVoRxGbiyCxRSl1Vihxsl92Rik1vK'+'+'+'/RRi0l0ZzdZhDGbwmqc5DODe0XgvGqEUjrYoijtxEVJK'+'+'+'RGbC1jwx5xcF60Yg4CiUQqeFtvE4YQ/FIw0yYDlZIIlUMR7x6GBaxesCloUFHhqRgSuBwiNdRFWxJcRGhAQvXtgYAsLeuyMLYuTIAcFfkW/DbKgrAshS/owbO49oEHGOgMYhGgR8yBFg5gUkwkaR6lTERiEmUnBgIKBI4JATLmaKDfABSQQvDRWA'+'+'+'DjkxomgUZLGgFSjqd0NOTZMwLMP3LAEGo9q0RhM7Ey8ATaIYGtkBE6/dPD9wgECAlOmG5puQEASilYUwoRVEQCXTAoEhg1DIL0lBcBQoV78kpm4dgAF4sjI2CcM6K8UlsVesKmmQCDijyIwhTDPj4YTvp1pcCFAsKfSnqzn2869exQKCcKLHy9'+'+'+'5fYMBNKrX59ehC2rsP3GR7a9hgES9/Pj348fxGNLVYgWEHci7KffgfgFkIF8IsEGmlfOiIDghCQEYENokghIXz72/hnooX6'+'+'+'jHACSiid8EKJJ74gWT7opVeDCATUEKOLE0Do3Y04eofdQfk8F90vGhgggnZkFLCgAivGogAMMBjw0hptRHMDkWKk4AEMEkgAwwbmjeFaQ17YSIYJWDKZJQwm5JbFZ2dIxdojC1Sg5ZxYSuCAAlQOMcKRaaHQ5XUKOFCmlmVWMIgTGvTFTygnaKRmlUQsQCadZ0rwwaNE9CVVFWvxZIEKjEEBnAeZbEDnoBBAJUkoWBnhAgAAHJBOFDgoIEEDDcylDwmUwsACEjiF4icSMjwAK6wWUJkCDLjSsEIIofKgwQQO0MkAsR0RScKxsJYlhAkVbLABCQQMgQCu9Ogi4IQMCGDpQWeqJPREAtzG'+'+'+'lEFD7SgrwpDpBBCA87SwMGfkJwQ7RYD1LvXEBXomy'+'+'+'/QyjgLLoQdxdDvQMUQYK'+'+'+'LTxQsRAeNLtCA0'+'+'+'uo8EB9cpGxAwd71tEAhM760Cea3xQLwlGNJyvx0bk0MDIuC68zggdcLvDRURs3PLHQoygA8AAh4BpLAbU68IROrtsxAU/o'+'+'+'utMzbUW4J1Sj98xLIAj7wC0rHcUK9BWLfcAtNtidyAmGossMOxEjhR9tx'+'+'+'ozvAwWvcQEMHDlCVs9x0DyHDDDqwgPeNSnecQ45rsJxvC5djToYCDrdQiudk1BBCCLpyEwQAIfkECAkAAAAsAAAAAEAAQACFBAIEhIKExMLEREJEpKKk5OLkZGJkJCIklJKU1NLUVFJUtLK09PL0dHJ0FBIUNDI0jIqMzMrMTEpMrKqs7OrsbGpsnJqc3NrcXFpcvLq8/Pr8fHp8DA4MLCosHBocPDo8BAYEhIaExMbEREZEpKak5ObkZGZkJCYklJaU1NbUVFZUtLa09Pb0dHZ0FBYUjI6MzM7MTE5MrK6s7O7sbG5snJ6c3N7cXF5cvL68/P78fH58PD48////AAAAAAAAAAAABv5AnnBILBqPwgIOV0I6n9Cos7SoLmbSrHabkFVlqa14fIR9VwmyujgTLGAap3mxWqSRrMhKgF0fNXVeEXILXmBOIlYZOX5GFFZVTWV0VXdGVIV1fY1DLDIrnws4SGaGYUYaOJlVLJxFc5kXk3VoRymQh65ELHVWK3GvX3ZGvKu/ukUXq8PBlJZDsJ82yEU5qp9eDMGmbGeijNREmFYiwb3PPAKhVRThRonYK5JCpYWnQjaUn'+'+'+'V'+'+'+'GjOtThhAEgWMhxlaljRkMKbNCQsKBY3MQAjwSBdQVWThi5AABoxpQm4Ju0cMhwUUBNodUeXLBrgigPTheHkkx8JQGSIOyQHDwv7JkzKQ9FqHQ2WRfDKYSMlR4CbIoyR'+'+'+'ojhJAEkELwMXiKg4BEcKmlE0wOBHZKJUqaOOsEiUCSudBBF1aom41ufUuydXcL3Esm2VFQWQ5UhAAC9eEoGj5LiwolevKgLAkmExwfDPGgkkP2FRLx4MXTgsW8CxV8uMRL1m6tJQ'+'+'+'KeMTWsKBJrnKsVJEhpdaUihGpkGEhE0NxLez53x48jJUEjAvLnz5k/Dsc0KacU0pBix'+'+'+'WXmToaO7'+'+'+'DDh0/Ql9JfXCSprQjwnb0O9'+'+'+'zjI7iZvf6nFemRLXi/gb///v2hYINj1Q2EDjIy9KeDggrG995nJaTQUUcpwEChhTAk5s501P5lEl1yIIYYolyckNgIAxucYKIaLGxwjC4EeAAAADVQg4MJBmwwSCMRPDDjjAeUxuIGBuBoAgS0bVHCDT822QKMBhQppQkkNJQFCyFw0GSTDwgpBgM6GDmlATTgsCIRMhyw5Y8uoECcGKnQIKaYOuRHBAUDrDkjCDRYyYOXUXDFAAEmzCklCn4SIYGeAEiQngY7BACoWig0cAkEhuJoARIgrHnCCkdY4IIHH8jwphA2GSBBDBmUQeSclh7RQZMcvEDiDB2MOuoOZ2pgQAyrSnCDkIBUIGYISAigJQAmGGXEBrp60OYQBGwAQQgbTDBEDcB2WxUSMxBqQAMaSiRAktxFJCDtuh9UtMED8D6ArBAs3BCsBAqUK9EFk0qhQrQugDrEu/HOK0QG3QJr8HErrDuqCkUQDO/CPDTQ7aqfHafBAw57kJ7E8haRwsUxVHDqGggAvIERLcDbQchFQHCxBAKHU8IBDncAmxA6xAtzWQokjMHOrjQAcI3P'+'+'+'kyxECSQjEI4BXTM6xEtF6zWr8EqQLQfAgDc6hE9W32ECMECm5srM5yw7g1OgLz0EC10i8GZZIgwwgEGbC3Euy//LFEACtBwtohwK024GmHD'+'+'+'8LhZGTgM1mMbyGDCjfUjEwQACH5BAgJAAAALAAAAABAAEAAhQQCBISChMTCxERCRKSipOTi5GRmZCQiJJSSlNTS1LSytPTy9FRSVHR2dDQyNIyKjMzKzKyqrOzq7GxubJyanNza3Ly6vPz6/FxaXDw6PBwaHExOTCwqLHx'+'+'+'fISGhMTGxERGRKSmpOTm5GxqbJSWlNTW1LS2tPT29FRWVHx6fDQ2NIyOjMzOzKyurOzu7HRydJyenNze3Ly'+'+'+'vPz'+'+'+'/FxeXDw'+'+'+'PBweHCwuLP///wAAAAAAAAAAAAAAAAAAAAAAAAAAAAb'+'+'+'QJxwSCwaj8KCTCZCOp/QqFOkqCpc0qx2m2hVW6WteHxkfU0JsrroEihYF6dZYVKkkSeISYBdHy91XhByCl5gTh9WFjN'+'+'+'RhJWVU1ldFV3RlSFdX2NQyctJp8KMkhmhmFGFzKZVSecRXOZFZN1aEclkIeuRCd1ViZxr192Rryrv7pFFavDwZSWQ7CfMchFM6qfXgvBpmxnoozURJhWH8G9zzgCoVUS4UaJ2CaSQqWFp0IxlJ/lfhcurU4WQBIFDIcZWpYuWDCmzckJFwWNuEAI8EgXUFVk4YOQgAWLaUJuCbtHbI6FTUVU'+'+'+'YoBrgggfTJaHpmxMJSFiENmVOj1SQD'+'+'+'Ep69ZLQzkq8FEykzCtQEWUSESkgmkEDwMlDBh4pDZJSQGeUCC35EFiTKFG/QkRNjsflKEBGnlognEvSq'+'+'+'sGtuKdq6RRANiPGQrKgTByNotNEr7kKBHAlc0LluiommGY5US8eC11zMH6Co8ZFoqCL1Shc9wGlmgKB5rnKJ2qvrgslYlKjWSF0I9t'+'+'+'cLvbzbv3ExMTDAgfTlw4Cd4JBChfzlz5hyYIAEifTp36CN4ySJCgoJ379u7bSxyoTn56Ct4CuKv/vp57i/HlyZ/f/YG9/fYRosevfn23hfsAUhCGCSMUZ6ABx'+'+'+'2WXHMMPufbgxBGOIRdt7lzwgMZUCjaCgL'+'+'+'aDhGCzdooEEI1HzQQQcrkKQGCxuIKKIKHm5xwgoBdFAjDEONIcEILvbYgS4KnNhBCicGoABWXSFgQ4891oAkYwjUKCWRHXjwgW5DmKACkxoAoMEBMGCpxQwfPECljTamgIBGSEiAApci2tABVk9OFpYCNQ4p5Ikh1IkDBlx6iYJkOJyAAgJ'+'+'+'InEBAR44AsOUUnYQARI9eqlBDaMYEYIDNzBggphkNiCcT0aUgACVRAbQ6BE19HgACaG5UIMKDtSKgm4XpEDcC3VeIIAHaXZAARIfHCBiA6YR4YEKN9TqAAxDtPAAdw9EJUQEwtEg3KRILNBCjQ'+'+'+'4doRnqpVKa63jN2xQ0AMbtLtBgoW'+'+'+'QNwI5RaxQAwxRmEAs7SqkKkQ7LqLABECDKftsL7J4GyzBhTBLgjtDkzEA9oOpyI1F2zAab9sAtwuxAgPUUFxKYipBgzOcrrqxO5uIDERFAiHwai7SVDDxg4MkGzAIDtS4HAvNERNBwurQKIRAUd8hALE0cAtMiLQegOtKNjlwQYQu/yHvMONILQrEKR8A6lIt/xyERDILFzHnCxw89QTOJG01kg8AHS'+'+'+'YrCAQQYNfO0w1kp3i8AIAbAtIQ5zn324FisAvkHIi2shQMtmRb6FBSOMYIE7QQAAIfkECAkAAAAsAAAAAEAAQACFBAIEhIKExMLEREJEpKKk5OLkZGJkJCIklJKU1NLUVFJUtLK09PL0dHJ0NDI0FBIUjIqMzMrMTEpMrKqs7OrsbGpsnJqc3NrcXFpcvLq8/Pr8fHp8PDo8DA4MLCosHBocBAYEhIaExMbEREZEpKak5ObkZGZkJCYklJaU1NbUVFZUtLa09Pb0dHZ0NDY0FBYUjI6MzM7MTE5MrK6s7O7sbG5snJ6c3N7cXF5cvL68/P78fH58PD48////AAAAAAAABv7AnnBILBqPwkIuV0I6n9Cos7SoLmjSrHabmFVnqa14fIx9VwmyukgTLGIap3mxWqSRrMhKgF0fNXVeEXILXmBOIlYZOn5GFFZVTWV0VXdGVIV1fY1DLDMrnws5SGaGYUYaOZlVLJxFc5kXk3VoRymQh65ELHVWK3GvX3ZGvKu/ukUXq8PBlJZDsJ83yEU6qp9eDMGmbGeijNREmFYiwb3PPQKhVRThRonYK5JCpYWnQjeUn'+'+'+'V'+'+'+'DCIFTxhQqpIDWA8ztCxpyGBMmxMWNAwaEfAAAAAT7Ugtk4UvQoIYMaYJuSXsHrE5GTYV8WDRYgcEEjutCPTtSapVGWIK0XGh1/4nAUhAtGzpYcWRfKLm2SzAcIbIIiVUQaKDRMLQoRI4EsmRQqfNGPyI'+'+'+'DsTb9CREgOutgTRwqEQr1IkskjQa'+'+'+'oCEXCFTPig1uILG3nF6LjRNBO2HBmhsAjRoS8AB60asXDjhVaVFU'+'+'+'zFMDQ14SuCGQLJYg8RoSLoQdINwJUV4TbNQReWLRBDSkTZAxaeAg8xloKcNRU6wLurrjx41ty7GjQgrnz5tAJGL'+'+'+'RoLr169VTtLPx4UX3797Dd99gvBQow6u8FHABvr34FyGMJ4BU12edHOzfu//wIX7xLqCch81UOVig34EvkFccLJdZEc8CAOWwwXMURjfdRynEkCGG1v6loBRyIIYYIm9kEKcLCwgoYOJqJMCBzAoSOODAAtSkgIIFJGSmRgoGyCijDCRmwQIJFtyIwhVqULCDj0zCoIsIRhZpgQUCCAeFBjZwwCSTKlg5hgYTRBmlDQmseEQOMW7pgQs8zGBmiQnYIKWYEwDkBA0VbOmjCzCo5mVcuwgw56DHHJGnmi7UoKMOFQC2BS8WOLKCmEaOcoQLTK6pQlhEzCCDBCYI8CYRGsSAwA47xHBEAQQMioJ0R6iwJw8keEmDCjJ8KkMFo/agwamoBgCBlzrEMKWRM5DCgwMuhKBSESjkKsGnEwyxAo5EWtpDBsFusEMGDxX4amInfVjEDeTSfmqCQTaYYIC7JAyhAQQ7eLtDAM8WwUAJQT6xg67TAjWEDe'+'+'+''+'+'+'Cys99967QbzIiSDBtNMqOIQF7sJbhAX2oqojNRoYkK4MOlJcMMNDFNBtADAZNwHAMkR6ccEmHDzEBKjWbFY4NCgAMAb5ElyxzEIwEIDCO0Dwmi4wsJysESJbPFHGOxhFDQXpSlCBVz4b/AcMQwewQQBHNxIDyzcXkXXMSKRQb83m'+'+'+'sGCChAH4MTZQBNhQ9cQ9KtFChUoEELYA8Nc9xAMEBAACnaKaLbgiqvRbsHVNj5GDDCbJLkWAgQQAKe6BAEAIfkECAkAAAAsAAAAAEAAQACFBAIEhIKExMLEREZEpKKk5OLkZGZkJCIklJKU1NLUVFZUtLK09PL0NDI0dHZ0FBIUjIqMzMrMTE5MrKqs7OrsbG5sLCosnJqc3NrcXF5cvLq8/Pr8PDo8DAoMfH58HBochIaExMbETEpMpKak5ObkbGpsJCYklJaU1NbUXFpctLa09Pb0NDY0fHp8jI6MzM7MVFJUrK6s7O7sdHJ0LC4snJ6c3N7cZGJkvL68/P78PD48DA4MHB4c////AAAAAAAABv7AnnBILBqPwgIOR0I6n9Cok7SoLmTSrHabiFVjqK14fHx9VQmyuiigdRQUp1lVTSNXEZUAuz4ydgCBInILMSoxdkchVho5fUY4gZILSGZWiUVUhXR8j0M2koEWG2WbYEcbOJtVK55FGaEALqV0C5hDKFaFYa5EJB2hO51Dloa3PSt0hgsqpL1EILEGRpZ11MybNs9FGzyxL0Vdh7ZFMmcLOI7bRDGxA0XVp0QCy1Vx60UDsRNE1WhENqwYCvEITxM5sQ60EmLGEKIhGzSsUsHgyQoZzo5EMPHhg4NhRQzEAvHpRYIEL7QJyYVN3pEVljSAHKLjw4OOJi5kJCIDkP6kUU9ySPSCbueQHBhqFSJ4pKNNpzpwHHER6MAIdUGVGFKZSeI5FUhS3Ox482YGrkI2WJixMMuGFwKMMFh0rgq4IyQUPHVqk0eAikNmZsHaY0OCWvUGGi2igoXNsjw6Wrjaa4MNZVWUqcBxD8oKFxwf8CjbcUDbNamsYGaGVgqJCpFFP3XQq'+'+'+'EhZQlOb3kh4ekDHYA9RdQVQrCYESxEj9hmwxCOAs9WgNCxuE8OHBiqP9Lehzu'+'+'+'7'+'+'+'DDPwkBAUQA8'+'+'+'jPq48B3sbJ9/Dfo4gzoQGNBvjz68dP8nuxcQ4VYkUBIuRHAwv3HZggCw2cAF4CumCT2Rk4SGAfg/hh2ACGNP44'+'+'+'J04AYZYCw4jGIjghQs20B8'+'+'+'1UzooiHQkQfCjDTWeB4/37mHEgomvcDjjvOJJ'+'+'+'SQREKED2G9rEBACd6NIRQGSPaBQwYSiCDVMwGhc1AfGMxQZZUGNKlFREQtUJwaMrggwZcirFlDbS1dohsUG0ygQJt4fsmWcKqssowKrTkRwg1sspmBBmK6dZlS9XD2hAwe5JknDBcEV5gaO61wWGJWRDCnEAFI'+'+'+'mUA0BGRgwcxJAoRDuwVMdcqq9xlxJqSlhDBERrcYEALEURpxAYojHDBCRjgpUpiGiBRApsppPpSBQboekMA3m0g7AnDElAdUpotENcRKKTQ5gmWFkFAtPzogiVECBOooMIETPUQAbb03nrHC3SoYFwPDKCwbw8FoKtrCxlN4MHBHlCSFgH0XnDBvxf5ugUEAhtgrxAxeBCAxgqvNOzHyYr3grTRzkKEwR60kHARMXyMbanfbdBCxTBjrPHBHQtBgssnTCCxJypUvFwRBrcQQAs5C4HDx8Pysg4DJQhcwZwGH72yqy5fUMOnj9RQccgsI3w1PFnH24sMBgjsga9VG520ECuM0PAFXKuBAsk3HGMzwm8LYUPWnbmywgzSenhE22MbsQC92q6DgQclVOpExkYnXkQyF8QQeJFCIN4351HEYLUH6oLOxcEbF2u6GPNeIOszQQAAIfkECAkAAAAsAAAAAEAAQACFBAIEhIKExMLEREJEpKKk5OLkZGJkJCIklJKU1NLUVFJUtLK09PL0dHJ0NDI0jIqMzMrMTEpMrKqs7OrsbGpsnJqc3NrcXFpcvLq8/Pr8fHp8PDo8HBochIaExMbEREZEpKak5ObkZGZkLCoslJaU1NbUVFZUtLa09Pb0dHZ0NDY0jI6MzM7MTE5MrK6s7O7sbG5snJ6c3N7cXF5cvL68/P78fH58PD48////AAAAAAAAAAAAAAAAAAAAAAAAAAAABv5AnHBILBqPwthoBEI6n9CocwGoAmjSrHY7swJg27D4ePGKxugiZHAQvZxl6xmZgZwE7/TxNeL4Z3BmTh4LhRg1ekYCfownSHFVc0YhhS4nLnmJQwWMfiqIRpAAkkQ1GIWFJxmaRTB'+'+'+'AH4kZIJGFpWXCaxFIZ0cI5lDoqRCKCeoC6q6RSS9KUYmtEQJCy7UJzLKRSgqr34lRcJFL6jGNKDZQye9JkXQckUC1NYT6M'+'+'+'9LkRd75vkCxCJKBLQc8KilwoUwaJlwGCp2gkGT1C8WIWExYARKjoAK5KiG4cVQ1aYEEHBhCwhJY65'+'+'+'IYEBQtDG4cocKDCgYMBBM4ReXGg0/4NikhMWVtAA2gpC8asCUBS06ZTEx6OMOOgwoXOoAVoVMM26dQxZEhgOB3rAEaBIjVu2ECoJeDSIgwI3bK0gAWSCSLIOlWxgq2QmFl0ZmCRlG4lCH6P0PhQE'+'+'+'PYDwuM6qkhw6thSzQGQkER44ZemyYSo8lAQx4ya9fChLDRuKmDDrpeWqPLQrIWFjNctxCdZuExD4DDnPhgE58yGZjP6kJBosXVRDVoWHiuybYm6vWya98ehUUFBCTAiw9P3lF2GQnSq1'+'+'+'fvgS9BRFaxJ8vv378k/WmVWs4u9KCAiLQJ6B9LRCgnWxJnYYKXTQESOCA8RmYnX79NZRgaQ9mGAF'+'+'+'6P68lKCCt/yHAwskkDfeiebVg14CLJTQIgsslpCAjCFwZ'+'+'+'ONOA6BnR7WAeSCDTuOQdp0yniQggEiRHVcITTUmIgMHYiApAEa9CiGb6gAhwYDMVAwpZQiSBCbPNUUkgBvT2RwgpdfTtmBlW3Fc5qFqUnBggZIgjllAwLAuUUGMhjzIZOaHcHACm2CSYEEifnphFEBXUJmUoghsYKeeiLgpI4IYOAoEYO9tZMHgvpXFxJeYmoDS0V4YIMNCCQQpBAZFHCCMVztUtplGCBhg54wHGJoBwFo8CoJcAqFiqdHZIDUUKIWYQEMIogAAkRILPCqDQHYIGoCGHggAA25oNQfq'+'+'+'BGuCRocDigYAG2SITQbbGwAoUBCRXgK'+'+'+'pCCT4U0QuzRhHDq8auSgQN'+'+'+'eYbrQz9/cNdCfNyK'+'+'+'EQ9yYcLQ5yolIoOhkgULAGAWwqBAYJk3DxCxa6YI52ArxK7wJG0ICvvkbIhkqu2TDQwbYBdADvEAjPfHG7xtCVDDo/0muDkkWQLPQRJXxYrjIvdOsyAtbJbPERQln4qRYycGusBhYgUTHNR1CSILtjoPCAsQE0YXbJQw/hwX7CZiNDBXGjKUTQCjuBgh145GjE2RXUbXgUgJPA9OJgl6wc5Fsk4IIEZaMTBAAh'+'+'+'QQICQAAACwAAAAAQABAAIUEAgSEgoTEwsREQkSkoqTk4uRkYmQkIiSUkpTU0tS0srT08vR0cnQUEhRUUlQ0MjQMCgyMiozMysysqqzs6uxsamycmpzc2ty8urz8'+'+'+'vx8enxcWlw8OjwcGhwEBgSEhoTExsRMTkykpqTk5uRkZmQsKiyUlpTU1tS0trT09vR0dnRUVlQ0NjQMDgyMjozMzsysrqzs7uxsbmycnpzc3ty8vrz8/vx8fnxcXlw8PjwcHhz///8AAAAAAAAAAAAAAAAG/sCdcEgsGo9CUS6nQDqf0KgT0'+'+'+'g0dCCpdsuVdb6NG3dMPlasX1V5XXxtOKqFk/RNO0chCEvCRi5yD4EyTjJVVWpIAwCLDSl9RhKBkgJIZ1Y6iEYwi5x8j0QjkoErGUd0aJlEKTqcAB4jn0U3og8iZpcdqUMfrQAVsUUUgJI5ckWnh0YFLa0tMcBFM4ElLA8fRmdXDbo7OL0m0EUpISzVDywXx2CYRSC9JaXhRDW0JEVeV7lENiy9KPJGSEyrVoMIMn1DZvQK8SjFiWdOLgQyF8KRkGw6tg1J0aCVh3ROUsSId'+'+'+'TEhhAhTBg78mHggxlDTJBQoYIETCEaenHb'+'+'+'EKB/gIMELGhRLkBho0jMXKYe7DiaMgDrRodyXABhU8YKCgdQTlgaIgKnoqICBQChdMnKWawAnCzyIgaPuMq'+'+'+'HckwNCuQwPAIpIhhAuLWhaQYHFWyAIQVxVgxRq2SAwNXgc46OpgBuAdl7ms3JEhwVWrc32CIHlEgAGvXjdgIN3HBg2rWD/DqEFBSoYJDkJIxotSRuYyGeDKjY2CxhgKCHJzRWkBmATFikG/YL3lBAOvBn6vyYAhtAIQQdlgOBkCQzgaimvsjZWBQAXqfYJfKAwM/iP7APPr3w8lgQgCAAYoIIAF5UdDAggmqCCCJ9RWgwEkQChhhBRCaEt'+'+'+'PUUHg4bQ/vlUgAYThlghCTDo55lcoHmHVQ01iehiifklsBgKGy4WnU81PDiiiyQQoF'+'+'+'GcaUIGlYF7OBfgDP8NwMBSzKpFUAHJvDCCS9ISaWVDfKn5ZZcDkEfewBxh8CX8dUwHzQvuHDDDS'+'+'+'ch'+'+'+'N6bIxgwQ0BaHADAviNwV2HIGymGQxrrhnAmubFAmSHCWjnRAYCfCConYFakKcWKdRAo1WwoVCcFicgEGiddGoQgQSTbuEaBjWmqABtaBHwKaQBBIACaaUiQZpDQyamgASKuhrqowTUxtcEIJC56AtPDhEDYpnG1eYRgdpZJwIgFZGACRaIYFwUNhTQnQLbugVXpjAUaoQJ/nUO'+'+'+'sFoR6RlAbYmTGCsEHvGttpUVQWZLBEFDBqAAn4SIQC87z67Aw0SnCBluCfEhdUJIfVUbnhFZEBDwETE8O7GIpx1aAJDcBfbXBhvFMO8WihAsAlFDiGxYhAP8VpijRm4MbZNEJGhVSALPLICwuZngwg3W0DxyzDErKxsNaDcxwtF77tDT6D1rLNc4OaXggU3W2ZEhlhZvVGzswI08MYWiO2yXGoLcYKqbT'+'+'+'ywM3xwicxjXHbANeMtW4xwsotf802EiNgTfF9M2xM1xFgoxC3ECAshoHTZIwwwQwYKDq1TzxHnNXhXSKtdJdjnGjV6KRvUUCHQafOBQ0C1BA4AzRBAAAh'+'+'+'QQICQAAACwAAAAAQABAAIUEAgSEgoTEwsREQkSkoqTk4uRkYmQkIiSUkpTU0tRUUlS0srT08vR0cnQ0MjQUEhSMiozMysxMSkysqqzs6uxsamwsKiycmpzc2txcWly8urz8'+'+'+'vx8enw8OjwMDgwcGhwEBgSEhoTExsRERkSkpqTk5uRkZmQkJiSUlpTU1tRUVlS0trT09vR0dnQ0NjSMjozMzsxMTkysrqzs7uxsbmwsLiycnpzc3txcXly8vrz8/vx8fnw8PjwcHhz///8AAAAG/kCfcEgsGo/ChUK1Qjqf0KhTUHM4XBGpdsvduRzVF3dMPnLA31B5XUxVVBCGs/UFq5EU3GEEYyNZGRKCAU5nNWlOCh8PHzUsfkYwMRIxk1lHdFYOd0YrjB'+'+'+'LfZBEFJWUEhUbZmibRywuoIw9M6NFCKeVMqtfNZxECJ'+'+'+'gLbVFFCq4GbRFHFVXvkIlB4uLNcrEQxOTkxIoRhwuh61FFcEPNtdFGya4MTfLmr1FMKDTPDroRSK4EhzL4eJDJHx60CMHPm/aKAkgYgjREBKxFpmAxOKGHCc3EsYwoErINyvxhLBwMPBEiiclRDxCgqGBARMkLh5BsW/CEBstQoTgYFNI/oBYPR48I1LCAAAAD0QZ2fHyJY0cHYswUJGQRlRXsD45chXCw9GjLpBUaGri5Y6TRmRQMiDgKpINJLKSOCLjwNevD5C8KMvXwEsEFNJVuLBSC4sWMSKNuHtXBRIGEPqa4FthQmEfl7nI9DGjAgjGXyVYOwKDA1m/LxuIcMtmAwKvoAGcaBJlwwIaLyeTDcCazAwLsQF4eNHbyQwbZU/PrZUhtonAZW5AIMshM5sZPe7yUMpGRIOyItAhONqjZy0WMnbcu8biwA7ro4r7kX'+'+'+'wvv37TzCsWMC/v3/'+'+'+'lxx0QwIEFmgggSkEFsEODDbooIO0HQQDfzKsIMMCFV54IX8F/rywQwAMgvhhiCFqYF8CFfa3H38rVpjDCwFwwCAHMX4oY40m1pfAfhliaKGP/OUQwY0yPthgAAvYN6F/Ky7Q4goF'+'+'+'KDff1QuECA'+'+'+'A8KQgpYwJMBlCgkkiN'+'+'+'YZJY5xHro0LfGBhGQgGYtG'+'+'+'SAwZuQYEAAChegRcwNQZYwCgUy4Imnm8RsoIF/IozGBQs5CHqBoFf6MaGG/SUAnxM6wHDBo5wKKoOaUjDqpJMZrrCCO1LcQEKnnRKg5yg63KDBhv/lAN0fK2zqKJ4XrEYEqG8RwcKOPqYI4KW5orDrBSso6kMOKdAJxQYwLFTEDCI0qeEKkQ7BaacTRGkEBkGKC4UO/gUcugCqRZSQA5AsIjHBo3jakEBvLDS5QA7S/vruhhr0tsGUPlprRAk2bCrApT4kQOF'+'+'+'GAxxQwRgwsBuCv1V'+'+'+'GoRLMCwX7NObFDCZkYwAK8GaC7JXwJDGGrsCiRzPEO/XIiQ8Qp'+'+'+'DjEphq/e0KKVY5bw8ALhEbHkfiwTIYCxC9x6kA7qWggyEQ5ruPEMD8vAr33kGpu00Swu8LXO/7GLTr4UOsnazjJsjBmPHwM7xtEYmk32qGMPkYK'+'+'+'eY/CQtorGERa2n37AHWxK8itBQU352wEiisW7oPQTTo7n9RAI7FkhZL7IIKGKONDgQDcMqxy206wEMEKAlheJt2dmwlFEtU8y15GAaM2bXt0OeTg'+'+'+'DVBAAA7')))
                    },
                    success: function(result){
                        $('#cargando').remove();

                        for (var i in result) {
                            var options_detalle = '';

                            $('#detalle_producto > thead > tr:first-child').prepend($('<th>'+result[i][0][0].nombre+'</th>'));
                            
                            for(var iDetProd in detalle_producto_data = result[i][2]) {
                                for(var iDetProdInterno in detalle_producto_data[iDetProd]) {
                                    options_detalle += '<option value="'+detalle_producto_data[iDetProd][iDetProdInterno].id+'">'+detalle_producto_data[iDetProd][iDetProdInterno].valor+'</option>';
                                }
                            }

                            $('#detalle_producto > tbody > tr:first-child').prepend($('<td><select name="atributo[]['+detalle_producto_data[iDetProd][iDetProdInterno].id+']">'+options_detalle+'</select></td>'));
                        }

                        $('#detalle_producto > thead > tr:first-child').prepend('<th></th>');
                        $('#detalle_producto > tbody > tr:first-child').prepend('<td></td>');
                    }});
            });

		guardar_almacen = function (elem) {
			var result = ''; almacen_nombre = [], cantidad = [];
			$('#list_almacenes').find('tr').each(function(index, elem){
				almacen_nombre[index] = $(elem).find('td:first-child').text();
				cantidad[index] = $(elem).find('td:last-child > input').val();
			});

			for (var i in almacen_nombre) {
				result += almacen_nombre[i] + ': ' + cantidad[i] + ' <br>';
				$("#detalle_producto > tbody > tr").eq($('#fila_seleccionada').val()).append($('<input type="hidden" name="almacenes_nombre[]" value="'+almacen_nombre[i]+'">'));
				$("#detalle_producto > tbody > tr").eq($('#fila_seleccionada').val()).append($('<input type="hidden" name="cantidad[]" value="'+cantidad[i]+'">'));
			};
			
			$("#detalle_producto > tbody > tr").eq($('#fila_seleccionada').val()).find('.almacenes').addClass('tip').attr('title', result);

			$('#fModal').modal('hide');
        	$(".tip").tooltip({html: true, placement: 'top', trigger: 'hover'});
		}
		seleccion_fila = function (elem) {
			$('#fila_seleccionada').val($('#detalle_producto > tbody > tr').index(elem));
		}
        resetear = function(elem){
        	if( confirm("Esta accion eliminara los registros que hallas agregado en la tabla. Desea continuar?") ) {
	            //$('#detalle_producto').parent().hide();
	            $('#detalle_producto > thead').html('<tr>'+localStorage.origin_thead+'</tr>');
	            $('#detalle_producto > tbody').html('<tr>'+localStorage.origin_tbody+'</tr>');

	            /*$("#categoria_atributos").prop("disabled", false);*/
	            $("#categoria_atributos").parent().find('.select2-container.select').show();

	            $(elem).parent().remove();
	        }
        }
        eliminar_datos = function(elem) {
            $(elem).closest('tr').remove();
        }
        agregar_datos = function(elem){
        	if(parseInt($('#categoria_atributos').val()) >= 0) {
        		$('#cargando').remove();
        		$('.agregar_datos').removeClass('disabled');
        	}
        	else {
				$('#detalle_producto').parent().prepend($('<div id="cargando" style="width: '+$('#detalle_producto').width()+'px; height: '+$('#detalle_producto').height()+'px; " align="center"></div>'));
        		$('.agregar_datos').addClass('disabled');
        	}

        	if( ! $(elem).hasClass('disabled')) {

	            var contenido_tbody = $(elem).closest('tbody'),
	                contenido_fila = $(elem).closest('tr')
	                append_contenido_tbody = contenido_tbody.append(contenido_fila.clone()).find('tr:last-child > td:first-child');

	            if ( append_contenido_tbody.html().length > 0 ) {
	                append_contenido_tbody.html('');
	            }

	            append_contenido_tbody.append('<button type="button" class="btn btn-danger" onclick="eliminar_datos(this)">Eliminar</button>');

	            contenido_fila.find('input').val('');
            } else {
        		alert("Por favor seleccione una categoria de atributo.");
            }

        	$(".tip").tooltip({html: true, placement: 'top', trigger: 'hover'});
        }
    });
</script>