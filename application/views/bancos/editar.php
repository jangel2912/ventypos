<style>
    .mr-0{margin-right:0px;}
</style>

<div class="page-header">    
    <div class="icon">
        <img alt="Bancos" class="iconimg" src="<?php echo $this->session->userdata('new_imagenes')['titulo_gastos']['original'] ?>">        
    </div>
     <h1 class="sub-title"><?php echo custom_lang("Nuevo banco", "Editar banco");?></h1>
</div>
<div id= 'mensagge_warning' class="alert alert-error" style="display:none">El número de cuenta suministrado ya pertenece a una Cuenta</div>
<div class="row">
    <div class="col-md-4">
        <form class="form-horizontal" action="<?php echo site_url('bancos/editar_banco')?>" method="post" id="validate">
           
        <div class="col-md-12 pl-0">
            <div class="form-group">
                <label for="almacen">Almacen:</label>
                <span class="glyphicon glyphicon-question-sign icon-help-config" aria-hidden="true" data-container="body" data-toggle="popover" data-placement="right" data-content="Nombre de la cuenta que deseas asociar (Bancolombia, BBVA, Banco Caja social ...)." data-trigger="hover"></span>
                <select name="almacen" id="" class="col-sm-6 form-control" required>
                    <?php foreach($almacenes as $almacen): ?>
                            <option value="<?= $almacen->id; ?>" <?php echo ($almacen->id == $banco->id_almacen)? 'selected' : '' ?>><?= ucfirst($almacen->nombre); ?></option>
                    <?php endforeach; ?>
                </select>
                <?php echo form_error('almacen'); ?>   
            </div>
        </div> 

         <div class="col-md-12 pl-0">
                <div class="form-group">
                    <label for="nombre_cuenta">Nombre de cuenta: </label>
                    <span class="glyphicon glyphicon-question-sign icon-help-config" aria-hidden="true" data-container="body" data-toggle="popover" data-placement="right" data-content="Nombre de la cuenta que deseas asociar (Bancolombia, BBVA, Banco Caja social ...)." data-trigger="hover"></span>
                    <input type="hidden"  name="id" value="<?= $banco->id; ?>">
                    <input type="text" class="form-control required"  name="nombre_cuenta" id="nombre_cuenta" value="<?= $banco->nombre_cuenta; ?>">
                    <?php echo form_error('nombre_cuenta'); ?>   
                </div>
            </div>

            <div class="col-md-12 pl-0">
                <div class="form-group">
                    <label for="numero_cuenta">Número de cuenta:</label>
                    <span class="glyphicon glyphicon-question-sign icon-help-config" aria-hidden="true" data-container="body" data-toggle="popover" data-placement="right" data-content="Número de cuenta que desea asociar" data-trigger="hover"></span>
                    <input type="text" class="form-control required"  name="numero_cuenta" id="numero_cuenta" value="<?= $banco->numero_cuenta; ?>">
                    <?php echo form_error('numero_cuenta'); ?>   
                </div>
            </div>

            <div class="col-md-12 pl-0">
                <div class="form-group">
                    <label for="saldo_inicial">Saldo inicial:</label>
                    <span class="glyphicon glyphicon-question-sign icon-help-config" aria-hidden="true" data-container="body" data-toggle="popover" data-placement="right" data-content="Saldo base con el que inicia su cuenta bancaria." data-trigger="hover"></span>
                    <input type="text" class="form-control"  id="saldo_inicial" disabled value="<?= $banco->saldo_inicial; ?>">
                    <?php echo form_error('saldo_inicial'); ?>   
                </div>
            </div>

            <div class="col-md-12 pl-0">
                <div class="form-group">
                    <label for="descripcion">Descripción:</label>
                    <span class="glyphicon glyphicon-question-sign icon-help-config" aria-hidden="true" data-container="body" data-toggle="popover" data-placement="right" data-content="Descripción corta del banco que desea asociar." data-trigger="hover"></span>
                    <textarea class="form-control" rows="3" name="descripcion" id="descripcion"><?= $banco->descripcion; ?></textarea>
                </div>
            </div>
           
       

            
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <button id="guardar" type="submit" class="btn btn-success pull-right mr-0">Guardar</button>
                    <a href="<?php echo site_url('bancos');?>" class="btn btn-default pull-right">Cancelar</a>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    $(document).ready(function () {
        $("#guardar").click(function (e) {  
            $("#guardar").prop('disabled',true);

            if ($("#numero_cuenta").val() == '<?php echo set_value('numero_cuenta', $banco->numero_cuenta); ?>') {
                document.getElementById('mensagge_warning').style.display = 'none';
                $("#validate").submit();  //Para enviar el formulario
                        
            } else {
                $.ajax({
                    url: "<?php echo site_url("bancos/validateNombreyCodigo"); ?>",
                    type: "POST",
                    dataType: "json",
                    data: {campo:'numero_cuenta', id: $("#numero_cuenta").val()},
                    success: function (data) {
                        if (data != 0) {
                            document.getElementById('mensagge_warning').style.display = 'inline-block';                        
                            $("#numero_cuenta").focus();
                            $("#guardar").prop('disabled',false);
                        } else {
                            document.getElementById('mensagge_warning').style.display = 'none';
                            $("#validate").submit();  //Para enviar el formulario                                    
                        }
                    }
                });    
            }
             
        });
    });

</script>