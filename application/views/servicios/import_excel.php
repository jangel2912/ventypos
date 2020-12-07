<div class="page-header">
    <div class="icon">
        <span class="ico-box"></span>
    </div>
    <h1><?php echo custom_lang("Servicios", "Servicios");?><small><?php echo $this->config->item('site_title');?></small></h1>
</div>
<div class="block title">
    <div class="head">
        <h2><?php echo custom_lang('sima_services_import', "Importar servicios desde excel");?></h2>                                          
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
                                <?php echo form_open_multipart("servicios/import_excel", array("id" =>"validate"));?>
                                <div class="row-form">
                                    <div class="span3"><?php echo custom_lang('sima_file', "Archivo");?>:<br/>
                                    </div>
                                    <div class="span9">                            
                                        <div class="input-append file">
                                            <input type="file" name="archivo"/>
                                            <input type="text"/>
                                            <button class="btn" type="button"><?php echo custom_lang('sima_search', "Buscar");?></button>
                                        </div> 
                                         <?php echo $data['data']['upload_error']; ?>
                                    </div>
                                </div> 
                                <div class="toolbar bottom tar">
                                    <div class="btn-group">
                                        <button class="btn" type="submit"><?php echo custom_lang("sima_submit", "Enviar");?></button>
                                        <button class="btn btn-warning" type="reset"><?php echo custom_lang('sima_cancel', "Cancelar");?></button>
                                    </div>
                                </div>
                            </div>
                            </form>
    </div>
    </div>
    
</div>