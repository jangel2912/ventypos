<?php     
    $ci = &get_instance();
    $ci->load->model("opciones_model");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

    <head>

        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

        <link rel="stylesheet" type="text/css" href="<?php echo base_url("/public/css/ticket.css"); ?>" media="screen"/>

        <link rel="stylesheet" type="text/css" href="<?php echo base_url("/public/css/ticket_print.css") ?>"  media="print"/>

        <style>
            * {
                font-size: 10px;
            }
        </style>

    </head>

    <body>

        <div id="contenedor">

            <div id="print_area">

<div id="ticket_wrapper">

    <div id="ticket_header">

        <div id="company_name"><?php echo $data['data_empresa']['data']['nombre']; ?></div>



        <div id="company_nit"><?php echo $data['data_empresa']['data']['documento'].":" . $data['data_empresa']['data']['nit']; ?></div>

        <div id="heading"> <?php echo $data['data_empresa']["data"]['cabecera_factura'];?></div>
    
        <table id="ticket_company" align="center">

            <tr>

               <td style="width:65%;text-align: center;"><?php echo $data['data_empresa']['data']['direccion']; ?> <?php // echo "Dir: ".$data['venta']['direccion'] ?></td>
               <!-- <td style="width:65%;text-align: left;"><?php // echo "Dir: ".$data['venta']['direccion'] ?></td> -->
               <!-- aterior <td style="width:35%;text-align: right;"><?php echo "Telf: ".$data['venta']['telefono'] ?></td>  -->            

            </tr>

            <tr>
                     <td style="width:65%;text-align: center;"><?php echo "Telf: ".$data['data_empresa']['data']['telefono']; ?> <?php // echo "Dir: ".$data['venta']['direccion'] ?></td>
            </tr>

        </table>            
 
        <table id="ticket_items" >
            <thead>
                <tr>
                    <th style="text-align:left">
                        Fecha
                    </th>
                    <th align="right">
                        Subtotal
                    </th>
                    <th align="right">
                        Total de ventas
                    </th>
                </tr>
            </thead>
            <tbody>
                    <?php 
function fechaespanol($fecha){ //yyyy-mm-dd
$diafecespanol=date("d", strtotime($fecha));
$diaespanol=date("N", strtotime($fecha));
$mesespanol=date("m", strtotime($fecha));
$anoespanol=date("Y", strtotime($fecha));
//Asignamos el nombre en español

// dia
    if($diaespanol == "1"){ $diaespan="Lunes"; }
    if($diaespanol == "2"){ $diaespan="Martes"; }
    if($diaespanol == "3"){ $diaespan="Miercoles"; }
    if($diaespanol == "4"){ $diaespan="Jueves"; }
    if($diaespanol == "5"){ $diaespan="Viernes"; }
    if($diaespanol == "6"){ $diaespan="Sabado"; }
    if($diaespanol == "7"){ $diaespan="Domingo"; }
        
//mes
    if($mesespanol == "1"){ $mesespan="Enero"; }
    if($mesespanol == "2"){ $mesespan="Febrero"; }
    if($mesespanol == "3"){ $mesespan="Marzo"; }
    if($mesespanol == "4"){ $mesespan="Abril"; }
    if($mesespanol == "5"){ $mesespan="Mayo"; }
    if($mesespanol == "6"){ $mesespan="Junio"; }
    if($mesespanol == "7"){ $mesespan="Julio"; }
    if($mesespanol == "8"){ $mesespan="Agosto"; }
    if($mesespanol == "9"){ $mesespan="Septiembre"; }
    if($mesespanol == "10"){ $mesespan="Octubre"; }
    if($mesespanol == "11"){ $mesespan="Noviembre"; }
    if($mesespanol == "12"){ $mesespan="Diciembre"; } 

//ano
    $anoespanol=$anoespanol;
    
//Fecha
$fecha=$diaespan." ".$diafecespanol." de ".$mesespan." del ".$anoespanol;

return $fecha;
}                $total=0; $subtotal=0;



				foreach($data1['total_ventas'] as $value){  ?>	
				   <tr><td  align="left" colspan="5"><b><?php echo fechaespanol($value['fecha_dia']);?> </b></td></tr>					
                        <tr>
                            <td></td>
	                        <td><p align="right"><b><?php echo $data_empresa['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($value['subtotal_precio_venta']);?> </b></td>						
                            <td><p align="right"><b><?php echo $data_empresa['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($value['total_precio_venta']);?></b></p></td>
                        </tr>																						
                    <?php   
					         $total += $value['total_precio_venta'];  
				             $subtotal += $value['subtotal_precio_venta'];  
				   ?>
				   <?php } ?>
                        <tr>
                       <?php if((!empty($data_empresa['data']['tipo_negocio'])) && (strtolower($data_empresa['data']['tipo_negocio'])=='restaurante')) { ?>
                            <td  style="border-top: inset 1px #000000;"><b>Totales - Devoluciones - Propinas</b></td>
                            <td align="right" style="border-top: inset 1px #000000;"><p align="right"><b><?php echo $data_empresa['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($subtotal -$data1['devoluciones']- $data1['propina'] );?></b></p></td>
                            <td align="right" style="border-top: inset 1px #000000;"><p align="right"><b><?php echo $data_empresa['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($total - $data1['devoluciones']- $data1['propina']);?></b></p></td>
                       <?php }else{ ?>
                            <td  style="border-top: inset 1px #000000;"><b>Totales - Devoluciones</b></td>
                            <td align="right" style="border-top: inset 1px #000000;"><p align="right"><b><?php echo $data_empresa['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($subtotal -$data1['devoluciones'] );?></b></p></td>
                            <td align="right" style="border-top: inset 1px #000000;"><p align="right"><b><?php echo $data_empresa['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($total - $data1['devoluciones']);?></b></p></td>
                        <?php } ?>
                            
							
                        </tr> 
				  </table> 




</div>

            </div>

        </div>

    </body>

</html>

<script type="text/javascript">

    window.print();

</script>