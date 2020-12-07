<div class="block title">
    <div class="head">
        <h2>Listado de bases de datos</h2>                                          
    </div>
</div>
<div class="row-fluid">
    <div class="span12">
        <div class="block">
            <?php 
                $message = $data['message']['msg'];
                            if(!empty($message)):?>
                            <div class="alert alert-<?php echo $data['message']['type']?>">
                                <?php echo $message;?>
                            </div>
                            <?php endif; ?>
            <div class="head blue">
                <div class="icon"><i class="ico-layout-9"></i></div>
                <h2>Todas las bases de datos</h2>
            </div>                
                <div class="data-fluid">
                    <table class="table aTable" cellpadding="0" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                
                                <th width="20%">Servidor</th>
                                <th>Base de datos</th>
                                <th width="20%">Usuario</th>
                                <th width="20%">Fecha</th>
                                <th width="80" class="TAC">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($data['data'] as $row):?>
                            <tr>
                                <td><?php echo $row->servidor; ?></td>
                                <td><?php echo $row->base_dato;?></td>
                                <td><?php echo $row->usuario;?></td>
                                <td><?php echo $row->fecha;?></td>
                                <td>
                                    <a href="#<?php //echo site_url("backend/db_config/editar/".$row->id);?>" class="button green">
                                        <div class="icon"><span class="ico-pencil"></span></div>
                                    </a>
                                    <a href="<?php echo site_url("backend/db_config/eliminar/".$row->id);?>" class="button red">
                                        <div class="icon"><span class="ico-remove"></span></div>
                                    </a>                                              
                                </td>
                            </tr>
                            <?php endforeach;?>                         
                        </tbody>
                    </table>                    
                </div> 
        </div> 
</div>
    <a href="<?php echo site_url("/backend/db_config/nuevo")?>" class="btn"><small class="ico-plus icon-white"></small> Nueva base de datos</a>
</div>