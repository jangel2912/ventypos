<div class="page-header">

    <div class="icon">

        <span class="ico-box"></span>

    </div>

    <h1><?php echo custom_lang("Configuracion", "Configuración");?><small><?php echo $this->config->item('site_title');?></small></h1>

</div>

<div class="block title">

    <div class="head">

        <h2><?php echo custom_lang('sima_config_header_terms', "Configurar términos y cabecera");?></h2>                                          

    </div>

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

            <div class="data-fluid">

                    <?php echo form_open("miempresa/terms_headers", array("id" =>"validate"));?>

                        <div class="block">

                                <div class="head blue">                                

                                    <h2><?php echo custom_lang("headers", "Cabecera") ?></h2>                               

                                </div>

                                <div class="data-fluid editor">

                                    <textarea id="header" style="height: 200px;" name="header"><?php echo set_value('header', $cabecera); ?></textarea>

                                </div>

                            </div> 

                            <div class="block">

                                <div class="head blue">                                

                                    <h2><?php echo custom_lang("terms", "Términos y Condiciones") ?></h2>                               

                                </div>

                                <div class="data-fluid editor">

                                    <textarea id="terms" style="height: 200px;" name="terms"><?php echo set_value('terms', $terminos); ?></textarea>

                                </div>

                            </div> 

                <div class="clearfix"></div>

                     

                            <div class="toolbar bottom tar">

                                <div class="btn-group">

                                    <button class="btn" type="submit"><?php echo custom_lang("sima_submit", "Enviar");?></button>

 <button class="btn btn-warning"  type="button" onclick="javascript:location.href='../frontend/configuracion'"><?php echo custom_lang('sima_cancel', "Cancelar");?></button>

                                </div>

                            </div>

                    </form> 

                </div>

            </div>

        </div>

    </div>

<script type="text/javascript">

    $(document).ready(function(){

        wEditor = $("#wysiwyg").cleditor({width:"100%", height:"300px"});

        $("#header").cleditor({width:"100%", height:"200px"});

      $("#terms").cleditor({width:"100%", height:"200px"});

    });

    

</script>