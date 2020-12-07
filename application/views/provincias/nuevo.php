<div class="block title">
    <div class="head">
        <h2>Nueva provincia</h2>                                          
    </div>
</div>
<div class="row-fluid">
    <div class="block">
                            <div class="data-fluid">
                                <?php echo form_open("provincias/nuevo", array("id" =>"validate"));?>
                                <div class="row-form">
                                    <div class="span3">Nombre de la provincia:</div>
                                    <div class="span9"><input type="text"  value="<?php echo set_value('nombre_provincia'); ?>" placeholder="Nombre de la provincia" name="nombre_provincia" />
                                            <?php echo form_error('nombre_provincia'); ?>
                                    </div>
                                </div>
                                <div class="toolbar bottom tar">
                                    <div class="btn-group">
                                        <button class="btn" type="submit">Submit</button>
                                        <button class="btn btn-warning" type="reset">Cancelar</button>
                                    </div>
                                </div>
                            </div>
                            </form>
    </div>
</div>
