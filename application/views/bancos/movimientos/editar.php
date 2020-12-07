
<style>
    .mr-0{margin-right:0px;}
</style>

<div class="page-header">    
    <div class="icon">
        <img alt="Bancos" class="iconimg" src="<?php echo $this->session->userdata('new_imagenes')['titulo_gastos']['original'] ?>">        
    </div>
     <h1 class="sub-title"><?php echo custom_lang("Editar movimiento bancario", "Editar movimiento bancario");?></h1>
</div>

<div class="row">
    <div class="col-md-5">
        <form class="form-horizontal" action="<?php echo site_url('bancos/actualizar_movimiento')?>" method="post" id="validate">
            
            <div class="form-group">
                <label for="referencia" class="col-sm-4 control-label text-left">Referencia:</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" required  name="referencia" id="referencia" value="<?= $movimiento->referencia;?>">
                </div>
                <?php echo form_error('referencia'); ?>
            </div>

            <div class="form-group">
                <label for="banco" class="col-sm-4 control-label text-left">Cuenta bancaria:</label>
                <div class="col-sm-8">
                    <input type="hidden" name="id_movimiento" value="<?= $movimiento->id;?>">
                    <select name="banco" id="" class="col-sm-8 form-control" required>
                        <?php foreach($bancos as $banco): ?>
                                <option value="<?= $banco->id; ?>" <?= ($banco->id == $movimiento->id_banco)? 'selected' : ''; ?>><?= ucfirst($banco->nombre_cuenta); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <?php echo form_error('banco'); ?>
            </div>

            <div class="form-group">
                <label for="tipo_movimiento" class="col-sm-4 control-label text-left">Tipo movimiento:</label>
                <div class="col-sm-8">
                    <select name="tipo_movimiento" id="" class="col-sm-8 form-control" required>
                        <?php foreach($tipo_movimientos as $tipo_movimiento): 
                                $tipo = ($tipo_movimiento->tipo == 1)? 'entrada' : 'salida';
                                ?>
                                <option value="<?= $tipo_movimiento->id; ?>" <?= ($tipo_movimiento->id == $movimiento->id_tipo)? 'selected' : ''; ?>><?= ucfirst($tipo_movimiento->nombre).' - '.ucfirst($tipo); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <?php echo form_error('tipo_movimiento'); ?>
            </div>

             <div class="form-group">
                <label for="valor" class="col-sm-4 control-label text-left">Valor:</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" required  name="valor" id="valor" value="<?= $movimiento->valor;?>">
                </div>
                <?php echo form_error('valor'); ?>
            </div>

            <div class="form-group">
                <label for="observacion" class="col-sm-4 control-label text-left">Observación:</label>
                <div class="col-sm-8">
                    <textarea name="observacion" id="" class="col-sm-8 form-control" ><?= $movimiento->observacion;?></textarea>
                </div>
            </div>

             <div class="form-group">
                <label for="nota_impresion" class="col-sm-4 control-label text-left">Nota de impresión:</label>
                <div class="col-sm-8">
                    <textarea name="nota_impresion" id="" class="col-sm-8 form-control" ><?= $movimiento->nota_impresion;?></textarea>
                </div>
            </div>

            
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <button type="submit" id="guardar" class="btn btn-success pull-right mr-0">Guardar</button>
                    <a href="<?php echo site_url('bancos/movimientos');?>" class="btn btn-default pull-right">Cancelar</a>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    $("#guardar").click(function (e) {  
        $("#guardar").prop('disabled',true);
        $("#validate").submit(); 
    });
</script>