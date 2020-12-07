<?php 
	$id_user=$this->session->userdata['user_id'];	
?>
<style>
	label.negrita {
		font-weight: 600;
	}

	/*#cargando{
		width:100%;
		height:50vh;
		min-height: 100%;
		background-color: #505050;		
	}*/
</style>

<div class="page-header">
	<div class="icon">
		<img alt="info_fiscal" class="iconimg"
			src="<?php echo $this->session->userdata('new_imagenes')['titulo_contactos']['original'] ?>">
	</div>
	<h1 class="sub-title"><?php echo custom_lang("info_fiscal", "Migrar BD");?></h1>
</div>
<div class="row">
	<!--
		<div class="col-md-12">
        <div class="col-md-6">              
            <div class="col-md-2">              
                <a href="<?php echo site_url("administracion_vendty/facturas_licencia/")?>" data-tooltip="Volver atrás">                       
                    <img alt="Volver atrás" class="btnimagenes" src="<?php echo $this->session->userdata('new_imagenes')['devolver_verde']['original'] ?>">                                                     
                </a>            
            </div>
        </div>
        <div class="col-md-6 btnizquierda"> 
            <div class="col-md-2 col-md-offset-10">
                <a href="<?php echo site_url("administracion_vendty/empresas/export_info_fiscal_excel/")?>" data-tooltip="Exportar a Excel">                       
                    <img alt="Exportar a Excel" class="btnimagenes" src="<?php echo $this->session->userdata('new_imagenes')['exportar_excel_verde']['original'] ?>">                                                     
                </a>
            </div>
        </div>
        <hr>
    </div>
	-->
</div>

<div>
	<?php echo form_open("administracion_vendty/empresas/migrar_bd", array("id" =>"validate",'class'=>"form-horizontal"));?>
	<div class="col-sm-12">
		<div class="col-sm-2">
			<div class="form-group">
				<label for="inputEmail3" class="col-sm-3 control-label negrita">Desde</label>
				<div class="col-sm-9">
					<select id="desde" name="desde" required>
						<option value="">Seleccione</option>
						<?php 
						foreach ($data['server'] as $value) { 
							if($value['id'] == 2) {
						?>
							<option value='<?= $value["id"] ?>'><?= $value["nombre"] ?></option>
						<?php
							}
						} 
						?>
					</select>
				</div>
			</div>
		</div>
		<div class="col-sm-2">
			<div class="form-group">
				<label for="inputEmail3" class="col-sm-3 control-label negrita">Hasta</label>
				<div class="col-sm-9">
					<select id="hasta" name="hasta" required>
						<option value="">Seleccione</option>
						<?php 
								foreach ($data['server'] as $value) { 
									if($value['id']==8){ 
								?>
									<option value='<?= $value["id"] ?>'><?= $value["nombre"] ?></option>
								<?php
									
									}								
								} 
								?>
					</select>
				</div>
			</div>
		</div>
		<div class="col-sm-3">
			<div class="form-group">
				<label for="inputEmail3" class="col-sm-2 control-label negrita">Correo</label>
				<div class="col-sm-10">
					<input type="text" id="correo" name="correo" required />
					<input type="hidden" id="bd_correo" name="bd_correo" required />
					<input type="hidden" id="bd_id_correo" name="bd_id_correo" required />
					<!--<select id="correo" name="correo" required>
							<option value="">Seleccione</option>
							<?php foreach ($data['correos'] as $value) { ?>
								<option value='<?= $value["base_dato"] ?>' ><?= $value["email"] ?></option>
							<?php } ?>							
						</select>-->
				</div>
			</div>
		</div>
		<div class="col-sm-3 hidden" id="opciondemoaproduccion">
			<div class="form-group">
				<label for="inputEmail3" class="col-sm-5 control-label negrita">Migrar Por</label>
				<div class="col-sm-7">
					<select id="opcion" name="opcion">
						<option value="">Seleccione</option>
						<option value="1">Probar Producción</option>
						<option value="2">Mintic</option>
					</select>
				</div>
			</div>
		</div>
		<div class="col-sm-2 text-center">
			<div class="form-group">
				<button type="button" id="buscar" class="btn btn-success">Buscar</button>
			</div>
		</div>
	</div>
	<div class="col-sm-12 text-center hidden" id="informacion">
		<div class="col-sm-12">
			<div class="col-sm-6">
				<label for="inputEmail3" class="control-label negrita">Información Para Migrar</label>
			</div>
		</div>
		<div class="col-sm-12">
			<div class="form-group">
				<label for="inputEmail3" class="col-sm-2 control-label negrita">Correo</label>
				<div class="col-sm-10">
					<label id="email" class="col-sm-2 control-label"></label>
				</div>
			</div>
		</div>
		<div class="col-sm-12">
			<div class="form-group">
				<label for="inputEmail3" class="col-sm-2 control-label negrita">Empresa</label>
				<div class="col-sm-10">
					<label id="empresa" class="col-sm-2 control-label"></label>
				</div>
			</div>
		</div>
		<div class="col-sm-12">
			<div class="form-group">
				<label for="inputEmail3" class="col-sm-2 control-label negrita">NIT</label>
				<div class="col-sm-10">
					<label id="nit" class="col-sm-2 control-label"></label>
				</div>
			</div>
		</div>
		<div class="col-sm-6 text-center">
			<div class="form-group">
				<button type="submit" id="migrar" class="btn btn-success" disabled>Migrar</button>
			</div>
		</div>
	</div>
	</form>
