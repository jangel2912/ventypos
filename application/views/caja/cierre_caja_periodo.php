
<link rel="stylesheet" type="text/css" href="<?php echo base_url("public/css"); ?>/bootstrap-chosen.css">
<div class="page-header">    
    <div class="icon">
        <img alt="Informes" class="iconimg" src="<?php echo $this->session->userdata('new_imagenes')['titulo_informes']['original'] ?>">        
    </div>
     <h1 class="sub-title"><?php echo custom_lang("Informes", "Informes");?></h1>
</div>

<div class="row-fluid">
    <div class="span12">
        <div class="block">
                <?php
                $message = $this->session->flashdata('message');
                if(!empty($message)):?>
                <div class="alert alert-error">
                    <?php echo $message;?>
                </div>
            <?php endif; ?>
         <div class="head blue">
                <h2><?php echo custom_lang('Cierre de Caja por Fecha', "Cierre de Caja por Fecha");?></h2>
            </div>
        </div>
    </div>
</div>
<div class="row-fluid">
    
    <?php echo form_open("caja/imprime_cierre_caja_periodo", array("id" => "validate")); ?>
    <div class="row-form">
         <div class="form-group">
            <div class="col-xs-12 ">
                <label for="fecha_inicia" class="col-sm-2 control-label"><?php echo custom_lang('sima_date_ini', "Fecha Inicial"); ?>:</label>
                <div class="col-sm-4">
                    <?php 
                        echo form_input(
                                array(
                                    'id'    => 'fecha_inicia',
                                    'name'  => 'fecha_inicia',
                                    'type'  => 'text',
                                    'class' => 'form-control required datepicker',
                                    'value' => date('Y-m-01'),
                                    'style' => 'text-align: center'
                                )
                             ); 
                    ?>
                </div>
                <label for="fecha_finalx" class="col-sm-2 control-label"><?php echo custom_lang('sima_date_fin', "Fecha Final"); ?>:</label>
                <div class="col-sm-4">
                    <?php 
                        echo form_input(
                                array(
                                    'id'    => 'fecha_finalx',
                                    'name'  => 'fecha_finalx',
                                    'type'  => 'text',
                                    'class' => 'form-control required datepicker',
                                    'value' => date('Y-m-d'),
                                    'style' => 'text-align: center'
                                )
                             ); 
                    ?>
                </div>
            </div>
        </div>
    </div>
    <div class="row-form">
        <div class="form-group">
            <div class="col-xs-12 ">
                <label for="fecha_inicia" class="col-sm-2 control-label"><?php echo custom_lang('sima_user', "Usuario"); ?>:</label>
                <div class="col-sm-9">
                    <?php
                        echo form_dropdown('user', $usuarios, '', "id='user' class='form-control  chosen-select'");
                    ?>
                </div>
                <div class="col-sm-1 ">
                    <!--<button class="btn btn-success" type="submit"><?php echo custom_lang("sima_submit", "Consultar"); ?></button>-->
                    <a data-tooltip="Consultar" onclick="$('#validate').submit()">                        
                        <img alt="Consultar" class="btnimagenes" src="<?php echo $this->session->userdata('new_imagenes')['buscar_verde']['original'] ?>"> 
                    </a> 
                </div>
            </div>
        </div>
    </div>
    
    <?php echo form_close(); ?>
</div>

<script src="<?php echo base_url("public/js"); ?>/chosen.jquery.js"></script>


<script>
    $(document).ready(function(){ $('.chosen-select').chosen(); });
    
    mixpanel.track("Informe_cierre_caja_periodo");  
</script>


