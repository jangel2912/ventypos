<div class="block title">
    <div class="head">
        <h2>Nueva base de datos</h2>                                          
    </div>
</div>
<div class="row-fluid">
          <div class="block">
                            <?php 
                            $message = $data['message'];
                            if(!empty($message['msg'])):?>
                            <div class="alert alert-<?php echo $message['type']?>">
                                <?php echo $message["msg"];?>
                            </div>
                            <?php endif; ?>
                            <div class="data-fluid">
                                <?php echo form_open("backend/db_config/nuevo", array("id" =>"validate"));?>
                                <div class="row-form">
                                    <div class="span3">Servidor:</div>
                                    <div class="span9"><input type="text"  value="<?php echo set_value('servidor'); ?>" placeholder="Servidor" name="servidor" />
                                            <?php echo form_error('servidor'); ?>
                                    </div>
                                </div>
                                <div class="row-form">
                                    <div class="span3">Usuario:</div>
                                    <div class="span9"><input type="text" value="<?php echo set_value('usuario'); ?>" name="usuario" placeholder="Usuario"/>
                                        <?php echo form_error('usuario'); ?>
                                    </div>
                                </div>
                                <div class="row-form">
                                    <div class="span3">Clave:</div>
                                    <div class="span9"><input type="password" value="<?php echo set_value('clave'); ?>" name="clave" placeholder="Clave"/>
                                        <?php echo form_error('clave'); ?>
                                    </div>
                                </div>                    
                                <div class="row-form">
                                    <div class="span3">Re-clave:</div>
                                    <div class="span9"><input type="password" value="<?php echo set_value('re-clave'); ?>" name="re-clave" placeholder="Repita la clave"/>
                                        <?php echo form_error('re-clave'); ?>
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
