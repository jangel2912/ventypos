<style>
    .mt-3{margin-top:20px;}
</style>
<div class="body-content">
    <div class="col-md-12">
        <div class="col-md-1 tablero">
        </div>
        <div class="col-md-10 tablero tabcentro">
            <div class="row"> 
                <div class="col-lg-12 col-md-12 col-sm-12 titulos">
                    <h4>Toma Pedido</h4>
                </div>
            </div>

            <?php if(!empty($this->session->flashdata('message')) && $this->session->flashdata('message') != ""): ?>
                <div class="alert alert-success alert-dismissible fade in mt-3" role="alert"> <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                    <p class="text-center"><?= $this->session->flashdata('message'); ?></p>
                </div>
            <?php endif; ?>

            <!--<div class="row">
                <div class="col-md-12">
                    <button type="button" class="btn btn-success btn-lg" data-toggle="modal" data-target="#modal-table-selected">
                        Cambiar aspecto de mesas
                    </button>
                </div>
            </div>
            <div class="modal fade" tabindex="-1" role="dialog" id="modal-table-selected">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Personalización de mesas</h4>    
                    </div>
                    <div class="modal-body">
                        <p>Seleccione un estilo para su mesa: </p>
                        <div>
                            <img src="<?= site_url('uploads/') ?>" alt="">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-success">Guardar</button>
                    </div>
                    </div>
                </div>
            </div>-->

            <div class="row">   
                <!--tabs-->
                <div class="col-lg-12 col-md-12 col-sm-12 tab_panel">                   
                    <div class="row centrarbtn">
                        <?php
                        foreach ($data['zonas'] as $key => $value) {
                            $active="";
                            if($value->id==$data['zonas'][0]->id)
                                $active="activeTabMesero";
                            echo'<div class="col-xs-3 col-sm-2 col-md-2 col-lg-2"><a class="tz" onclick="selectTab('.$value->id.')" aria-controls="home" role="tab" data-toggle="tab" href="#'.$value->id.'">
                                <div role="zonas" class="btnmesas '.$active.'" data-id="'.$value->id.'" id="tabMesero'.$value->id.'" id="">'.strtoupper($value->nombre_seccion).'</div></a></div>';
                        }
                        ?>
                    </div>
                </div> 
                <!--tabs-->
                
                <!--INFO MESAS-->       
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="tab-content" style="height: 80vh; overflow-y: auto;">
                        <?php
                        //print_r($data);die();
                        foreach ($data['zonas'] as $key => $value) {
                            $active="";
                            $classmesa="content-mesa";
                            if($value->id==$data['zonas'][0]->id)
                                $active="active";                                                           

                            echo '<div role="tabpanel" class="tab-pane '.$active.'" id="'.$value->id.'">';
                            if(isset($data['mesas'][$value->id])){
                                foreach ($data['mesas'][$value->id] as $mesas => $mesa): 
                                    $comensales = ($mesa->comensales > 0)? $mesa->comensales : '';
                                    $estado = ($mesa->pedidos_en_mesa)? 'verde' : 'gris'; 
                                    $classmesa=($mesa->pedidos_en_mesa)? "content-mesa-active" : "content-mesa";
                                    
                                    $fecha_creacion  = '';
                                    if (!empty($mesa->fecha_creacion)) {
                                        $fecha_creacion = new DateTime($mesa->fecha_creacion);
                                        $fecha_creacion = $fecha_creacion->format('H:i');
                                    }
                                ?>
                                    <div class="col-md-2 col-sm-4 col-xs-6 panel_mesa">
                                        <div class="<?= $classmesa ?>">
                                            <a href="<?= site_url('orden_compra/mi_orden/').'/'.$mesa->id_seccion.'/'.$mesa->id ?>">
                                                <!--<img class="mesa" src="<?= base_url().'uploads/mesa-'.$estado.'.svg';?>" alt="">-->
                                                <!--<img class="mesa" src="<?php echo $this->session->userdata('new_imagenes')['mesas-'.$estado]['original'] ?>" alt="mesa">-->
                                                
                                                <img class="mesa" src="<?= base_url().'uploads/tables/mesa'.get_option('table_selected').'_'.$estado.'.svg'; ?>" alt="mesa">
                                                <h6 class="nombre_mesa"><?= $mesa->nombre_mesa; ?> <?= ($fecha_creacion != null)? '('.$fecha_creacion.')' : ''; ?></h6>
                                                <!--<div class="fecha_creacion_comanda text-center">
                                                    <?= $fecha_creacion; ?>
                                                </div>-->
                                            </a>
                                            <?php if($mesa->orders_in_command && $mesa->id_seccion < 0 ):  ?>
                                                <button class="btn btn-success btn-pay-order" onclick="pay_order(<?= $mesa->id_seccion; ?>,<?= $mesa->id; ?>)">Pagar</button>
                                            <?php endif; ?>
                                            <?php if($mesa->comensales > 0){ ?>
                                                <div class="comensales"><?= $comensales ?></div>
                                            <?php } ?>
                                        </div>
                                    </div>

                                <?php endforeach; 
                            }
                            echo'</div>';
                        }
                        ?>
                    </div>
                </div>               
                <!--INFO MESAS-->
            </div>
        </div>        
        <div class="col-md-1 tablero">
        </div>
    </div>
</div>
<!--mixpanel-->
<script>
        var id='<?php echo $this->session->userdata('user_id') ?>';       
        var email='<?php echo $this->session->userdata('email') ?>';
        var nombre_empresa='<?php echo $this->session->userdata('nombre_empresa') ?>';
        mixpanel.identify(id);  
</script>
<?php 
    if($data['estado']==2){?>
        
    <script>

        mixpanel.track("Tablero de Mesas Prueba", { 
            "$email": email,   
            "$empresa": nombre_empresa,    
        });    

    </script>

<?php
    }else{ ?>
        
    <script>                
        
        mixpanel.track("Tablero de Mesas", { 
            "$email": email,   
            "$empresa": nombre_empresa,    
        });    

    </script>
<?php
    }?>

<script>

    function selectTab(id) {
        $('[role="zonas"]').removeClass().addClass( "btnmesas col-xs-12 col-sm-12 col-md-2 col-lg-2 col-2" );
        $("#" + 'tabMesero' + id).addClass( "activeTabMesero" );
    }

    function pay_order(zone,table){
        
        

        get_order_product_restaurant(zone,table);
        $("#valor_recibido").select();
        verify_state_box();
        //open_modal_payment();
    }
</script>
