<style>
    .mr-0{margin-right:0px;}
    .pl-0{padding-left:0;}
    .pr-0{padding-right:0;}
    .link_new{font-size:11px;}
    .alert-danger{    background-color: #f2dede;border-color: #ebcccc;color: #a94442;}
    .modal-content {padding-bottom:10px; box-sizing:border-box;}
    .content-modal .titulo-modal{font-weight:bold;}
    .content-modal .modal-title{padding-top:10px;margin-bottom: 10px;}
    .content-modal .close-modal{ position: absolute;right: 10px;top: 10px;}
    .content-modal input,select{margin:5px;}
    .content-modal button{margin-top:10px;}
    .d-flex{display:flex; align-items:center;}

</style>

<div class="page-header">    
    <div class="icon">
        <img alt="Bancos" class="iconimg" src="<?php echo $this->session->userdata('new_imagenes')['titulo_gastos']['original'] ?>">        
    </div>
     <h1 class="sub-title"><?php echo custom_lang("Nuevo movimiento bancario", "Nuevo movimiento bancario");?></h1>
</div>

<div class="row">
    <div class="col-md-12">
        <?php 
            $message = $this->session->flashdata('message'); 
            if(!empty($message)){ ?>
                <div class="alert alert-success text-center"><?= $message; ?></div>
            <?php }?>

        <?php 
            $error = $this->session->flashdata('error'); 
            if(!empty($error)){ ?>
                <div class="alert alert-danger text-center"><?= $error; ?></div>
            <?php }?>
    </div>
</div>

<div class="row">
    <div class="col-md-7">
        <form class="form-horizontal" action="<?php echo site_url('bancos/crear_movimiento')?>" method="post" id="validate">
            
            <div class="col-md-7">
                <div class="form-group">
                    <label for="referencia">Referencia:</label>
                    <span class="glyphicon glyphicon-question-sign icon-help-config" aria-hidden="true" data-container="body" data-toggle="popover" data-placement="right" data-content="Referencia del movimiento bancario." data-trigger="hover"></span>
                    <input type="text" class="form-control"  required name="referencia" id="referencia" placeholder="No.Referencia">
                    <?php echo form_error('referencia'); ?>   
                </div>
            </div>

             <div class="col-md-7">
                <div class="form-group">
                    <label for="banco">Cuenta bancaria:</label>
                    <span class="glyphicon glyphicon-question-sign icon-help-config" aria-hidden="true" data-container="body" data-toggle="popover" data-placement="right" data-content="Nombre de la cuenta que deseas asociar (Bancolombia, BBVA, Banco Caja social ...)." data-trigger="hover"></span>
                    <select name="banco" id="" class="col-sm-6 form-control" required>
                        <?php foreach($bancos as $banco): ?>
                                <option value="<?= $banco->id; ?>"><?= ucfirst($banco->nombre_cuenta); ?></option>
                        <?php endforeach; ?>
                    </select>
                    <?php echo form_error('banco'); ?>   
                </div>
            </div>

            <div class="col-md-12 d-flex">
                <div class="col-md-7 pl-0">
                    <div class="form-group">
                        <label for="tipo_movimiento">Tipo movimiento:</label>
                        <span class="glyphicon glyphicon-question-sign icon-help-config" aria-hidden="true" data-container="body" data-toggle="popover" data-placement="right" data-content="Tipo de movimiento que puedes generar a tu banco de tipo entrada o salida" data-trigger="hover"></span>
                        <select name="tipo_movimiento" id="" class="col-sm-6 form-control" required>
                            <?php foreach($tipo_movimientos as $tipo_movimiento): 
                                    $tipo = ($tipo_movimiento->tipo == 1)? 'entrada' : 'salida';
                                    ?>
                                    <option value="<?= $tipo_movimiento->id; ?>"><?= ucfirst($tipo_movimiento->nombre).' - '.ucfirst($tipo); ?></option>
                            <?php endforeach; ?>
                        </select>
                        <?php echo form_error('tipo_movimiento'); ?>   
                    </div>
                </div>
                
                <div class="col-md-5">
                    <a class="link_new" href="" id="crear_tipo_movimiento">Nuevo tipo de movimiento</a> 
                </div>
            </div>
                

             <div class="col-md-7">
                <div class="form-group">
                    <label for="valor">Valor:</label>
                    <span class="glyphicon glyphicon-question-sign icon-help-config" aria-hidden="true" data-container="body" data-toggle="popover" data-placement="right" data-content="Valor total del movimiento." data-trigger="hover"></span>
                    <input type="text" class="form-control"  required name="valor" id="valor" placeholder="Ej: 100000">
                    <?php echo form_error('valor'); ?>   
                </div>
            </div>

            <div class="col-md-7">
                <div class="form-group">
                    <label for="observacion">Observación:</label>
                    <span class="glyphicon glyphicon-question-sign icon-help-config" aria-hidden="true" data-container="body" data-toggle="popover" data-placement="right" data-content="Descripción corta del movimiento que se esta asociando al banco." data-trigger="hover"></span>
                    <textarea name="observacion" id="" class="col-sm-6 form-control" placeholder="Observación del movimiento"></textarea> 
                </div>
            </div>

             <div class="col-md-7 ">
                <div class="form-group">
                    <label for="nota_impresion">Nota de impresión:</label>
                    <span class="glyphicon glyphicon-question-sign icon-help-config" aria-hidden="true" data-container="body" data-toggle="popover" data-placement="right" data-content="Nota visible al momento de imprimir" data-trigger="hover"></span>
                    <textarea name="nota_impresion" id="" class="col-sm-6 form-control" placeholder="Nota de impresión"></textarea>
                </div>
            </div>
            
            <div class="form-group ">
                <div class="col-sm-10">
                    <a href="<?php echo site_url('bancos/movimientos');?>" class="btn btn-default pull-left mr-2">Cancelar</a>
                    <button id="guardar" type="submit" class="btn btn-success pull-left ">Guardar</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- modal tipo de movimiento -->
<div class="modal fade content-modal" tabindex="-1" role="dialog"  id="modal-nuevo-tipo">
  <div class="" role="document">
    <div class="">
      <div class="modal-header">
        <button type="button" class="close close-modal" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title text-center">Nuevo tipo de movimiento</h4>
      </div>

       <form class="" action="<?php echo site_url('bancos/crear_tipo_movimiento')?>" method="post" id="tipo_movimiento_validate" >
            <div class="">
                <div class="form-group">
                    <label for="nombre_movimiento" class="col-sm-4 control-label text-left">Nombre del movimiento:</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control required"  name="nombre_movimiento" id="nombre_movimiento" placeholder="Ej: Consignación">
                    </div>
                </div>

                <div class="form-group">
                    <label for="tipo_movimiento" class="col-sm-4 control-label text-left">Tipo de movimiento:</label>
                    <div class="col-sm-8">
                        <select name="tipo_movimiento" id="" class="col-sm-8 form-control">
                            <option value="1">Entrada</option>
                            <option value="2">Salida</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="pull-right">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                <button type="submit" id="tipo_movi" class="btn btn-success">Guardar</button>
            </div>
        </form>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<script>
    $("#crear_tipo_movimiento").click(function(e){
        e.preventDefault();
        $("#modal-nuevo-tipo").modal("show");
    })

    $("#tipo_movi").click(function (e) {  
        $("#tipo_movi").prop('disabled',true);
        $("#tipo_movimiento_validate").submit(); 
    });
    
    $("#guardar").click(function (e) {  
            $("#guardar").prop('disabled',true);
            $("#validate").submit(); 
        });
</script>