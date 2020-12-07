<div class="page-header">    
    <div class="icon">
        <img alt="ventas_online" class="iconimg" src="<?php echo $this->session->userdata('new_imagenes')['titulo_venta_online']['original'] ?>">        
    </div>
     <h1 class="sub-title"><?php echo custom_lang("Ventas Online", "Ventas Online");?></h1>
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
            <?php 
                $permisos = $this->session->userdata('permisos');
                $is_admin = $this->session->userdata('is_admin');
             ?>
            <div class="col-md-6">
            </div>
            <?php if(in_array("68", $permisos) || $is_admin == 't'): ?>
                <!--<a href="<?php echo site_url("ventas_online/ventas")?>" class="btn"><small class="ico-sale icon-white"></small> <?php echo custom_lang('sima_new_bill', "Hostorico de Solicitudes de Ventas ");?></a>-->
            <div class="col-md-6 btnizquierda">
                <div class="col-md-2 col-md-offset-10">
                    <a href="<?php echo site_url("ventas_online/ventas")?>" data-tooltip="Listado de Ventas Online">                        
                        <img alt="ventas Online" class="btnimagenes" src="<?php echo $this->session->userdata('new_imagenes')['ventas_online_verde']['original'] ?>">                           
                    </a>                    
                </div>
            </div>
            <?php endif;?>
        </div>
    </div>
</div>
<div class="row-fluid">
    <div class="span12">
        <div class="block">            
            <div class="head blue">
                <h2><?php echo custom_lang('sima_outstanding_all', "Listado de Venta Online Anuladas"); ?></h2>
            </div>
            <div class="data-fluid">
                <table class="table aTable" cellpadding="0" cellspacing="0" width="100%" id="ventas_onlineTable">
                    <thead>
                        <tr> 
                            <th width="7%"><?php echo custom_lang('ventaonline_number', "N°.");?></th>
                            <th width="18%"><?php echo custom_lang('ventaonline_name', "Nombre");?></th>
                            <th width="12%"><?php echo custom_lang('ventaonline_customer_dni', "Cédula");?></th>
                            <th width="18%"><?php echo custom_lang('ventaonline_email', "Email");?></th>
                            <th width="10%"><?php echo custom_lang('ventaonline_fecha', 'Fecha');?></th>
                            <th width="12%"><?php echo custom_lang('ventaonline_valor', "Valor");?></th>
                            <th width="13%"><?php echo custom_lang('ventaonline_estado', "Estado");?></th>
                            <th  width="10%"><?php echo custom_lang('sima_action', "Acciones");?></th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                    <tfoot>                           
                        <tr> 
                            <th width="7%"><?php echo custom_lang('ventaonline_number', "N°.");?></th>
                            <th width="18%"><?php echo custom_lang('ventaonline_name', "Nombre");?></th>
                            <th width="12%"><?php echo custom_lang('ventaonline_customer_dni', "Cédula");?></th>
                            <th width="18%"><?php echo custom_lang('ventaonline_email', "Email");?></th>
                            <th width="10%"><?php echo custom_lang('ventaonline_fecha', 'Fecha');?></th>
                            <th width="12%"><?php echo custom_lang('ventaonline_valor', "Valor");?></th>
                            <th width="13%"><?php echo custom_lang('ventaonline_estado', "Estado");?></th>
                            <th  width="10%"><?php echo custom_lang('sima_action', "Acciones");?></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>       
    </div>
</div>   

<style >

    .tabhead{

        background-color: #DDD ;

        //border: 1px solid #393b3b !important;

        text-align: center;

    }

    .tabp{

        width: 220px;

        border: 1px solid #DDD;

    }

    .tabpr{

        width: 150px;

        text-align: center;

        border: 1px solid #DDD;

    }

    .tabc{

        width: 100px;

        text-align: center;

        border: 1px solid #DDD;

    }

    .tabt{

        width: 150px;

        text-align: center;

        border: 1px solid #DDD;

    }

    .oscuro{

        font-weight: bold;

    }

    .oscurorojo{

        font-weight: bold;

        color: red;

    }

</style>



