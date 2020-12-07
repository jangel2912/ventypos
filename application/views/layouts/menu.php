<nav class="navbar navbar-vendty">               
 <!--   <div class="navbar-collapse collapse" collapse="collapseMenu"> -->
        <ul class="nav navbar-nav">
            <?php if($this->session->userdata('es_estacion_pedido')!=1){ ?>
            <li class="hidden-xs">
                <a href="<?php echo base_url().'index.php/ventas/nuevo'; ?>">
                <img onerror="ImgError(this)"  class="navbar-brand-logo2" src="<?php echo base_url(); ?>/public/v2/img/logodas.fw.png" title="Vendtys" style="visibility: hidden; width: 79px;height: auto;margin: 0px 0px 0px -17px;">
                </a>
            </li>
        <?php } ?>
        <?php if(($this->session->userdata('es_estacion_pedido')!=1)&&(in_array("11", $permisos ) || $isAdmin == 't')){ ?>
            <li>
                <a class="margen10" href="<?php echo base_url().'index.php/ventas/nuevo'; ?>">
                    <i class="site-menu-icons glyphicon-shopping-cart"></i>                  
                    <p class="titulomenu">VENDER</p>                   
                </a>
            </li>
        <?php } ?>
        <?php if(($this->session->userdata('es_estacion_pedido')!=1)&&(in_array("10", $permisos ) || in_array("11", $permisos ) || in_array("66", $permisos ) || in_array("69", $permisos ) || in_array("27", $permisos ) || in_array("1024", $permisos ) || in_array("1037", $permisos ) || in_array("1037", $permisos ) || (in_array("1022", $permisos ) && $comanda["comanda"] == "si") || $isAdmin == 't')){ ?>
            <li>
                <a class="dropdown-toggle submenu margen10" data-toggle="dropdown" data-id="ventasmenu">
                    <i class="site-menu-icons glyphicon-tag"></i>                    
                    <p class="titulomenu">VENTAS</p>                   
                </a>
                <ul class="dropdown-menu" role="menu" id="ventasmenu">
                <?php if((isset($data['tipo_negocio'])&&($data['tipo_negocio']=='restaurante')) && (in_array("1037", $permisos ) || $isAdmin == 't')){ ?>                                                                 
                    <li class="sub-menus">
                        <a class="actionb" href="<?php echo base_url().'index.php/tomaPedidos'; ?>">
                            <span>TOMAR PEDIDO</span>
                        </a>
                    </li>
                    <?php } ?>
                    <?php if (in_array("10", $permisos) || $isAdmin == 't') { ?>
                    <li class="sub-menus">
                        <a href="<?php echo base_url().'index.php/ventas'; ?>">
                            <span>HISTORICO DE VENTAS</span>
                        </a>
                    </li>
                    <?php } ?>
                    <?php if( in_array("1035", $permisos ) || $isAdmin == 't'){ ?>
                    <li class="sub-menus">
                        <a href="<?php echo base_url().'index.php/caja/quickCerrarCaja'; ?>">
                            <span>CERRAR CAJA (CAJERO)</span>
                        </a>
                    </li>
                    <?php } ?>
                    <?php if( in_array("1009", $permisos ) || $isAdmin == 't'){ ?>
                    <li class="sub-menus">
                        <a href="<?php echo base_url().'index.php/caja/listado_cierres'; ?>">
                            <span>CIERRE DE CAJAS</span>
                        </a>
                    </li>
                    <?php } ?>
                    <li class="sub-menus">
                        <a href="<?php echo base_url().'index.php/ventas_separe/facturas'; ?>">
                            <span>PLAN SEPARE</span>
                        </a>
                    </li>                    
                    <?php if($isAdmin == 't' && $ventaOnline){ ?>
                    <li class="sub-menus">
                        <a href="<?php echo base_url().'index.php/ventas_online/ventas'; ?>">
                            <span>VENTAS ONLINE</span>
                        </a>
                    </li>
                    <?php } ?>
                    <?php if( in_array("27", $permisos ) || $isAdmin == 't'){ ?>
                    <li class="sub-menus">
                        <a href="<?php echo base_url().'index.php/presupuestos'; ?>">
                            <span>COTIZACIONES</span>
                        </a>
                    </li>
                    <?php } ?>
                    <?php if( in_array("69", $permisos ) || $isAdmin == 't'){ ?>
                    <li class="sub-menus">
                        <a href="<?php echo base_url().'index.php/credito'; ?>">
                            <span>CREDITOS</span>
                        </a>
                    </li>
                    <?php } ?>
                    <!--
                    <?php if( in_array("1022", $permisos ) || $isAdmin == 't'){ ?>
                        <?php if( $comanda["comanda"] == "si" && $comanda["push"] == "1" ){ ?>
                        <li class="sub-menus">
                            <a href="<?php echo base_url().'index.php/comanda'; ?>">
                                <span>COMANDAS</span>
                            </a>
                        </li>
                        <?php } ?>
                    <?php } ?>-->
                </ul>
            </li>
        <?php } ?>
        <?php if(($this->session->userdata('es_estacion_pedido')!=1)&&(in_array("2", $permisos ) || in_array("14", $permisos ) || in_array("67", $permisos ) || in_array("68", $permisos ) || in_array("1036", $permisos ) || in_array("1036", $permisos ) || $isAdmin == 't')){ ?>    
            <li>
                <a class="dropdown-toggle submenu" data-toggle="dropdown" data-id="inventariomenu">
                    <i class="site-menu-icons wb-payment"></i>
                    <p class="titulomenu">INVENTARIO</p>                    
                </a>
                <ul class="dropdown-menu" role="menu" id="inventariomenu"> 
                    <?php if( in_array("2", $permisos ) || $isAdmin == 't'){ ?>
                    <li class="sub-menus">
                        <a href="<?php echo site_url().'/productos'; ?>">
                            <span>PRODUCTOS</span>
                        </a>
                    </li>
                    <?php } ?>
                    <?php if((isset($data['tipo_negocio'])&&($data['tipo_negocio']=='restaurante')) && (in_array("1036", $permisos ) || $isAdmin == 't')){ ?>
                    <li class="sub-menus">
                        <a href="<?php echo site_url().'/ProductoRestaurant'; ?>">
                            <span>PRODUCTOS RESTAURANTE</span>
                        </a>
                    </li>
                    <?php } ?>
                    <?php if( in_array("14", $permisos ) || $isAdmin == 't'){ ?>
                    <li class="sub-menus">
                        <a href="<?php echo site_url().'/categorias'; ?>">
                            <span>CATEGORIAS</span>
                        </a>
                    </li>
                    <?php } ?>
                    <?php if( in_array("67", $permisos ) || $isAdmin == 't'){ ?>
                    <li class="sub-menus">
                        <a href="<?php echo site_url().'/inventario'; ?>">
                            <span>MOVIMIENTOS</span>
                        </a>
                    </li>
                    <?php } ?>
                    <li class="sub-menus">
                        <a href="<?php echo site_url().'/lista_precios/index'; ?>">
                            <span>LIBRO DE PRECIOS</span>
                        </a>
                    </li>
                    <li class="sub-menus">
                        <a href="<?php echo site_url().'/produccion'; ?>">
                            <span>PRODUCCION</span>
                        </a>
                    </li>
                    <li class="sub-menus">
                        <a href="<?php echo site_url().'/auditoria'; ?>">
                            <span>AUDITORIA INVENTARIO</span>
                        </a>
                    </li>
                </ul>
            </li>
            
            <li>
                <a class="dropdown-toggle submenu" data-toggle="dropdown" data-id="fidelizacionmenu">
                    <i class="site-menu-icons glyphicon-thumbs-up"></i>
                    <p class="titulomenu">FIDELIZACION</p>
                </a>
                <ul class="dropdown-menu" role="menu" id="fidelizacionmenu">
                     <?php if((isset($data['tipo_negocio']))&&($data['tipo_negocio']=='moda' || $data['tipo_negocio']=='retail')){ ?>                              
                                
                    <li class="sub-menus">
                        <a href="<?php echo site_url().'/productos/listaGiftCards'; ?>">
                            <span>GIFT CARDS</span>
                        </a>
                    </li>
                    <?php } ?>
                    <?php if($isAdmin == 't'){ ?>
                    <li class="sub-menus">
                        <a href="<?php echo site_url().'/puntos/index'; ?>">
                            <span>PUNTOS</span>
                        </a>
                    </li>
                    <li class="sub-menus">
                        <a href="<?php echo site_url().'/promociones/index'; ?>">
                            <span>PROMOCIONES</span>
                        </a>
                    </li>
                    <?php } ?>
                </ul>
            </li>
            <?php } ?>  
        <?php if(($this->session->userdata('es_estacion_pedido')!=1)&&(in_array("58", $permisos ) || in_array("70", $permisos ) || in_array("71", $permisos ) || $isAdmin == 't')){ ?>    
            <li>
                <a class="dropdown-toggle submenu" data-toggle="dropdown" data-id="comprasmenu">
                    <i class="site-menu-icons wb-briefcase"></i>
                    <p class="titulomenu">COMPRAS</p>
                </a>
                <ul class="dropdown-menu" role="menu" id="comprasmenu">
                <?php if( in_array("58", $permisos ) || $isAdmin == 't'){ ?>
                    <li class="sub-menus">
                        <a href="<?php echo site_url().'/proformas'; ?>">
                            <span>GASTOS</span>
                        </a>
                    </li>
                <?php } ?>
                <?php if( in_array("70", $permisos ) || $isAdmin == 't'){ ?>
                    <li class="sub-menus">
                        <a href="<?php echo site_url().'/orden_compra'; ?>">
                            <span>ÓRDENES DE COMPRA</span>
                        </a>
                    </li>
                <?php } ?>
                </ul>
            </li>
        <?php } ?> 
        <?php if(($this->session->userdata('es_estacion_pedido')!=1)&&(in_array("32", $permisos ) || in_array("45", $permisos ) || in_array("62", $permisos ) || $isAdmin == 't')){ ?>   
            <li>
                <a class="dropdown-toggle submenu" data-toggle="dropdown" data-id="contactosmenu">
                    <i class="site-menu-icons wb-users"></i>
                    <p class="titulomenu">CONTACTOS</p>                    
                </a>
                <ul class="dropdown-menu" role="menu" id="contactosmenu">
                    <?php if( in_array("32", $permisos ) || $isAdmin == 't'){ ?>
                    <li class="sub-menus">
                        <a href="<?php echo site_url().'/clientes'; ?>">
                            <span>CLIENTES</span>
                        </a>
                    </li>
                    <?php } ?>
                     <?php if((isset($data['tipo_negocio'])&&($data['tipo_negocio']!='restaurante'))&& (in_array("45", $permisos ) || $isAdmin == 't')){ ?>
                    <li class="sub-menus">
                        <a href="<?php echo site_url().'/vendedores'; ?>">
                            <span>VENDEDORES</span>
                        </a>
                    </li>
                    <?php } ?>
                    <?php if((isset($data['tipo_negocio'])&&($data['tipo_negocio']=='restaurante')) && (in_array("1039", $permisos ) || $isAdmin == 't')){ ?>
                    <li class="sub-menus">
                        <a href="<?php echo site_url().'/vendedores/index/-1'; ?>">
                            <span>VENDEDORES/MESEROS</span>
                        </a>
                    </li>
                    <?php } ?>
                    <?php if( in_array("62", $permisos ) || $isAdmin == 't'){ ?>
                    <li class="sub-menus">
                        <a href="<?php echo site_url().'/proveedores'; ?>">
                            <span>PROVEEDORES</span>
                        </a>
                    </li>
                    <?php } ?>
                </ul>
            </li>
        <?php } ?>    
        <?php if(($this->session->userdata('es_estacion_pedido')!=1)&&(in_array("1", $permisos ) || $isAdmin == 't')){ ?>
            <li>
                <a href="<?php echo site_url().'/informes'; ?>">
                    <i class="site-menu-icons wb-graph-up"></i>                  
                    <p class="titulomenu">INFORMES</p>
                    
                </a>
            </li>
        <?php } ?>   
        <!--<?php if(($this->session->userdata('es_estacion_pedido')!=1)&&($isAdmin == 't' )){ ?>
            <li>
                <a id="admin_shop" >
                    <i class="site-menu-icons wb-settings"></i>                    
                    <p class="titulomenu">TIENDA 1</p>                    
                </a>
            </li>
        <?php } ?>  -->
        <?php if(($this->session->userdata('es_estacion_pedido')!=1)&&($isAdmin == 't' )){ ?>
            <li>
                <a href="<?php echo site_url().'/frontend/configuracion'; ?>">
                    <i class="site-menu-icons wb-settings"></i>                    
                    <p class="titulomenu">CONFIGURACIÓN</p>                    
                </a>
            </li>
            <?php } ?>                        
        </ul>   
 
 </nav>
