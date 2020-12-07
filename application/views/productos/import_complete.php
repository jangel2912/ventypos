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
                              
							   <th width="35%"><?php echo custom_lang('sima_total_data', "Total de datos");?></th>

                                <th width="35%"><?php echo custom_lang('sima_import_success', "Importaciones correctas");?></th>


                            </tr>

                        </thead>
		
                        <tr>

                            <td><?php echo ($total); ?></td>

                            <td><?php echo ($total_correctos-1); ?></td>                 
		
                        </tr>

                    </table>

                  </div>

        </div>

    </div>

    

</div>