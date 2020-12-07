<?php
$ci =&get_instance();
$ci->load->model("opciones_model");
?>

<style>
    .ui-datepicker{
        background-color: white;
    }
</style>
<div class="page-header">    
    <div class="icon">
        <img alt="Informes" class="iconimg" src="<?php echo $this->session->userdata('new_imagenes')['titulo_informes']['original'] ?>">        
    </div>
     <h1 class="sub-title"><?php echo custom_lang("Informes", "Informes");?></h1>
</div>

<!--<a href="#" id="ex" class="btn btn-success"><small class="ico-circle-arrow-down icon-white"></small><?php echo custom_lang('sima_export', "Exportar a Excel");?></a>-->
<!--<a data-tooltip="Exportar Excel" id="ex">                        
    <img alt="Exportar" class="btnimagenes" src="<?php echo $this->session->userdata('new_imagenes')['exportar_excel_verde']['original'] ?>"> 
</a> -->
<div class="row-fluid">
    <div class="span12">
        <div class="block">
            <?php
                $is_admin = $this->session->userdata('is_admin');
		        $username = $this->session->userdata('username');	
                $message = $this->session->flashdata('message');
                if(!empty($message)):?>
                <div class="alert alert-success">
                    <?php echo $message;?>
                </div>
            <?php endif; ?>    
            <div id="mensaje" class="alert alert-error hidden"></div>  
            <div class="head blue">
                <h2><?php echo custom_lang('ventasxclientes', "H&aacute;bitos de Consumo por Día");?></h2>
            </div>
            <br>
            <form class="form-inline" action="<?php echo site_url("informes/habitos_consumo_dia_data");?>" method="POST"  id="f_consumo_dia" >
                <div class="form-group">
                    <label for="exampleInputName2">Fecha Inicial</label>
                    <input type="text" id="dateinicial" name="dateinicial" value="<?php echo $this->input->post('dateinicial');?>" class="datepicker form-control" readonly required/>
                </div>
                <div class="form-group">
                    <label for="exampleInputEmail2">Fecha Final</label>
                    <input type="text" id="datefinal" name="datefinal" value="<?php echo $this->input->post('datefinal');?>" class="form-control datepicker" readonly required/>
                </div>
                <?php  if( $is_admin == 't' || $is_admin == 'a'){ //administrador ?>
                <div class="form-group">
                    <label for="exampleInputEmail3">Almacén</label>
                    <?php 
                        echo "<select id='almacen' name='almacen' class='form-control' >";    
                        echo "<option value='0'>Todos los Almacenes</option>";    
                        foreach($data1['almacen'] as $f){
                            if($f->id == $this->input->post('almacen')){
                                $selected = " selected=selected ";
                            } else {
                                $selected = "";
                            }        
                            echo "<option $selected value=" . $f->id . ">" . $f->nombre . "</option>";
                        }    
                        echo "</select>";?>
                </div>
                <?php }	 ?>  
                                
                <a data-tooltip="Consultar" onclick="verificar()">                        
                    <img alt="Consultar" class="btnimagenes" src="<?php echo $this->session->userdata('new_imagenes')['buscar_verde']['original'] ?>"> 
                </a> 
            </form>
        </div>
    </div>
