<?php

$resultPermisos = getPermisos();
$permisos = $resultPermisos["permisos"];
$isAdmin = $resultPermisos["admin"];
?>

<div style="position: inherit; z-index: 10; width: 100%; height: 100vh; padding: 0px; margin: 0px !important; overflow-x: hidden !important; overflow-y: auto !important;">

    <div class="col-md-7 col-xs-12" style="height: 100% !important; padding: 0;">
        <div class="fill-height-or-more">
            <div class="row" style="padding-bottom: 1px !important;">
                <div class="col-md-12">

                    <div class="input-group" style="padding: 10px;">
                        <input id="buscarproducto" type="text" class="form-control" placeholder="Encuentre tu producto aquí..." aria-describedby="basic-addon1" style="height: 35px !important;">

                        <span class="input-group-addon" id="basic-addon1">
                        <div>
                            <i class="glyphicon glyphicon-search"></i>
                        </div>
                    </span>
                    </div>

                </div>
            </div>

            <div class="row" style="padding: 0px; margin-top: 0px !important; padding-top: 0px !important">
                <div class="col-md-12">
                    <div class="multiple-items">
                            <div>
                                <a class="category-option" data-id="0" id="0" onclick="selectCategory(0)">
                                    <img style="width: 50px; height: 50px;" src="<?= base_url().'uploads/default.png'?>" alt="Todos">
                                    <p>Todos</p>
                                </a>
                            </div>
                        <?php
                        
                        foreach($data['categorias']['registros'] as $categoria):
                            
                            $nombre = base_url().'uploads/default.png';
                            if(!empty($categoria['imagen'])){
                                if(file_exists('uploads/'.$categoria['imagen'])):
                                    $nombre=base_url().'uploads/'.$categoria['imagen'];
                                else:
                                    $nombre=base_url().'uploads/'.$this->session->userdata('base_dato').'/categorias_productos/'.$categoria['imagen'];
                                endif;
                            }
                            ?>
                            <div>
                                <a class="category-option" data-id="<?php echo $categoria['id']; ?>" id="<?php echo $categoria['id']; ?>" onclick="selectCategory(<?php echo $categoria['id']; ?>)">
                                    <img style="width: 50px; height: 50px;" src="<?= $nombre;?>" alt="<?= $categoria['imagen']; ?>">
                                    <p><?php echo $categoria['nombre']; ?></p>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <div class="row bodyMobile">
                <div class="col-md-12 col-sm-12 col-xs-12 thumb-productos cellMobile">
                </div>
            </div>

        </div>

    </div>
    <div class="col-md-5 col-xs-12" style="height: 100% !important;">
        <div class="fill-height-or-more-right">        
            <?php if($data['quick-service'] == 'si' && ($data["seccion"] <= -1)): ?>
                <div class="row">
                    <div class="col-md-12 pl-0">
                        <div class="form-group">
                            <div class="input-group" style="display:flex;">
                                <div class="control-edit-client hidden" id="edit-client">
                                    <i class="fas fa-user-edit"></i>
                                </div>
                                <div class="content-easy-client">
                                    <input type="text" class="form-control" id="search-clients" placeholder="Cliente">
                                </div>
                                <div class="input-group-addon" id="add-generic-client">
                                    <span id="icocambiar" class="icon ico-plus vender" style="color:#5cb85c;top: 2px;left: -2px;"></span>
                                </div>
                            </div>
                        </div>
                       
                    </div>
                </div>
            <?php endif; ?>

            <?php if($data['quick-service'] == 'no' || ($data["seccion"] != -1)): ?>

                <div class="row" style="padding: 10px 0px">
                    <div class="col-md-12">
                        <div class="col-2 col-md-2 col-sm-2 col-xs-2" style="text-align: left">
                                <img id="btn_cancelar" style="cursor: pointer; height: 40px" data-toggle="tooltip" data-placement="bottom" title="Cancelar Pedido" id="btn_cancelar" src='<?php echo $this->session->userdata("new_imagenes")["equis_roja"]["original"] ?>' alt='Cancelar'>
                        </div>

                        <div class="col-8 col-md-8 col-sm-8 col-xs-8" style="text-align: center">
                            <img style="max-width: 150px" src='<?php echo $this->session->userdata("new_imagenes")["logo_vendty_color"]["original"] ?>' alt='logo'>
                        </div>

                        <div class="col-2 col-md-2 col-sm-2 col-xs-2" style="text-align: right">
                            <a href="<?php echo site_url().'/tomaPedidos/index/'; ?>">
                                <img id="btn_back_mesas" style="cursor: pointer; height: 40px" data-toggle="tooltip" data-placement="bottom" title="Regresar" id="btn_back"src='<?php echo $this->session->userdata("new_imagenes")["arrow_left"]["original"] ?>' alt='Back'>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="row specificMobile-1">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-sm-2 col-xs-2 col-md-2 col-lg-2" style="text-align: center; display: flex; justify-content: center; flex-direction: column">
                                <img src='<?php echo $this->session->userdata("new_imagenes")["orden_mesa"]["original"] ?>' alt='Mesa'>
                                <div class="btnTitleSup"><?= (isset($data["seccion_mesa"]->nombre_seccion)) ? $data["seccion_mesa"]->nombre_seccion : 'Barra' ?></div>
                                <div style="font-size:10px; "><?= (isset($data["seccion_mesa"]->nombre_mesa)) ? $data["seccion_mesa"]->nombre_mesa : 'Barra' ?></div>
                            </div>

                            <div class="col-sm-2 col-xs-2 col-md-2 col-lg-2" style="text-align: center">
                                <img src='<?php echo $this->session->userdata("new_imagenes")["orden_comensales"]["original"] ?>' alt='Comensales'>
                                <div class="btnTitleSup">Comensales</div>

                                <div id="delete-comensal" class="col-sm-4 col-md-4 col-lg-4" style="background-color: #e8e8e8; cursor: pointer; text-align: center !important;">
                                    <span alt='minus'> - </span>
                                </div>

                                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4" style="text-align: center !important; z-index: 1000;">
                                    <div id="cantidadComensales" style="text-align: center"><?= (isset($data["seccion_mesa"]->nombre_seccion)) ? $data['seccion_mesa']->comensales : 0 ?></div>
                                </div>

                                <div id="add-comensal" class="col-sm-12 col-md-12 col-lg-4" style="background-color: #e8e8e8; cursor: pointer; text-align: center !important;">
                                    <span alt='plus'> + </span>
                                </div>
                            </div>

                            <div class="col-sm-2 col-xs-2 col-md-2 col-lg-2" style="text-align: center">
                                <img src='<?php echo $this->session->userdata("new_imagenes")["orden_factura"]["original"] ?>' alt='Factura'>
                                <div class="btnTitleSup">Órden</div>
                                <div style="font-size:10px; font-weight: bold; text-transform: capitalize">N° <span id="orden_consecutivo"><?= (isset($data["seccion_mesa"]->nombre_seccion)) ? $data['seccion_mesa']->consecutivo_orden_restaurante : 0 ?></span> </div>
                            </div>

                            <div class="col-sm-2 col-xs-2 col-md-2 col-lg-2" style="text-align: center;">
                                <div data-toggle="modal" data-target="#modal-cambio-mesa" data-backdrop="false" style="cursor: pointer">
                                    <img src='<?php echo $this->session->userdata("new_imagenes")["cambio_mesa"]["original"] ?>' alt='Mesa'>
                                    <div class="btnTitleSup">Cambio de Mesa</div>
                                </div>
                            </div>

                            <div class="col-sm-2 col-xs-2 col-md-2 col-lg-2" style="text-align: center">
                                <div data-toggle="modal" data-target="#modal_nota_comanda" data-backdrop="false" style="cursor: pointer">
                                    <img src='<?php echo $this->session->userdata("new_imagenes")["orden_pedido"]["original"] ?>' alt='Nota'>
                                    <div class="btnTitleSup">Nota</div>
                                </div>
                            </div>
                            
                            <div class="col-sm-2 col-xs-2 col-md-2 col-lg-2" style="text-align: center">
                                <div style="cursor: pointer">
                                    <img src='<?php echo $this->session->userdata("new_imagenes")["orden_pedido"]["original"] ?>' alt='Nota'>
                                    <div class="btnTitleSup" id="btn_propine">Propina</div>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>

                
            <?php endif; ?>
            <div class="row" style="justify-content: flex-end !important;margin-left: 0px;">
                <div class="col-md-12">

                    <table id="tbl_orden_o" class="table btnTitleSup">
                        <thead>
                        <tr>
                            <td width="50%"><strong>Producto</strong></td>
                            <td width="30%"><strong>Cantidad</strong></td>
                            <td width="20%"><strong>Modificaciones</strong></td>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>

                </div>
            </div>
           
            <div class="row specificMobile-10" style="height:100%; overflow-y: scroll; margin-left:0px; ">
                <div class="col-md-12" style="padding: 0px;">
                    <div>
                        <form action="">
                            <input type="hidden" id="txt_seccion" name="seccion" value="<?= $data['seccion']; ?>">
                            <input type="hidden" id="txt_mesa" name="mesa" value="<?= $data['mesa']; ?>">
                        </form>
                        <div style="display: none">
                            <div class="col-md-6 col-xs-5">
                                <h5><b>Orden Actual</b>
                                    <h6>
                                        <b>Zona: </b> <?= (isset($data["seccion_mesa"]->nombre_seccion)) ? $data["seccion_mesa"]->nombre_seccion : 'Barra' ?> -
                                        <b>Mesa:</b> <?= (isset($data["seccion_mesa"]->nombre_mesa)) ? $data["seccion_mesa"]->nombre_mesa : 'Barra' ?><br>
                                        <b>Comensales:</b> <span id="order_comensales"><?= (!is_null($data['seccion_mesa']->comensales)) ? $data['seccion_mesa']->comensales : '1' ?></span><br>
                                        <b>Orden:</b> N° <span id="orden_consecutivo"><?= $data["seccion_mesa"]->consecutivo_orden_restaurante ?></span> -
                                        <b>Fecha:</b> <span id="fecha_orden"></span>
                                        <?= (($this->session->userdata('vendedor_estacion_actual_nombre')) != "") ? '<br><b>Mesero:</b>' . $this->session->userdata('vendedor_estacion_actual_nombre') : '' ?>
                                    </h6>
                                </h5>
                            </div>
                            <div class="col-md-6 col-xs-7">
                                <div class="pull-right">
                                    <div class="col-md-2 col-xs-2">
                                        <a href="<?php echo site_url().'/tomaPedidos/index/'; ?>" class="btn btn-default" >
                                <span data-toggle="tooltip" data-placement="bottom" title="Ir a las Mesas" id="home">
                                    <i class="glyphicon glyphicon-home"></i>
                                </span>
                                        </a>
                                    </div>
                                    <div class="col-md-2 col-xs-2">
                                        <a href="#" class="btn btn-default" id="comensales">
                                <span data-toggle="tooltip" data-placement="bottom" title="Comensales" data-placement="bottom">
                                    <i class="glyphicon glyphicon-user"></i>
                                </span>
                                        </a>
                                        <div id="form-comensales">
                                            <h4>Comensales</h4>
                                            <div class="form-group">
                                                <input type="text" name="total-comensales" value="<?= (!is_null($data['seccion_mesa']->comensales)) ? $data['seccion_mesa']->comensales : '1' ?>" id="txt_comensales">
                                                <button class="btn btn-success" id="add-comensal"><span class="glyphicon glyphicon-plus"></span></button>
                                                <button class="btn btn-danger" id="delete-comensal"><span class="glyphicon glyphicon-minus"></span></button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-xs-2">
                            <span data-toggle="modal" data-target="#modal_nota_comanda" class="btn btn-default" data-toggle="tooltip" data-placement="bottom" title="Nota Comanda" id="btn_nota">
                                <i class="glyphicon glyphicon-edit"></i>
                            </span>
                                    </div>
                                    <div class="col-md-4 col-xs-4">
                            <span data-toggle="modal" data-target="#modal-cambio-mesa" class="btn btn-default">
                                <img data-toggle="tooltip" data-placement="bottom" title="Cambio de Mesa" style="width: 20px; height: 20px;" src="<?php echo base_url().'uploads/mesas/cambiojuntosinfondo2.png' ?>" alt="Cambiomesa">
                            </span>
                                    </div>
                                    <div class="col-md-2 col-xs-2">
                            <span data-toggle="tooltip" data-placement="bottom" title="Cancelar Pedido" id="btn_cancelar" class="btn btn-default">
                                <i class="glyphicon glyphicon-remove"></i>
                            </span>
                                    </div>
                                    <?php if($this->session->userdata('es_estacion_pedido')==1){ ?>
                                        <div class="col-md-2 col-xs-2">
                                            <a href="<?php echo site_url().'/tomaPedidos/salir_mesero'; ?>" class="btn btn-default" >
                                <span data-toggle="tooltip" data-placement="bottom" title="Salir" id="salir">
                                    <i class="glyphicon glyphicon-off"></i>
                                </span>
                                            </a>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <table id="tbl_orden" class="table hidden">
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <?php if($data['quick-service'] == 'si' && ($data["seccion"] <= -1)): ?>
                <div class="row" style="width:100%; display: flex;justify-content: center;align-items: center;position: absolute;bottom: 86px;">
                    <div class="col-md-12">
                        <?php $active_propine = getGeneralOptions("sobrecosto"); 
                        if($active_propine->valor_opcion == "si"): ?>
                            <div class="col-md-2"> <button class="btn btn-default" style="background-color:#505050 !important; color:#fff !important;" id="btn_propine">Propina</button> </div>
                        <?php endif; ?>
                        <div class="col-md-2"> <button class="btn btn-default" style="background-color:#505050 !important; color:#fff !important;" id="btn_note_invoice">Nota Factura</button> </div>
                    </div>
                </div>
            <?php endif; ?>

            <div class="row">
                <div class="col-md-12" style="cursor: pointer;display: flex;justify-content: center;align-items: center;bottom: 0;z-index: 999;background-color: #f3f3f4;padding: 10px 0px;">
                    <div class="col-xs-8 col-sm-8 col-md-8">
                        <?php if($data['quick-service'] == 'no' || ($data["seccion"] != -1)): 
                              //if( ($data['quick-service'] == 'si' && $data['quick-service-command'] == 'si' && $data["seccion"] == -1 && $data["mesa"] == -1) || ($data["seccion"] != -1 && $data["mesa"] != -1) ):
                            ?>
                            <div class="col-xs-4 col-sm-4 col-md-4 item-toma-pedido" id="btn_confirmarNew" style="display: flex; justify-content: center; align-items: center;">
                                <div class="" style="background-color: #505050; border-radi  us: 5px !important; height: 40px !important; width: 100% !important;display: flex; justify-content: center; align-items: center;">
                                    <a  style="font-weight:bold; color: #fff !important; position: inherit; border-radius: 5px !important">COMANDA</a>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if(($this->session->userdata('es_estacion_pedido')!=1)&&(in_array("1038", $permisos) || $isAdmin == 't')) {?>
                        <div class="col-xs-4 col-sm-4 col-md-4 item-toma-pedido" style="display: flex; justify-content: center;">
                            <div class="" style="background-color: #5cb85c; border-radius: 5px !important; height: 40px !important; width: 100% !important;display: flex; justify-content: center; align-items: center;">
                                <a style="font-weight:bold; color:#fff !important;" id="pagar_cuenta">PAGAR</a>
                            </div>
                        </div>
                        <?php } ?>

                        <div class="col-xs-4 col-sm-4 col-md-4 item-toma-pedido" style="display: flex; justify-content: center;">
                            <div class="" style="background-color: #5cb85c; border-radius: 5px !important; height: 40px !important; width: 100% !important;display: flex; justify-content: center; align-items: center;">
                                <a style="font-weight:bold; color:#fff !important;" id="domicilios">DOMICILIOS</a>
                            </div>
                        </div>
                    </diV>
                    <div class="col-xs-4 col-sm-4 col-md-4" style="display: flex; justify-content: center; align-items: center;">                        
                        <div style="background-color: #f3f3f4; border-radius: 5px !important; height: 40px !important; width: 90% !important;">     
                            <div id="orden_result" class="text-center"> </div>                        
                        </div>                        
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>


<!--Modal cambio mesa-->
<div class="modal fade" id="modal-cambio-mesa" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title text-center">Cambio de mesa</h4>
            </div>
            <div class="modal-body">
                <p>Por favor selecciona una de las mesas disponibles y haga clic en guardar.</p>

                <?php foreach($data["data_mesas_disponibles"]["zonas"] as $zona): ?>
                    <div class="row zonas_mesas">
                        <div class="col-md-12 col-xs-12"><h4><?php echo $zona["nombre"]; ?></h4></div>
                        <?php foreach($data["data_mesas_disponibles"]["mesas"] as $mesa): ?>
                            <?php if($mesa->id_zona == $zona["id"] ): ?>
                                <div class="col-md-2 col-xs-6  text-center">
                                    <div class="mesa_home" data-idzona="<?php echo $zona['id'];?>" data-idmesa="<?php echo $mesa->id;?>">
                                        <img src="<?php echo base_url();?>uploads/mesas/dining-table_64.png" alt="Mesa disponible">  <br>
                                        <?php echo $mesa->nombre;?>
                                    </div>
                                </div>

                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success btn_cambiar_mesa">Cambiar mesa</button>
            </div>
        </div> <!-- modal-content -->
    </div><!-- modal-dialog -->
</div> <!-- modal  -->
<!--Modal nota Comanda-->
<div class="modal fade" id="modal_nota_comanda" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="z-index: 9999">
            <div class="modal-header">
                <h4 class="modal-title text-center">Nota Comanda</h4>
            </div>
            <div class="modal-body">
                <form>
                    <div class="form-group">
                        <label for="message-text" class="control-label">Nota:</label>
                        <textarea class="form-control" id="message-nota" placeholder="Escriba la nota comanda" ></textarea>
                        <div id="error_nota"></div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn_cancelar_nota" data-dismiss="modal">Eliminar y Cerrar</button>
                <button type="button" class="btn btn-success btn_nota_comanda">Guardar</button>
            </div>
        </div> <!-- modal-content -->
    </div><!-- modal-dialog -->
</div> <!-- modal  -->



<div class="modal fade" id="comensales" tabindex="-1" role="dialog" aria-labelledby="comensalesLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="comensalesLabel">Modal title</h4>
            </div>
            <div class="modal-body">
                ...
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>

<div id="composite-products">
    <div class="composite-products-modal col-xs-12 col-sm-10 col-sm-offset-1 col-md-6 col-md-offset-3">
        <div class="composite-products-close">
            x
        </div>
        <div class="composite-products-title">
            <span class="title">Adiciones</span>

            <div style="display: none">
                <input type="text" placeholder="Ingrese la nueva modificación y enter">
            </div>
        </div>
        <div class="composite-products-options">
            <ul>
                <li class="active" data-content="#composite-content-additions"><span>Adiciones</span></li>
                <li data-content="#composite-content-modify"><span>Modificaciones</span></li>
                <li><span>Tamaño (Proximamente)</span></li>
            </ul>
        </div>
        <div id="composite-content-additions" class="composite-contents">
            <div class="composite-products-content additions">
            </div>
            <div class="composite-products-content actives">
            </div>
        </div>
        <div id="composite-content-modify" class="composite-contents" style="display: none">
            <div class="composite-modify-content modify">
            </div>
            <div class="composite-modify-content actives">
            </div>
        </div>
        <div class="composite-products-close down hidden-sm hidden-md hidden-lg">
            Cerrar
        </div>
        <div class="composite-products-ingredients hidden-xs hidden-sm">
            Ingredientes:
        </div>
    </div>
</div>

<style>

    #composite-products {
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        position: absolute;
        left: 0;
        top: 0;
        z-index: 9999;
        display: none;
    }

    #composite-products .composite-products-close {
        font-size: 2rem;
        line-height: 45px;
        text-align: center;
        width: 50px;
        height: 50px;
        background-color: #fff;
        border-radius: 5px;
        position: absolute;
        left: -55px;
        top: 0;
        cursor: pointer;
    }

    #composite-products .composite-products-close.down {
        width: 100%;
        margin: auto;
        position: absolute;
        bottom: -47px;
        top: initial;
        left: 0;
    }

    #composite-products .composite-products-title {
        color: #fff;
        font-size: 16px;
        text-align: center;
        line-height: 3;
        height: 50px;
        background-color: #384951;
    }

    #composite-products .composite-products-title div {
        position: absolute;
        top: 0;
        right: 10px;
        width: 30%;
    }

    #composite-products .composite-products-title div input {
        color: #fff !important;
        background-color: #5e737d;
        border: none;
        outline: none;
    }

    #composite-products .composite-products-ingredients {
        color: #fff;
        font-size: 13px;
        line-height: 2;
        width: 100%;
        height: auto;
        padding: 2px 10px;
        background-color: #989d9e;
        position: absolute;
        bottom: 0;
    }

    #composite-products .composite-products-ingredients span {
        border: solid 1px #fff;
        border-radius: 5px;
        padding: 2px 5px;
        margin-right: 5px;
    }

    #composite-products .composite-products-modal {
        height: 380px;
        background-color: #f0f4f5;
        box-shadow: 0px 5px 9px rgba(0, 0, 0, 0.2);
        /*overflow: hidden;*/
        padding: 0;
        position: absolute;
        top: 20%;
    }

    #composite-products .composite-products-modal .composite-products-options {
        width: 20%;
        float: left;
    }

    #composite-products .composite-products-modal .composite-products-options ul {
        list-style: none;
        border-right: solid 1px #cccccc;
        margin: 0;
        padding: 0;
    }

    #composite-products .composite-products-modal .composite-products-options ul li {
        text-align: center;
        border-bottom: solid 1px #cccccc;
        height: 100px;
        background-color: #c5e7f3;
        display: flex;
        align-items: center;
        justify-items: center;
        cursor: pointer;
    }

    #composite-products .composite-products-modal .composite-products-options ul li span {
        width: 100%;
    }

    #composite-products .composite-products-modal .composite-products-options ul li.active,
    #composite-products .composite-products-modal .composite-products-options ul li:hover,
    #composite-products .composite-products-modal .composite-products-options ul li:active,
    #composite-products .composite-products-modal .composite-products-options ul li:focus{
        color: #fff;
        background-color: #5cb85c;
    }

    #composite-products .composite-products-modal .composite-products-content,
    #composite-products .composite-products-modal .composite-modify-content{
        width: 55%;
        float: left;
        padding: 5px;
        overflow-y: scroll;
        height: 270px;

    }

    #composite-products .composite-products-modal .composite-products-content .option,
    #composite-products .composite-products-modal .composite-modify-content .option {
        color: #000;
        text-align: center;
        padding: 10px 20px;
        margin-bottom: 10px;
        border: solid 1px #cccccc;
        border-radius: 5px;
        margin: 5px 5px;
        display: inline-block;
        /* background-color: #384951; */
        cursor: pointer;
    }

    #composite-products .composite-products-modal .composite-products-content .option.active,
    #composite-products .composite-products-modal .composite-products-content .option:hover,
    #composite-products .composite-products-modal .composite-products-content .option:active,
    #composite-products .composite-products-modal .composite-products-content .option.focus,

    #composite-products .composite-products-modal .composite-modify-content .option.active,
    #composite-products .composite-products-modal .composite-modify-content .option:hover,
    #composite-products .composite-products-modal .composite-modify-content .option:active,
    #composite-products .composite-products-modal .composite-modify-content .option.focus {
        color: #fff;
        background-color: #384951;
    }

    #composite-products .composite-products-modal .composite-products-content.actives,
    #composite-products .composite-products-modal .composite-modify-content.actives {
        width: 25%;
        border-left: solid 1px #cccccc;
        float: right;
        height: 270px;
        overflow-y: scroll;
    }

    @media (max-width: 992px) {
        #composite-products .composite-products-modal {
            height: auto;
            position: relative;
            top: initial;
        }
    }

    #form-comensales {
        text-align: center;
        background-color: #fff;
        border: solid 1px #a2b0b7;
        border-radius: 5px;
        width: 200px;
        padding: 2px;
        position: absolute;
        right: -79px;
        top: 35px;
        z-index: 9999;
        display: none;
    }

    #form-comensales h4 {
        border-bottom: solid 1px #a2b0b7;
    }

    #form-comensales input {
        width: 90%;
        height: 30px;
        margin-bottom: 5px;
    }

    .multiple-items{
        opacity: 0;
        visibility: hidden;
        transition: opacity 1s ease;
        -webkit-transition: opacity 1s ease;
    }
    .multiple-items.slick-initialized{
        visibility: visible;
        opacity: 1;    
    }
    .slick-initialized{
        display:block !important;
    }
    .fa-stack.fa-danger .fa:hover{
        color: #e62626 !important;        
    }
    #page-content {
        background-color: #fff !important;
    }   
