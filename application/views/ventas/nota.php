<div class="page-header">
    <div class="icon">
        <img alt="ventas" class="iconimg"
            src="<?php echo $this->session->userdata('new_imagenes')['titulo_contactos']['original'] ?>">
    </div>
    <h1 class="sub-title">Nota de credito</h1>
</div>
<div class="row-fluid">

    <div class="col-md-12">
        <?php if(!$nota['nota_exist']) : ?>
        <div class="block">
            <div class="alert" style="color: #8a6d3b;background-color: #fcf8e3;border-color: #faebcc;">
                <p class="">
                    Indique un comentario asociado a la factura N° <?php echo $venta->factura;?>
                </p>
            </div>
        </div>
        <form action="<?php echo site_url('ventas/genera_nota');?>" method="POST">
            <input type="hidden" name="venta_id" value="<?php echo $venta->id; ?>">
            <input type="hidden" name="valor" value="<?php echo $venta->total_venta; ?>">
            <input type="hidden" name="consecutivo" value="<?php echo $nota['consecutivo']; ?>">
            <input type="hidden" name="cliente_id" value="<?php echo $venta->cliente_id; ?>">
            <span>Consecutivo generado nota de credito</span>
            <div class="row items-container ml-0 mr-0">
                <div class="form-group col-md-6">
                    <input type="text" name="consecutivo" value="<?php echo $nota['consecutivo'] ?>"
                        readonly="readonly">
                </div>
            </div>
            <span>Comentario asociado a la nota de credito</span>
            <div class="row items-container ml-0 mr-0">
                <div class="form-group col-md-6">
                    <textarea name="nota" id="" cols="10" rows="10" placeholder="Comentario"></textarea>
                </div>
            </div>
            <button class="btn btn-success" id="generar" type="submit">Generar</button>


        </form>
        <?php else: ?>
        <div class="block">
            <div class="alert" style="color: #8a6d3b;background-color: #fcf8e3;border-color: #faebcc;">
                <p class="">
                    No es posible generar una nota de credito para la factura N° <?php echo $venta->factura;?> ya se
                    encuentra generada
                </p>
            </div>
            <a href="<?php echo site_url('ventas'); ?>" class="btn btn-success" id="volver" type="submit">Volver</a>
        </div>
        <?php endif; ?>

    </div>
</div>