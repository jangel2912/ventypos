<?php
$ci =&get_instance();
$ci->load->model("opciones_model");
$moneda =$ci->opciones_model->getDataMoneda();
?>

<style type="text/css">
    .ui-dialog{
        z-index: 9000!important;
    }
    .site-footer{
        display: none;
    }
    table{
        color:#000;
    }

    .tamano_letra{
        font-size:14px;
    }

    b{
        font-weight: 600;
    }
    table {
        width: 100%;
    }
</style>

<?php echo $html; ?>
