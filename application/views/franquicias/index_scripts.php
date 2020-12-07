<?php

// Obtengo permisos del usuario.
$permisos = $this->session->userdata('permisos');
// Pregunto si es administrador o no.
$is_admin = $this->session->userdata('is_admin');

?>
<script type="text/javascript">

jQuery(document).ready(function(){

    jQuery('#franquiciasTable').dataTable( {
        "bProcessing": true,
        //"bServerSide": true,
        "sAjaxSource": "<?php echo site_url("franquicias/get_ajax_data");?>",
        "sPaginationType": "full_numbers",
        "iDisplayLength": 10,
        "aLengthMenu": [5,10,25,50,100],
        "aoColumnDefs" : [{ "bSortable": false, "aTargets": [ 0 ], "bSearchable": false }/*,
                        {  

                            "bSortable": false, "aTargets": [ 2 ], "bSearchable": false, 
                            "mRender": function ( data, type, row ) {
                                var buttons = '';
                                <?php //if(in_array('4', $permisos) || $is_admin == 't'):?>
                                    buttons += '<a href="<?php //echo site_url("franquicias/editar/");?>/'+data+'" class="button green"><div class="icon"><span class="ico-pencil"></span></div></a>';
                                <?php //endif;?> 
                                <?php //if(in_array('5', $permisos) || $is_admin == 't'):?>
                                    buttons += '<a href="<?php //echo site_url("franquicias/eliminar/");?>/'+data+'" class="button red"><div class="icon"><span class="ico-remove"></span></div></a>';									
                                <?php //endif;?>
                                return buttons;
                            } 
                        }*/]           
    });
});

</script>