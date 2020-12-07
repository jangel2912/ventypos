<div class="page-header">
    <div class="icon">
        <span class="ico-box"></span>
    </div>
    <h1>
        <?php echo custom_lang("Informes", "Informe Devoluciones");?><small><?php echo $this->config->item('site_title');?></small></h1>
</div>
<!--div class="block title">
    <div class="head">
        <h2>
            <?php echo custom_lang('cuadrecaja', "Informe devoluciones por fecha");?>
        </h2>
    </div>
</div-->

<a href="#" id="ex" class="btn"><small class="ico-circle-arrow-down icon-white"></small><?php echo custom_lang('sima_export', "Exportar a Excel");?></a>
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
                        <?php echo custom_lang('ventasxclientes', "Informe Devoluciones rango de fechas");?>
                    </h2>
                </div>
                <form action="">
                    <table>
                        <tr>
                            <td width="30%">Fecha Inicial : <input type="text" name="dateinicial" value="<?php echo $this->input->post('dateinicial');?>"
                                    class="datepicker" /> </td>
                            <td width="30%">Fecha Final : <input type="text" name="datefinal" value="<?php echo $this->input->post('datefinal');?>"
                                    class="datepicker" /> </td>
                            <td width="30%"><br/> <input type="submit" value="Enviar" class="btn btn-primary" /></td>
                        </tr>
                    </table>
                </form>
        </div>
    </div>
</div>