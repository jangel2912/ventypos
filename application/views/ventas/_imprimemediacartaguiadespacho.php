<!DOCTYPE html>
<html>

    <head>

        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

        <style>

            body{

                font-family: sans-serif;

                background-color:#FFFFFF;

                font-size:9pt;

            }

            .header{

                 font-size:10pt;

            }

            #contenedor{

    margin-top: 20px;
    margin-bottom: 1px;
    margin-right: 0px;
    margin-left: 30px;

            }


            #print_area{

            border:0px;

          }

          .resolucion{
             font-size:8pt;
          }

            

        </style>

    </head>

    <body>

        <div id="contenedor" >

            <div id="print_area" >

                <div id="ticket_header" >

                  <!--<div align="center"><img src="logo_ticket.jpg" width="338" height="114" border="0" /></div>-->
                     <table style="border: inset 1px #000000; border-bottom: 0px solid red;" width=818>
                        <tr>
                            <td  align="center" style=" font-size: 50px">
                               <B>REMITE DELICADO</B>
                        
                            </td>
                        <tr>  
                  </table>				  
				  
                     <table style="border: inset 1px #000000; border-bottom: 0px solid red;" width=818>
                        <tr>
                            <td width="33%"  align="center" style=" font-size: 16px">
                               <B><?php echo strtoupper($data['data_empresa']['data']['nombre']); ?></B><br>                                    
                                   
                                        <?php echo $data['data_empresa']['data']['documento'].":" . $data['data_empresa']['data']['nit']; ?><br/>
                                        <?php echo $data['data_empresa']['data']['resolucion'];?><br/>
                                        <?php echo $data['data_empresa']['data']['direccion'] ?><br/>
                                        <?php echo "<B>TEL:" . $data['data_empresa']['data']['telefono'] ?> <br/>
										<B><?php echo "" . $data['data_empresa']['data']['web'] ?> </B><br/>
										<B><?php echo "" . $data['data_empresa']['data']['email'] ?> </B>
                        
                            </td>

                            <td width="20%"  align="center">
                                  <?php if(!empty($data['data_empresa']['data']['logotipo'])) : ?>
                                    <img width="130px" height="130px" src="<?php echo base_url("uploads/{$data['data_empresa']['data']['logotipo']}");?>" />
                                <?php endif; ?>                                   
                            </td>

                            <td width="33%" align="left" style="border-left: 1px inset #000000;  font-size:27px">
                                <b>&nbsp;&nbsp; GUIA DE DESPACHO </b>
                          </td>
                        <tr>  
                  </table>
                    
                     <table style="border: inset 1px #000000; border-bottom: 0px solid red;" width=818>
                        <tr>
                            <td  align="center" style=" font-size: 43px">
                              <u> <i><B>DESTINATARIO:</B></i> </u>
                        
                            </td>
                        <tr>  
                  </table>	
                   <table  width=818 style=" font-size: 30px; border-left: 1px inset #000000; border-right: 1px inset #000000; border-top: 1px inset #000000; border-bottom: 0px solid red; ">
                     <tr>
                      <td><?php echo "" . strtoupper(($data['venta']['nombre_comercial'] == "" ? "Mostrador" : $data['venta']["nombre_comercial"])); ?></td>    			  
                      <?php if(isset($data['venta']['factura']) && !empty($data['venta']['factura'])):?>
                        <td style="border-left: 1px inset #000000;"> Factura: <?php echo $data['venta']['factura']; ?></td>    
                      <?php endif; ?>			  
                     </tr>
				  </table>
                   <table  width=818 style=" font-size: 30px; border-left: 1px inset #000000; border-right: 1px inset #000000; border-top: 1px inset #000000; border-bottom: 0px solid red; ">
                     <tr>
					 <td><?php echo "<B>".$data['venta']['tipo_identificacion'].":</B>  ".$data['venta']["nif_cif"]?></td>				  
                     </tr>
				  </table>
                   <table  width=818 style=" font-size: 30px; border-left: 1px inset #000000; border-right: 1px inset #000000; border-top: 1px inset #000000; border-bottom: 0px solid red; ">
                     <tr>
					 <td><?php echo "<B>DIRECCIÓN:  ".$data['venta']["cliente_direccion"]?></B></td>				  
                     </tr>
				  </table>
                   <table  width=818 style=" font-size: 30px; border-left: 1px inset #000000; border-right: 1px inset #000000; border-top: 1px inset #000000; border-bottom: 0px solid red; ">
                     <tr>
					 <td><?php echo "<B>CIUDAD:  ".$data['venta']["cliente_provincia"]?></B></td>				  
                     </tr>
				  </table>				  				  	 
                   <table  width=818 style=" font-size: 30px; border-left: 1px inset #000000; border-right: 1px inset #000000; border-top: 1px inset #000000; border-bottom: 0px solid red; ">					 
                     <tr>       
                      <td><?php echo "<B>Tel&eacute;fono: ".$data['venta']["cliente_telefono"]?></B></td>
					  <td><?php echo "<B>Celular: ".$data['venta']["cliente_movil"]; ?></B></td>				  
                     </tr>
                    <tr>
                     <td width="48%"></td>
					  <td></td>	
					   <td></td>	        
                    </tr>
                  
                  </table>
				<?php  if($data['venta']['nota'] != ''){   ?>
                   <table  width=821 style="border-left: 1px inset #000000; border-right: 1px inset #000000; border-top: 1px inset #000000; border-bottom: 0px solid red; ">
                     <tr>
                      <td width="48%"><?php echo $data['venta']['nota']; ?></td>        
                     </tr>	        
                    </tr>
                  </table>				  
				<?php  }   ?>  

                   <table  width=818 style="border: inset 1px #000000; border-bottom: inset 1px #000000;">

                        <th  style="border: inset 1px #000000; font-size: 17px" align="left"><?php echo "Ref" ?></th>
	                        <th  style="border: inset 1px #000000; font-size: 17px" align="left"><?php echo "Cantidad" ?></th>					
                        <th  style="border: inset 1px #000000; font-size: 17px"  align="left"><?php echo "Descripción" ?></th>						

                    </tr>							
                    <?php

                        $total = 0;

                        $timp  = 0;

                        $subtotal = 0;

                        $total_items = 0;

                    $group_by_impuesto = array();
                      $counter=NULL;
					  $hasta=NULL;
                    foreach ($data["detalle_venta"] as $p) {
					  if($p["nombre_producto"] != 'PROPINA'){  	
                        ?>
						   <tr>
                            <td  style="font-size: 17px" align="left"><?php echo $p["codigo_producto"] ?></td>	
	                        <td  style="font-size: 17px" align="left"><?php echo $p["unidades"] ?></td>											
                            <td  style="font-size: 17px" align="left"><?php echo $p["nombre_producto"] ?></td>
                           </tr>		
                       <?php
				      }
                    }
                    ?>
			
                  </table>
                   <table  width=818   style="border: inset 1px #000000; font-size: 30px">
                   <tr>
                     <td style=" width: 15%; border-right: inset 1px #000000;" align="left"  valign="bottom">
					 <b>TRANSPORTADORA:</b></td>
					  <td style=" width: 300px; border-right: inset 1px #000000; font-size: 11px"></td>	
                     <td style=" width: 300px;"></td>	
					  </td>

                    </tr>
                </table>			  
        <!--
                   <table  width=818 height=90  style="border: inset 1px #000000; font-size: 11px">
                   <tr>
                     <td style=" width: 15%;" align="center"  valign="bottom">
					 ___________________________________________
					 <br><B>FIRMA DEL DESPACHADOR</B></td>	
                     <td style="font-size: 9px;  width: 10%;"></td>
                     <td  style="width: 15%;" align="center"  valign="bottom">
					  ___________________________________________
					 <br><B>FIRMA DE QUIEN DE RECIBE</B>
					  </td>

                    </tr>
                </table>
          -->
                    <table  width=860>
                   <tr>
                     <td >&nbsp;</td>
                   </tr>
                </table>           

            </div>

        </div>

    </body>

</html>

<script type="text/javascript">

    window.print();

</script>

