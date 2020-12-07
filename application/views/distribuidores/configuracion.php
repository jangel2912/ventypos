
<style>
     
     .checker{
         float: left;
         text-align: left;
     }
     .contListas{
         padding: 10px;
     }
     
     .well{
         padding: 0px !important;
         background-color: #f7f7f7 !important;
     }
     
     .well > div{
         display: block;
         width: 100%;
     }
 
     .listasCont{
         padding: 15px;
         background-color: #fff;
     }
     
     .listasCont div{
         margin-bottom: 10px;
     }
     
     .listasCont div.checker span, .listasCont div.radio span {
         background-image: none !important;
     }
     .listasCont span.switchery, .listasCont .checker {
         width: 40px;
         margin-right: 10px;
     }
       .switchery > small {
         width: 18px;
         height: 18px;
     }
 
     .no-padding {
         padding:0;
     }
     .no-margin {
         margin:0 !important;
     }
     .no-border {
         border:none !important;
     }
 
     .link_session{
         color: red;
         /* text-decoration: underline; */
         background-color: #fff;
         padding: 2px 5px 2px 5px;
     }
     .panel-config .panel-default {
         margin-bottom:5px !important;
     }
 
     .panel-group .panel-title:before,.panel-group .panel-title:after {
         content: none !important;
     }
 
     .panel-config .panel-collapase ul
     {
         margin:0px;
         border-bottom:1px solid #ccc;
     }
     .panel-config .panel-collapase li {
         padding:5%;
         height:35px;
         list-style:none;
         background-color:#fafafa;
         border-bottom:1px solid #e0e8e9;
     }
     .panel-config .panel-collapase li a:hover,panel-config .panel-collapase li a:focus {
         text-decoration: none !important;
         font-weight:bold;
         cursor:pointer;
 
     }
 
     .panel-config .panel-title {
         text-transform:uppercase;
         font-weight:bold;
     }
 
     .control-check{
         display: flex !important;
         align-items:center;
     }
 
     .control-check span{
         margin-right:5px;
     }
 
     .alert-danger-red{
         background-color:#a94442
     }

     .file-config{
         margin-bottom:12px;
         font-size: 14px;
     }
 </style>
 <div class="page-header">
    <div class="icon">
        <span class="ico-box"></span>
    </div>
    <h1><?php echo custom_lang("Informes", "Configuración");?></h1>
</div>
 <div class="row-fluid">
    <div class="col-md-12 no-padding tab-config">
        <div class="col-md-4 no-padding">
            <div class="panel-group panel-config" id="configuration">
                <div class="panel panel-default no-padding no-margin">
                    <div class="">
                        <div class="panel-title">
                            <a data-toggle="collapse" data-action="collapse"  href="#collapseOne" >
                                <span class="glyphicon glyphicon-cog"></span>
                                Información General
                            </a>
                        </div>
                        <div id="collapseOne" class="panel-collapase collapse in" aria-expanded="true">
                            <ul>
                                <li><a href="#tab-1" data-toggle="tab" aria-expanded="true">Datos de mi empresa</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="panel panel-default no-padding no-margin">
                    <div class="">
                        <div class="panel-title">
                            <a data-toggle="collapse" data-action="collapse" href="#collapseTwo">
                                <span class="glyphicon glyphicon-user"></span>
                                Mis Usuarios
                            </a>
                        </div>
                    </div>
                    <?php if($distribuidor->group_id == 3){ ?>
                    <div id="collapseTwo" class="panel-collapase collapse">
                        <ul>
                            <li><a href="#tab-2" data-toggle="tab">Lista de usuarios</a></li>
                            <!--<li><a href="#tab-3" data-toggle="tab">Clientes</a></li>-->
                        </ul>
                    </div>
                    <?php } ?>
                </div>

                 <div class="panel panel-default no-padding no-margin">
                    <div class="">
                        <div class="panel-title">
                            <a data-toggle="collapse" data-action="collapse" href="#collapseThree">
                                <span class="glyphicon glyphicon-shopping-cart"></span>
                                Pagos (pronto)
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="col-md-8">
            <div class="tab-content">
                <div class="tab-pane fade in active" id="tab-1">
                    <div class="row">
                        <div class="col-md-10">
                            <form action="<?php echo site_url('administracion_vendty/distribuidores/guardar_configuracion')?>" method="post">
                                <div class="col-md-6 form-group">
                                    <label for="Last_Name">Nombre</label>
                                    <input type="text" class="form-control" name="username" id="username" value="<?php echo ($distribuidor->username != '')? $distribuidor->username : '';?>" required>
                                </div>

                                <div class="col-md-6 form-group">
                                    <label for="Last_Name">Nombre Contacto:</label>
                                    <input type="text" class="form-control" name="first_name" id="first_name" value="<?php echo ($distribuidor->first_name != '')? $distribuidor->first_name : '';?>" required>
                                </div>

                                <div class="col-md-6 form-group">
                                    <label for="Last_Name">Email</label>
                                    <input type="text" class="form-control" name="email" id="email" value="<?php echo ($distribuidor->email != '')? $distribuidor->email : '';?>" required>
                                </div>

                                <div class="col-md-6 form-group">
                                    <label for="Last_Name">Empresa</label>
                                    <input type="text" class="form-control" name="company" id="company" value="<?php echo ($distribuidor->company != '')? $distribuidor->company : '';?>" required>
                                </div>

                                <div class="col-md-6 form-group">
                                    <label for="Last_Name">Teléfono</label>
                                    <input type="text" class="form-control" name="phone" id="phone" value="<?php echo ($distribuidor->phone != '')? $distribuidor->phone : '';?>" required>
                                </div>

                                <div class="col-md-12 form-group">
                                    <button type="sunmit" class="btn btn-success pull-right">Guardar información</button>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="tab-2">
                    <div> <a class="btn btn-default" href="<?php echo site_url('administracion_vendty/distribuidores/nuevousuario');?>">Crear usuario</a></div>
                    <br>
                    <div class="row-fluid">
                        <div class="table-responsive">
                            <table class="table">
                                <tr>
                                    <td>Correo</td>
                                    <td>Nombre</td>
                                    <td>Contacto</td>
                                </tr>

                                <?php foreach($vendedores as $vendedor){?>
                                    <tr>
                                        <td><?php echo $vendedor->email; ?></td>
                                        <td><?php echo $vendedor->username; ?></td>
                                        <td><?php echo $vendedor->phone; ?></td>
                                    </tr>
                                <?php } ?> 
                            </table>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="tab-3">
                    <div class="row-fluid">
                        <div class="table-responsive">
                            <table class="table">
                                <tr>
                                    <td>Correo</td>
                                    <td>Nombre</td>
                                    <td>Empresa</td>
                                    <td>Contacto</td>
                                    <?php  foreach($clientes as $cliente){?>
                                    <tr>
                                        <td><?php echo $cliente->email; ?></td>
                                        <td><?php echo $cliente->username; ?></td>
                                        <td><?php echo $cliente->empresa; ?></td>
                                        <td><?php echo $cliente->phone; ?></td>
                                    </tr>
                                <?php } ?> 
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>