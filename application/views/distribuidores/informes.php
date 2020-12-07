<style>
    #v2Cont.panel {
        margin-bottom: 0px !important;
        border: none !important;
        box-shadow: none !important;
    }
    #v2Cont.panel,.body,.wrapper{
        margin: 0px;
        padding: 0px;
        background-color: transparent;
    }
    .panel-title{
        padding: 5px;
    }

    table a{
        color: #66B12F;
        font-size: 13px;
    }
    table a:hover{
        text-decoration: underline;
        color: #5B7D3A;
    }
    
</style>
<div class="row">                                             
    <div class="col-xs-12 col-md-12">
        <div class="example-col panel" style=" padding: 5px 15px;">
            <div class="page-header">
                <div class="icon">
                    <span class="ico-box"></span>
                </div>
                <h1><?php echo custom_lang("Informes", "Informes"); ?><small><?php echo $this->config->item('site_title'); ?></small></h1>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xs-12 col-md-6">
        <div class="example-col panel">
            <div class="row">
                <div class="col-xs-12 col-md-12">
                    <div class="panel-heading">
                        <h3 class="panel-title" style="text-align: center"><?php echo custom_lang('margin_utility', "LISTADO DE INFORMES"); ?></h3>
                        <hr>
                    </div>
                    <table class="table aTable" cellpadding="0" cellspacing="0" width="50%" id="informesTable">
                        <tbody>
                                <tr>
                                    <td width="100%">
                                        <a href="<?php echo site_url('administracion_vendty/distribuidores/informe_clientes') ?>" >Informe de clientes</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="100%">
                                        <a href="<?php echo site_url('administracion_vendty/distribuidores/informe_licencias') ?>" >Informe de licencias</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="100%">
                                        <a href="<?php echo site_url('administracion_vendty/distribuidores/informe_pagos') ?>" >Informe de pagos</a>
                                    </td>
                                </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
</div>


