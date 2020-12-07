<div class="page-header">    
    <div class="icon">
        <img alt="Impuestos" class="iconimg" src="<?php echo $this->session->userdata('new_imagenes')['titulo_impuestos']['original'] ?>">        
    </div>
     <h1 class="sub-title"><?php echo custom_lang("Impuestos", "Impuestos");?></h1>
</div>

<div class="block title">
    <div class="head">
        <h2><?php echo custom_lang('sima_tax_import', "Importar Impuestos desde Excel");?></h2>  
    </div>
</div>

<div class="row-fluid">

    <div class="span6">

        <div class="block">

                <?php

                        $message = $this->session->flashdata('message');

                        if(!empty($message)):?>

                        <div class="alert alert-error">

                            <?php echo $message;?>

                        </div>

                <?php endif; ?>

                            <?php echo validation_errors(); ?>

                            <div class="data-fluid">

                                <?php echo form_open_multipart("impuestos/import_excel", array("id" =>"validate"));?>

                                <div class="row-form">

                                    <div class="span3"><?php echo custom_lang('sima_file', "Archivo");?>:<br/></div>
<!--
                                    <div class="span9">  
                                        <div class="input-append file">
                                            <input type="file" name="archivo"/>
                                            <input type="text"/>
                                            <button class="btn btn-success" type="button"><?php echo custom_lang('sima_search', "Buscar");?></button>
                                        </div>                                         
                                         <?php echo $data['data']['upload_error']; ?>
                                    </div>-->
                                        <div class="span9">
                                            <div class="input-append file">
                                                <input type="file" name="archivo"/>
                                                <button class="btn btn-success" type="button"><?php echo custom_lang('sima_search', "Buscar Archivo");?></button>
                                            </div>
                                            <?php echo $data['data']['upload_error']; ?>
                                        </div>
                                </div> 

                                <div class="toolbar bottom tar">

                                    <div>
                                        <button class="btn btn-default"  type="button" onclick="javascript:location.href='index'"><?php echo custom_lang('sima_cancel', "Cancelar");?></button>
                                        <!--<button class="btn btn-default" type="reset"><?php echo custom_lang('sima_cancel', "Cancelar");?></button>-->
                                        <button class="btn btn-success" type="submit"><?php echo custom_lang("sima_submit", "Guardar");?></button>
                                        

                                    </div>

                                </div>

                            </div>

                            </form>

    </div>

    </div>

</div>