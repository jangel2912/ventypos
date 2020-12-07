<div class="block title">
    <div class="head">
        <h2>Cambiar mensaje de activaci&oacute;n</h2>                                          
    </div>
</div>
<div class="row-fluid">
    <div class="block">
                            <div class="data-fluid">
                                <?php echo form_open("backend/opciones/save_activation", array("id" =>"validate"));?>
                                <div class="row-form">
                                    <div class="span3">Email de activaci&oacute;n:</div>
                                    <div class="span9">
                                        <textarea name="email"/><?php echo $data['email_activation'];?></textarea>
                                        <span class="bottom">Las etiquetas v&aacute;lidas para el cambio [identidad] y [vinculo]</span>
                                    </div>
                                </div> 
                                <div class="toolbar bottom tar">
                                    <div class="btn-group">
                                        <button class="btn" type="submit">Submit</button>
                                        <button class="btn btn-warning" type="reset"><?php echo custom_lang('sima_cancel', "Cancelar");?></button>
                                    </div>
                                </div>
                                </form>
                            </div>
                            
    </div>
</div>
