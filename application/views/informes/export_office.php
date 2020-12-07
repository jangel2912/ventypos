<div class="page-header">
    <div class="icon">
        <span class="ico-box"></span>
    </div>
    <h1><?php echo custom_lang("Informes", "Informes");?></h1>
</div>
<div class="block title">
    <div class="head">
        <h2><?php echo custom_lang('cuadrecaja', "Exportaci&oacute;n a WorldOffice");?></h2>                                          
    </div>
</div>
<div class="row-fluid">
    <div class="span12">
        <div class="block">
            <?php
                $is_admin = $this->session->userdata('is_admin');
		        $username = $this->session->userdata('username');
                $message = $this->session->flashdata('message');
                if(!empty($message)):
            ?>
                <div class="alert alert-success">
                    <?php echo $message;?>
                </div>
            <?php endif; ?>
            <div class="head blue">
                <div class="icon"><i class="ico-box"></i></div>
                <h2><?php echo custom_lang('ventasxclientes', "Exportaci&oacute;n a WorldOffice");?></h2>
            </div>
                <form action="<?php echo site_url("informes/export_office_data");?>" method="POST">
                <table>
                    <tr>
                        <td width="30%">Fecha Inicial : <input type="text" name="dateinicial" value="<?php echo $this->input->post('dateinicial');?>" class="datepicker"/>  </td>
                        <td width="30%">Fecha Final : <input type="text" name="datefinal" value="<?php echo $this->input->post('datefinal');?>" class="datepicker"/>   </td>
						
					<?php if( $is_admin == 't' || $is_admin == 'a'){ //administrador ?>	
						<td width="30%">Almacen :   
	<?php 
	echo "<select  name='almacen' >";    
    echo "<option value='0'>Todos los Almacenes</option>";    
    foreach($data1['almacen'] as $f){
        if($f->id == $this->input->post('almacen')){
            $selected = " selected=selected ";
        } else {
            $selected = "";
        }        
        echo "<option $selected value=" . $f->id . ">" . $f->nombre . "</option>";
    }    
    echo "</select>";
    ?> 
	               </td>
					<?php } ?>		
                        <td width="30%"><br/> <input type="submit" value="Descargar" class="btn btn-success"/></td>
                    </tr>
                </table>
            </form>
            </div>
        </div>
