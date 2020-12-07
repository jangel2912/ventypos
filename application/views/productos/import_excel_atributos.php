<div class="page-header">

    <div class="icon">

        <span class="ico-box"></span>

    </div>

    <h1><?php echo custom_lang("Productos", "Productos");?><small><?php echo $this->config->item('site_title');?></small></h1>

</div>

<div class="block title">

    <div class="head">

        <h4>Bienvenido sigua los siguiente pasos para cargar los productos desde Excel </h4> 
		<br />
        <h5>1. De click en la siguiente enlace para descargar la plantilla de excel &nbsp;&nbsp;<a href="<?php echo base_url("/uploads1/Plantilla Productos.xlsx"); ?>">CLICK AQUI</a>&nbsp;&nbsp; llamada Plantilla Productos.</h5>
		<table>
		<tr>
		<td>
		
		<table  border="1" cellpadding="0" cellspacing="0" width="400px" >
		<tr>
		<td class="head blue"><h5>&nbsp;&nbsp;Categorias</h5></td>
		<?php foreach($data['categorias'] as $value){  ?>
		<tr><td class="odd">&nbsp;&nbsp;<?php echo  $value->nombre; ?></td></tr>
		<?php  }  ?>	
		</table>	
        
		</td>
			<td width="50px" >	</td>
		
		</td>
			<td width="50px" >	</td>
		<td>		
		
		<table  border="1" cellpadding="0" cellspacing="0" width="250px" >
		<tr>
		<td class="head blue"><h5>&nbsp;&nbsp;Impuestos</h5></td>
		<?php foreach($data['impuestos'] as $value){  ?>
			
		<tr>
		<td >&nbsp;&nbsp;<?php echo  $value->nombre_impuesto; ?></td></tr>
		<?php  }  ?>	
		</table>

        </td>
		</tr>
		</table>				
        <h5>2. Abra el archivo Plantilla Productos que descargo a su computador y comience a ingresar los productos en el Excel que descargo como se muestra en la imagen de abajo,  las categor&iacute;as tiene que ser igual a las que ingreso al sistema previamente  que podr&acirc; ver en la tabla que dice categor&iacute;as que se encuentra arriba si falta una categoria puede dar <a href="../categorias/index" >click aqui para ingresar o editar las categorias</a> y el impuesto tiene que ser igual al que se encuentra en la tabla impuesto que se encuentra arriba.</h5> 	                                        
       <img src="<?php echo base_url("/public/img/");?>/csv_1.png?con=1" width="990px" />  	
        <h5>3. Click en el boton buscar selecione la plantilla de excel que se encuentra en su computador con los productos que ingreso en el excel.</h5> 	
        <h5>4. Por ultimo click en enviar.</h5> 		   	
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

                                <?php echo form_open_multipart("productos/import_excel_atributos", array("id" =>"validate"));?>

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

                                        <button class="btn"  onclick="javascript:this.form.submit();this.disabled= true;"  type="submit"><?php echo custom_lang("sima_submit", "Enviar");?></button>

                                        <button class="btn btn-warning" type="reset"><?php echo custom_lang('sima_cancel', "Cancelar");?></button>

                                    </div>

                                </div>

                            </div>

                            </form>

    </div>

    </div>

    

</div>