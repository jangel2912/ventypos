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
                                                <img class="mesa" src="<?php echo $this->session->userdata('new_imagenes')['mesas-'.$estado]['original'] ?>" alt="mesa">
                                                <h6 class="nombre_mesa"><?= $mesa->nombre_mesa; ?></h6>
                                                <div class="fecha_creacion_comanda text-center">
                                                    <?= $fecha_creacion; ?>
                                                </div>
                                            </a>
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
        
        mixpanel.track("Tablero de Mesas Prueba Estacion", { 
            "$email": email,   
            "$empresa": nombre_empresa,    
        });    

    </script>

<?php
    }else{ ?>
        
    <script>
        
        mixpanel.track("Tablero de Mesas Estacion", { 
            "$email": email,   
            "$empresa": nombre_empresa,    
        });    

    </script>
<?php
    }?>

   