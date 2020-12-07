<style>
    #reporte tr td 
    {
        white-space: nowrap;
    }
</style>
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
            <?php 
                endif; 
            ?>    
            <div class="head blue">
                <div class="icon"><i class="ico-box"></i></div>
                <h2><?php echo custom_lang('ventasxclientes', "Total de inventario por atributos");?></h2>
            </div>
        </div>
     </div>
</div>
<div class="row-fluid">
    <div class="span12 well">
        <form id="formulario" action="<?php echo site_url("informes/total_inventario_atributos_data");?>" method="POST">
            <div class="row-fluid">
                <?php if( $is_admin == 't' || $is_admin == 'a') { ?>
                    <div class="span3 form-group">
                        Almacen :  
                        <?php 
                        echo "<select  name='almacen' >";    
                            echo "<option value='0'>Todos los Almacenes</option>";    
                                foreach($data1['almacen'] as $f)
                                {
                                    if($f->id == $this->input->post('almacen')){
                                        $selected = " selected=selected ";
                                    } else {
                                        $selected = "";
                                    }        
                                    echo "<option $selected value=" . $f->id . ">" . $f->nombre . "</option>";
                                }
                        echo "</select>";    
                        ?>
                    </div>
                <?php } ?>
                <?php if( $filtro_ciudad == 'si') {?>
                    <div class="span3 form-group">
                        Ciudad : <?php echo form_dropdown('provincia', array(), set_value('provincia'), "id='provincia'");?>
                    </div>
                <?php } ?>
                <?php if( $is_admin == 't' || $is_admin == 'a') { //administrador    ?>
                    <div class="span3 form-group">
                        Categoria :  
                        <?php 
                            echo "<select name='id_categoria' id='id_categoria' >";    
                                echo "<option value='0'>Todas los categoria</option>";    
                                foreach($data1['categoria'] as $f){
                                    if($f->id == $this->input->post('categoria')){
                                        $selected = " selected=selected ";
                                    } else {
                                        $selected = "";
                                    }        
                                    echo "<option $selected value=" . $f->id . ">" . $f->nombre . "</option>";
                                }    
                            echo "</select>";
                        ?>
                    </div>
                <?php } ?>  
            </div>
            <div class="row-fluid" id="select">
                
            </div>
            <div class="row-fluid">
                <div class="span4">
                    <input id="consultar" type="button" value="Consultar" class="btn btn-primary"/> &nbsp; 
                    <input type="submit" value="Exportar a excel" class="btn btn-primary"/>
                    
                </div>
                <div class="span4">
                  <div id="div_mensaje_consultando" style="display: none;">
                    <div class="alert alert-info">
                        Consultando.... por favor espere un momento
                    </div>
                  </div>
                </div>
            </div>
      </form>
	</div>
</div>
<div class="row-fluid">
    <div class="span12"> 
        <div class="overflow" style="width:100%; heigt:auto; overflow-x:auto;">
             <table id="reporte" width="100%" class="table table-striped">
              
            </table>
        </div>
    </div>