</style>

<script>
    var toggle = false;
    var totalComensales = 0;
    $('#comensales').click(function(event) {
        event.preventDefault();

        if (!toggle) {
            $('#form-comensales').show();
            toggle = true;
        } else if (toggle) {
            $('#form-comensales').hide();
            toggle = false;
        }
    });

    $('#add-comensal').click(function(event) {

        totalComensales++;
        $('#txt_comensales').val(totalComensales);
        $('#order_comensales').text(totalComensales);

        $.post('<?= site_url('orden_compra/actualizar_comensales') ?>', {
            comensales: totalComensales,
            id_seccion: '<?= $data['seccion']; ?>',
            id_mesa: '<?= $data['mesa']; ?>'
        }, function(data, textStatus, xhr) {
            $('#cantidadComensales').text(totalComensales);
        });
    });

    $('#pagar_cuenta').click(function(e) {   
        //verificar si tengo algun comidas comanda 
        
        zona= '<?= $data['seccion']; ?>';
        mesa= '<?= $data['mesa']; ?>';
        
        //get_order_product_restaurant(zona,mesa);

        if(zona == -1 && quick_service == 'si'){            
            //verificar si hay por lo menos un producto
            url="<?php echo site_url('orden_compra/getOrden')?>";
                $.ajax({
                url:url,
                type:'POST',
                dataType:'json',
                data:{zona:zona,mesa:mesa},
                success: function(data){
                    //console.log(data);
                    //console.log(data.fecha_orden);
                    tengo=0;
                    $.each(data.orden,function(key,value){
                        if((value.estado == 1)){
                            tengo=1;
                        }                    
                    });

                    if(tengo==0){                         
                        swal({
                            position: 'center',
                            type: 'error',
                            title: 'No Posee ningún producto seleccionado',
                            showConfirmButton: false,
                            timer: 1500
                        })                       
                    }else{   
                        load_propine();
                        updateTotal();                     
                        get_order_product_restaurant(zona,mesa);
                        open_modal_payment();
                    }
                }
            });
            
            
        }else{
            url="<?php echo site_url('orden_compra/getOrden')?>";
                $.ajax({
                url:url,
                type:'POST',
                dataType:'json',
                data:{zona:zona,mesa:mesa},
                success: function(data){
                    //console.log(data);
                    //console.log(data.fecha_orden);
                    tengo=0;
                    $.each(data.orden,function(key,value){
                        if((value.estado == 2)||(value.estado == 3)){
                            tengo=1;
                        }                    
                    });

                    if(tengo==0){                       
                        swal({
                            position: 'center',
                            type: 'error',
                            title: 'No Posee pedidos en comanda',
                            showConfirmButton: false,
                            timer: 1500
                        })                                            
                    }else{
                        $("#valor_recibido").select();
                        location.href = "<?= site_url('ventas/nuevo/');?>/"+zona+"/"+mesa;
                    }
                }
            });
        }        
        
    });

    $('#delete-comensal').click(function(event) {
        totalComensales--;

        if (totalComensales < 1) {
            totalComensales = 1
        }

        $('#txt_comensales').val(totalComensales);
        $('#order_comensales').text(totalComensales);

        $.post('<?= site_url('orden_compra/actualizar_comensales') ?>', {
            comensales: totalComensales,
            id_seccion: '<?= $data['seccion']; ?>',
            id_mesa: '<?= $data['mesa']; ?>'
        }, function(data, textStatus, xhr) {
            $('#cantidadComensales').text(totalComensales);
        });

    });

    var zona_seleccionada = 0;
    var mesa_seleccionada = 0;
    $(".zonas_mesas .mesa_home").each(function(index,element){
        $(this).click(function(){
            atualizar_mesa_seleccionada();
            $(this).css({"background-color":"red"});
            $(this).css({"color":"#fff"});
            zona_seleccionada = $(this)[0].dataset.idzona;
            mesa_seleccionada = $(this)[0].dataset.idmesa;
        })
    })
    function atualizar_mesa_seleccionada(){
        $(".zonas_mesas .mesa_home").each(function(){
            $(this).css({"background-color":"transparent"});
            $(this).css({"color":"#5cb85c"});
        });
    }
    $(".btn_cambiar_mesa").click(function(){
        if(zona_seleccionada == 0 && mesa_seleccionada == 0){          
            swal({
                position: 'center',
                type: 'error',
                title: 'Debes seleccionar una mesa',
                showConfirmButton: false,
                timer: 1500
            })
            
        }else{
            $.post("<?php echo site_url().'/orden_compra/changeOrden';?>",{
                zona_seleccionada : zona_seleccionada,
                mesa_seleccionada : mesa_seleccionada,
                zona_anterior : '<?php echo $this->uri->segment(3);?>',
                mesa_anterior : '<?php echo $this->uri->segment(4);?>'
            },function(data){
                if(data==1){                    
                    swal({
                        position: 'center',
                        type: 'success',
                        title: 'Se ha cambiado la mesa satisfactoriamente',
                        showConfirmButton: false,
                        timer: 1500
                    })
                    setTimeout(function(){
                        location.href= "<?php echo site_url().'/tomaPedidos'?>";
                    }, 1600);
                }else{
                    swal({
                        position: 'center',
                        type: 'error',
                        title: 'Error al momento de cambiar de mesa',
                        showConfirmButton: false,
                        timer: 1500
                    })
                }
            });
        }

    });

    /*nota comanda */
    $(".btn_cancelar_nota").click(function(e){
        $('#error_nota').empty();
        $('#message-nota').val("");
    });

    $(".btn_nota_comanda").click(function(e){

        e.preventDefault();
        nota=$.trim($("#message-nota").val());
        $('#error_nota').empty();
        if ((nota.length > 0)){
            sms = "<div class='text-success'>La nota se ha guardado</div>";
            $('#error_nota').addClass('form-group has-success');
            $('#error_nota').empty();
            $('#error_nota').append(sms);
            setTimeout(function(){
                $('#modal_nota_comanda').modal('hide');
                $('#error_nota').empty();
            }, 2000);
        }else{

            $("#message-nota").val('');
            $("#message-nota").focus();
            sms = "<div class='text-danger'>El campo no puede estar vacío</div>";
            $('#error_nota').addClass('form-group has-error');
            $('#error_nota').empty();
            $('#error_nota').append(sms);
        }

    });

    //pagarTomapedido
    $(".pagarTomapedido").click(function(e){

        e.preventDefault();
        zona= '<?php echo $this->uri->segment(3);?>';
        mesa= '<?php echo $this->uri->segment(4);?>';
        url=$(this).attr('href');

        $.post("<?php echo site_url().'/ventas/eliminar_dividir_cuenta';?>",{
            zona : zona,
            mesa : mesa
        },function(data){
            if(data){
                setTimeout(function(){
                    location.href= url;
                }, 500);
            }
        });
    })

    $('.multiple-items').slick({
        dots: false,
        infinite: false,
        speed: 300,
        prevArrow: '<div class="slick-prev"><img style="width: 50px; height: 50px;" src="<?php echo base_url();?>uploads/mesas/flechaizquierdaverdegruesa.png" alt="prev"></div>',
        nextArrow: '<div class="slick-next"><img style="width: 50px; height: 50px;" src="<?php echo base_url();?>uploads/mesas/flechaderechaverdegruesa.png" alt="ext"></div>',
        slidesToShow: 4,
        slidesToScroll: 3,
        responsive: [
            {
                breakpoint: 1024,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 3,
                    infinite: true,
                    dots: false
                }
            },
            {
                breakpoint: 600,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 2
                }
            },
            {
                breakpoint: 480,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1
                }
            }
            // You can unslick at a given breakpoint now by adding:
            // settings: "unslick"
            // instead of a settings object
        ]
    });


    function selectCategory(idCategory) {
        $('.category-option').removeClass().addClass( 'category-option' );
        $('#' + idCategory).addClass( 'activeCategory' );
    }

