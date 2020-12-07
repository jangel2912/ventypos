<style>
    #wrapper{
       /* overflow-y: auto !important;*/
       height: 98vh;
    }
    .tab-content{
        overflow-x: hidden !important;
        /*height: 98vh;*/
    }
    .body-content{
        background-color:#fff;
    }
    .panel .panel-body{
        height: 80vh !important;
    }
    
element.style {
}
input[type="number"] {
    height:34px;
}

</style>

<div class="header-content">
    <h2></i>Productos Restaurante <span>Crear nuevo producto</span></h2>
    <div class="breadcrumb-wrapper hidden-xs">
        <span>Estas aqui:</span>
        <ol class="breadcrumb">
            <li class="active">Crear Productos</li>
        </ol>
    </div>
</div>
<div class="body-content">
    <div class="row">
        <div class="col-md-12">
        <div id="mensaje"></div>
        <!--
        <div class="alert alert-warning hidden">
            <span></span>
            <div class="notification-info">
                <ul class="clearfix notification-meta">
                    <li class="pull-left notification-sender"><p class="validateTips">Campos requeridos.</p></li>                    
                </ul>
                
            </div>
        </div>-->
        
        <div class="panel panel-tab panel-tab-double shadow">
        <!-- Start tabs heading -->
        <div class="panel-heading no-padding">
            <ul class="nav nav-tabs">
                <li class="active nav-border nav-border-top-success">
                    <a href="#tab6-1" data-toggle="tab" class="text-center">
                        Datos Basicos
                    </a>
                </li>
                <li class="nav-border nav-border-top-info">
                    <a href="#tab6-4" data-toggle="tab" class="text-center">
                        Ingredientes
                    </a>
                </li>
                <li class="nav-border nav-border-top-success">
                    <a href="#tab6-2" data-toggle="tab" class="text-center">
                        Adicionales
                    </a>
                </li>
                <li class="nav-border nav-border-top-success">
                    <a href="#tab6-3" data-toggle="tab" class="text-center">
                        Modificaciones
                    </a>
                </li>
                
            </ul>
        </div><!-- /.panel-heading -->
        <!--/ End tabs heading -->

        <!-- Start tabs content -->
        <div class="panel-body">
            <div class="tab-content">
                <div class="tab-pane fade in active" id="tab6-1">
                    <form id="frm_producto_basico">
                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-6">                                    
                                    <input  id="precio_almacen" name="precio_almacen" type="hidden" value="<?php echo $data['precio_almacen']; ?>">
                                    <?php if(isset($data['producto'])): ?>
                                    <input type="hidden" name="form" value="<?php if(isset($data['id'])) echo $data['id']; ?>">                                        
                                    <?php endif; ?>
                                    <div class="form-group">
                                            <label class="col-md-4">Tipo de Producto</label>    
                                        <div class="col-sm-8">
                                            <div class="rdio rdio-theme inline mr-10">
                                                <?php 
                                                    $checked = 'checked';
                                                    if(isset($data['producto']['ingredientes'])){
                                                        $data['producto']['combo'] == 0 || $data['producto']['ingredientes'] == 0 ? $checked = 'checked' : $checked = '';  
                                                    }    

                                                ?> 
                                                <input <?php echo $checked; ?> id="tipo_producto_unico" value="1" name="tipo_producto_id" type="radio">
                                                <label for="tipo_producto_unico">Unico</label>
                                            </div>
                                            <div class="rdio rdio-theme inline">
                                                <?php 
                                                $chk_ing = 'checked';
                                                if(isset($data['producto']['ingredientes'])){
                                                    
                                                    if($data['producto']['ingredientes'] == '1')  
                                                         $chk_ing = 'checked';
                                                    else
                                                         $chk_ing = '';  
                                                }   
                                                ?>
                                                <input <?php echo $chk_ing; ?> id="tipo_producto_compuesto" name="tipo_producto_id" value="2" type="radio">
                                                <label for="tipo_producto_compuesto">Compuesto</label>
                                            </div>
                                            <div class="rdio rdio-theme inline">
                                                <?php 
                                                $chk_combo = 'checked';
                                                if(isset($data['producto']['combo'])){
                                                    
                                                    if($data['producto']['combo'] == '1')  
                                                         $chk_combo = 'checked';
                                                    else
                                                         $chk_combo = '';  
                                                }   
                                                ?>
                                                <input id="tipo_producto_combo" <?php echo $chk_combo; ?> name="tipo_producto_id" value="3" type="radio">
                                                <label for="tipo_producto_combo">Combo</label>
                                            </div>
                                        </div>
                                    </div><br>
                                    <div class="form-group">
                                        <label class="control-label">Nombre</label>
                                        <input class="form-control" id="txt_nombre" name="txt_nombre" type="text" value="<?php if(isset($data['producto'])) echo $data['producto']['nombre']; ?>">
                                    </div><!-- /.form-group -->

                                    <div class="form-group">
                                        <label class="control-label">Codigo</label>
                                        <input class="form-control rounded" id="txt_codigo" name="txt_codigo" type="text" value="<?php if(isset($data['producto'])) echo $data['producto']['codigo'];?>">
                                    </div><!-- /.form-group -->
                                    <?php if ($data['precio_almacen'] == 0) { ?>
                                    <div class="form-group">
                                        <label class="control-label">Precio de compra</label>
                                        <input class="form-control form-focus" id="precio_compra" name="precio_compra" type="number" required value="<?php if(isset($data['producto'])) echo $data['producto']['precio_compra']; else echo 0;?>">                                        
                                    </div><!-- /.form-group -->

                                    <div class="form-group">
                                        <label class="control-label">Precio de Venta</label>
                                        <input class="form-control" placeholder="" type="number" id="precio_venta" name="precio_venta" required value="<?php if(isset($data['producto'])) echo $data['producto']['precio_venta']; else echo 0;?>" readonly="readonly" />
                                    </div><!-- /.form-group -->

                                    <div class="form-group">
                                        <label class="control-label">Impuesto</label>
                                        <?php $impuesto_producto=(isset($data['producto']['impuesto'])) ? $data['producto']['impuesto'] : ""; ?>
                                        <?php echo form_dropdown('id_impuesto', $data['impuestos'],$impuesto_producto,'class="form-control" id="id_impuesto"', $this->form_validation->set_value('id_impuesto'), "id='id_impuesto'"); ?>
                                        <input  type="hidden" value="" name="impue" id="impue" readonly="readonly" />
                                    </div><!-- /.form-group -->

                                    <div class="form-group">
                                        <label class="control-label">Precio de venta con impuesto</label>
                                        <input class="form-control spinner" placeholder="" type="text" id="precio_venta_impuesto" name="precio_venta_impuesto" required value="<?php if(isset($data['producto'])) echo $data['producto']['precio_venta']; else echo 0;?>">
                                    </div><!-- /.form-group -->
                                    <?php } ?>
                                    <div class="form-group">
                                        <label class="control-label">Descripción</label>
                                        <textarea class="form-control" name="text_description"rows="5"><?php if(isset($data['producto'])) echo $data['producto']['descripcion'];?></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label" for="disabledinput1">Proveedor</label>
                                       <select class="form-control" id="slt_proveedor" name="slt_proveedor">
                                            <option value="" disabled hidden>Seleccione</option>
                                            <?php foreach($data['proveedores'] as $proveedor):
                                                $check=((isset($data['producto']))&& ($proveedor['id_proveedor']==$data['producto']['id_proveedor'])) ? "selected" : "";
                                                echo $check;
                                                ?>
                                                <option value="<?php echo $proveedor['id_proveedor']; ?>" <?php echo $check ?> ><?php echo $proveedor['nombre_comercial']; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div><!-- /.form-group -->
                        
                                    <div class="form-group">                                       
                                        <div class="ckbox ckbox-theme">
                                            <?php $checked = 'checked'; ?>
                                            <?php if(isset($data['producto']['activo'])){                                                
                                                ($data['producto']['activo'] == 1) ? $checked = 'checked' : $checked = '';
                                            } ?>
                                            <input id="checkbox-activo" type="checkbox" <?php echo $checked; ?> name="chk_activo" value="1">
                                            <label for="checkbox-activo">Activo</label>
                                        </div>
                                        <div class="ckbox ckbox-theme">
                                            <?php $checked = 'checked'; ?>
                                            <?php if(isset($data['producto']['muestraexist'])){    
                                                ($data['producto']['muestraexist']  == 1) ? $checked = 'checked' : $checked = '';
                                            }?>
                                        </div>
                                        <div class="ckbox ckbox-theme">
                                            <?php if(isset($data['producto']['material']) && $data['producto']['material']  == 1 ? $chk_ingrdiente = 'checked' : $chk_ingrdiente = '') ?>
                                            <input id="checkbox-ingrendiente" <?php echo $chk_ingrdiente; ?>  type="checkbox" name="chk_ingrediente" value="1">
                                            <label for="checkbox-ingrendiente">Es ingrediente</label>
                                        </div>
                                    </div>                                    
                                </div>  
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Imagen Producto</label>
                                        <div class="fileinput fileinput-new" data-provides="fileinput">
                                            <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width: 200px; height: 150px;"></div>
                                            <div>
                                            <span class="btn btn-default btn-file">
                                                <span class="fileinput-new">Select image</span>
                                                <span class="fileinput-exists">Change</span>
                                                <input type="file" name="imagen1">
                                            </span>
                                            <a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput">Remove</a>
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                    
                                    <div class="form-group">
                                        <label class="control-label">Categoria</label>
                                        <select class="form-control" id="slt_categoria" name="slt_categoria">
                                            <option value="">Seleccione</option>
                                            <?php foreach($data['categorias'] as $categoria): ?>
                                            <?php if(isset($data['producto']) && $data['producto']['categoria_id'] == $categoria->id){ 
                                                    $selected = 'selected';
                                                    }else{
                                                        $selected = '';} 
                                                ?>
                                                <option <?php echo $selected; ?> value="<?php echo $categoria->id; ?>"><?php echo $categoria->nombre; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div><!-- /.form-group -->
                                    <div class="form-group">
                                        <label class="control-label">Unidad de Medida</label>
                                        <select class="form-control" id="unidad_medida" name="unidad_medida">
                                            <option value="">Seleccione</option>
                                            <?php foreach($data['unidades'] as $unidades): ?>
                                            <?php if(isset($data['producto']) && $data['producto']['unidad_id'] == $unidades->id){ 
                                                    $selected = 'selected';
                                                    }else{
                                                        $selected = '';} 
                                                ?>
                                                <option <?php echo $selected; ?> value="<?php echo $unidades->id; ?>"><?php echo $unidades->nombre; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div> 
                            </div>
                        </div><!-- /.form-body -->
                    </form>
                    <form id="frm_producto_stock" style="overflow-x: auto;">  
                        <div class="form-body">
                            <div class="row">                    
                                <div class="col-md-12"> 
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Almacen</th>
                                                <th>Cantidad</th>
                                                <?php if(isset($data['id'])){ ?>                               
                                                    <th>Cantidad actual</th>
                                                <?php } ?>
                                                <?php if ($data['precio_almacen'] == 1) { ?>
                                                    <th>Stock Mínimo</th>
                                                    <th>Precio Compra</th>
                                                    <th>Precio Venta</th>
                                                    <th>Impuesto</th>
                                                    <th>Fecha Vencimiento</th>
                                                    <th>Activo</th>
                                                <?php } ?>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                            $is_admin = $this->session->userdata('is_admin');
                                            foreach ($data['almacenes'] as $value) :                    
                                                $desactivado="";               
                                                if(!isset($data['id'])){ 
                                                    $value->almacen_id=$value->id;
                                                    $value->unidades=0;
                                                    $value->stock_minimo=0;
                                                    $value->precio_compra=0;
                                                    $value->precio_venta=0;
                                                    $value->impuesto=array_values($data['impuestos'])[0];
                                                } 
                                                if(!empty($data['almacenes_inactivo'])){
                                                                            
                                                    if (array_key_exists($value->almacen_id, $data['almacenes_inactivo'])) {
                                                        $desactivado='readonly';                                                                                                  
                                                    }
                                                } 
                                                ?>                                      
                                                    <tr>

                                                        <td style="padding-top: 18px;"><b><?php echo $value->nombre; ?></b></td>
                                                        
                                                        <td><input <?=$desactivado?> name="Stock[<?php echo $value->almacen_id; ?>]" min="0" type="number" value="<?php echo isset($_POST['Stock'][$value->almacen_id]) ? $_POST['Stock'][$value->almacen_id] : 0; ?>" data-almacen_id="<?= $value->almacen_id ?>" data-stock_actual="<?= $value->unidades ?>" /></td>
                                                        <?php if(isset($data['id'])){ ?>  
                                                        <td style="padding-top: 18px;"><?php echo $value->unidades; ?></td>
                                                        <?php } ?>
                                                        <?php if ($data['precio_almacen'] == 1) { ?>
                                                            <td><input <?=$desactivado?> name="Stock_minimo[<?php echo $value->almacen_id; ?>]" min="0" type="number" value="<?= $value->stock_minimo; ?>" data-almacen_id="<?= $value->almacen_id ?>" data-stock_minimo="<?= $value->stock_minimo ?>" /></td>
                                                            <td><input <?=$desactivado?> name="Precio_compra[<?php echo $value->almacen_id; ?>]" min="0" type="number" value="<?= $value->precio_compra; ?>" data-almacen_id="<?= $value->almacen_id ?>" data-precio_compra="<?= $value->precio_compra ?>" /></td>
                                                            <td><input <?=$desactivado?> name="Precio_venta[<?php echo $value->almacen_id; ?>]" min="0" type="number" value="<?= $value->precio_venta; ?>" data-almacen_id="<?= $value->almacen_id ?>" data-precio_venta="<?= $value->precio_venta ?>" /></td>
                                                            <td><?php echo form_dropdown('Impuesto['.$value->almacen_id.']', $data['impuestos'], $value->impuesto, 'data-almacen_id="'.$value->almacen_id.'" data-impuesto="'.$value->impuesto.'"'); ?></td>
                                                            <td><input <?=$desactivado?> class="datepicker" name="Fecha_vencimiento[<?php echo $value->almacen_id; ?>]" min="0" type="text" value="<?= $value->fecha_vencimiento; ?>" data-almacen_id="<?= $value->almacen_id ?>" data-fecha_vencimiento="<?= $value->fecha_vencimiento ?>" /></td>
                                                            <td><?php echo form_dropdown('Activo['.$value->almacen_id.']', array('1'=>'Si','0'=>'No'), $value->activo, 'data-almacen_id="'.$value->almacen_id.'" data-activo="'.$value->activo.'"'); ?></td>
                                                        <?php } ?>
                                                    </tr>                                       
                                            <?php endforeach; ?>

                                        </tbody>
                                    </table>                                
                                </div>
                            </div>
                        </div>
                    </form>                    
                </div>
                <div class="tab-pane fade" id="tab6-2">
                        <h5>Adicionales</h5>
                        <form action="">
                            
                                <div class="row">
                                    <div class="form-group">
                                        <div class="col-md-6">
                                            <div class="input-group mb-15">
                                                <select name="" class="form-control select2" id="slt_adicional"></select>
                                                <span class="input-group-btn">
                                                    <button id="add_row" type="button" class="btn btn-success" style="height: 35px; width: 35px;">+</button>    
                                                </span>
                                                
                                            </div>
                                        </div>
                                    </div>
                                    
                                </div>
                        </form>
                    <form id="frm_adicionales">
                        <table class="table table-bordered table-hover table-sortable" id="tab_logic">
                            <thead>
                                <tr >
                                    <th>
                                    </th>                    
                                    <th class="text-center">
                                        Adicional
                                    </th>
                                    <th class="text-center">
                                        Precio
                                    </th>
                                    <th class="text-center">
                                        Cantidad
                                    </th>
                                    <th class="text-center" style="border-top: 1px solid #ffffff; border-right: 1px solid #ffffff;">
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                
                            <tr id='addr0' data-id="0" class="hidden">
                                    <td data-name="id_producto">
                                        <input type="hidden" name='producto[0][id_producto]'  placeholder='Producto' class="form-control"/>    
                                    </td>
                                    <td data-name="producto">   
                                        <input type="text" name='producto[0][producto]'  placeholder='Producto' class="form-control"/>
                                    </td>
                                    <td data-name="precio">
                                        <input type="text" name='producto[0][precio]' placeholder='Precio' class="form-control"/>
                                    </td>
                                    <td data-name="cantidad">
                                        <input type="text" name='producto[0][cantidad]' placeholder='Cantidad' class="form-control"/>
                                    </td>
                                    <td data-name="del">
                                        <button nam"del0" data-adicion="0" data-producto="0" class='btn btn-danger glyphicon glyphicon-remove row-remove'></button>
                                    </td>
                                </tr>
                                <?php if(isset($data['adicionales'])): 
                                    $counter = 1;
                                        foreach($data['adicionales'] as $adicional):
                                ?>
                                                        
                                    <tr id='addr<?php echo $counter ?>' data-id="<?php echo $counter; ?>">
                                    <td data-name="id_producto">
                                        <input type="hidden" name='producto[<?php echo $counter; ?>][id_producto]' value="<?php echo $adicional['id_adicional']; ?>"  placeholder='Producto' class="form-control"/>    
                                    </td>
                                    <td data-name="producto">   
                                        <input type="text" name='producto[<?php echo $counter; ?>][producto]' value="<?php echo $adicional  ['nombre'] ?>"  placeholder='Producto' class="form-control"/>
                                    </td>
                                    <td data-name="precio">
                                        <input type="text" name='producto[<?php echo $counter; ?>][precio]' placeholder='Precio' value="<?php echo $adicional['precio']; ?>" class="form-control"/>
                                    </td>
                                    <td data-name="cantidad">
                                        <input type="text" name='producto[<?php echo $counter; ?>][cantidad]' placeholder='Cantidad' value="<?php echo $adicional['cantidad']; ?>" class="form-control"/>
                                    </td>
                                    <td data-name="del">
                                        <button type="button" nam"del0"  data-adicion="<?php echo $adicional['id_adicional']; ?>" data-producto="<?php echo $data['id']; ?>"  onclick="eliminar(this,1)"  class='btn btn-danger glyphicon glyphicon-remove row-remove'></button>
                                    </td>
                                    </tr>                    
                                <?php 
                                    $counter++;
                                    endforeach;
                                endif;     
                                ?>
                            </tbody>
                        </table>    
                    </form>
                    
                </div>
                <div class="tab-pane fade" id="tab6-3">
                    <h5>Modificaciones</h5>
                    <input id="txt_modificacion" type="text" value="<?php echo isset($data['modificacion']) ? $data['modificacion'] : ''; ?>" class="form-control tags" data-role="tagsinput" >
                </div>
                <div class="tab-pane" id="tab6-4">
                    <form id="frm_ingredientes">                   
                        <div class="pull-left">
                            <a id="add_ing" class="btn btn-default">Adicionar ingrediente</a>
                            <div class="clearfix"></div>
                        </div> 
                        <br><br> 
                        
                        <table class="table table-bordered table-hover table-sortable" id="tab_ing">
                        <thead>
                        <tr>
                            <th></th>        
                            <th class="text-center">
                                Producto
                            </th>
                            <th class="text-center">
                                Cantidad
                            </th>
                            <th class="text-center">                            
                            </th>    
                        </tr>
                        </thead>
                        <tbody>                                

                        <tr id='addr0' data-id="0" class="hidden">
                            <td data-name="id" class="">
                                <input type="hidden" name='producto[0][id]'  placeholder='Name' class="form-control"/>
                            </td>
                            <td data-name="name">
                                <select  name='producto[0][name]' placeholder='producto' class="form-control seleted" data-id="0"/>
                                    <?php if(isset($data['productos'])): 
                                        foreach($data['productos'] as $producto):
                                            
                                    ?>
                                        <option value="<?php echo $producto['id'] ?>"><?php echo $producto['nombre'] ?></option>
                                        <?php endforeach; endif; ?>                                    
                                </select>
                            </td>
                            <td data-name="cantidad">
                                <input type="text" idI="0" name='producto[0][cantidad]' placeholder='cantidad' class="form-control ingreCantidad"/>
                            </td>
                            <td data-name="del">
                                <button nam"del0" class='btn btn-danger glyphicon glyphicon-remove row-remove'></button>
                            </td>
                        </tr>
                        <?php if(isset($data['ingredientes'])): 
                                        $counter = 1;
                                            foreach($data['ingredientes'] as $ingrediente):
                                    ?>
                                                            
                                        <tr id='addr<?php echo $counter ?>' data-id="<?php echo $counter; ?>">
                                        <td data-name="id">
                                            <input type="hidden" id="ingrediente_<?php echo $counter; ?>" name='producto[<?php echo $counter; ?>][id]' value="<?php echo $ingrediente['id']; ?>"  placeholder='Producto' class="form-control"/>    
                                        </td>
                                        <td data-name="producto">   
                                        <select  name='producto[<?php echo $counter ?>][name]' placeholder='producto' class="form-control seleted"/>
                                        <?php if(isset($data['productos'])): 
                                                foreach($data['productos'] as $producto):
                                                $selected = '';
                                                if($producto['id'] == $ingrediente['id'])    
                                                    $selected = 'selected';
                                            ?>
                                                <option <?php echo $selected; ?> value="<?php echo $producto['id']; ?>"><?php echo $producto['nombre'] ?></option>
                                                <?php endforeach; endif; ?>                                    
                                        </select>
                                        </td>
                                        <td data-name="cantidad">
                                            <input type="text" idP="<?php echo $data['id']; ?>" idI="<?php echo $ingrediente['id']; ?>" idCI="<?php echo $ingrediente['cantidad_ingrediente']; ?>" name='producto[<?php echo $counter; ?>][cantidad]' placeholder='cantidad' value="<?php echo $ingrediente['cantidad_ingrediente']; ?>" class="form-control ingreCantidad"/>
                                        </td>
                                        <td data-name="del">
                                            <button type="button" data-producto="<?php echo $data['id'] ?>" data-adicion="<?php echo $ingrediente['id']; ?>" data-producto="<?php echo $data['id']; ?>"  onclick="eliminar(this,2)" name="producto[<?php echo $counter; ?>][del]" class='btn btn-danger glyphicon glyphicon-remove row-remove' id="material_<?php echo $counter; ?>"></button>
                                        </td>
                                        </tr>                    
                                    <?php 
                                        $counter++;
                                        endforeach;
                                    endif;     
                                    ?>
                        
                        
                        </tbody>
                        </table>
                    </form>    
                </div>  
                <div class="panel-footer">
                    <div class="pull-right">
                        <a href="<?php echo base_url().'index.php/ProductoRestaurant' ?>" class="btn btn-default mr-5">Cancelar</a>
                        <button id="btn_store" class="btn btn-success" type="submit">Guardar</button>
                    </div>
                    <div class="clearfix"></div>
                </div>              
            </div>           
        </div><!-- /.panel-body -->
        
        <!--/ End tabs content -->
    </div>
        </div>
    </div>
