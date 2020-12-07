<style>
    .site-menu-item h5 {
        font-size: 10px !important;
    }
    .site-menubar-unfold .site-menu>.site-menu-item>a {        
        line-height: 25px !important;
    }
</style>
<div class="site-menubar" style=" background-color: #505050;">
    <div class="site-menubar-body">                                             
        <ul class="site-menu" id="dataStep1">
            <br>          
            <li class="site-menu-item mayuscula menu-items" style=" margin-top: 0px;">
                <a href="<?php echo site_url("administracion_vendty/empresas/"); ?>">
                    <center><i id="menuIconB" class="site-menu-icon glyphicon-shopping-cart" aria-hidden="true" style=" color: #f9f9f9 !important;font-size:16px;padding: 10px 0px 0px 0px;"></i></br>
                    <span class="site-menu-title" style=" margin-top: -20px;"><h5 style=" color: #f9f9f9;">Empresas</h5></span></center>
                </a>
            </li>
            <li class="site-menu-item mayuscula menu-items" style=" margin-top: 0px;">
                <a href="<?php echo site_url("administracion_vendty/formas_pago/"); ?>">
                    <center><i id="menuIconB" class="site-menu-icon glyphicon-shopping-cart" aria-hidden="true" style=" color: #f9f9f9 !important;font-size:16px;padding: 10px 0px 0px 0px;"></i></br>
                    <span class="site-menu-title" style=" margin-top: -20px;"><h5 style=" color: #f9f9f9;">Formas Pago</h5></span></center>
                </a>
            </li>
            <li class="site-menu-item mayuscula menu-items" style=" margin-top: 0px;">
                <a href="<?php echo site_url("administracion_vendty/planes/"); ?>">
                    <center><i id="menuIconB" class="site-menu-icon glyphicon-shopping-cart" aria-hidden="true" style=" color: #f9f9f9 !important;font-size:16px;padding: 10px 0px 0px 0px;"></i></br>
                    <span class="site-menu-title" style=" margin-top: -20px;"><h5 style=" color: #f9f9f9;">Planes</h5></span></center>
                </a>
            </li>
            <li class="site-menu-item mayuscula menu-items" style=" margin-top: 0px;">
                <a href="<?php echo site_url("administracion_vendty/pagos_factura/"); ?>">
                    <center><i id="menuIconB" class="site-menu-icon glyphicon-shopping-cart" aria-hidden="true" style=" color: #f9f9f9 !important;font-size:16px;padding: 10px 0px 0px 0px;"></i></br>
                    <span class="site-menu-title" style=" margin-top: -20px;"><h5 style=" color: #f9f9f9;"><br>Pagos <br>Licencias</h5></span></center>
                </a>
            </li>
            <li class="site-menu-item mayuscula menu-items" style=" margin-top: 0px;">
                <a href="<?php echo site_url("administracion_vendty/almacenes_cliente/"); ?>">
                    <center><i id="menuIconB" class="site-menu-icon glyphicon-shopping-cart" aria-hidden="true" style=" color: #f9f9f9 !important;font-size:16px;padding: 10px 0px 0px 0px;"></i></br>
                    <span class="site-menu-title" style=" margin-top: -20px;"><h5 style=" color: #f9f9f9;"><br>Almacenes</h5></span></center>
                </a>
            </li>
            <li class="site-menu-item mayuscula menu-items" style=" margin-top: 0px;">
                <a href="<?php echo site_url("administracion_vendty/licencia_empresa/"); ?>">
                    <center><i id="menuIconB" class="site-menu-icon glyphicon-shopping-cart" aria-hidden="true" style=" color: #f9f9f9 !important;font-size:16px;padding: 10px 0px 0px 0px;"></i></br>
                    <span class="site-menu-title" style=" margin-top: -20px;"><h5 style=" color: #f9f9f9;"><br>Licencias</h5></span></center>
                </a>
            </li>
            <li class="site-menu-item mayuscula menu-items" style=" margin-top: 0px;">
                <a href="<?php echo site_url("administracion_vendty/facturas_licencia/"); ?>">
                    <center><i id="menuIconB" class="site-menu-icon glyphicon-shopping-cart" aria-hidden="true" style=" color: #f9f9f9 !important;font-size:16px;padding: 10px 0px 0px 0px;"></i></br>
                    <span class="site-menu-title" style=" margin-top: -20px;"><h5 style=" color: #f9f9f9;"><br> Facturas</h5></span></center>
                </a>
            </li>   
            <!--
            <li class="site-menu-item mayuscula menu-items" style=" margin-top: 0px;">
                <a href="<?php echo site_url("administracion_vendty/empresas/info_fiscal/"); ?>">
                    <center><i id="menuIconB" class="site-menu-icon glyphicon-shopping-cart" aria-hidden="true" style=" color: #f9f9f9 !important;font-size:16px;padding: 10px 0px 0px 0px;"></i></br>
                    <span class="site-menu-title" style=" margin-top: -20px;"><h5 style=" color: #f9f9f9;"><br>Información <br>Fiscal</h5></span></center>
                </a>

            </li>    --> 
            <li class="site-menu-item has-sub  menu-items">
                <a href="javascript:void(0)">
                    <center><i id="menuIconB" class="site-menu-icon glyphicon-shopping-cart" aria-hidden="true" style=" color: #f9f9f9 !important;font-size:16px;padding: 10px 0px 0px 0px;"></i></br>
                    <span class="site-menu-title" style=" margin-top: -20px;"><h5 style=" color: #f9f9f9;"><br>Información <br>Fiscal</h5></span></center>                                        
                </a>
                <ul class="site-menu-sub">   
                    <li class="site-menu-item" title="Informe de orden de compra">
                        <a class="animsition-link" href="<?php echo site_url("administracion_vendty/empresas/info_fiscal/"); ?>">
                            <span class="menu-title">Info Fiscal por Licencias</span>
                        </a>
                    </li>   
                    <li class="site-menu-item" title="Informe de orden de compra">
                        <a class="animsition-link" href="<?php echo site_url("administracion_vendty/empresas/info_fiscal_cliente/"); ?>">
                            <span class="menu-title">Info Fiscal por Cliente</span>
                        </a>
                    </li>    
                    <li class="site-menu-item" title="migrar BD">
                        <a class="animsition-link" href="<?php echo site_url("administracion_vendty/empresas/migrar_bd/"); ?>">
                            <span class="menu-title">Migrar BD</span>
                        </a>
                    </li>                 
                </ul>
            </li> 
            <li class="site-menu-item has-sub  menu-items">
                <a href="javascript:void(0)">
                    <center><i id="menuIconB" class="site-menu-icon glyphicon-shopping-cart" aria-hidden="true" style=" color: #f9f9f9 !important;font-size:16px;padding: 10px 0px 0px 0px;"></i></br>
                    <span class="site-menu-title" style=" margin-top: -20px;"><h5 style=" color: #f9f9f9;"><br>Informes </h5></span></center>                                        
                </a>
                <ul class="site-menu-sub">   
                    <li class="site-menu-item" title="Informe de orden de compra">
                        <a class="animsition-link" href="<?php echo site_url("administracion_vendty/empresas/informe_prueba/"); ?>">
                            <span class="menu-title">Cuentas Pruebas</span>
                        </a>
                    </li>                                    
                </ul>
            </li>                                         
        </ul>                        
    </div>
</div>