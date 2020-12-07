<style type="text/css">
    .product-table{
        width: 400px;
        /*height: 150px;
        overflow: auto;*/
   }
   .product_name{
       color: #005683;
   }
   .precio_venta{
       color:  #C22439;
   }
   .search-row{
       border-bottom: 1px solid #ccc;
       cursor: point;
   }
   .search-row:hover{
       background-color: #ccc;
       cursor: pointer;
   }
</style>
<div class="page-header">
    <div class="icon">
        <span class="ico-files"></span>
    </div>
    <h1><?php echo custom_lang("Facturas", "Factura");?><small><?php echo $this->config->item('site_title');?></small></h1>
</div>
<div class="block title">
    <div class="head">
        <h2><?php echo custom_lang('sima_new_bill', "Nueva factura");?></h2>                                          
    </div>
</div>
<div class="row-fluid">
    <div class="block">
        <div class="row-form">
            <div class="span7">
                <div class="span2">
                    <i><strong>Cantidad:</strong></i><br/>
                    3</div>
                <div class="span5">
                    <i><strong>B&uacute;scalo:</strong></i><br/>
                    <input type="text" placeholder="Buscalo"/>
                     <br>
                    <div class="search-row">
                        <div class="product_name">Memoria USB 4GB Kington</div>
                        <div class="product_count">Cantidad: 40&nbsp;&nbsp;Precio compra: 12.000</div>
                        <div class="precio_venta">$40.000</div>
                    </div>
                     <div class="search-row">
                        <div class="product_name">Memoria USB 4GB Kington</div>
                        <div class="product_count">Cantidad: 60&nbsp;&nbsp;Precio compra: 30.000</div>
                        <div class="precio_venta">$20.000</div>
                    </div>
                     <div class="search-row">
                        <div class="product_name">Memoria USB 4GB Kington</div>
                        <div class="product_count">Cantidad: 1110&nbsp;&nbsp;Precio compra: 40.000</div>
                        <div class="precio_venta">$50.000</div>
                    </div>
                </div>
                <div class="span3">
                    <i><strong>Acciones</i></strong><br/>
                    <a href="#" class="btn"><i class="ico-plus"></i></a>
                    <a href="#" class="btn btn-danger"><i class="ico-remove"></i></a>
                    <a href="#" class="btn btn-success"><i class="ico-arrow-right"></i></a>
                </div>
            </div>
            <div class="span5">
                <table class="product-table">
                    <thead>
                        <tr>
                            <td colspan="3"><i><strong>Productos</strong></i></td>
                        </tr>
                        <tr>
                            <td width="60%"><strong>Descripci&oacute;n</strong></td>
                            <td width="10%"><strong>#</strong></td>
                            <td width="20%"><strong>$</strong></td>
                            <td width="10%"><strong>Borrar</strong></td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>prroduct 1</td>
                            <td>5</td>
                            <td>30.000</td>
                            <td><a href="#" class="btn btn-danger"><i class="ico-remove"></i></a></td>
                        </tr>
                        <tr>
                            <td>prroduct 2</td>
                            <td>5</td>
                            <td>30.000</td>
                            <td><a href="#" class="btn btn-danger"><i class="ico-remove"></i></a></td>
                        </tr>
                        <tr>
                            <td>prroduct 3</td>
                            <td>5</td>
                            <td>30.000</td>
                            <td><a href="#" class="btn btn-danger"><i class="ico-remove"></i></a></td>
                        </tr>
                        <tr>
                            <td>prroduct 3</td>
                            <td>5</td>
                            <td>30.000</td>
                            <td><a href="#" class="btn btn-danger"><i class="ico-remove"></i></a></td>
                        </tr>
                        <tr>
                            <td>prroduct 3</td>
                            <td>5</td>
                            <td>30.000</td>
                            <td><a href="#" class="btn btn-danger"><i class="ico-remove"></i></a></td>
                        </tr>
                        <tr>
                            <td>prroduct 3</td>
                            <td>5</td>
                            <td>30.000</td>
                            <td><a href="#" class="btn btn-danger"><i class="ico-remove"></i></a></td>
                        </tr>
                    </tbody>
                </table>
                <table class="product-table">
                    <thead>
                         <tr>
                            <td><i><strong>Cliente</strong></i></td>
                        </tr> 
                    </thead>
                     <tbody>
                        <tr>
                            <td width="100px;">Nombre</td>
                            <td><input type="text" name="nombre_cliente" placeholder="Nombre"/></td>
                        </tr> 
                        <tr>
                            <td>Identificaci&oacute;n</td>
                            <td><input type="text" name="identificacion" placeholder="identificacion"/></td>
                        </tr> 
                        <tr>
                            <td>Correo</td>
                            <td><input type="text" name="identificacion" placeholder="correo" class="xxlarge"/></td>
                        </tr> 
                        <tr>
                            <td>Tel&eacute;fono</td>
                            <td><input type="text" name="telefono" placeholder="telefono" class="span12"/></td>
                        </tr> 
                     </tbody>
                </table>
                <table class="product-table">
                    <tbody>
                        <tr>
                            <td><i><strong>Vendedor</strong></i></td>
                        </tr> 
                        <tr>
                            <td width="100px;">Nombre</td>
                            <td><input type="text" name="nombre_vendedor" placeholder="Nombre"/></td>
                        </tr>
                    </tbody>
                </table>
                 <table class="product-table">
                    <tr>
                       
                        <td><button class="btn btn-large" style="float: right;"><i class="ico-ok-sign"></i> Pagar</button> </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>