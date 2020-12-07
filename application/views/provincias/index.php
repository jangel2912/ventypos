<div class="block title">
    <div class="head">
        <h2>Listado de provincias</h2>                                          
    </div>
</div>
<div class="row-fluid">
    <div class="span12">
        <div class="block">
            <?php
                $message = $this->session->flashdata('message');
                if(!empty($message)):?>
                <div class="alert alert-success">
                    <?php echo $message;?>
                </div>
            <?php endif; ?>
            <div class="head blue">
                <div class="icon"><i class="ico-layout-9"></i></div>
                <h2>Todas las provincias</h2>
            </div>
                <div class="data-fluid">
                    <table class="table aTable" cellpadding="0" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                
                                <th width="92%">Nombre</th>
                                <th  class="TAC">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($data['data'] as $row):?>
                            <tr>
                                <td><?php echo $row->nombre_provincia; ?></td>
                                <td>
                                    <a href="<?php echo site_url("provincias/editar/".$row->id_provincia);?>" class="button green">
                                        <div class="icon"><span class="ico-pencil"></span></div>
                                    </a>
                                    <a href="<?php echo site_url("provincias/eliminar/".$row->id_provincia);?>"  onclick="if(confirm('Esta seguro que desea eliminar el registro?')){return true;}else{return false;}" class="button red">
                                        <div class="icon"><span class="ico-remove"></span></div>
                                    </a>                                              
                                </td>
                            </tr>
                            <?php endforeach;?>                         
                        </tbody>
                    </table>
                    <div class="pagination pagination-centered">
                        <ul>
                            <?php
                                $config['base_url'] = site_url('provincias/index');
                                $config['total_rows'] = $data["total"];
                                $config['per_page'] = 8;
                                $config['num_tag_open'] = '<li>';  
                                $config['num_tag_close'] = '</li>';
                                $config['cur_tag_open'] = '<li class="active"><a href="#">';
                                $config['cur_tag_close'] = '</a></li>';
                                $config['prev_tag_open'] = '<li>';
                                $config['prev_tag_close'] = '</li>';
                                $config['next_tag_open'] = '<li>';
                                $config['next_tag_close'] = '</li>';
                                $config['last_tag_open'] = '<li>';
                                    $config['last_link'] = '»';
                                $config['last_tag_close'] = '</li>';
                                $config['first_tag_open'] = '<li>';
                                    $config['first_link'] = '«';
                                $config['first_tag_close'] = '</li>';
                                
                                $this->pagination->initialize($config); 
                                echo $this->pagination->create_links();
                            ?>
                        </ul>
                    
                    </div>
                    <br/>
                    <a href="<?php echo site_url("provincias/nuevo")?>" class="btn"><small class="ico-plus icon-white"></small> Nueva provincia</a>
                </div>
            </div>
            
        </div>
    </div>