</div>
<script type="text/javascript">
    var data_table;
    $(document).ready(function() {
        $("#id_categoria").change(function() {
              $("#select").html('');
              $.ajax({
                  async: false, //mostrar variables fuera de el function 
                  url: "<?php echo site_url("informes/filtro_atributos_categoria"); ?>",
                  type: "post",
                  dataType: "json",
                  data: {  
                      id: $("#id_categoria").val() 
                  },
                  success: function(data1) {  
                      for(var e in data1) {
                          rowHtml = "<div class='span3 form-group'>"+
                                        data1[e].nombre_atributo+": "+
                                    "<select name='nombreatributo_"+data1[e].atributo_id+"_"+data1[e].nombre_atributo+"' id='"+data1[e].nombre_atributo+"' ><option value='-1'>Todos(as) los(as) "+data1[e].nombre_atributo+"</option>";
          		            $.ajax({
          				            async: false, //mostrar variables fuera del function 
                              url: "<?php echo site_url("informes/filtro_atributos_detalle"); ?>",
                              type: "post",
                              dataType: "json",
                              data: {  id: data1[e].atributo_id },
                              success: function(data2) {
                                  for(var i in data2)
                                  {
                                      rowHtml += "<option value='"+data2[i].valor+"'>"+data2[i].valor+"</option>";
                                  }
                              }
                          });
                          rowHtml += "</select></div>";
                          $("#select").append(rowHtml);
                          //data_table.fnDraw();
                      }
                  }
            });	
        });	

        $('#consultar').on('click', function(e){
            $("#div_mensaje_consultando").show('slow');

            $.post(
                "<?= site_url('informes/total_inventario_atributos_ajax_data'); ?>",
                $('#formulario').serialize(),
                function(data){
                    $("#div_mensaje_consultando").hide();
                    var ventas = data.total_ventas;
                    var html = '<thead><tr>';
                        $.each(ventas['columnas'], function(index, el) {
                            html += '<th>'+el+'</th>';
                        });
                    html += '</tr></thead><tbody>';

                    $.each(ventas, function(index, el){
                      if(index != 'columnas'){
                          html += '<tr>';
                            $.each(ventas[index], function(index, el){
                                html += '<td>'+(el == null ? '' : el)+'</td>';
                            });
                          html += '</tr>';
                      }
                    });
                    html+='</tbody>'
                    $('#reporte').html(html);
                    crear_datatable();
                },
                'json'
            );
        });
       
        <?php if($this->input->post('categoria')){ ?>
   $(document).ready(function(){
	           $.ajax({
			               async: false, //mostrar variables fuera de el function 
                            url: "<?php echo site_url("informes/filtro_atributos_categoria"); ?>",
                            type: "post",
                            dataType: "json",
                            data: {  id: <?php echo $this->input->post('categoria'); ?> },
                            success: function(data1) {
		   //alert(data);
                              fo
                              r(var e in data1)
					 	      {
					              
		                            rowHtml = ""+data1[e].nombre_atributo+": <select  name='"+data1[e].nombre_atributo+"' id='"+data1[e].nombre_atributo+"' ><option value='0'>Todos(as) los(as) "+data1[e].nombre_atributo+"</option>";
	                  
		<?php 	
$i=1;		
 foreach($atributos as $nombre_campo => $valor){ 
  if($i >= 5 && $valor != '0' ){
 ?>
          //alert('<?php echo $valor ?> '+data2[i].valor);
	    if(data1[e].nombre_atributo ==  '<?php  echo $nombre_campo ?>'){
            rowHtml += "<option selected=selected value='<?php  echo $valor ?>'><?php  echo $valor ?> selecionado</option>";
        } 
<?php 
   } 
   $i++;
  }
		?> 		
	
		           $.ajax({
				           async: false, //mostrar variables fuera de el function 
                            url: "<?php echo site_url("informes/filtro_atributos_detalle"); ?>",
                            type: "post",
                            dataType: "json",
                            data: {  id: data1[e].atributo_id },
                            success: function(data2) {
		 
                              for(var i in data2)
					 	      {

							      rowHtml += "<option value='"+data2[i].valor+"'>"+data2[i].valor+"</option>";
									
                              }
		       
                        }
                });									
									
									rowHtml += "</select>";
                                 $("#select").append(rowHtml);
					 	 
                              }
		       
                        }
                });	   
    });
<?php } ?>

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

            data: {"pais" : 'Colombia'},

            dataType: "json",

            success: function(data) {

                $("#provincia").html('');
				
				  $("#provincia").append("<option value=''>Todas las ciudades</option>"); 

                $.each(data, function(index, element){

                    provincia = "<?php echo $this->input->post('provincia');?>"

                    sel = provincia == element[0] ? "selected='selectted'" : '';

                   $("#provincia").append("<option value='"+ element[0] +"' "+sel+">"+ element[0] +"</option>"); 

                });

            }

        });

    }


    function crear_datatable(){
        
      if ( ! $.fn.DataTable.isDataTable( '#reporte' ) ) {

          data_table = $('#reporte').dataTable();
      }else{
          //data_table.fnDraw();
          //data_table = $('#reporte').DataTable();
          data_table.fnDestroy();
          data_table = $('#reporte').dataTable();
      } 

     } 
    
</script> 