</div>
<script>

    var arrayingredientes=<?php echo json_encode($data['productos'])?>;    
    var precio=$("#precio_venta").val();
    var impuesto="";

    function precio_compra(cantA,ingrediente_modificado){            
        var tabla=$("#tab_ing");
        var valor=0;
        var cantidad=0;
        var precio_compra=0;
        var arrayingredientesActuales=<?php echo json_encode(isset($data['ingredientes']) ? $data['ingredientes'] : [])?>;
        if(cantA > 0){       
            
            //buscar todos los ingref
            $.each($("#tab_ing tbody tr"), function() {
                cur_td=$(this);
                idtabla=cur_td.data("id");
                if(idtabla>0){
                    ingre=$("#ingrediente_"+idtabla).val(); 
                   
                    //busco el valor del ingrediente
                    for(var i=0;i<arrayingredientes.length;i++){   

                        if(ingre==arrayingredientes[i]['id']){
                            //console.log(arrayingredientes[i]);
                            valor=arrayingredientes[i]['precio_compra'];                            
                        }                        
                    }
                    //busco la cantidad que hay actualmente
                    for(var i=0;i<arrayingredientesActuales.length;i++){
                        if(ingre==arrayingredientesActuales[i]['id']){
                            cantidad=arrayingredientesActuales[i]['cantidad_ingrediente'];
                        }                        
                    }
                                        
                    if(ingrediente_modificado==ingre){
                        precio_compra += parseFloat(valor*cantA);
                    }else{
                        precio_compra += parseFloat(valor*cantidad);                        
                    }

                    $("#precio_compra").val(precio_compra);
                                        
                }               
            });    
            //console.log("precio_compra="+precio_compra);       
        }  
    }

    function eliminar(element,tipo) {
        //tipo=1 Adiciones
        //tipo=2 Ingredientes
        var button = $(element);
        var adicional = button.data('adicion');
        var id = button.data('producto');        
        
            url="<?php echo site_url('ProductoRestaurant/eliminar_Adicion_Ingredientes') ?>";
            
            $.ajax({
                type: 'POST',
                url: url,
                data: {id:id,adicional:adicional,type:tipo},
                dataType: "json",                
                success: function (data) {  

                    if(data.success==true){                        
                         
                        swal({
                            position: 'center',
                            type: 'success',
                            title: "success",
                            html: data.mensaje,
                            showConfirmButton: false,
                            timer: 1500
                        })
                        button.closest("tr").remove();      
                        //cambiar el precio compra
                        var arrayingredientesActuales=<?php echo json_encode(isset($data['ingredientes']) ? $data['ingredientes'] : [])?>;
                        valor=0;
                        precio_compra=0;
                        $.each($("#tab_ing tbody tr"), function() {
                            cur_tr=$(this);                            
                            idtabla=cur_tr.data("id");
                                                        
                            if(idtabla>0){
                                ingre=$("#ingrediente_"+idtabla).val(); 
                               //cantidad
                               x=cur_tr.find('.ingreCantidad');
                               cantidad=x.val();
                                //cantidad=
                                 //busco el valor del ingrediente
                                for(var i=0;i<arrayingredientes.length;i++){   

                                    if(ingre==arrayingredientes[i]['id']){                                    
                                        valor=arrayingredientes[i]['precio_compra'];                            
                                    }                        
                                } 
                                precio_compra += parseFloat(valor*cantidad);       
                                
                            }
                        });          
                        $("#precio_compra").val(precio_compra);     
                        //console.log(precio_compra);  
                    }
                }
            })  
    }