<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="width: 600px !important">

    <div class="modal-dialog">

                    <div class="modal-content">

                        <div class="modal-header">

                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>

                            <h4 class="modal-title" id="myModalLabel">&nbsp; Detalles de la Solicitud de Venta Online #<span id="det_id"></span>  </h4>

                        </div>

                        <div class="modal-body">

                            <div id="det_fecha_estado" class="span6" style="text-align: right; position: absolute; margin-left: 0px !important"></div>

                            

                            <div class="span6" style="margin-left: 0px !important">

                                <h6>Datos del Cliente:</h6>

                                <div id="det_nombre" class="span3"></div>

                                <div id="det_cedula" class="span2"></div>

                                <div id="det_email" class="span3"></div>

                                <div id="det_telefono" class="span2"></div>

                                <div id="det_cpostal" class="span3"></div>

                                <div id="det_movil" class="span2"></div>

                                <div id="det_direccion" class="span3"></div>

                                <div id="det_fax" class="span2"></div>

                                <div id="det_notas" class="span4"></div>

                            </div>

                            <div class="span6" style="margin-left: 0px !important">

                                <h6>Relaci&oacute;n de productos:</h6>

                                <table id="det_productos">

                                    

                                    

                               </table>

                                

                            </div>

                            <div class="span6" style="margin-left: 0px !important"> &nbsp; </div>

                            <div class="span6" style="margin-left: 0px !important">

                                

                                <div class="span2"> </div>

                                <div id="det_subtotal" class="span3" style="text-align: right"> Subtotal:  </div>

                                <div class="span2"> </div>

                                <div id="det_impuesto" class="span3" style="text-align: right"> Impuesto:  </div>

                                <div class="span2"> </div>

                                <div id="det_total" class="span3" style="text-align: right"> Total:  </div>

                            </div>



                        </div>

                        <div class="modal-footer">

                            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>



                        </div>

                    </div>

                    

                </div>

    <!-- /.modal-dialog -->

</div>

<script type="text/javascript">



    $(document).ready(function(){



        $('#ventas_onlineTable').dataTable( {



                "aaSorting": [[ 1, "desc" ]],



                "bProcessing": true,



                "bServerSide": true,



                "sAjaxSource": "<?php echo site_url("ventas_online/get_ajax_data_anulada");?>",



                "sPaginationType": "full_numbers",



                "iDisplayLength": 10, "aLengthMenu": [5,10,25,50,100],



                "aoColumnDefs" : [



                    { "bSortable": false, "aTargets": [ 7 ], "bSearchable": false, 



                        "mRender": function ( data, type, row ) {



                         // alert(row);

                           var buttons = "";



                            <?php if(in_array('57', $permisos) || $is_admin == 't'):?>



                               buttons += '<a style="cursor:pointer;"  id="d_'+row[0]+'" data-toggle="modal" data-target="#myModal"  class="button blue detall" title="Ver Detalles"><div class="icon"><span class="ico-files"></span></div></a>';



                            <?php endif;?>                           

							

	                     



                            return buttons;



                        } 



                    }



                ]



        });

        

$('body').on('click','.detall',function(e){           

                     e.preventDefault();

                     var ids =$(this).attr('id');

                     var id = ids.split('_');

                     var url = "<?php echo site_url('ventas_online/get_detalle_solicitud/')?>" ;

                     $.getJSON(

                        url +'/'+ id[1],

                        function(data){  

                            //alert(data);

                           $('#det_nombre').html("Nombre: "+ data['nombre']); 

                           $('#det_cedula').html("C&eacute;dula: "+ data['cedula']); 

                           $('#det_email').html("Email: "+ data['email']); 

                           $('#det_telefono').html("Tel&eacute;fono: "+ data['telefono']); 

                           $('#det_movil').html("Movil: "+ data['movil']); 

                           $('#det_fax').html("Fax: "+ data['fax']); 

                           $('#det_cpostal').html("C&oacute;digo Postal: "+ data['cpostal']); 

                           $('#det_direccion').html("Direcci&oacute;n: "+ data['direccion']); 

                           $('#det_notas').html("Notas: "+ data['notas']); 

                           $('#det_id').html(data['id']); 

                           $('#det_fecha_estado').html(data['fecha_estado']); 

                           $('#det_productos').html(data['productos']);

                           

                           $('#det_subtotal').html("Subtotal: $ "+ data['subtotal']);

                           $('#det_impuesto').html("Impuesto: $ "+ data['impuesto']);

                           $('#det_total').html("Total: $ "+ data['total']);

                        });

                    });

            



    });

function cartel(){ alert('Visor de detalles en desarrollo');}

</script>







