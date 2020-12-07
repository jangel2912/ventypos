<div class="block title">
    <div class="head">
        <h2>Nueva opci&oacute;n</h2>                                          
    </div>
</div>
<div class="row-fluid">
    <div class="block">
                            <div class="data-fluid">
                                <?php echo form_open("backend/opciones/nuevo", array("id" =>"validate"));?>
                                <div class="row-form">
                                    <div class="span3">Nombre de la opci&oacute;n:</div>
                                    <div class="span9"><input type="text"  value="<?php echo set_value('nombre_opcion'); ?>" placeholder="Nombre de la opcion" name="nombre_opcion" />
                                            <?php echo form_error('nombre_opcion'); ?>
                                    </div>
                                </div>
                                <div class="row-form">
                                    <div class="span3">Mostrar de la opci&oacute;n:</div>
                                    <div class="span9"><input type="text"  value="<?php echo set_value('mostrar_opcion'); ?>" placeholder="Valor de la opcion" name="mostrar_opcion" />
                                            <?php echo form_error('mostrar_opcion'); ?>
                                    </div>
                                </div>
                                <div class="row-form">
                                    <div class="span3">Valor de la opci&oacute;n:</div>
                                    <div class="span9"><input type="text"  value="<?php echo set_value('valor_opcion'); ?>" placeholder="Valor de la opcion" name="valor_opcion" />
                                            <?php echo form_error('valor_opcion'); ?>
                                    </div>
                                </div>
                                <div class="toolbar bottom tar">
                                    <div class="btn-group">
                                        <button class="btn" type="submit">Guardar</button>
                                        <button class="btn btn-warning" type="reset">Cancelar</button>
                                    </div>
                                </div>
                            </div>
                            </form>
    </div>
</div>