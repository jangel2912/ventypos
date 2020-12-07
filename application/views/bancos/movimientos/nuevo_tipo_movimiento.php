<style>
    .mr-0{margin-right:0px;}
</style>

<div class="page-header">    
    <div class="icon">
        <img alt="Bancos" class="iconimg" src="<?php echo $this->session->userdata('new_imagenes')['titulo_gastos']['original'] ?>">        
    </div>
     <h1 class="sub-title"><?php echo custom_lang("Nuevo tipo de movimiento", "Nuevo tipo de movimiento");?></h1>
</div>

<div class="row">
    <div class="col-md-5">
        <form class="form-horizontal" action="<?php echo site_url('bancos/crear_tipo_movimiento')?>" method="post">
            <div class="form-group">
                <label for="nombre_movimiento" class="col-sm-4 control-label text-left">Nombre del movimiento:</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control required"  name="nombre_movimiento" id="nombre_movimiento" placeholder="Ej: Consignación">
                </div>
                <?php echo form_error('nombre_movimiento'); ?>
            </div>

            <div class="form-group">
                <label for="tipo_movimiento" class="col-sm-4 control-label text-left">Tipo de movimiento:</label>
                <div class="col-sm-8">
                    <select name="tipo_movimiento" id="" class="col-sm-8 form-control">
                        <option value="1">Entrada</option>
                        <option value="2">Salida</option>
                    </select>
                </div>
                
                <?php echo form_error('tipo_movimiento'); ?>
            </div>

            
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <button type="submit" class="btn btn-success pull-right mr-0">Guardar</button>
                    <a href="<?php echo site_url('bancos/movimientos');?>" class="btn btn-default pull-right">Cancelar</a>
                </div>
            </div>
        </form>
    </div>
</div>