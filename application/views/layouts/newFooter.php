    </section>
</section>
<aside class="sidebar-right sidebar-right-effect">
    <div class="panel panel-tab">
        <div class="panel-heading no-padding">
            <div class="pull-right">
                <ul class="nav nav-tabs">

                    <li class="active">
                        <a href="#sidebar-modificacion" data-toggle="tab">
                            <i class="fa fa-eye"></i>
                            <span>Modificaciones</span>
                        </a>
                    </li>
                    <li class="">
                        <a href="#sidebar-adicional" data-toggle="tab">
                        <i class="fa fa-pencil"></i>
                            <span>Adicionales</span>
                        </a>
                    </li>
                    <li class="">
                        <a href="#" id="sidebar-close">
                        <i class="fa fa-close"></i>
                            <span>Cerrar</span>
                        </a>
                    </li>
                    
                </ul>
            </div>
            <div class="clearfix"></div>
        </div>
        <div class="panel-body no-padding" style="height: 100% !important;">
            <div class="tab-content">
                <div class="tab-pane in active" id="sidebar-modificacion">
                    <div  class="producto-area">Modificaciones agregadas</div>
                    <div class="" id="producto-modificado"></div>    
                    <div  class="producto-area">Modificaciones sin Agregar <span title="Nueva Modificación" class="pull-right" id="nueva_modificacion"><i class="glyphicon glyphicon-plus"></i></span></div>
                    <div id="formnuevam" hidden class="text-center" style="margin-top: 1%;">                        
                    </div>
                    <div id="erroresm" class="text-center">                        
                    </div>
                    <div id="producto-sinmodificar" class=""></div>   
                </div><!-- /#sidebar-Adicionales -->
                <div class="tab-pane" id="sidebar-adicional">
                    <div class="table-responsive sidebar-adicional">
                    <div id="" class="producto-area">Adiciones agregadas</div>
                    <div id="producto-adicionado" class=""></div>
                    <div id="" class="producto-area">Adiciones sin agregar</div>
                    <div id="producto-sinadicionar" class=""></div>
                    </div>
                </div><!-- /#sidebar-Modificaciones -->
            </div>
        </div>
    </div>
</aside>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
        <script src="<?php echo base_url("public/v2"); ?>/global/js/core.min.js"></script>
        <script src="<?php echo base_url("public/v2"); ?>/base/assets/js/site.min.js"></script>  
        <script src="<?php echo base_url("public/v2"); ?>/base/assets/js/sections/menu.min.js"></script>
        
        <script src="<?php echo base_url(); ?>public/js/app/bootbox.js"></script>
        <script src="<?php echo base_url(); ?>public/js/app/jquery.dataTables.min.js"></script>
        <script src="<?php echo base_url(); ?>public/js/app/dataTables.bootstrap.js"></script>
        <script src="<?php echo base_url(); ?>public/js/app/datatables.responsive.js"></script>
        <script src="https://cdn.datatables.net/buttons/1.4.0/js/dataTables.buttons.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/1.4.0/js/buttons.bootstrap.min.js"></script>
        <script src="<?php echo base_url(); ?>public/js/app/jasny-bootstrap.fileinput.min.js"></script>
        <script src="<?php echo base_url(); ?>public/js/app/bootstrap-tagsinput.min.js"></script>
        <script src="<?php echo base_url(); ?>public/js/app/fuelux.min.js"></script>
        <script src="<?php echo base_url(); ?>public/js/app/jquery.inputmask.bundle.min.js"></script>
        <script src="<?php echo base_url(); ?>public/js/app/select2.full.min.js"></script>
        <script src="<?php echo base_url(); ?>public/js/app/jquery.nicescroll.min.js"></script>
        <script src="<?php echo base_url(); ?>public/js/app/jquery.validate.min.js"></script>
        <script src="<?php echo base_url(); ?>public/js/app/vendty.apps.js"></script>
        <script src="<?php echo base_url(); ?>public/js/app/vendty.table.js"></script>
        <script src="<?php echo base_url(); ?>public/js/app/vendty.demo.js"></script>
        <script src="<?php echo base_url(); ?>public/js/app/vendty.producto.js"></script>
        <script src="<?php echo base_url(); ?>public/js/app/vendty.orden.js?<?php echo rand() ?>"></script>  
        <script src="<?php echo base_url("public/js"); ?>/sweetalert2.min.js"></script>
        <link rel="stylesheet" type="text/css" href="<?php echo base_url("public/css"); ?>/sweetalert2.min.css">

        <style>
            @media(min-width:321px) and (max-width:768px){
                .zona_tomapedido{
                    clear:both;
                }
            }
        </style>

        <script type="text/javascript">      
            $(document).ready(function ($) {            
                Site.run();   
                $('[data-toggle="tooltip"]').tooltip();                 
            });
       
            function confirmarCierre() {
                //le doy un tiempo a la funcion cerrar sesion para que el usuario tenga un tiempo para confirmar, sino lo hizo en el tiempo se cerrara la sesion automaticamente
                var cerrar = setTimeout(cerrarSesion,1000);//4 minutos de prueba                
            }

            function cerrarSesion() {
               
                var URLactual ='<?php echo $_SERVER["REQUEST_URI"] ?>';
                var estacion ='<?php echo $this->session->userdata('es_estacion_pedido'); ?>';
                URLactual=URLactual.split("index.php/");                
                if(estacion==1){
                    if(URLactual[1]!="tomaPedidos/estacion_pedidos"){
                        $.ajax({
                            type: 'POST',
                            async: false,
                            url:'<?php echo site_url('tomaPedidos/salir_mesero') ?>'                   
                        })
                        .done(function(data){
                            location.href='<?php echo site_url('tomaPedidos') ?>';
                        });    
                    } 
                }               
            }

            // se llamará a la función que confirmar Cierre después de 10 segundos
            var temp = setTimeout(confirmarCierre, 300000);

            // cuando se detecte actividad en cualquier parte de la app
            $( document ).on('click keyup keypress keydown blur change', function(e) {
                // borrar el temporizador de la funcion confirmarCierre
                clearTimeout(temp);
                // y volver a iniciarlo con 10segs
                temp = setTimeout(confirmarCierre,300000);
                //console.log('actividad detectada');                                               
            });
        </script>       
        
        <script>
            document.getElementById('admin_shop').addEventListener('click', function(e) {
                e.preventDefault();

                var xhttp = new XMLHttpRequest();

                xhttp.onreadystatechange = function() {

                    if (this.readyState == 4 && this.status == 200) {
                        console.log('-----------------------------------');
                        console.log(this.responseText);
                        console.log('-----------------------------------');

                        localStorage.setItem("data", this.responseText);
                        
                        var popUp = window.open('http://admintienda.vendty.com/admin/crosslogin', '_blank');
                        if (popUp == null || typeof(popUp) == 'undefined') {
                            console.log('-----------------------------------');
                            console.log('Se bloqueo el popup');
                            console.log('-----------------------------------');
                            document.getElementById('validate-popup').style.display = 'block';
                        } 
                    }
                };

                xhttp.open("GET", '<?php echo site_url('tienda/crossDomain');?>', true);
                xhttp.send();
            });

                
            </script>   
    </body>  
</html>