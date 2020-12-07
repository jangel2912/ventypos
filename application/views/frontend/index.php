<?php 
    session_start();
    if(isset($_SESSION["api_auth"])){
        print("<script>console.log(".$_SESSION["api_auth"]."); localStorage.setItem('api_auth', JSON.stringify(".$_SESSION["api_auth"]."));</script>");
    }else {
        print("<h3 style=\"color:red\">No se encontro token</h3>");
    }
?>

<div class="page-header">



    <div class="icon">



        <span class="ico-monitor"></span>



    </div>



    <h1><?php echo custom_lang("Inicio", "Inicio");?></h1>



</div>



<div class="block title">



    <div class="head">



        <h2><?php echo custom_lang('Panel', "Panel de control");?></h2>                                          



    </div>



</div>



<div class="row-fluid">                                                            



    <div class="span12">



        <div class="widgets">



            <div class="widget green icon">



                <div class="left">



                    <div class="icon">



                        <span class="ico-group"></span>



                    </div>



                </div>



                <div class="right">



                    <table cellpadding="0" cellspacing="0" width="100%">



                        <tr>



                            <td><?php echo custom_lang('sima_total_users', "Total de Usuarios");?></td><td><?php echo $data['usuarios'];?></td>



                        </tr>



                    </table>



                </div>



                <div class="bottom">



                    <a href="<?php echo site_url("usuarios/index");?>"><?php echo custom_lang("sima_see_usuario", "Ver usuarios");?></a>



                </div>



            </div>



            <div class="widget red icon">



                <div class="left">



                    <div class="icon">



                        <span class="ico-user"></span>



                    </div>



                </div>



                <div class="right">



                    <table cellpadding="0" cellspacing="0" width="100%">



                        <tr>



                            <td><?php echo custom_lang('sima_total_clients', "Total de Clientes");?></td><td><?php echo $data['clientes'];?></td>



                        </tr>



                    </table>



                </div>



                <div class="bottom">



                    <a href="<?php echo site_url("clientes/index");?>"><?php echo custom_lang('sima_see_clients', "Ver Clientes");?></a>



                </div>                            



            </div>



            <div class="widget red icon">



                <div class="left">                                    



                    <div class="icon">



                        <span class="ico-group"></span>



                    </div>



                </div>



                <div class="right">



                   <table cellpadding="0" cellspacing="0" width="100%">



                        <tr>



                            <td><?php echo custom_lang('sima_total_providers', "Total de Proveedores");?></td><td><?php echo $data['proveedores'];?></td>



                        </tr>



                    </table>



                </div>



                <div class="bottom">



                    <a href="<?php echo site_url("proveedores/index");?>"><?php echo custom_lang('sima_see_providers', "Ver proveedores");?></a>



                </div>                            



            </div>



            <div class="widget purple icon">



                <div class="left">                                    



                    <div class="icon">



                        <span class="ico-box"></span>



                    </div>



                </div>



                <div class="right">



                   <table cellpadding="0" cellspacing="0" width="100%">



                        <tr>



                            <td><?php echo custom_lang('sima_total_products', "Total de Productos");?></td><td><?php echo $data['productos'];?></td>



                        </tr>



                    </table>



                </div>



                <div class="bottom">



                    <a href="<?php echo site_url("productos/index");?>"><?php echo custom_lang('sima_see_products', "Ver productos");?></a>



                </div>                            



            </div>



            <div class="widget orange icon">



                <div class="left">                                    



                    <div class="icon">



                        <span class="ico-files"></span>



                    </div>



                </div>



                <div class="right">



                   <table cellpadding="0" cellspacing="0" width="100%">



                        <tr>



                            <td><?php echo custom_lang('sima_total_bills', "Total de Facturas");?></td><td><?php echo $data['facturas']['total'];?></td>



                        </tr>



                        <tr>



                            <td><?php echo custom_lang('sima_payment_bills', "Total de Pagadas");?></td><td><?php echo $data['facturas']['pagadas'];?></td>



                        </tr>



                        <tr>



                            <td><?php echo custom_lang('sima_pending_bills', "Total sin Pagar");?></td><td><?php echo $data['facturas']['pendientes'];?></td>



                        </tr>



                    </table>



                </div>



                <div class="bottom">



                    <a href="<?php echo site_url("facturas/index_pendientes");?>"><?php echo custom_lang('sima_see_bills', "Ver Facturas");?></a>



                </div>                            



            </div>



        </div>



    </div>



</div>



    