<style>
    .mr-0{margin-right:0px;}
</style>

<div class="page-header">    
    <div class="icon">
        <img alt="Bancos" class="iconimg" src="<?php echo $this->session->userdata('new_imagenes')['titulo_gastos']['original'] ?>">        
    </div>
     <h1 class="sub-title"><?php echo custom_lang("Nuevo banco", "Nuevo banco");?></h1>
</div>
<div id= 'mensagge_warning' class="alert alert-error" style="display:none">El número de cuenta suministrado ya pertenece a una Cuenta</div>
<div class="row">
    <div class="col-md-4">
            
        <form class="form-horizontal" action="<?php echo site_url('bancos/crear_banco')?>" method="post" id="validate" >
            
            <div class="col-md-12 pl-0">
                <div class="form-group">
                    <label for="almacen">Almacen:</label>
                    <span class="glyphicon glyphicon-question-sign icon-help-config" aria-hidden="true" data-container="body" data-toggle="popover" data-placement="right" data-content="Almacen al cual quedara asociada la cuenta bancaria." data-trigger="hover"></span>
                    <select name="almacen" id="" class="col-sm-6 form-control" required>
                        <?php foreach($almacenes as $almacen): ?>
                                <option value="<?= $almacen->id; ?>"><?= ucfirst($almacen->nombre); ?></option>
                        <?php endforeach; ?>
                    </select>
                    <?php echo form_error('almacen'); ?>   
                </div>
            </div>

            <div class="col-md-12 pl-0">
                <div class="form-group">
                    <label for="nombre_cuenta">Nombre de cuenta: </label>
                    <span class="glyphicon glyphicon-question-sign icon-help-config" aria-hidden="true" data-container="body" data-toggle="popover" data-placement="right" data-content="Nombre de la cuenta que deseas asociar (Bancolombia, BBVA, Banco Caja social ...)." data-trigger="hover"></span>
                    <input type="text" class="form-control required"  name="nombre_cuenta" id="nombre_cuenta" placeholder="Nombre de la cuenta">
                    <?php echo form_error('nombre_cuenta'); ?>   
                </div>
            </div>

            <div class="col-md-12 pl-0">
                <div class="form-group">
                    <label for="numero_cuenta">Número de cuenta:</label>
                    <span class="glyphicon glyphicon-question-sign icon-help-config" aria-hidden="true" data-container="body" data-toggle="popover" data-placement="right" data-content="Número de cuenta que desea asociar" data-trigger="hover"></span>
                    <input type="text" class="form-control required"  name="numero_cuenta" id="numero_cuenta" placeholder="Número de cuenta">
                    <?php echo form_error('numero_cuenta'); ?>   
                </div>
            </div>

            <div class="col-md-12 pl-0">
                <div class="form-group">
                    <label for="saldo_inicial">Saldo inicial:</label>
                    <span class="glyphicon glyphicon-question-sign icon-help-config" aria-hidden="true" data-container="body" data-toggle="popover" data-placement="right" data-content="Saldo base con el que inicia su cuenta bancaria." data-trigger="hover"></span>
                    <input type="text" class="form-control required"  name="saldo_inicial" id="saldo_inicial" placeholder="Saldo inicial">
                    <?php echo form_error('saldo_inicial'); ?>   
                </div>
            </div>

            <div class="col-md-12 pl-0">
                <div class="form-group">
                    <label for="descripcion">Descripción:</label>
                    <span class="glyphicon glyphicon-question-sign icon-help-config" aria-hidden="true" data-container="body" data-toggle="popover" data-placement="right" data-content="Descripción corta del banco que desea asociar." data-trigger="hover"></span>
                    <textarea class="form-control" rows="3" name="descripcion" id="descripcion" placeholder="Ingrese una descripción"></textarea>
                </div>
            </div>

            
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <button type="submit" id="guardar" class="btn btn-success pull-right mr-0">Guardar</button>
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
            //verificar que no existe el codigo
            $.ajax({
                url: "<?php echo site_url("bancos/validateNombreyCodigo"); ?>",
                type: "POST",
                dataType: "json",
                data: {campo:'numero_cuenta', id: $("#numero_cuenta").val()},
                success: function (data) {
                    console.log(data);
                    if (data) {
                        document.getElementById('mensagge_warning').style.display = 'inline-block';                        
                        $("#numero_cuenta").focus();
                        $("#guardar").prop('disabled',false);
                    } else {
                        //document.getElementById('mensagge_warning').style.display = 'none';
                        $("#validate").submit();  //Para enviar el formulario                                    
                    }
                }
            });    
                
        });
    });

</script>