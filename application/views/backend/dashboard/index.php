<div class="block title">
    <div class="head">
        <h2>Panel de control</h2>                                          
    </div>
</div>
<div class="row-fluid">                                                            
    <div class="span12">
        <div class="widgets">
            <div class="widget yellow icon">
                <div class="left">
                     <div class="icon">
                        <span class="ico-group"></span>
                    </div>
                </div>
                <div class="right">
                   <table cellpadding="0" cellspacing="0" width="100%">
                        <tr>
                            <td>Usuarios</td><td><?php echo $data['total_users'];?></td>
                        </tr>
                        <tr>
                            <td>Activos</td><td><?php echo $data['total_active'];?></td> 
                        </tr>
                        <tr>
                            <td>No activos</td><td><?php echo $data['total_deactive']?></td> 
                        </tr>
                    </table>
                </div>
                <div class="bottom">
                    <a href="<?php echo site_url("backend/usuarios/index");?>">Ver usuarios</a>
                </div>
            </div>
            <div class="widget purple icon">
                <div class="left">
                    <div class="icon">
                        <span class="ico-file-3"></span>
                    </div>
                </div>
                <div class="right">
                    <table cellpadding="0" cellspacing="0" width="100%">
                        <tr>
                            <td>Menues</td><td><?php echo $data['total_menu'];?></td>
                        </tr>
                    </table>
                </div>
                <div class="bottom">
                    <a href="<?php echo site_url("backend/menu/index");?>">Menu</a>
                </div>                            
            </div>
            <div class="widget red icon">
                <div class="left">
                    <div class="icon">
                        <span class="ico-file-2"></span>
                    </div>
                </div>
                <div class="right">
                    <table cellpadding="0" cellspacing="0" width="100%">
                        <tr>
                            <td>Sub menu</td><td><?php echo $data['total_submenu'];?></td>
                        </tr>
                    </table>
                </div>
                <div class="bottom">
                    <a href="<?php echo site_url("backend/sub_menu/index");?>">Submenu</a>
                </div>                            
            </div>
        </div>
    </div>
</div>
