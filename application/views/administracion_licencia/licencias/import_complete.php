<div class="page-header">

    <div class="icon">

        <span class="ico-group"></span>

    </div>

    <h1><?php echo custom_lang("Licencias", "Licencias");?></h1>

</div>
<style>
    b{
        font-weight: 700 !important;
    }
</style>

<div class="block title">

    <div class="head">

        <h2><?php echo custom_lang('sima_tax_import_complete', "Importaci&oacute;n completada correctamente");?></h2>                                          

    </div>

</div>

<div class="row-fluid">

    <div class="span12">

        <div class="block">
            <div class="data-fluid">
                <table class="table aTable" cellpadding="0" cellspacing="0" width="100%">
                    <thead>
                        <tr>  
                            <th width="35%"><b><?php echo custom_lang('sima_total_data', "Total de datos");?></b></th>
                            <th width="35%"><b><?php echo custom_lang('sima_import_success', "Importaciones correctas");?></b></th>
                            <th width="35%"><b><?php echo custom_lang('sima_import_failure', "Importaciones incorrectas");?></b></th>
                        </tr>
                    </thead>
                    <tbody>
                        <td><?php echo $data["count"]; ?></td>
                        <td><?php echo $data["adicionados"]; ?></td>
                        <td><?php echo $data["noadicionados"]; ?></td>   
                    </tbody>
                </table>
            </div>
            <?php if(!empty($data["errores_importar"])){ ?>
            <div class="data-fluid">
                 <div><br><b>NOTA:</b> No se insertaron las siguientes licencias:</div>
                <div class="list-group-item list-group-item-danger"><?php echo $data["errores_importar"]; ?></div>                
            </div>
            <?php } ?>
            <div><br><button class="btn btn-default"  type="button" onclick="javascript:location.href='../licencia_empresa'"><?php echo custom_lang('sima_cancel', "Volver");?></button></div>
        </div>

    </div>

    

</div>