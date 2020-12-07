<style>
    .has-error {
        border: 1px solid #C22439 !important;
        color: #C22439 !important;
    }
</style>
<div class="page-header">

    <div class="icon">

        <span class="ico-box"></span>

    </div>

    <h1><?php echo custom_lang("Categorias", "Atributos");?><small><?php echo $this->config->item('site_title');?></small></h1>

</div>

<div class="block title">

    <div class="head">

        <h2><?php echo custom_lang('sima_new_category', "Nuevo Atributo");?></h2>

    </div>

</div>

<div class="row-fluid">

    <div class="span6">
        
        <div class="block">

                            <div class="data-fluid">

                                <?php echo form_open_multipart("atributos/nuevo", array("id" =>"validate"));?>

                                <div class="row-form">

                                    <div class="span3"><?php echo custom_lang('sima_name', "Nombre");?>:</div>

                                    <div class="span9"><input type="text"  value="<?php echo set_value('nombre'); ?>" placeholder="" name="nombre" />

                                            <?php echo form_error('nombre'); ?>

                                    </div>

                                </div>
                                
                                <h5>Por favor agregar los valores que tendrá este atributo.</h5>
                                
                                <div id="added-attrs">
                                    <table width="100%">
                                        <thead>
                                            <tr>
                                                <td>
                                                    <input type="text" value="<?php echo set_value('valor'); ?>" placeholder="Valor (Oblgatorio)" id="valor" name="valor" />
                                                    <?php echo form_error('valor'); ?>
                                                    <input type="hidden" id="edit-id">
                                                </td>
                                                <td>
                                                    <input type="text" value="<?php echo set_value('descripcion'); ?>" placeholder="Descripción" id="descripcion" name="descripcion" />
                                                    <?php echo form_error('descripcion'); ?>
                                                </td>
                                                <td><a href="javacsript: void(0)" class="btn btn-info add-value-attr">Agregar</a></td>
                                            </tr>
                                        </thead>
                                    </table>

                                    <div>
                                        <table id="result" class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Valor</th>
                                                    <th>Descripción</th>
                                                    <th>Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
                                </div>

                                    <hr>

                                <div class="data-fluid">

                                    <div class="row-form">
    
                                        <div class="span2"><button class="btn btn-success" type="submit"><?php echo custom_lang("sima_submit", "Guardar");?></button></div>
                                        &nbsp;
                                        <div class="span2"><button class="btn btn-warning" type="reset"><?php echo custom_lang('sima_cancel', "Cancelar");?></button></div>

                                    </div>

                                </div>

                            </div>

                </form>

    </div>

    </div>

    

</div>

<script>
    deleteReg = function (elem, id) {
        //if(confirm("Esta seguro que desea elminar el registro #"+id+"?")){
        $('#reg_'+id).remove();
        //}
    }
    viewReg = function (elem, id) {
        var dataValue = $(elem).closest('tr').find('[data-value]').text(),
            dataDesc = $(elem).closest('tr').find('[data-desc]').text(),
            valorInput = $('#valor'),
            descInput = $('#descripcion');

        $('#edit-id').val(id);

        valorInput.val(dataValue);
        descInput.val(dataDesc);

        $('.add-value-attr').text('Editar');
    }

    $(function () {
        $('#valor')
            .click(function (e) {
                $(this).removeClass('has-error');
            });

        $('.add-value-attr')
            .click(function (e) {
                var id = parseInt($('#edit-id').val()) || 0,
                    index = $('#result > tbody > tr').length + 1;
                    valorInput = $('#valor'),
                    descInput = $('#descripcion');

                if ( valorInput.val() != '' ) {
                    if(parseInt($('#edit-id').val())) {
                        $('#reg_'+ id).find('[data-value]').text(valorInput.val());
                        $('#reg_'+ id).find('[data-desc]').text(descInput.val());
                        $('#reg_'+ id).find('.value').text(valorInput.val());
                        $('#reg_'+ id).find('.desc').text(descInput.val());
                    } else {
                        $('#result > tbody').append(
                                '<tr id="reg_'+index+'">'+
                                    '<td data-value="'+ valorInput.val() +'">'+
                                        valorInput.val()+
                                        '<input type="hidden" name="valores[]" class="value" value="'+ valorInput.val() +'"/>'+
                                    '</td>'+
                                    '<td data-desc="'+ descInput.val() +'">'+
                                        descInput.val()+
                                        '<input type="hidden" name="descs[]" class="desc" value="'+ descInput.val() +'"/>'+
                                    '</td>'+
                                    '<td>'+
                                        '<a href="javacsript: void(0)" onclick="viewReg(this, '+ index +')">Editar</a> - '+
                                        '<a href="javacsript: void(0)" onclick="deleteReg(this, '+ index +')">Eliminar</a> '+
                                    '</td>'+
                                '</tr>');
                    }

                    $(this).text('Agregar');
                    $('#edit-id').val('');
                    valorInput.val('');
                    descInput.val('');
                } else {
                    valorInput.addClass('has-error');
                    alert("El campo valor no puede estar vacio.");
                }
            });
    })
</script>