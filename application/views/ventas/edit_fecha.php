<div class="page-header">
    <div class="icon">
        <img alt="ventas" class="iconimg"
            src="<?php echo $this->session->userdata('new_imagenes')['editar']['original'] ?>">
    </div>
    <h1 class="sub-title">Editar fecha factura</h1>
</div>
<div class="row-fluid">

    <div class="col-md-12">
        
        <div class="block">
            <div class="alert" style="color: #8a6d3b;background-color: #fcf8e3;border-color: #faebcc;">
                <p class="">
                    Indique la fecha de la factura N° <?php echo $venta->factura;?>
                </p>
            </div>
        </div>
        <div class="block">
            <div class="alert" style="color: #8a6d3b;background-color: red;border-color: #faebcc;color:#fff">
                <p class="">
                    si desea mover facturas a cajas anteriores esta debe estar cerrada previamente
                </p>
            </div>
        </div>
        <form action="<?php echo site_url('ventas/update_fecha');?>" method="POST">
            <input type="hidden" name="venta_id" value="<?php echo $venta->id; ?>">
            <div class="row items-container ml-0 mr-0">
                <div class="form-group col-md-6">
                    <input type="date" name="fecha">
                </div>
            </div>

            <span>Cierre de caja a actualizar</span>
            <div class="row items-container ml-0 mr-0">
                <div class="form-group col-md-6">
                    <select class="form-control" name="id_cierre" id="">
                        <option value="">Seleccione</option>
                        <?php foreach($cajas as $caja): ?>
                        <option value="<?php echo $caja['id'] ?>"><?php echo 'Nombre: '.$caja['nombre_caja'].' Fecha: '.$caja['fecha_cierre'].' Total cierre: '.$caja['total_cierre']?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <button class="btn btn-success" id="generar" type="submit">actualizar</button>


        </form>

    </div>
</div>