</div>
<?php if(isset($fechafinal) && !empty($fechainicial)){?>
<div class="col-md-12">
    <div class="col-md-6">
        <!--<a href="#" id="ex" class="btn btn-success"><small class="ico-circle-arrow-down icon-white"></small><?php echo custom_lang('sima_export', "Exportar a Excel");?></a>-->    
    </div>
    <div class="col-md-6 btnizquierda">
        <div class="col-md-2 col-md-offset-10">
            <a data-tooltip="Exportar Excel" id="ex">                        
                <img alt="Exportar" class="btnimagenes" src="<?php echo $this->session->userdata('new_imagenes')['exportar_excel_verde']['original'] ?>"> 
            </a> 
        </div>
    </div>
</div>

<div id="habitos_consumo_dia">
    <div class="row-fluid">                             
        <div class="span12">
            <div class="block">       
                <table class="table" width="100%" cellspacing="0" cellpadding="0">                     
                    <tr>
                        <td colspan="2"><b>Fecha </b></td>
                        <td colspan="3" align="right"><b>Total de ventas</td>		
                    </tr>
                    <template v-for="(item_1, i_1) in total_ventas_1">
                        <tr :id="'tr-'+(i_1 + 1)">
                            <th><b>{{ fechaespanol(item_1.fecha) }}</b></th>
                            <th><b>Nombre del producto</b></th>
                            <th><b>Cantidad Vendida</b></th>
                            <th>&nbsp;</th>
                            <th>
                                <p align="right">
                                    <b>{{ number_format(Number(item_1.total_venta) - ((item_1.devolucion) ? item_1.devolucion : 0) ) }}
                                        
                                    </b>
                                    <span v-if="item_1.devolucion">
                                        ( {{ number_format(Number(item_1.total_venta)) }} - {{ number_format(item_1.devolucion) }} )
                                    </span>
                                </p>
                            </th>
                            <!-- <th><p align="right"><b>{{ number_format(Number(item_1.total_venta) - devoluciones)  }}</b></p></th> -->
                        </tr>
                        <template v-for="(item_3, vindex) in item_1.sales">
                            <tr v-if="item_1.fecha == item_3.fecha">
                                <td>{{ item_3.codigo_producto }}</td>
                                <td>{{ item_3.nombre }}</td>
                                <td>{{ item_3.unidades }}</td>
                                <td colspan="2" style="text-align: center;">{{ number_format(item_3.total_detalleventa) }}</td>
                            </tr>
                            <template v-if="(item_1.sales.length - 1) == vindex && item_1.sales.length >= 10 && !item_1.statusShow">
                                <tr class="text-center">
                                    <td colspan="5">
                                    <button v-if="!item_1.statusShow" class='btn btn-success' name='filtrar_2' id='filtrar_2' value='Filtrar' v-on:click="getMoreSales(item_1)">
                                        <small class='fa fa-search'></small> Cargar mas...</button>
                                        <!-- <a v-if="!item_1.statusLoadding" style="cursor: pointer;" v-on:click="getMoreSales(item_1)">Cargar mas...</a> -->
                                        <!-- <img v-if="item_1.statusLoadding" src="<?= base_url('assets/esperar.gif'); ?>" alt="Cargando..." width="30"> -->
                                    </td>
                                </tr>
                            </template>
                        </template>
                    </template>
                    </tr>
                    <!-- Jeisson Rodriguez --- 02-09-2019 -->
                    <!--  -->
                    
                    <tr v-if="status_show">
                        <td><b>Total </b></td>
                        <td></td>
                        <td colspan="3"><p align="right"><b> {{ returnTotal() }} </b></p></td>
                        <!--<td><b>&nbsp; </td>-->
                    </tr>
                </table>
                <div class="text-center center"  style="height: 100px; min-height: 100px; padding-top: 1em;">
                    <h3 v-if="status_show_registers" style="color: #ccc;">NO HAY MAS REGISTROS</h3>
                    <h5 v-if="status_show_registers" style="color: #ccc;">Solo se mostrarán los 10 primeros registros por día, para ver mas detalladamente las ventas del día exportar el Excel o ir al histórico de ventas.</h5>
                    <img v-if="status_show_img" src="<?= base_url('assets/esperar.gif'); ?>" alt="Cargando..." width="30">
                </div>
            </div>
        </div>
    </div>
</div>

<?php } ?>
<script src="https://use.fontawesome.com/512cd430cc.js"></script>
<script src="<?= base_url('/assets/toMoney.js') ?>"></script>
<script>
    var url_habitos = "<?php echo site_url("informes/habitos_consumo_dia_data_ajax");?>";
    var url_sales_days = "<?php echo site_url("informes/getSalesByDay");?>";
    datacurrency = JSON.parse('<?php echo json_encode($datacurrency);?>');
</script>
<script src="<?= base_url('/assets/api_url.js') ?>"></script>
<script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
<script src="https://cdn.jsdelivr.net/vue.resource/1.3.1/vue-resource.min.js"></script>
<script src="<?= base_url('/application/views/informes_vue/habitos_consumo_dia.js') ?>"></script>

<script type="text/javascript">
    window.onscroll = () => {
        let bottomOfWindow = Math.trunc(document.documentElement.offsetHeight + document.documentElement.scrollTop) == document.documentElement.scrollHeight;
        if (bottomOfWindow) {
            app.getHabitosConsumo();
        }
    };

    function verificar(){
        var fechainicial = $("#dateinicial").val();
        var fechafinal = $("#datefinal").val();
        var almacen = $("#almacen").val();
        
        if((fechainicial != "") &&(fechafinal != "") && (almacen!="")){
            if((fechainicial)<=(fechafinal)) {                    
                $("#mensaje").html("");
                $("#mensaje").addClass('hidden');
                $('#f_consumo_dia').submit();
            }else{                    
                $("#mensaje").html("La Fecha Final debe ser mayor a la Fecha Inicial");
                $("#mensaje").removeClass('hidden');
            }       
        }else{                
            $("#mensaje").html("Debe seleccionar los filtros a consultar");
            $("#mensaje").removeClass('hidden');
        }
    }

    $(document).ready(function(){
        $("#ex").click(function(event){
            event.preventDefault();    
            var $fecha_inicio = $("input[name='dateinicial']"),$fecha_final = $("input[name='datefinal']"),$almacen = $("input[name='almacen']");
            if($fecha_inicio.val() == '' && $fecha_final.val() == ''){
                $("#mensaje").html("Debe seleccionar el rango de fechas, antes de generar el Excel");
                $("#mensaje").removeClass('hidden');
            }else{
                var dir = "<?php echo site_url("informes/habitos_consumo_dia_excel");?>";
                $("#mensaje").html("");
                $("#mensaje").addClass('hidden');
                $("#f_consumo_dia").attr('action',dir);
                $("#f_consumo_dia").submit();
            }
        });
    })
                
    mixpanel.track("Informe_habitos_consumo_dia");  
</script>