</div>
<div id="cargando" class="hidden">
	<div class="text-center">
		Espere Migrando Base de Datos
		<img src="<?php echo base_url(); ?>public/img/loaders/loading_icon.gif" alt="cargando" height="42" width="42">
	</div>


	<script type="text/javascript">
		$(document).ready(function () {

			$("#desde").change(function () {
				desde = $("#desde").val();
				hasta = $("#hasta").val();

				if (((desde != "") && (hasta != "")) && (desde != hasta) && ((desde == 2) && (hasta == 1))) {
					$("#opciondemoaproduccion").removeClass('hidden');
				} else {
					$("#opciondemoaproduccion").addClass('hidden');
				}
			});

			$("#hasta").change(function () {
				desde = $("#desde").val();
				hasta = $("#hasta").val();

				if (((desde != "") && (hasta != "")) && (desde != hasta) && ((desde == 2) && (hasta == 1))) {
					$("#opciondemoaproduccion").removeClass('hidden');
				} else {
					$("#opciondemoaproduccion").addClass('hidden');
				}
			});

			$("#buscar").click(function () {
				$("#buscar").prop('disabled', true);
				desde = $("#desde").val();
				desde1 = $('select[name="desde"] option:selected').text();
				hasta = $("#hasta").val();
				hasta1 = $('select[name="hasta"] option:selected').text();
				correo = $("#correo").val();

				var url = '<?php echo site_url("administracion_vendty/empresas/correo_activo") ?>';
				var urlexistebd = '<?php echo site_url("administracion_vendty/empresas/bd_existen") ?>';
				if (correo != "") {
					if (desde != hasta) {
						//valido el correo
						$.ajax({
							url: url,
							data: {
								"correo": correo
							},
							method: 'POST',
							dataType: "json",
							success: function (data) {

								if (data['success'] == 1) {
									$("#empresa").html();
									$("#nit").html();
									$("#email").html();
									$("#empresa").html(data['empresa'][0]['nombre_empresa']);
									$("#nit").html(data['empresa'][0]['identificacion_empresa']);
									$("#email").html(data['user'][0]['email']);
									$("#bd_correo").val(data['user'][0]['base_dato']);
									$("#bd_id_correo").val(data['user'][0]['id']);

									//valido desde y hasta					
									$.ajax({
										url: urlexistebd,
										data: {
											"desde": desde,
											"hasta": hasta,
											"correo": data['user'][0]['base_dato']
										},
										method: 'POST',
										dataType: "json",
										success: function (data) {
											caso = data.split("_");
											//desde		
											if (caso[0] == 0) {
												swal({
													position: 'center',
													type: 'error',
													title: 'La BD no existe en la instancia ' +
														desde1,
													showConfirmButton: false,
													timer: 1500
												});
												$("#buscar").prop('disabled', false);
											} else {
												if ((hasta == 3) && (caso[1] == 1)) {
													caso[1] = 0;
													swal({
														position: 'center',
														type: 'error',
														title: 'Ya existe esa BD en la instacia ' +
															hasta1 +
															', pero se remplazaría',
														showConfirmButton: false,
														timer: 1500
													});
													$("#buscar").prop('disabled',
														false);
												}
												if (caso[1] == 1) { //hasta
													swal({
														position: 'center',
														type: 'error',
														title: 'Ya existe esa BD en la instacia ' +
															hasta1 +
															' para transferirla',
														showConfirmButton: false,
														timer: 1500
													});
													$("#buscar").prop('disabled',
														false);
												}
											}

											if ((caso[0] == 1) && (caso[1] ==
												0)) { //desde y hasta y correo bien								
												$("#migrar").prop('disabled', false);
												$("#buscar").prop('disabled', false);
												$("#informacion").removeClass(
													'hidden');
											}
										}
									});

								} else {
									$("#migrar").prop('disabled', true);
									$("#informacion").addClass('hidden');
									swal({
										position: 'center',
										type: 'error',
										title: 'El correo ingresado no existe, por favor verifique e intente nuevamente',
										showConfirmButton: false,
										timer: 1500
									});
									$("#buscar").prop('disabled', false);
								}
							}
						});

					} else {
						swal({
							position: 'center',
							type: 'error',
							title: 'No puedes migrar la BD a la misma instancia',
							showConfirmButton: false,
							timer: 1500
						});
						$("#buscar").prop('disabled', false);
					}
				} else {
					swal({
						position: 'center',
						type: 'error',
						title: 'Debe ingresar un correo',
						showConfirmButton: false,
						timer: 1500
					});
					$("#buscar").prop('disabled', false);
				}

			});

			$("#validate").submit(function (e) {
				e.preventDefault();
				$("#migrar").prop('disabled', false);
				desde = $("#desde").val();
				desde1 = $('select[name="desde"] option:selected').text();
				hasta = $("#hasta").val();
				hasta1 = $('select[name="hasta"] option:selected').text();
				correo = $("#bd_correo").val();
				correo_id = $("#bd_id_correo").val();
				correo1 = $("#correo").val();
				opcion = $("#opcion").val();
				url = $(this).attr('action');

				if (correo != "") {
					if (desde != hasta) {
						//verificar que exista la bd en desde y hasta
						var urlexistebd =
							'<?php echo site_url("administracion_vendty/empresas/bd_existen") ?>';
						$.ajax({
							url: urlexistebd,
							data: {
								"desde": desde,
								"hasta": hasta,
								"correo": correo
							},
							method: 'POST',
							dataType: "json",
							success: function (data) {
								caso = data.split("_");
								//desde		
								if (caso[0] == 0) {
									swal({
										position: 'center',
										type: 'error',
										title: 'La BD no existe en la instancia ' +
											desde1,
										showConfirmButton: false,
										timer: 1500
									});
									$("#migrar").prop('disabled', false);
								} else {
									if ((hasta == 3) && (caso[1] == 1)) {
										caso[1] = 0;
										swal({
											position: 'center',
											type: 'error',
											title: 'Ya existe esa BD en la instacia ' +
												hasta1 + ', pero se remplazaría',
											showConfirmButton: false,
											timer: 1500
										});
										$("#migrar").prop('disabled', false);
									}
									if (caso[1] == 1) { //hasta
										swal({
											position: 'center',
											type: 'error',
											title: 'Ya existe esa BD en la instacia ' +
												hasta1 + ' para transferirla',
											showConfirmButton: false,
											timer: 1500
										});
										$("#migrar").prop('disabled', false);
									}
								}

								if ((caso[0] == 1) && (caso[1] == 0)) { //desde y hasta
									$("#cargando").removeClass('hidden');
									$.ajax({
										url: url,
										data: {
											"desde": desde,
											"hasta": hasta,
											"correo": correo,
											"correo_id": correo_id,
											"opcion": opcion
										},
										method: 'POST',
										dataType: "json",
										success: function (data) {
											if (data.success == 0) {
												swal({
													position: 'center',
													type: 'error',
													title: 'Ya existe la BD del usuario ' +
														correo1 +
														' en la instacia que desea transferirla',
													showConfirmButton: false,
													timer: 1500
												});
												$("#migrar").prop('disabled', false);
											} else {
												$("#cargando").addClass('hidden');
												swal({
													position: 'center',
													type: 'success',
													title: 'LA BD del usuario ' +
														correo1 +
														' ya fue migrada correctamente',
													showConfirmButton: false,
													timer: 1500
												});

												setTimeout(function () {
													location.reload(true);
												}, 1600);

											}
										}
									});
								}
							}
						});
					} else {
						swal({
							position: 'center',
							type: 'error',
							title: 'No puedes migrar la BD a la misma instancia',
							showConfirmButton: false,
							timer: 1500
						});
						$("#migrar").prop('disabled', false);
					}
				} else {
					swal({
						position: 'center',
						type: 'error',
						title: 'Debe ingresar un correo',
						showConfirmButton: false,
						timer: 1500
					});
					$("#migrar").prop('disabled', false);
				}
			});

		});
	</script>