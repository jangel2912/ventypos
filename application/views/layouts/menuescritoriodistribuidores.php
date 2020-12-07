<div class="site-menubar" style=" background-color: #505050;">
                <div class="site-menubar-body">                                             
                    <ul class="site-menu" id="dataStep1">
                        <br>                               
                        <?php if ( in_array("11", $permisos ) || $isAdmin == 't' ) { ?>
                        <!--<li class="site-menu-item mayuscula menu-items" style=" margin-top: 0px;">
                            <a href="<?php echo site_url("administracion_vendty/distribuidores/nueva_suscripcion"); ?>">
                                <center><i id="menuIconB" class="site-menu-icon glyphicon-shopping-cart" aria-hidden="true" style=" color: #f9f9f9 !important;font-size:20px;padding: 10px 0px 0px 0px;"></i></br>
                                <span class="site-menu-title" style=" margin-top: -20px;"><h5 style=" color: #f9f9f9;"><br>Nueva <br> Suscripción</h5></span></center>
                            </a>
                        </li>-->
                        <li class="site-menu-item mayuscula menu-items" style=" margin-top: 0px;">
                            <a href="<?php echo site_url("administracion_vendty/distribuidores/suscripciones"); ?>">
                                <center><i id="menuIconB" class="site-menu-icon glyphicon-shopping-cart" aria-hidden="true" style=" color: #f9f9f9 !important;font-size:20px;padding: 10px 0px 0px 0px;"></i></br>
                                <span class="site-menu-title" style=" margin-top: -20px;"><h5 style=" color: #f9f9f9;">Clientes</h5></span></center>
                            </a>
                        </li>
                        <li class="site-menu-item mayuscula menu-items" style=" margin-top: 0px;">
                            <a href="<?php echo site_url("administracion_vendty/distribuidores/licencias"); ?>">
                                <center><i id="menuIconB" class="site-menu-icon glyphicon-shopping-cart" aria-hidden="true" style=" color: #f9f9f9 !important;font-size:20px;padding: 10px 0px 0px 0px;"></i></br>
                                <span class="site-menu-title" style=" margin-top: -20px;"><h5 style=" color: #f9f9f9;">Licencias</h5></span></center>
                            </a>
                        </li>
                        <li class="site-menu-item mayuscula menu-items" style=" margin-top: 0px;">
                            <a href="<?php echo site_url("administracion_vendty/distribuidores/herramientas"); ?>">
                                <center><i id="menuIconB" class="site-menu-icon glyphicon-shopping-cart" aria-hidden="true" style=" color: #f9f9f9 !important;font-size:20px;padding: 10px 0px 0px 0px;"></i></br>
                                <span class="site-menu-title" style=" margin-top: -20px;"><h5 style=" color: #f9f9f9;">Herramientas</h5></span></center>
                            </a>
                        </li>
                        <li class="site-menu-item mayuscula menu-items" style=" margin-top: 0px;">
                            <a href="<?php echo site_url("administracion_vendty/distribuidores/informes"); ?>">
                                <center><i id="menuIconB" class="site-menu-icon glyphicon-shopping-cart" aria-hidden="true" style=" color: #f9f9f9 !important;font-size:20px;padding: 10px 0px 0px 0px;"></i></br>
                                <span class="site-menu-title" style=" margin-top: -20px;"><h5 style=" color: #f9f9f9;">Informes</h5></span></center>
                            </a>
                        </li>
                        <?php
                            //distribuidor
                            $user=$this->session->userdata('user_id');
                            $distribuidor = $this->crm_model->get_distribuidor2(array('users_id'=>$user));
                            $iddistribuidor = $distribuidor[0]['id_distribuidores_licencia'];   
                            if($iddistribuidor==1){
                        ?>
                            <li class="site-menu-item mayuscula menu-items" style=" margin-top: 0px;">
                                <a href="<?php echo site_url("administracion_vendty/distribuidores/churm"); ?>">
                                    <center><i id="menuIconB" class="site-menu-icon glyphicon-shopping-cart" aria-hidden="true" style=" color: #f9f9f9 !important;font-size:20px;padding: 10px 0px 0px 0px;"></i></br>
                                    <span class="site-menu-title" style=" margin-top: -20px;"><h5 style=" color: #f9f9f9;">Churm</h5></span></center>
                                </a>
                            </li>
                        <?php 
                            }
                        ?>
                        <li class="site-menu-item mayuscula menu-items" style=" margin-top: 0px;">
                        <a href="<?php echo site_url("administracion_vendty/distribuidores/configuracion"); ?>">
                            <center><i id="menuIconB" class="site-menu-icon glyphicon-shopping-cart" aria-hidden="true" style=" color: #f9f9f9 !important;font-size:20px;padding: 10px 0px 0px 0px;"></i></br>
                            <span class="site-menu-title" style=" margin-top: -20px;"><h5 style=" color: #f9f9f9;">Configuración</span></center>
                        </a>
                    </li>
                        <?php } ?>                        
                    </ul>                        
                </div>
            </div>