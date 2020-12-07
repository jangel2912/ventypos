<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <style type="text/css">
            .logo{padding:20px 0 20px 20px;}
            h1{padding-left:5px;color:#424242;font-family:Arial,Helvetica,sans-serif;font-size:19px;line-height:20px;padding-bottom:5px;margin:0}
           
           h3{color:#424242;text-align:center; font-family:Arial,Helvetica,sans-serif;font-size:16px;line-height:20px;padding-bottom:5px;margin:0; margin-bottom:10px;}
            strong{color:#fff;}
            .fecha,h2{color:#9e9e9e;padding-left:5px;padding-bottom:18px;font-family:sans-serif;font-size:14px;font-weight:normal;line-height:16px;margin:0 0 10px 0;border-bottom:1px solid #e5e5e5}
            .templateColumns{border: solid 1px lightgray;-webkit-border-radius: 5px;-moz-border-radius: 5px;border-radius: 5px;}
            .column-table{background-color:#f5f5f5; padding: 10px 0px;}
            .flex{padding: 20px;box-sizing: border-box; border: solid 1px lightgray;-webkit-border-radius: 0px 0px 0px 5px;-moz-border-radius: 0px 0px 0px 5px;border-radius: 0px 0px 5px 5px; border-top:none;}
            .title-span{width:100%;  border-bottom:solid 1px #eceff1; color:#333;font-family:Arial,Helvetica,sans-serif;font-size:15px; text-transform:uppercase;padding:5px 10px 8px}
            .description-span{color:#37474f;padding-left:10px; font-family:sans-serif; font-weight:bold;font-size:26px;line-height:22px; margin:0; margin-top:12px;}
            .intro .cuenta{float:right;}
            .boton{text-align:right;}
            @media only screen and (max-width: 480px){
                .logo{padding: 20px 0 20px 0px;display: inline-block !important;}
                .boton{margin-bottom: 17px; text-align:center;}
                /*.intro tbody{text-align:center;}*/
                .intro tbody tr td{display:block;}
                .templateColumnContainer{display:block !important;width:100% !important;}
                .templateColumnContainer tbody {width: 100%;display: block;}
                .templateColumnContainer tbody tr{ width: 100%;display: inline-table;padding-left: 5%;}
                .templateColumnContainer tbody tr td{ vertical-align: top;}
            }
        </style>
    </head>
    <body>


    <table border="0" cellpadding="0" cellspacing="0" width="600" align="center" class="templateColumns" id="templateColumns">
        <tr>
            <td>
                 <table class="intro" width="100%" cellpadding="0" cellspacing="0">
                    <tbody>
                        <tr>
                            <td class="logo">
                                <a href="https://www.vendty.com/" target="_blank">
                                    <img src="http://vendty.com/wp-content/uploads/2016/11/logo_white_bg_zoho.jpg" width="171" height="44" alt="Vendty" border="0" style="display:block"></a>
                            </td>
                            <td  class="boton" style="padding-left:10px;padding-right:10px;" valign="middle">
                                <a href="https://pos.vendty.com/" style="background-color:#5cb85c;padding:6px;border-radius:3px;font-family:Arial,Helvetica,sans-serif;font-size:14px;line-height:14px;text-decoration:none; border:none;" target="_blank"><strong>Ingresar a mi cuenta</strong></a>
                            </td>

                            <!--<td style="padding:0 20px 20px 0;vertical-align:bottom">
                                <table class="cuenta" cellpadding="0" cellspacing="0" style="background-color:#5cb85c;padding:6px;border-radius:3px">
                                    <tbody>
                                        <tr>
                                            <td height="28" style="padding-left:10px;padding-right:10px" valign="middle">
                                                <a href="https://pos.vendty.com/" style="font-family:Arial,Helvetica,sans-serif;font-size:14px;line-height:14px;text-decoration:none; border:none;" target="_blank"><strong>Ingresar a mi cuenta</strong></a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>-->
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>

        <tr>
            <td>
                <table width="100%" cellpadding="0" cellspacing="0" style="background-color:#5cb85c">
                    <tbody>
                        <tr>
                            <td height="5" style="font-size:1px;line-height:1px">&nbsp;</td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>

        <tr>
            <td>
            <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" class="flex">
                <tr>
                    <td>
                        <!--<span class="fecha"><?php echo date('Y-m-d'); ?></span>-->
                    <h1>Hola, <?php echo $user;?></h1> 
                        <h2>Vendty POS y Tienda Virtual - Resumen de ventas del día: <?php echo $fecha; ?></h2>
                        <h3> Almacen: <?php echo $almacen; ?> </h3>
                    </td>    
                </tr>
                <tr>
                    <td align="center" valign="top">
                        <table align="left" border="0" cellpadding="10" cellspacing="5" width="49%" class="templateColumnContainer">
                            <tr>
                                <td class="leftColumnContent column-table">
                                    <table class="title-span">
                                        <tr>
                                            <td>
                                            Ventas diarias:
                                            </td>
                                        </tr>
                                    </table>
                                    <p class="description-span">
                                        <?php echo $ventas_diarias;?>
                                    </p>
                                </td>
                            </tr>
                        </table>

                        <table align="right" border="0" cellpadding="10" cellspacing="5" width="49%" class="templateColumnContainer">
                            <tr>
                                <td class="leftColumnContent column-table">
                                    <table class="title-span">
                                        <tr>
                                            <td>
                                            Total de gastos:
                                            </td>
                                        </tr>
                                    </table>
                                
                                    <p class="description-span">
                                        <?php echo $total_gastos;?>
                                    </p>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

                <tr>
                    <td align="center" valign="top">
                        <table align="left" border="0" cellpadding="10" cellspacing="5" width="49%" class="templateColumnContainer">
                            <tr>
                                <td class="leftColumnContent column-table">
                                    <table class="title-span">
                                        <tr>
                                            <td>
                                            Total de utilidad:
                                            </td>
                                        </tr>
                                    </table>
                                    <p class="description-span">
                                        <?php echo $total_utilidad;?>
                                    </p>
                                </td>
                            </tr>
                        </table>

                        <table align="right" border="0" cellpadding="10" cellspacing="5" width="49%" class="templateColumnContainer">
                            <tr>
                                <td class="leftColumnContent column-table">
                                    <table class="title-span">
                                        <tr>
                                            <td>
                                                Devoluciones:
                                            </td>
                                        </tr>
                                    </table>
                                    
                                    <p class="description-span">
                                        <?php echo $devoluciones;?>
                                    </p>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

                <tr>
                    <td align="center" valign="top">
                        <table align="left" border="0" cellpadding="10" cellspacing="5" width="49%" class="templateColumnContainer">
                            <tr>
                                <td class="leftColumnContent column-table">
                                    <table class="title-span">
                                        <tr>
                                            <td>
                                                Formas de pago:
                                            </td>
                                        </tr>
                                    </table>
                                    <p class="description-span">
                                        <?php if(count($total_formas_pago) > 0){
                                            foreach($total_formas_pago as $pago){ ?>
                                            <span style="font-size:12px; text-transform: uppercase;"><?php echo $pago["forma_pago"]; ?> : </span> 
                                            <span style="font-size:12px;">$<?php echo ($pago["total_venta"]); ?> </span> 
                                            <br>
                                        <?php } } else{ ?>
                                            $0
                                        <?php }?>
                                    </p>
                                </td>
                            </tr>
                        </table>

                        <table align="right" border="0" cellpadding="10" cellspacing="5" width="49%" class="templateColumnContainer">
                            <tr>
                                <td class="leftColumnContent column-table">
                                    <table class="title-span">
                                        <tr>
                                            <td>
                                                Productos mas vendidos:
                                            </td>
                                        </tr>
                                    </table>
                                    <p class="description-span">
                                        <?php if(count($productos_mas_vendidos) > 0){
                                            foreach($productos_mas_vendidos as $producto){ ?>
                                            <span style="font-size:12px; text-transform: uppercase;"><?php echo $producto["nombre"]; ?> : </span> 
                                            <span style="font-size:12px;"><?php echo $producto["count_productos"]; ?> </span> 
                                            <br>
                                        <?php } } else{ ?>
                                            $0
                                        <?php }?>
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td height="5"></td>
                </tr>
                
                <tr>
                    <td>
                        <table width="100%" cellpadding="0" cellspacing="0" style="background-color:#5cb85c;">
                            <tbody>
                                <tr>
                                    <td align="center" style="padding:10px;font-family:sans-serif;font-size:12px;color:#fff;line-height:20px">
                                        Este mensaje se ha enviado de forma automatica de acuerdo a la información de su almacen. 
                                        <!--<a href="http://vendty.com" style="color:#00f;text-decoration:underline" target="_blank">Visita nuestro sitio web</a>.-->
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
        
            </table>  
        
            </td>
        </tr>
        
    </table>
    
    <table border="0" cellpadding="0" cellspacing="0" width="600" align="center">
        <tr>
            <td><img src="https://ci4.googleusercontent.com/proxy/YfbC6qefhFw_k-V4FwTEa5Cb3xq8mc3q-BZ5MkRhGvDUBJ4K3TsvGepFmrBBVY9mBB_6y2xJg9-bzNl7sf7N0eS9OsihQCWBUk8-11hA3rU=s0-d-e1-ft#https://www.freshbooks.com/images/emails/border-shadow.gif" width="600" height="15" alt="" style="display:block" class="CToWUd"></td>
        </tr>
    </table>
</body>
</html>