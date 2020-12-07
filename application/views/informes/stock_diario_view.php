<div class="page-header">
    <div class="icon">
        <span class="ico-box"></span>
    </div>
    <h1>
        <?php echo custom_lang("Informes", "Informe Auditoria de Productos");?><small><?php echo $this->config->item('site_title');?></small></h1>
</div>
<!--div class="block title">
    <div class="head">
        <h2>
            <?php echo custom_lang('cuadrecaja', "Informe Auditoria producto por fecha");?>
        </h2>
    </div>
</div-->


<div class="row-fluid">
    <div class="span12">
        <div class="block">
            <?php
                $is_admin = $this->session->userdata('is_admin');
                $username = $this->session->userdata('username');	
                $message = $this->session->flashdata('message');
                if(!empty($message)):?>
                <div class="alert alert-success">
                    <?php echo $message;?>
                </div>
                <?php endif; ?>
                <div class="head blue">
                    <div class="icon"><i class="ico-box"></i></div>
                    <h2>
                        <?php echo custom_lang('ventasxclientes', "Informe Audtiria producto por  rango de fechas");?>
                    </h2>
                </div>
                <form action="" method="POST">
                    <table>
                        <tr>
                            <td width="20%">Fecha Inicial : <input type="text" name="dateinicial" value="<?php echo $this->input->post('dateinicial');?>"
                                    class="datepicker" /> </td>
                            <td width="20%">Fecha Final : <input type="text" name="datefinal" value="<?php echo $this->input->post('datefinal');?>"
                                    class="datepicker" /> </td>
                                    <td width="20%">producto :<input type="text" name="producto" value="<?php echo $this->input->post('producto');?>"</td>        
                            <td width="20%"><br/> <input type="submit" value="Enviar" class="btn btn-primary" /></td>
                            
                        </tr>
                        <tr>
                        <td width="20%">Almacen :<?php $combo = "<select id='almacenes' name='almacen'><option value='0'>Todos</option><option value='-1'>Consolidado de existencias</option>"; foreach ($data['almacenes'] as $key => $value){ $combo .= "<option value='".$key."'>".$value."</option>";} $combo .= "</select>"; echo $combo; ?>"</td>                
                            
                        </tr>
                    </table>
                </form>
        </div>
    </div>
</div>