</script>


<!--mixpanel-->
<script>
        var id='<?php echo $this->session->userdata('user_id') ?>';       
        var email='<?php echo $this->session->userdata('email') ?>';
        var nombre_empresa='<?php echo $this->session->userdata('nombre_empresa') ?>';
</script>


<?php if($data['quick-service'] == 'si' && ($data["seccion"] <= -1)): ?>
    <script>
        mixpanel.track("Quick Service", { 
            "$email": email,   
            "$empresa": nombre_empresa,    
        });
    </script>
<?php endif; ?>

<?php 
    if($data['estado']==2){?>
    <script>
               
        mixpanel.identify(id);          
            
        mixpanel.track("Tomar Pedidos Prueba", { 
            "$email": email,   
            "$empresa": nombre_empresa,    
        });  

    </script>
    <script>
        $("#btn_confirmarNew").click(function(e){          
            mixpanel.track("Comanda Prueba", { 
                "$email": email,   
                "$empresa": nombre_empresa,    
            }); 
        });
    </script>

<?php
    }else{ ?>

     <script>        
        mixpanel.identify(id);          
            
         mixpanel.track("Tomar Pedidos", { 
            "$email": email,   
            "$empresa": nombre_empresa,    
        });   

    </script>        
    <script>
        $("#btn_confirmarNew").click(function(e){ 
            mixpanel.track("Comanda", { 
                "$email": email,   
                "$empresa": nombre_empresa,    
            }); 
        });

    </script>
    
<?php
    }?>
