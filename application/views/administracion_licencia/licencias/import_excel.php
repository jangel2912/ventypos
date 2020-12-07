<div class="page-header">

    <div class="icon">

        <span class="ico-group"></span>

    </div>

    <h1><?php echo custom_lang("Licencias", "Licencias");?></h1>

</div>

<div class="block title">

    <div class="head">

        <h2><?php echo custom_lang('sima_poviders_import', "Importar Licencias desde excel");?></h2>                                          

    </div>

</div>

<div class="row-fluid">

    <div class="span6">

        <div class="block"><?php

                $message = $this->session->flashdata('message');

                if(!empty($message)):?>

                <div class="alert alert-error">

                    <?php echo $message;?>

                </div>

                <?php endif; ?>

                        <?php echo validation_errors(); ?>

                            <div class="data-fluid">

                                <?php echo form_open_multipart("administracion_vendty/licencia_empresa/import_excel", array("id" =>"validate"));?>

                                <div class="row-form">

                                    <div class="span3"><?php echo custom_lang('sima_file', "Archivo");?>:<br/>

                                    </div>

                                    <div class="span9">                            

                                        <div class="input-append file">

                                            <input type="file" name="archivo"/>

                                            <input type="text"/>

                                            <button class="btn btn-success" type="button"><?php echo custom_lang('sima_search', "Buscar");?></button>

                                        </div> 

                                         <?php echo $data['data']['upload_error']; ?>

                                    </div>

                                </div> 

                                <div class="toolbar bottom tar">
                                    <button class="btn btn-default" type="reset"><?php echo custom_lang('sima_cancel', "Cancelar");?></button>
                                    <button class="btn btn-success" type="submit"><?php echo custom_lang("sima_submit", "Subir");?></button>                                    
                                </div>

                            </div>

                        </form>

                </div>
            </div>
</div>