$(document).ready(function() {
    $("#frm_adicionales").submit(function(event) {
        event.preventDefault();
    });

    $.ajax({
        url: "<?php echo site_url("productos/impuesto_valor"); ?>",
        type: "GET",
        dataType: "json",
        data: {id_impuesto: parseFloat($("#id_impuesto").val())},
        success: function (data) {
            $("#impue").val(parseFloat(data.porciento));
            if (parseFloat($("#precio_venta").val()) > 0) {
                $("#precio_venta_impuesto").val(parseFloat(parseFloat($("#precio_venta").val()) * ((parseFloat($("#impue").val()) / 100) + 1)).toFixed(2));
                if (parseFloat(data.porciento) == 0){
                    $("#precio_venta_impuesto").val($("#precio_venta").val().toFixed(2));
                }
            } else {
                $("#precio_venta_impuesto").val(0);
            }
        }
    });

    $('#id_impuesto').change(function () {
        $.ajax({
            url: "<?php echo site_url("productos/impuesto_valor"); ?>",
            type: "GET",
            dataType: "json",
            data: {id_impuesto: parseFloat($("#id_impuesto").val())},
            success: function (data) {
                $("#impue").val(parseFloat(data.porciento));
                if (parseFloat($("#precio_venta_impuesto").val()) > 0) {
                    $("#precio_venta").val(parseFloat(parseFloat($("#precio_venta_impuesto").val()) / ((parseFloat($("#impue").val()) / 100) + 1)).toFixed(2));
                    if (parseFloat(data.porciento) == 0){
                        $("#precio_venta").val(parseFloat($("#precio_venta_impuesto").val()).toFixed(2));
                    }
                } else {
                    $("#precio_venta").val(0);
                }
            }
        });
    });

    //importante!
    $("#precio_venta_impuesto").keyup(function (e) {
        if (parseFloat($("#precio_venta_impuesto").val()) >= 0) {
            $("#precio_venta").val(parseFloat(parseFloat($("#precio_venta_impuesto").val()) / ((parseFloat($("#impue").val()) / 100) + 1)).toFixed(2));
        }
    });
     
    $(document).on('keyup', '.ingreCantidad', function() {
        if(jQuery.isFunction(precio_compra)){
            
        }else{
            function precio_compra(cantA,ingrediente_modificado){            
                var tabla=$("#tab_ing");
                var valor=0;
                var cantidad=0;
                var precio_compra=0;
                var arrayingredientesActuales=<?php echo json_encode(isset($data['ingredientes']) ? $data['ingredientes'] : [])?>;
                if(cantA > 0){       
                    
                    //buscar todos los ingref
                    $.each($("#tab_ing tbody tr"), function() {
                        cur_td=$(this);
                        idtabla=cur_td.data("id");
                        if(idtabla>0){
                            ingre=$("#ingrediente_"+idtabla).val(); 
                        
                            //busco el valor del ingrediente
                            for(var i=0;i<arrayingredientes.length;i++){   

                                if(ingre==arrayingredientes[i]['id']){
                                    //console.log(arrayingredientes[i]);
                                    valor=arrayingredientes[i]['precio_compra'];                            
                                }                        
                            }
                            //busco la cantidad que hay actualmente
                            for(var i=0;i<arrayingredientesActuales.length;i++){
                                if(ingre==arrayingredientesActuales[i]['id']){
                                    cantidad=arrayingredientesActuales[i]['cantidad_ingrediente'];
                                }                        
                            }
                                                
                            if(ingrediente_modificado==ingre){
                                precio_compra += parseFloat(valor*cantA);
                            }else{
                                precio_compra += parseFloat(valor*cantidad);                        
                            }

                            $("#precio_compra").val(precio_compra);
                                                
                        }               
                    });    
                    //console.log("precio_compra="+precio_compra);       
                }  
            }
        }

        var cantA=$(this).val();
        var ingrediente_modificado = $(this).attr("idI");       
        precio_compra(cantA,ingrediente_modificado);
    });
});


</script>