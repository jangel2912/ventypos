<div class="page-header">    
    <div class="icon">
        <img alt="movimientos" class="iconimg" src="<?php echo $this->session->userdata('new_imagenes')['titulo_movimientos']['original'] ?>">        
    </div>
     <h1 class="sub-title"><?php echo custom_lang("movimientos", "Movimientos");?></h1>
</div>
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
            <div class="head blue">
                <h2><?php echo custom_lang('Consolidado del inventario', "Consolidado del Inventario");?></h2>
            </div>
            </div>
        </div>
											
    <div class="span12 block">

                            <div class="data-fluid">


                                <div class="row-form">

                                    <div class="span10">
                           <h5>De click en la siguiente enlace para descargar la plantilla de excel &nbsp;&nbsp;<a href="<?php echo base_url("/uploads1/consolidado.xlsx"); ?>">CLICK AQUI</a>&nbsp;&nbsp; llamada consolidado.</h5>
                                    </div>
								</div>	

                                <div class="row-form">

                                    <div class="span1"><?php echo custom_lang('sima_file', "Excel");?>:<br/>

                                    </div>

                                    <div class="span9">   
	                                    <?php echo form_open_multipart("inventario/consolidado_inventario_almacen", array("id" =>"validate"));?>					
                                        <div class="input-append file">

                                            <input type="file" name="archivo"/>

                                            <input type="text"/>

                                            <button class="btn btn-success" type="button"><?php echo custom_lang('sima_search', "Buscar");?></button>

                                        </div> 
										
	
                                <div class="toolbar bottom tar" style="float:left">

                                    <div class="btn-group" align="center">

                                        <button class="btn btn-success"  onclick="javascript:this.form.submit();this.disabled= true;"  type="submit"><?php echo custom_lang("sima_submit", "Comparar");?></button>

                                    </div>

                                </div>									
    </form>			
                                      </div>

                                </div> 
			</div>					
		</div>						
	                 <table class="table" width="100%" cellspacing="0" cellpadding="0">
                        <tr>
                           <td  style="border-bottom: inset 1px #000000;"><p><b>Almacén</b></p></td>
							<td style="border-bottom: inset 1px #000000;"><p ><b>Código del producto</b></p></td>
                            <td style="border-bottom: inset 1px #000000;"><b>Nombre del producto</b></td>							
                            <td style="border-bottom: inset 1px #000000;"><p><b>Cantidad actual del sistema</b></p></td>
                           <td  style="border-bottom: inset 1px #000000;"><p><b>Cantidad física</b></p></td>
                           <td  style="border-bottom: inset 1px #000000;"><p><b>La diferencia es</b></p></td>
                        </tr>			
				<?php foreach($productos as $value){ ?>	
                        <tr>
                            <td><b>
							<?php echo ($value['almacen']);  ?> 
							</b></td>
                            <td><b><?php echo ($value['codigo']);?> </b></td>
                            <td><b><?php echo ($value['nombre']);?> </b></td>
	                        <td>
							  <b><?php echo number_format($value['unidades']);?></b>
						 	  <input type="hidden" name="unidades_actuales" id="unidades_actuales<?php echo $value['id'];?>" value="<?php echo $value['unidades'];?>" /> </b>
							 </td>						
                            <td width="20%"><p align="right">
							  <?php if(!empty($value['unidades_fisicas'])){ ?>
						     	<input type="text" name="unidades_fisicas" id="unidades_fisicas<?php echo $value['id'];?>"  value="<?php echo $value['unidades_fisicas'];?>" />
							  <?php 
							  }
							  else{
							  ?><input type="text" name="unidades_fisicas" id="unidades_fisicas<?php echo $value['id'];?>"  value="0" /><?php 
							  }
							   ?>
							 </b></td>
							<td id="diferencia<?php echo $value['id'];?>"></td>
                        </tr>	
				 <?php } ?>		
				  </table> 		
				  
				  
    </div>
    </div>
<script type="text/javascript">
        function calculate(){

        }
		
    $(document).ready(function(){

       $("#pais").change(function(){

           load_provincias_from_pais($(this).val());

       }); 
   <?php foreach($productos as $value){ ?>	
     $( "#unidades_fisicas<?php echo $value['id'];?>").live("keyup",(function(){
		 
		    var diferencia = parseFloat($("#unidades_actuales<?php echo $value['id'];?>").val()) - parseFloat($("#unidades_fisicas<?php echo $value['id'];?>").val());

       if(parseFloat($("#unidades_actuales<?php echo $value['id'];?>").val()) != parseFloat($("#unidades_fisicas<?php echo $value['id'];?>").val())){
	   
	        if(diferencia < 0){
		     var diferencia = "'"+(diferencia)+"'";
		       var diferencia_final = diferencia.replace("-", "");
		          diferencia_final = diferencia_final.replace("'", "");
		          diferencia_final = diferencia_final.replace("'", "");
              $("#diferencia<?php echo $value['id'];?>").text('Falta '+ diferencia_final );
		     } 
	           if(diferencia > 0){
		          var diferencia = "'"+(diferencia)+"'";
		         var diferencia_final = diferencia.replace("-", "");
		          diferencia_final = diferencia_final.replace("'", "");
		          diferencia_final = diferencia_final.replace("'", "");
                 $("#diferencia<?php echo $value['id'];?>").text('Sobra '+ diferencia_final );
		        } 
		}
       if(parseFloat($("#unidades_actuales<?php echo $value['id'];?>").val()) == parseFloat($("#unidades_fisicas<?php echo $value['id'];?>").val())){
	   
           $("#diferencia<?php echo $value['id'];?>").text('0');
		  
		}		
		
	  }));
<?php  } ?>	


   <?php foreach($productos as $value){ ?>	
		 
		    var diferencia = parseFloat($("#unidades_actuales<?php echo $value['id'];?>").val()) - parseFloat($("#unidades_fisicas<?php echo $value['id'];?>").val());

       if(parseFloat($("#unidades_actuales<?php echo $value['id'];?>").val()) != parseFloat($("#unidades_fisicas<?php echo $value['id'];?>").val())){
	   
	        if(diferencia < 0){
		     var diferencia = "'"+(diferencia)+"'";
		       var diferencia_final = diferencia.replace("-", "");
		          diferencia_final = diferencia_final.replace("'", "");
		          diferencia_final = diferencia_final.replace("'", "");
              $("#diferencia<?php echo $value['id'];?>").text('Falta '+ diferencia_final );
		     } 
	           if(diferencia > 0){
		          var diferencia = "'"+(diferencia)+"'";
		         var diferencia_final = diferencia.replace("-", "");
		          diferencia_final = diferencia_final.replace("'", "");
		          diferencia_final = diferencia_final.replace("'", "");
                 $("#diferencia<?php echo $value['id'];?>").text('Sobra '+ diferencia_final );
		        } 
		}
       if(parseFloat($("#unidades_actuales<?php echo $value['id'];?>").val()) == parseFloat($("#unidades_fisicas<?php echo $value['id'];?>").val())){
	   
           $("#diferencia<?php echo $value['id'];?>").text('0');
		  
		}		
		
<?php  } ?>	


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
    
</script> 