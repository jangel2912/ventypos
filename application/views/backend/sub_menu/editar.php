<div class="block title">
    <div class="head">
        <h2>Editar menu</h2>                                          
    </div>
</div>
<div class="row-fluid">
    <div class="block">
                            <div class="data-fluid">
                                <?php echo form_open("backend/sub_menu/editar/".$data['data']['id_sub_menu'], array("id" =>"validate"));?>
                                 <input type="hidden" value="<?php echo set_value('id_sub_menu', $data['data']['id_sub_menu']); ?>" name="id" />
                                <div class="row-form">
                                    <div class="span3">Nombre del v&iacute;nculo:</div>
                                    <div class="span9"><input type="text"  value="<?php echo set_value('nombre_link', $data['data']['nombre_link']); ?>" placeholder="Nombre del vinculo" name="nombre_link" />
                                            <?php echo form_error('nombre_link'); ?>
                                    </div>
                                </div>
                                <div class="row-form">
                                    <div class="span3">Direcci&oacute;n:</div>
                                    <div class="span9"><input type="text" value="<?php echo set_value('direccion', $data['data']['direccion']); ?>" name="direccion" placeholder="controlador/accion"/>
                                        <?php echo form_error('direccion'); ?>
                                    </div>
                                </div>
                                 <div class="row-form">
                                    <div class="span3">Peso:</div>
                                    <div class="span9"><input type="text" name="peso" placeholder="Peso del v&iacute;culo" value="<?php echo set_value('peso', $data['data']['peso']); ?>"/>
                                        <?php echo form_error('peso'); ?>
                                    </div>
                                </div>
                                 <div class="row-form">
                                    <div class="span3">Menu:</div>
                                    <div class="span9"><?php echo form_dropdown('menu_id', $data['menu'], set_value('menu_id', $data['data']['menu_id']));?>
                                        <?php echo form_error('menu_id'); ?>
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