<div class="page-header">

    <div class="icon">

        <span class="ico-box"></span>

    </div>

    <h1><?php echo custom_lang("Inventario", "Inventario");?><small><?php echo $this->config->item('site_title');?></small></h1>

</div>
<?php echo form_open_multipart("inventario/import_excel_nombre", array("id" =>"validate"));?>
<div class="block title">

    <div class="head">

        <h4>Bienvenido sigua los siguiente pasos para subir inventario desde Excel </h4> 
		<br />
        <h5>1. De click en la siguiente enlace para descargar la plantilla de excel &nbsp;&nbsp;<a href="<?php echo base_url("/uploads1/Plantilla Inventario.xlsx"); ?>">CLICK AQUI</a>&nbsp;&nbsp; llamada Plantilla Inventario.</h5>

        <h5>2. Escoja la siguientes opciones:</h5>

<div class="row-fluid">
    <div class="block">
        
                            <div class="data-fluid">
                                <div class="span6">
                                    <div class="row-form">
                                        <div class="span3"><?php echo custom_lang('sima_date', "Fecha");?>:</div>
                                        <div class="span9"><input type="text"  value="<?php echo date("Y/m/d"); ?>" name="fecha" id="fecha"/>
                                                <?php echo form_error('fecha'); ?>
                                        </div>
                                    </div>
                                    <div class="row-form">
                                        <div class="span3"><?php echo custom_lang('tipo_movimiento', "Tipo");?>:</div>
                                        <div class="span9">
                                                <?php echo form_dropdown('tipo_movimiento', $data['tipo']); ?>
                                                <?php echo form_error('tipo_movimiento'); ?>
                                        </div>
                                    </div>
                                    <div class="row-form">
                                        <div class="span3"><?php echo custom_lang('almacen', "Almacen");?>:</div>
                                        <div class="span9">
                                                <?php echo form_dropdown('almacen_id', $data['almacenes']) ?>
                                                <?php echo form_error('almacen_id'); ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="span6">
                                    <div class="row-form">
                                        <div class="span3"><?php echo custom_lang('sima_date_v', "Proveedor");?>:</div>
                                        <div class="span9">
                                                <?php echo form_dropdown('proveedor_id', $data['proveedores']) ?>
                                                <?php echo form_error('proveedor_id'); ?>
                                        </div>
                                    </div>
                                    <div class="row-form">
                                        <div class="span3"><?php echo custom_lang('sima_date_v', "Codigo factura");?>:</div>
                                        <div class="span9">
                                                <?php echo form_input('codigo_factura') ?>
                                                <?php echo form_error('codigo_factura'); ?>
                                        </div>
                                    </div>
                                    <div class="row-form">
                                        <div class="span3"><?php echo custom_lang('sima_date_v', "Almacen de traslado");?>:</div>
                                        <div class="span9">
                                                <?php echo form_dropdown('almacen_traslado_id', $data['almacenes'], '', "disabled='disabled'"); ?>
                                                <?php echo form_error('almacen_traslado_id'); ?>
                                        </div>
                                    </div>
                                </div>

                                </div>
                                </div>

			
        <h5>2. Abra el archivo Plantilla Inventario que descargo a su computador y comience a ingresar el nombre que tiene el producto tal como se ingreso en el sistema y la cantidad</a>.</h5> 	                                        
       <img src="<?php echo base_url("/public/img/");?>/csv_inventario_1.png?act=2" width="800px" />
   
        <h5>4. Click en el boton buscar selecione la plantilla de excel que se encuentra en su computador con el inventario que que ingreso en el excel.</h5> 	
        <h5>5. Por ultimo click en enviar.</h5> 		   	
    </div>

</div>

<div class="row-fluid">

    <div class="span6">

        <div class="block">

        <?php

                $message = $this->session->flashdata('message');

                if(!empty($message)):?>

                <div class="alert alert-error">

                    <?php echo $message;?>

                </div>

                <?php endif; ?>

                            <?php echo validation_errors(); ?>

                            <div class="data-fluid">


                                <div class="row-form">

                                    <div class="span3"><?php echo custom_lang('sima_file', "Archivo");?>:<br/>

                                    </div>

                                    <div class="span9">                            

                                        <div class="input-append file">

                                            <input type="file" name="archivo"/>

                                            <input type="text"/>

                                            <button class="btn" type="button"><?php echo custom_lang('sima_search', "Buscar");?></button>

                                        </div> 

                                        <?php echo $data['data']['upload_error']; ?>
                                    </div>

                                </div> 

                                <div class="toolbar bottom tar">

                                    <div class="btn-group">

                                        <button class="btn"   onclick="javascript:this.form.submit();this.disabled= true;" type="submit"><?php echo custom_lang("sima_submit", "Enviar");?></button>

 <button class="btn btn-warning"  type="button" onclick="javascript:location.href='index'"><?php echo custom_lang('sima_cancel', "Cancelar");?></button>

                                    </div>

                                </div>

                            </div>

                            </form>

    </div>

    </div>

    

</div>