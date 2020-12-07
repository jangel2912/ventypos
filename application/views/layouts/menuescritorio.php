<?php $options = getOptions();?>
<div class="site-menubar" style=" background-color: #505050;">
    <div class="site-menubar-body">
        <ul class="site-menu" id="dataStep1">
            <br>
            <?php if (( in_array("11", $permisos ) || $isAdmin == 't' )&& ($this->session->userdata('es_estacion_pedido')!=1)) { ?>
            <li class="site-menu-item menu-items" style=" margin-top: 0px;">
                <a href="<?php echo site_url("ventas/nuevo"); ?>">
                    <center><img alt="vender" class="iconimg" src="<?php echo base_url('/uploads/iconos/Blanco/'); ?>/icono_blanco-05.svg"></center>                               
                    <center class="menu-title">Vender</center>
                </a>
            </li>
            <?php } ?>

            <?php if(($this->session->userdata('es_estacion_pedido')!=1)&&( in_array("10", $permisos ) || in_array("1037", $permisos ) || in_array("11", $permisos ) || in_array("66", $permisos ) || in_array("69", $permisos ) || in_array("27", $permisos ) || in_array("1024", $permisos ) || (in_array("1022", $permisos ) && $comanda["comanda"] == "si") || $isAdmin == 't')){ ?>
            <li class="site-menu-item has-sub  menu-items">
                <a href="javascript:void(0)">
                    <center><img alt="ventas" class="iconimg" src="<?php echo base_url('/uploads/iconos/Blanco/'); ?>/icono_blanco-06.svg"></center>
                    <center class="menu-title">Ventas</center>
                </a>
                <ul class="site-menu-sub">
                        <?php if((isset($data['tipo_negocio'])&&($data['tipo_negocio']=='restaurante')) && (in_array("1037", $permisos ) || $isAdmin == 't')){ ?>
                    <li class="site-menu-item is-shown">
                        <a class="animsition-link" href="<?php echo site_url("tomaPedidos"); ?>">
                            <span class="menu-title">Toma Pedido</span>
                        </a>
                    </li>
                    <?php } ?>
                    <?php if( isset($data['tipo_negocio']) && $data['tipo_negocio']=='restaurante' && isset($options['quick_service']) && $options['quick_service']=='si' ){ ?>
                    <li class="site-menu-item is-shown">
                        <a class="animsition-link" href="<?php echo site_url("quickservice/index"); ?>">
                            <span class="menu-title">Quick service</span>
                        </a>
                    </li>
                    <?php } ?>
                    <?php if( isset($options['puntos_leal']) && $options['puntos_leal']=='si' ){ ?>                                                              
                    <li class="site-menu-item is-shown">
                        <a class="animsition-link" href="<?php echo site_url("puntos_leal/index"); ?>">
                            <span class="menu-title">Redimir Puntos Leal</span>
                        </a>
                    </li>
                    <?php } ?>
                    <?php if (in_array("10", $permisos) || $isAdmin == 't') { ?>
                    <li class="site-menu-item is-shown">
                        <a class="animsition-link" href="<?php echo site_url("ventas"); ?>">
                            <span class="menu-title">Histórico de Ventas</span>
                        </a>
                    </li>
                    <?php } ?>
                    <?php if( in_array("1035", $permisos ) || $isAdmin == 't'){ ?>
                        <li class="site-menu-item">
                            <a class="animsition-link" href="<?php echo site_url("caja/quickCerrarCaja"); ?>">
                                <span class="menu-title">Cerrar Caja (Cajero)</span>
                            </a>
                        </li>
                    <?php } ?>
                    <?php if( in_array("1009", $permisos ) || $isAdmin == 't'){ ?>
                    <li class="site-menu-item is-shown">
                        <a class="animsition-link" href="<?php echo site_url("caja/listado_cierres")?>">
                            <span class="menu-title">Cierres de Caja</span>
                        </a>
                    </li>
                    <?php } ?>
                    <li class="site-menu-item is-shown">
                        <a class="animsition-link" href="<?php echo site_url("ventas_separe/facturas")?>">
                            <span class="menu-title">Plan Separe</span>
                        </a>
                    </li>
                    <?php if($isAdmin == 't' && isset($data['tipo_negocio'])){ ?>
                    <li class="site-menu-item">
                        <a class="animsition-link" href="<?php echo site_url("ventas_online/ventas"); ?>">
                            <span class="menu-title">Ventas Online</span>
                        </a>
                    </li>
                    <?php } ?>
                    <?php if((isset($data['tipo_negocio']))&&($data['tipo_negocio']=='restaurante')){ ?>
                    <li class="site-menu-item">
                        <a class="animsition-link" href="<?php echo site_url("ventas_online/ventas_orden"); ?>">
                            <span class="menu-title">Ordenes</span>
                        </a>
                    </li>
                    <?php } ?>
                    <?php if( in_array("27", $permisos ) || $isAdmin == 't'){ ?>
                    <li class="site-menu-item">
                        <a class="animsition-link" href="<?php echo site_url("presupuestos"); ?>">
                            <span class="menu-title">Cotizaciones</span>
                        </a>
                    </li>
                    <?php } ?>
                    <?php if( in_array("69", $permisos ) || $isAdmin == 't'){ ?>
                    <li class="site-menu-item" title="Créditos">
                        <a class="animsition-link" href="<?php echo site_url("credito"); ?>">
                            <span class="menu-title">Créditos</span>
                        </a>
                    </li>
                    <?php } ?>
                    <?php if( in_array("1022", $permisos ) || $isAdmin == 't'){ ?>
                        <?php if( $comanda["comanda"] == "si" && $comanda["push"] == "1" ){ ?>
                        <!-- Jeisson Rodriguez (15-07-2019) -->
                        <!-- Se comenta el modulo de comandas por petición de Edward, ya que no se utiliza -->
                        <!-- <li class="site-menu-item">
                            <a class="animsition-link" href="<?php // echo site_url("comanda"); ?>">
                                <span class="menu-title">Comandas</span>
                            </a>
                        </li> -->
                        <?php } ?>
                    <?php } ?>
                </ul>
            </li>
            <?php } ?>

            <?php if(($this->session->userdata('es_estacion_pedido')!=1)&&( in_array("2", $permisos ) || in_array("14", $permisos ) || in_array("67", $permisos ) || in_array("68", $permisos ) || in_array("1036", $permisos ) || $isAdmin == 't')){ ?>
            <li class="site-menu-item has-sub  menu-items">
                <a href="javascript:void(0)">
                    <center><img alt="inventario" class="iconimg" src="<?php echo base_url('/uploads/iconos/Blanco/'); ?>/icono_blanco-07.svg"></center>
                    <center class="menu-title">Inventario</center>
                </a>
                <ul class="site-menu-sub">
                    <?php if( in_array("2", $permisos ) || $isAdmin == 't'){ ?>
                    <li class="site-menu-item is-shown">
                        <a class="animsition-link" href="<?php echo site_url("productos"); ?>">
                            <span class="menu-title">Productos</span>
                        </a>
                    </li>
                    <?php } ?>
                    <?php if((isset($data['tipo_negocio'])&&($data['tipo_negocio']=='restaurante')) && (in_array("1036", $permisos ) || $isAdmin == 't')){ ?>
                    <li class="site-menu-item is-shown">
                        <a class="animsition-link" href="<?php echo site_url("ProductoRestaurant"); ?>">
                            <span class="menu-title">Productos Restaurante</span>
                        </a>
                    </li>  
                    <?php } ?>
                    <?php if( in_array("14", $permisos ) || $isAdmin == 't'){ ?>
                    <li class="site-menu-item">
                        <a class="animsition-link" href="<?php echo site_url("categorias"); ?>">
                            <span class="menu-title">Categorías</span>
                        </a>
                    </li>
                    <?php } ?>
                    <?php if( in_array("67", $permisos ) || $isAdmin == 't'){ ?>
                    <li class="site-menu-item">
                        <a class="animsition-link" href="<?php echo site_url("inventario"); ?>">
                            <span class="menu-title">Movimientos</span>
                        </a>
                    </li>
                    <?php } ?>
                    <li class="site-menu-item">
                        <a class="animsition-link" href="<?php echo site_url("lista_precios/index"); ?>">
                            <span class="menu-title">Libro de Precios</span>
                        </a>
                    </li>
                    <li class="site-menu-item">
                        <a class="animsition-link" href="<?php echo site_url("produccion"); ?>">
                            <span class="menu-title">Producción</span>
                        </a>
                    </li>
                    <li class="site-menu-item">
                        <a class="animsition-link" href="<?php echo site_url("auditoria"); ?>">
                            <span class="menu-title">Auditoría Inventario</span>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="site-menu-item has-sub  menu-items">
                <a href="javascript:void(0)">
                    <center><img alt="fidelización" class="iconimg" src="<?php echo base_url('/uploads/iconos/Blanco/'); ?>/icono_blanco-08.svg"></center>
                    <center class="menu-title">Fidelización</center>
                </a>
                <ul class="site-menu-sub">
                    <?php if((isset($data['tipo_negocio']))&&($data['tipo_negocio']=='moda' || $data['tipo_negocio']=='retail')){ ?>
                    <li class="site-menu-item is-shown">
                        <a class="animsition-link" href="<?php echo site_url("productos/listaGiftCards"); ?>">
                            <span class="menu-title">Gift Cards</span>
                        </a>
                    </li>
                    <?php } ?>
                    <?php if($isAdmin == 't'){ ?>
                    <li class="site-menu-item">
                        <a class="animsition-link" href="<?php echo site_url("puntos/index"); ?>">
                            <span class="menu-title">Puntos</span>
                        </a>
                    </li>
                    <li class="site-menu-item">
                        <a class="animsition-link" href="<?php echo site_url("promociones/index"); ?>">
                            <span class="menu-title">Promociones</span>
                        </a>
                    </li>
                    <?php } ?>
                </ul>
            </li>
            <?php } ?>

            <?php if(($this->session->userdata('es_estacion_pedido')!=1)&&(in_array("58", $permisos ) || in_array("70", $permisos ) || in_array("71", $permisos ) || $isAdmin == 't')){ ?>
            <li class="site-menu-item has-sub  menu-items">
                <a href="javascript:void(0)">
                    <center><img alt="compras" class="iconimg" src="<?php echo base_url('/uploads/iconos/Blanco/'); ?>/icono_blanco-09.svg"></center>
                    <center class="menu-title">Compras</center>
                </a>
                <ul class="site-menu-sub">
                    <?php if( in_array("58", $permisos ) || $isAdmin == 't'){ ?>
                    <li class="site-menu-item is-shown">
                        <a class="animsition-link" href="<?php echo site_url("proformas"); ?>">
                            <span class="menu-title">Gastos</span>
                        </a>
                    </li>
                    <?php } ?>
                    <?php if( in_array("70", $permisos ) || $isAdmin == 't'){ ?>
                    <li class="site-menu-item">
                        <a class="animsition-link" href="<?php echo site_url("orden_compra"); ?>">
                            <span class="menu-title">Órdenes de Compras</span>
                        </a>
                    </li>
                    <?php } ?>

                    <?php if( in_array("1039", $permisos ) || $isAdmin == 't'){ ?>
                    <li class="site-menu-item is-shown">
                        <a class="animsition-link" href="<?php echo site_url("bancos"); ?>">
                            <span class="menu-title">Bancos</span>
                        </a>
                    </li>
                    <?php } ?>

                    <?php if( in_array("1039", $permisos ) || $isAdmin == 't'){ ?>
                    <li class="site-menu-item is-shown">
                        <a class="animsition-link" href="<?php echo site_url("bancos/movimientos"); ?>">
                            <span class="menu-title">Movimientos bancarios</span>
                        </a>
                    </li>
                    <?php } ?>

                    <?php if( in_array("1039", $permisos ) || $isAdmin == 't'){ ?>
                    <li class="site-menu-item is-shown">
                        <a class="animsition-link" href="<?php echo site_url("bancos/conciliaciones"); ?>">
                            <span class="menu-title">Conciliaciones</span>
                        </a>
                    </li>
                    <?php } ?>
                </ul>
            </li>
            <?php } ?>

            <?php if(($this->session->userdata('es_estacion_pedido')!=1)&&(in_array("32", $permisos ) || in_array("45", $permisos ) || in_array("62", $permisos ) || $isAdmin == 't')){ ?>
            <li class="site-menu-item has-sub  menu-items">
                <a href="javascript:void(0)">
                    <center><img alt="Contactos" class="iconimg" src="<?php echo base_url('/uploads/iconos/Blanco/'); ?>/icono_blanco-10.svg"></center>
                    <center class="menu-title">Contactos</center>
                </a>
                <ul class="site-menu-sub">
                    <?php if( in_array("32", $permisos ) || $isAdmin == 't'){ ?>
                    <li class="site-menu-item is-shown">
                        <a class="animsition-link" href="<?php echo site_url("clientes"); ?>">
                            <span class="menu-title">Clientes</span>
                        </a>
                    </li>
                    <?php } ?>
                    <?php if((isset($data['tipo_negocio'])&&($data['tipo_negocio']!='restaurante'))&& (in_array("45", $permisos ) || $isAdmin == 't')){ ?>
                    <li class="site-menu-item">
                        <a class="animsition-link" href="<?php echo site_url("vendedores"); ?>">
                            <span class="menu-title">Vendedores</span>
                        </a>
                    </li>
                    <?php } ?>
                    <?php if((isset($data['tipo_negocio'])&&($data['tipo_negocio']=='restaurante')) && (in_array("1039", $permisos ) || $isAdmin == 't')){ ?>
                    <li class="site-menu-item">
                        <a class="animsition-link" href="<?php echo site_url("vendedores/index/"); ?>">
                            <span class="menu-title">Vendedores/Meseros</span>
                        </a>
                    </li>
                    <?php } ?>
                    <?php if( in_array("62", $permisos ) || $isAdmin == 't'){ ?>
                    <li class="site-menu-item" title="Informe de orden de compra">
                        <a class="animsition-link" href="<?php echo site_url("proveedores"); ?>">
                            <span class="menu-title">Proveedores</span>
                        </a>
                    </li>
                    <?php } ?>
                    <li class="site-menu-item">
                        <a class="animsition-link" href="<?php echo site_url("domiciliarios"); ?>">
                            <span class="menu-title">Domiciliarios</span>
                        </a>
                    </li>
                </ul>
            </li>
            <?php } ?>

            <?php if(($this->session->userdata('es_estacion_pedido')!=1) && (in_array("1", $permisos ) || $isAdmin == 't')){ ?>
            <li class="site-menu-item  menu-items">
                <a href="<?php echo site_url("informes"); ?>">
                    <center><img alt="informes" class="iconimg" src="<?php echo base_url('/uploads/iconos/Blanco/'); ?>/icono_blanco-32.svg"></center>
                    <center class="menu-title">Informes</center>
                </a>
            </li>
            <?php } ?>

            <?php if ($this->session->userdata('es_estacion_pedido') != 1) { ?>
                <?php if (in_array("1039", $permisos) || $isAdmin == 't') { ?>
                    <li class="site-menu-item  menu-items">
                        <a id="admin_shop" href="javascript:void(0);">
                            <center>
                                <img alt="tienda" class="iconimg" src="<?php echo base_url('/uploads/iconos/svg/003-mobile-purchase.svg'); ?>">
                            </center>
                            <center class="menu-title">Tienda</center>
                        </a>
                    </li>
                    <script>
                        document.getElementById('admin_shop').addEventListener('click', function(e) {
                            e.preventDefault();
                            var xhttp = new XMLHttpRequest();

                            xhttp.onreadystatechange = function() {
                                if (this.readyState == 4 && this.status == 200) {
                                    localStorage.setItem("data", this.responseText);

                                    var popUp = window.open('http://admintienda.vendty.com/admin/crosslogin', '_blank');
                                    if (popUp == null || typeof(popUp) == 'undefined') {
                                        document.getElementById('validate-popup').style.display = 'block';
                                    }
                                }
                            };

                            xhttp.open("GET", '<?php echo site_url('tienda/crossDomain');?>', true);
                            xhttp.send();
                        });
                    </script>
                <?php } ?>
            <?php } ?>

            <?php
            if($this->session->userdata('es_estacion_pedido')==1){
                $url=explode("index.php",$_SERVER["REQUEST_URI"]);
                $url=trim($url[1],"/");
                $url=explode("/",$url);
                
                if($url[1]=='estacion_pedidos'){
                    $href="auth/logout";
                }else{
                        $href="tomaPedidos/salir_mesero";
                }
            ?>
            <li class="site-menu-item  menu-items">
                <a href="<?php echo site_url($href); ?>">
                    <center><img alt="Salir" class="iconimg" src="<?php echo base_url('/uploads/iconos/Blanco/'); ?>/icono_blanco-12.svg"></center>
                    <center class="menu-title">Cerrar Sesión</center>
                </a>
            </li>
            <?php } ?>
        </ul>
    </div>
</div>
