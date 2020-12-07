<div class="page-header">    
    <div class="icon">
        <img alt="Informes" class="iconimg" src="<?php echo $this->session->userdata('new_imagenes')['titulo_informes']['original'] ?>">        
    </div>
     <h1 class="sub-title"><?php echo custom_lang("Informes", "Informes");?></h1>
</div>
<div class="row-fluid">
    <div class="span12">
        <div class="block">
            <?php
                $message = $this->session->flashdata('message');
                if(!empty($message)):?>
                <div class="alert alert-success">
                    <?php echo $message;?>
                </div>
            <?php endif; ?>    
            <div id="mensaje" class="alert alert-error hidden"></div>  
            <div class="head blue">
                <h2><?php echo custom_lang('', "Ventas con Propina");?></h2>
            </div>
                <form action="<?php echo site_url("informes/export_propina_data");?>" method="POST" id="validate">
                <table>
                    <tr>
                        <td width="30%">Fecha Inicial : <input type="text" id="dateinicial" name="dateinicial" value="<?php echo $this->input->post('dateinicial');?>" class="datepicker" readonly required/>  </td>
                        <td width="30%">Fecha Final : <input type="text" id="datefinal" name="datefinal" value="<?php echo $this->input->post('datefinal');?>" class="datepicker" readonly required/>   </td>
						<td width="30%">Almacén :   
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
    ?> </td>
                        <!--<td width="30%"><br/> <input type="submit" value="Descargar" class="btn btn-success"/></td>-->
                        <td width="30%">
                            <a data-tooltip="Descargar Excel" onclick="verificar()">                        
                                <img alt="Descargar Excel" class="btnimagenes" src="<?php echo $this->session->userdata('new_imagenes')['buscar_verde']['original'] ?>"> 
                            </a> 
                        </td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
</div>

<script>
function verificar(){
        var fechainicial = $("#dateinicial").val();
        var fechafinal = $("#datefinal").val();
        
        if((fechainicial != "") &&(fechafinal != "")){                
            if((fechainicial)<=(fechafinal)) { 
                $("#mensaje").html("");
                $("#mensaje").addClass('hidden');
                $('#validate').submit();
            }else{                    
                $("#mensaje").html("La Fecha Final debe ser mayor a la Fecha Inicial");
                $("#mensaje").removeClass('hidden');
            }         
            
        }else{                
            $("#mensaje").html("Debe seleccionar los filtros a consultar");
            $("#mensaje").removeClass('hidden');
        }
    }
    //mixpanel.track("Informe_propina");     
</script>