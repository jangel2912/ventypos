<div class="page-header">

    <div class="icon">

        <span class="ico-group"></span>

    </div>

    <h1><?php echo custom_lang("Contactos", "Contactos");?><small><?php echo $this->config->item('site_title');?></small></h1>

</div>

<div class="block title">

    <div class="head">

        <h2><?php echo custom_lang('sima_assign_group', "Asignar grupo");?></h2>                                          

    </div>

</div>


<style type="text/css">

#asignar-grupo{
border: 1px solid #e0e0e0;
}	

#asignar-grupo ul{
	list-style: none;
	padding: 0px;
	margin: 0px;
}

#asignar-grupo ul li{
	cursor: pointer;
	
	text-decoration: none;
}
#asignar-grupo ul li:hover{
	background: #009AD7;
}
</style>

<div class="row-fluid">
	<form action="">
		<div class="row-fluid">
				<div class="span4">
					<label for="">Grupo</label>
					<select>
						<option>Seleccionar..</option>
						<?php 
							foreach ($grupo_clientes as $key => $value) {
			                        echo "<option value='".$value->id."'>".$value->nombre."</option>"; 
			                    }
						 ?>
				    </select>
				</div>
		 	
				<div class="span8">
					<table>
						<thead>
							<th>Nombre comercial</th>
							<th>Razón social</th>
							<th>NIF/CIF</th>
							<th>Contacto</th>
							<th>Correo electronico</th>
							<th>Pais</th>
						</thead>
						<tbody>
							<?php 
			                    $i=0;
			                    foreach ($clientes as $key => $value) {
			                        echo "<tr>
			                               <td>".$value->nombre_comercial."</td>
			                               <td>".$value->razon_social."</td>
			                               <td>".$value->nif_cif."</td>
			                               <td>".$value->contacto."</td>
			                               <td>".$value->email."</td>
			                               <td>".$value->pais."</td>
			                              </tr>"; 
			    
			                        $i++;
			                    }
			                ?>
						</tbody>
					</table>
				</div>
		</div>

		<div class="row-fluid">

			<div class="span4">
				<br>
				<label>Ciente</label>
				<br>
				<div id="asignar-grupo">	
					<ul >
						<?php 

						 	foreach ($clientes as $key => $value) {      
				              echo "<li id='".$value->id_cliente."'>".$value->nombre_comercial."</li>"; 
				            }

					 	?>
					</ul>
				</div>
			</div>
		</div>
	</form>
</div>

<script type="text/javascript">

    $(document).ready(function(){

       $("#pais").change(function(){

           load_provincias_from_pais($(this).val());

       }); 

       

       var pais = $("#pais").val();

       if(pais != ""){

           load_provincias_from_pais(pais);

       }

      

    });

    function load_provincias_from_pais(pais){

        $.ajax({

            url: "<?php echo site_url("frontend/load_provincias_from_pais")?>",

            data: {"pais" : pais},

            dataType: "json",

            success: function(data) {

                $("#provincia").html('');

                $.each(data, function(index, element){

                    provincia = "<?php echo set_value('provincia');?>"

                    sel = provincia == element[0] ? "selected='selectted'" : '';

                   $("#provincia").append("<option value='"+ element[0] +"' "+sel+">"+ element[0] +"</option>"); 

                });

            }

        });

    }

</script>

<script type="text/javascript">
    $(document).ready(function(){
        $("#ms").multiSelect();
    });
    
</script>