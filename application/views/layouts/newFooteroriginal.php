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
        <div class="panel-body no-padding">
            <div class="tab-content">
                <div class="tab-pane in active" id="sidebar-modificacion">
                    <div  class="producto-area">Modificaciones agregadas</div>
                    <div class="" id="producto-modificado"></div>    
                    <div  class="producto-area">Modificaciones sin Agregar</div>
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
<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>-->
<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
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
<link rel="stylesheet" href="<?php echo base_url("public/v2"); ?>/global/css/owl.carousel.min.css">
<link rel="stylesheet" href="<?php echo base_url("public/v2"); ?>/global/css/owl.theme.default.min.css">
<script src="<?php echo base_url("public/v2"); ?>/global/js/owl.carousel.min.js"> </script>

<style>
    @media(min-width:321px) and (max-width:768px){
        .zona_tomapedido{
            clear:both;
        }
    }
</style>
</body>
</html>

