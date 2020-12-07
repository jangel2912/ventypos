<script src="<?php echo base_url("index.php/OpcionesController/index") ?>"></script>
<script>
    $(document).on('blur', '.dataMoneda', function () {
        $(this).val(limpiarCampo($(this).val()));
    });
</script>
<style type="text/css">
    .ui-dialog{
        z-index: 9000!important;
    }
    .site-footer{
        display: none !important;
    }

</style>

<div class="page-header">    
    <div class="icon">
        <img alt="Apertura de caja" class="iconimg" src="<?php echo $this->session->userdata('new_imagenes')['titulo_caja']['original'] ?>">        
    </div>
     <h1 class="sub-title"><?php echo custom_lang("Apertura de Caja", "Apertura de Caja");?></h1>
</div>
<div class="row-fluid">
    <div class="span12">
    <?php

            //Adicionando mensaje segun incidencia #814
            $message = $this->session->flashdata('message');

            if(!empty($message)):?>

            <div class="alert alert-error">

                <?php echo $message;?>

            </div>
            <?php endif; ?>
    </div>
    <div class="span6">
        <div class="block">
            <?php
           
            if (isset($data1['almacen'])) {
                echo form_open("caja/apertura/", array("id" => "apertura_caja"));
                ?>

                <div class="data-fluid">
                    <div class="row-form">
                        <div class="span3">Fecha:</div>
                        <div class="span9"><input type="text" readonly="readonly" value="<?php echo date('Y-m-d'); ?>" placeholder="" name="fecha" />
                        </div>
                    </div>
                    <div class="data-fluid">
                        <div class="row-form">
                            <div class="span3">Almacen:</div>
                            <div class="span9"><input type="text" readonly="readonly" value="<?php echo $data1['almacen']['nombre']; ?>" placeholder="" name="almacen_show" /> 
                                <input type="hidden" name="almacen" readonly="readonly" value="<?php echo $data1['almacen']['id']; ?>"  />
                            </div>
                        </div>

                    </div>			  
                    <?php
                    $i = 0;
                    foreach ($data1['forma_pago'] as $f) {
                        $i++;

                        if ($f->valor_opcion == 'efectivo' ) {
                            ?>
                            <div class="data-fluid">
                                <div class="row-form">
                                    <div class="span3">
                                        Valor apertura:<input type="hidden"   placeholder="" name="foma_pago[]" value="<?php echo $f->valor_opcion; ?>"  /></div>
                                    <div class="span9">  <input type="text" class="dataMoneda dinero_inicial" value="0" placeholder="" name="valor[]"  id="foma_pago<?php echo $i; ?>" />
                                    </div>
                                </div>
                                <?php
                            }
                        }
                        ?>

                        <div class="data-fluid">
                            <div class="row-form">
                                <div class="span3">Total:</div>
                                <div class="span9">  <input type="text" readonly="readonly"  value="0" placeholder="" name="total_formapago"  id="total_formapago" />
                                </div>
                            </div>

                            <div class="data-fluid">
                                <div class="row-form">
                                    <div class="span3"></div>
                                    <div class="span9"> <br /> <input type="submit"   value="Guardar" id="enviar" class="btn btn-success"/>
                                    </div>
                                </div>			  			  		  
                            </div>
                        </div>
                        <input type="hidden" value="<?php echo $back; ?>" name="back">
                        <input type="hidden" value="<?php echo $url; ?>" name="url">
                    </div>
                </div>

                <?php 
                echo form_close();
            } else { 
                ?>

                <div class="alert alert-error">
                <?php if($this->session->userdata('is_admin')=="t") {?>
                    Este usuario no tiene asociado un almacen en el sistema. <br>Por favor ingrese al siguiente link para configurarlo. <a class="btn btn-success" href="<?= base_url() ?>index.php/usuarios/index">Configuracion <i class="icon icon-arrow-right icon-white"></i> Usuarios <i class="site-menu-icon wb-settings"></i></a>
                <?php }else{ ?>
                    Este usuario no tiene asociado un almacen en el sistema. <br>Por favor dirijase al administrador para que lo configure.
                <?php } ?>
                </div>
            </div>
        </div>
    </div>
<?php } ?>

<script type="text/javascript">

    $(document).ready(function () {



        $(".dinero_inicial").keyup(function (e) {

            $("#total_formapago").val(formatDollar($(".dinero_inicial").val()));

        });

        function formatDollar(num) {
            num = parseFloat(num);
            (num % 1 == 0) ? p = num.toFixed(0).split(".") : p = num.toFixed(0).split(".");
            return p[0].split("").reverse().reduce(function (acc, num, i, orig) {
                return  num + (i && !(i % 3) ? "," : "") + acc;
            }, "") /*+ "." + p[1]*/;
        }


        $("#enviar").click(function () {
            document.getElementById('enviar').disabled = true;
            document.getElementById("apertura_caja").submit();
        });

    });

</script>