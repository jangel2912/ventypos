<div class="page-header">    
    <div class="icon">
        <img alt="Proveedores" class="iconimg" src="<?php echo $this->session->userdata('new_imagenes')['titulo_proveedores']['original'] ?>">        
    </div>
     <h1 class="sub-title"><?php echo custom_lang("Proveedores", "Proveedores");?></h1>
</div>

<div class="block title">
    <div class="head">
        <h2><?php echo custom_lang('sima_poviders_import', "Importar Proveedores desde Excel");?></h2>                                          
    </div>
</div>


<div class="row-fluid">
    <div class="span12">
        <h4>PASO 1</h4>
        <p>Descargar la plantilla haciendo clic <a href="<?php echo base_url().'uploads/plantilla_carga_proveedores.xls'?>">Aqui</a></p>
    </div>
</div>

<div class="row-fluid">
    <div class="span6">
        <h4>PASO 2</h4>
        <p>Subir el archivo</p>
        <?php
            $message = $this->session->flashdata('message');
            if(!empty($message)):?>
            <div class="alert alert-error">
                <?php echo $message;?>
            </div>
            <?php endif; ?>
            
            <?php echo validation_errors(); ?>
            <div class="data-fluid">
                <?php echo form_open_multipart("proveedores/import_excel", array("id" =>"validate"));?>
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
                    <button class="btn btn-success" type="submit"><?php echo custom_lang("sima_submit", "Guardar");?></button>                    
                </div>
            </div>
            </form>
    </div>
</div>


<!--
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
                <?php echo form_open_multipart("proveedores/import_excel", array("id" =>"validate"));?>
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
</div> -->