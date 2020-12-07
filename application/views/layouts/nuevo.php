<div class="content" >

                <div class="row-fluid">

                    <div class="span6">

                        <div class="block">

                            <div class="head" style="text-align: center;">

                                <div class="icon"><span class="ico-arrow-right"></span></div><br/><h2>B&uacute;scalo</h2>

                            </div>

                            <div class="toolbar">

                                    <div class="input-append">

                                        <input type="text" name="text" class="span12" placeholder="" id="search"/>

                                        <button class="btn btn-success" id="faqSearch" type="button"><span class="icon-search icon-white"></span></button>

                                    </div>                     

                            </div>

   

                            <table class="table aTable" cellpadding="0" cellspacing="0" width="100%" id="">

                                <tbody>



                                </tbody>

                            </table>

                            

                            <table width="100%" id="facturasTable">

                                <tbody>

                                    <tr class="nothing">

                                        <td>No existen elementos</td>

                                    </tr>

                                </tbody>

                            </table>

                            <div class="dataTables_info">Mostrando desde 0 hasta 0 de 0 elementos</div>

                            <div id="DataTables_Table_2_paginate" class="dataTables_paginate paging_full_numbers">

                                <a class="first paginate_button paginate_button_disabled" tabindex="0">Primero</a>

                                <a class="previous paginate_button paginate_button_disabled" tabindex="0">Anterior</a>

                              <!--  <a class="paginate_active paginate_button_disabled" tabindex="0">1</a> -->

                                <a class="next paginate_button paginate_button_disabled" tabindex="0">Siguiente</a>

                                <a class="last paginate_button paginate_button_disabled" tabindex="0">Ultimo</a>

                            </div>

                        </div>

                    </div>

                    <div class="span6" style="margin-top: 10px;">

                        <div class="block">                    

                            <div class="head green" style="color: #fff; font-size: 20px;">

                                <div class="icon">

                                    <span class="ico-info"></span>

                                </div>

                                Total= <span style="float: right;font-size: 25px; font-weight: bold;">$<span id="total">0.00</span></span>

                            </div>

                            <div class="data-fluid">

                                <div style="height:200px;overflow:auto; width:100%">

                                    <table cellpadding="0" cellspacing="0" width="100%" class="table">

                                        <tbody height="50px" id="productos-detail">

                                            <tr class="nothing">

                                                <td>No existen elementos</td>

                                            </tr>                     

                                        </tbody>

                                    </table>

                                </div>

                                <table cellpadding="0" cellspacing="0" width="100%" class="table">

                                    <tr>

                                        <td>Iva:</td>

                                        <td style="text-align: right;"><span id="iva-total">0.00</span></td>

                                    </tr> 

                                    <tr>

                                        <td >Subtotal:</td>

                                        <td style="background-color: #E9E9E9; text-align: right; font-weight: bold;">$<span id="subtotal">0.00</span></td>

                                    </tr>

                                </table>

                                <br/>

                                <div class="head green">

                                    <div class="icon"><span class="ico-user"></span></div>

                                    <h2>Cliente</h2>

                                    <ul class="buttons">                                    

                                        <li><a href="#" class="clienteHideButton"><div class="icon"><span class="ico-sort"></span></div></a></li>

                                    </ul>  

                                </div>

                                <div class="" id="clienteBlock">

                                    <table width="100%">

                                        <tr>

                                            <td width="50%">

                                                <div class="input-prepend">

                                                    <span class="add-on green"><i class="icon-user icon-white"></i></span>

                                                    <input type="text" placeholder="Usuario" id="cliente-usuario" class="span12">

                                                </div>

                                            </td>   

                                            <td width="50%">

                                                <div class="input-prepend">

                                                    <span class="add-on green"><i class=" icon-th icon-white"></i></span>

                                                    <input type="text" placeholder="Identificaci&oacute;n" id="cliente-identificacion" class="span12">

                                                </div>

                                            </td>

                                        </tr>

                                        <tr>

                                            <td>

                                                <div class="input-prepend">

                                                    <span class="add-on green"><i class="icon-envelope icon-white"></i></span>

                                                    <input type="text" placeholder="Correo" id="cliente-correo" class="span12">

                                                </div>

                                            </td>

                                            <td>

                                                <div class="input-prepend">

                                                    <span class="add-on green"><i class="ico-phone-2  icon-white" style="color:white;"></i></span>

                                                    <input type="text" placeholder="telefono" id="cliente-telefono" class="span12">

                                                </div>

                                            </td>

                                        </tr>

                                    </table>

                                </div>

                                <br/>

                                    <div class="head green">

                                        <div class="icon"><span class="ico-user"></span></div>

                                            <h2>Vendedor</h2>

                                        <ul class="buttons">                                    

                                            <li><a href="#" class="vendedorHideButton"><div class="icon"><span class="ico-sort"></span></div></a></li>

                                        </ul>  

                                    </div>

                                <div class="row-form" id="vendedorBlock">

                                        <div class="span3">Nombre:</div>

                                        <div class="span9">                                    

                                            <?php echo form_dropdown('vendedor', $data['vendedores'], array(), "id='vendedor'") ?>

                                        </div>

                                    </div>

                            </div>

                            <div class="toolbar">

                                <button class="btn btn-success" id="pagar" style="width: 100%; height: 35px;">

                                        <span class="ico-icon"></span>

                                    <span style="font-size: 28px; margin-top: 2px; letter-spacing: 2px;">Pagar</span></button>

                            </div>

                            </div>  

                        </div>

                    </div>



                </div>

<script type="text/javascript">

    $url = "<?php echo site_url("productos/productos_filter");?>";

    $urlImages = "<?php echo base_url("/uploads/productos");?>";

    $sendventas = "<?php echo site_url("/ventas/nuevo");?>";

    $reload = "<?php echo site_url("ventas/index");?>";

    $reloadThis = "<?php echo site_url("ventas/nuevo");?>";

</script>