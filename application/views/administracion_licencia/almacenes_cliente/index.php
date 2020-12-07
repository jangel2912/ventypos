<div class="page-header">

    <div class="icon">

        <span class="ico-box"></span>

    </div>

    <h1><?php echo custom_lang("Almacenes", "Almacenes Clientes");?></h1>

</div>

<div class="row-fluid">
    <div class="span12">
        <div class="block">
            <?php
                $message = $this->session->flashdata('message');
                if(!empty($message)):
                    $message_type = $this->session->flashdata('message_type');
            ?>
                <div class="alert alert-<?php echo $message_type; ?>">
                    <?php echo $message;?>
                </div>
            <?php endif; ?>
            <div class="head blue">
                <div class="icon"><i class="ico-box"></i></div>
                <h2><?php echo custom_lang('', "Todos los Almacenes");?></h2>
            </div>						
                <div class="data-fluid">
				<!--	
				<form>					
					<div class="row-form">
						<div class="span3"><?php echo custom_lang('sima_name', "Nombre");?>:</div>
						<div class="span9">
							<select name="email" id="email" value="">
								<?php									
									foreach ($data['datosusuario'] as $key => $value) {
										echo "<option value=$value->id-$value->db_config_id>".$value->email."</option>";
									}
								?>     
							</select>
						</div>
					</div>
				</form>-->
						<table class="table" cellpadding="0" cellspacing="0" width="100%" id="almacenes_cliente">
							<thead>
								<tr>
									<th width="50%"><?php echo custom_lang('', "Email");?></th>
									<th width="50%"><?php echo custom_lang('', "almacenes");?></th>							
									
								</tr>
							</thead>
							<tbody>
								<?php									
									foreach ($data['datosusuario'] as $key => $value) {										
										echo'<tr>';
											echo'<td>'.$value["email"].'</td>';
											echo'<td>'.$value["id"].'</td>';
										echo'</tr>';
									}
								?>    						
								
							</tbody>
							<tfoot>
								<tr>
									<th width="30%"><?php echo custom_lang('', "Email");?></th>
									<th width="30%"><?php echo custom_lang('', "almacenes");?></th>									
								</tr>
							</tfoot>
						</table>
						</div>
				</div>			
			</div>			
		</div>
			
<script type="text/javascript">
	$(document).ready(function(){
		$('#almacenes_cliente').DataTable();

		$("#email").on('change',function(){
		id=$(this).val();
		consultar_almacen_cliente(id);
	});		
	});

var url_distribuidores ='<?php echo site_url("administracion_vendty/licencia_empresa/consultar_usuarios_distribuidores") ?>';
var url_consulta_almacenes = '<?php echo site_url('administracion_vendty/activaciones_licencia/consultar_almacen_cliente') ?>';

function consultar_almacen_cliente(id){
		var id = id;
		var ids = id.split("-");
		$("#almacenes_cliente tbody").html("");
		if(!isNaN(ids[1])){
			$.ajax({
				type: "post",
				dataType: "json",
				url: url_consulta_almacenes,
				data:{id_config:ids[1],activo:false},
				success: function(result){
					$("#almacenes_cliente tbody").html("");
					$.each(result,function(index,value){											
						td="<tr><td>"+value.id+"</td><td>"+value.nombre+"</td></tr>";
						$(td).appendTo("#almacenes_cliente tbody");
					});				
				}

			});
		}
	}
</script>

