<?php
$primero = 1;
foreach ($data['definecrear'] as $key => $value) {
    $primero = $key;
    break;
}

if (isset($data["tipo_negocio"]) && $data["tipo_negocio"] != "restaurante") {
    unset($data["permisos"]["1036"]);
    unset($data["permisos"]["1037"]);
    unset($data["permisos"]["1038"]);
}

//cierre de caja
// print_r($data['empresa']); die();
$cierre_caja = ($data['empresa']['data']['cierre_automatico'] == 0) ? "0" : "1";
$valor_caja = ($data['empresa']['data']['valor_caja'] == 'no') ? "no" : "si";

if ($valor_caja == "no") {
    echo '
        <style>
            .cierre_automatico{
                display:none;
            }
        </style>';
}

?>

<script>
var licencia;
var dataepa = {};
</script>

<style>
    img {
        max-width: 100%; /* This rule is very important, please do not ignore this! */
    }

     /* General Styles */
    .m-5{margin:5px;}
    .p-5{padding:5px; box-sizing:border-box;}
    .mb-10{margin-bottom:10px !important;}
    .checker{
        float: left;
        text-align: left;
    }
    .contListas{
        padding: 10px;
    }

    .well{
        padding: 0px !important;
        background-color: #f7f7f7 !important;
    }

    .well > div{
        display: block;
        width: 100%;
    }

    .listasCont{
        padding: 15px;
        background-color: #fff;
    }

    .listasCont div{
        margin-bottom: 10px;
    }

    .listasCont div.checker span, .listasCont div.radio span {
        background-image: none !important;
    }
    .listasCont span.switchery, .listasCont .checker {
        width: 40px;
        margin-right: 10px;
    }
    .switchery > small {
        width: 18px;
        height: 18px;
    }

    .no-padding {
        padding:0;
    }
    .no-margin {
        margin:0 !important;
    }
    .no-border {
        border:none !important;
    }

    .link_session{
        color: red;
        /* text-decoration: underline; */
        background-color: #fff;
        padding: 2px 5px 2px 5px;
    }
    .panel-config .panel-default {
        margin-bottom:5px !important;
    }

    .panel-group .panel-title:before,.panel-group .panel-title:after {
        content: none !important;
    }

    .panel-config .panel-collapase ul
    {
        margin:0px;
        border-bottom:1px solid #ccc;
    }
    .panel-config .panel-collapase li {
        padding:5%;
        height:35px;
        list-style:none;
        background-color:#fafafa;
        border-bottom:1px solid #e0e8e9;
    }
    .panel-config .panel-collapase li a:hover,panel-config .panel-collapase li a:focus {
        text-decoration: none !important;
        font-weight:bold;
        cursor:pointer;

    }

    .panel-config .panel-title {
        text-transform:uppercase;
        font-weight:bold;
    }

    .control-check{
        display: flex !important;
        align-items:center;
    }

    .control-check span{
        margin-right:5px;
    }

    .alert-danger-red{
        background-color:#a94442
    }

    .datos-facturas{margin-top:2px; border-left: 6px solid #dee222;}
    .datos-facturas b{font-weight: 800;}
    .alert-datos-facturas{background-color:#FFF; color:#333;}
    .link-datos-facturas{color:#dee222; font-weight:bold;}
    .icon-datos-facturas{color:#dee222;}

    /* Clases para el switch */
    .switch {
        position: relative;
        display: inline-block;
        width: 50px;
        height: 22px;
    }

    .switch input {display:none;}

    .slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #ccc;
    -webkit-transition: .4s;
    transition: .4s;
    }

    .slider:before {
    position: absolute;
    content: "";
    height: 16px;
    width: 16px;
    left: 3px;
    bottom: 3px;
    background-color: white;
    -webkit-transition: .4s;
    transition: .4s;
    }

    input:checked + .slider {
    background-color: #2196F3;
    }

    input:focus + .slider {
    box-shadow: 0 0 1px #2196F3;
    }

    input:checked + .slider:before {
    -webkit-transform: translateX(26px);
    -ms-transform: translateX(26px);
    transform: translateX(26px);
    }

    /* Rounded sliders */
    .slider.round {
    border-radius: 34px;
    }

    .slider.round:before {
    border-radius: 50%;
    }

    .admin_shop{
        cursor:pointer;
    }
    .form-horizontal .control-label span{
        color:red;
    }

    .bs-callout-success {border-left-color: #4cae4c !important;}
    .bs-callout {padding: 2px;border: 1px solid #eee;border-left-width: 5px;border-radius: 3px;}
    .bs-callout .title_api_key{color:#4cae4c; margin-left: 5px;}
    .content-table{ cursor:pointer; border: solid 1px lightgray;display: flex;align-items: center;padding: 5px;border-radius: 9px;}
    .active-table{ border: 2px solid #5ca745 !important;box-shadow: 0 0 5px #5ca745;}
</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropper/4.0.0/cropper.min.css">
    <?php
$ip = "";
$paisip = "Colombia";
//busco la ip
if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    $ip = $_SERVER['HTTP_CLIENT_IP'];
}
if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
} else {
    $ip = $_SERVER['REMOTE_ADDR'];
}

$res = file_get_contents('https://www.iplocate.io/api/lookup/' . $ip);
$res = json_decode($res);
$paisip = !isset($res) || is_null($res) ? null : $res->country;

if (empty($paisip)) {
    $paisip = "Colombia";
}

$id_db_config = (!empty($this->session->userdata('db_config_id'))) ? $this->session->userdata('db_config_id') : 0;
if ($id_db_config == '11152') {
    $paisip = "Colombia1";
}

?>
<div class="row-fluid">

    <?php
if (!empty($this->session->flashdata('message')) && $this->session->flashdata('message') != "") {?>
        <div class="alert alert-success text-center">
        <?php echo $this->session->flashdata('message'); ?>
        </div>
    <?php }?>

    <?php
if (!empty($this->session->flashdata('error')) && $this->session->flashdata('error') != "") {?>
        <div class="alert alert-danger-red text-center">
        <?php echo $this->session->flashdata('error'); ?>
        </div>
    <?php }?>

    <div class="col-md-12 no-padding tab-config">
        <div class="col-md-3 no-padding">
            <div class="panel-group panel-config" id="configuration">
                <div class="panel panel-default no-padding no-margin tab_config tab_config">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <img alt="Configuración inicial"  src="<?php echo base_url('/uploads/iconos/Negro/'); ?>/icono_negro-12.svg">
                            <a data-toggle="collapse" href="#collapseOne" data-parent="#configuration">
                                Configuracion inicial
                            </a>
                        </div>
                    </div>
                    <div id="collapseOne" class="panel-collapase collapse">
                        <ul>
                            <li><a href="#tab-1" data-pane="tab-1" data-src="https://player.vimeo.com/video/266923686?color=ffffff&title=0&byline=0&portrait=0" data-toggle="tab" aria-expanded="true">Datos de mi empresa</a></li>
                            <li><a href="#tab-2" data-pane="tab-2" data-src="https://player.vimeo.com/video/266924158?color=ffffff&title=0&byline=0&portrait=0" data-toggle="tab" aria-expanded="true">Impresiones a usar</a></li>
                            <li><a href="#tab-3" data-pane="tab-3" data-toggle="tab" aria-expanded="true">Consecutivos</a></li>
                            <li><a href="#tab-4" data-pane="tab-4" data-src="https://player.vimeo.com/video/266924283?color=ffffff&title=0&byline=0&portrait=0" data-toggle="tab" aria-expanded="true">Mis impuestos</a></li>
                            <li><a href="<?php echo site_url('forma_pago/index'); ?>" aria-expanded="true">Listado de formas de pago</a></li>
                            <li><a href="#tab-facturacion" data-pane="tab-facturacion" data-toggle="tab" aria-expanded="true">Datos Facturación Licencias</a></li>
                        </ul>
                    </div>
                </div>
                <div class="panel panel-default no-padding no-margin tab_config">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <img alt="Usuarios"  src="<?php echo base_url('/uploads/iconos/Negro/'); ?>/icono_negro-13.svg">
                            <a data-toggle="collapse" data-action="collapse" href="#collapseTwo">
                                Usuarios del Sistema
                            </a>
                        </div>
                    </div>
                    <div id="collapseTwo" class="panel-collapase collapse">
                        <ul>
                            <li><a href="#tab-5" data-pane="tab-5"  data-src='https://player.vimeo.com/video/266923841?loop=1&color=ffffff&title=0&byline=0&portrait=0' data-toggle="tab">Mis Usuarios</a></li>
                            <li><a href="#tab-9" data-pane="tab-6"  data-src='https://player.vimeo.com/video/266773865?color=ffffff&title=0&byline=0&portrait=0' data-toggle="tab">Roles</a></li>
                        </ul>
                    </div>
                </div>
                <div class="panel panel-default no-padding no-margin tab_config">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <img alt="Almacenes"  src="<?php echo base_url('/uploads/iconos/Negro/'); ?>/icono_negro-14.svg">
                            <a data-toggle="collapse" href="#collapseThree">
                                Almacenes
                            </a>
                        </div>
                    </div>
                    <div id="collapseThree" class="panel-collapase collapse">
                        <ul>
                            <li><a data-toggle="tab" href="#tab-6">Mis Almacenes</a></li>
                            <?php if ($data['tengo_licencia']) {?>
                            <li><a data-toggle="tab" href="#tab-11">Mis Bodegas</a></li>
                            <?php }?>
                            <li><a data-toggle="tab" href="#tab-10" data-pane="tab-10" data-src="https://player.vimeo.com/video/266923776?color=ffffff&title=0&byline=0&portrait=0">Cajas</a></li>
                        </ul>
                    </div>
                </div>
                 <div class="panel panel-default no-padding no-margin tab_config">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <img alt="Importaciones"  src="<?php echo base_url('/uploads/iconos/Negro/'); ?>/icono_negro-15.svg">
                            <a data-toggle="collapse" href="#collapseFour">
                                Importaciones
                            </a>
                        </div>
                    </div>
                    <div id="collapseFour" class="panel-collapase collapse">
                        <ul>
                            <li><a data-toggle="tab" href="#tab-7">Importar productos</a></li>
                            <li><a data-toggle="tab" href="#tab-upload-images">Subir zip de imágenes</a></li>
                        </ul>
                    </div>
                </div>

                <!--
                <?php if ($data["tienda"] == "si") {?>
                     <div class="panel panel-default no-padding no-margin tab_config">
                     <div class="panel-heading">
                         <div class="panel-title">
                             <img alt="tienda"  src="<?php echo base_url('/uploads/iconos/Negro/'); ?>/icono_negro-16.svg">
                             <a id="admin_shop" class="admin_shop">
                                 Administración de tienda
                             </a>
                         </div>
                     </div>
                 </div>
                <?php }?>-->



                <?php
$option = "";
$propina = "";
$eliminar_producto_comanda = "";
$cierre_caja_mesas_abiertas = "";
$permitir_formas_pago_pendiente = "";
$comanda_virtual = "";
foreach ($data["opciones"] as $opcion) {
    if ($opcion["nombre_opcion"] == "sobrecosto") {
        $propina = $opcion["valor_opcion"];
    }

    if ($opcion["nombre_opcion"] == "eliminar_producto_comanda") {
        $eliminar_producto_comanda = $opcion["valor_opcion"];
    }

    if ($opcion["nombre_opcion"] == "cierre_caja_mesas_abiertas") {
        $cierre_caja_mesas_abiertas = $opcion["valor_opcion"];
    }

    if ($opcion["nombre_opcion"] == "permitir_formas_pago_pendiente") {
        $permitir_formas_pago_pendiente = $opcion["valor_opcion"];
    }

    if ($opcion["nombre_opcion"] == "domicilios") {
        $domicilios = $opcion["valor_opcion"];
    }

    if ($opcion["nombre_opcion"] == "comanda_virtual") {
        $comanda_virtual = $opcion["valor_opcion"];
    }

    if ($opcion["nombre_opcion"] == "enviar_valor_inventario") {
        $enviar_valor_inventario = $opcion["valor_opcion"];
    }

    if ($opcion["nombre_opcion"] == "correo_valor_inventario") {
        $correo_valor_inventario = $opcion["valor_opcion"];
    }

    if ($opcion["nombre_opcion"] == "stock_historico") {
        $stock_historico = $opcion["valor_opcion"];
    }

    if ($opcion["nombre_opcion"] == "correo_stock_historico") {
        $correo_stock_historico = $opcion["valor_opcion"];
    }

    if ($opcion["nombre_opcion"] == "impresion_rapida") {
        $impresion_rapida = $opcion["valor_opcion"];
    }

    if ($opcion["nombre_opcion"] == "nueva_impresion_rapida") {
        $nueva_impresion_rapida = $opcion["valor_opcion"];
    }

    if ($opcion["nombre_opcion"] == "puntos_leal") {
        $puntos_leal = $opcion["valor_opcion"];
    }

    if ($opcion["nombre_opcion"] == "usuario_puntos_leal") {
        $usuario_puntos_leal = $opcion["valor_opcion"];
    }

    if ($opcion["nombre_opcion"] == "contraseña_puntos_leal") {
        $contraseña_puntos_leal = $opcion["valor_opcion"];
    }

    if ($opcion["nombre_opcion"] == "tipo_negocio") {
        if ($opcion["valor_opcion"] == "restaurante") {
            $option = "restaurante";?>
                                <div class="panel panel-default no-padding no-margin tab_config">
                                    <div class="panel-heading">
                                        <div class="panel-title">
                                            <img alt="restaurante"  src="<?php echo base_url('/uploads/iconos/Negro/'); ?>/iconos vendty_Restaurantes.svg">
                                            <a data-toggle="collapse" href="#collapseSix">
                                                Restaurante
                                            </a>
                                        </div>
                                    </div>
                                    <div id="collapseSix" class="panel-collapase collapse">
                                        <ul>
                                            <li><a href="<?php echo site_url('impresoras_restaurante/index'); ?>" aria-expanded="true">Impresoras</a></li>
                                            <li><a href="<?php echo site_url('mesas_secciones/index'); ?>" aria-expanded="true">Mesas</a></li>
                                            <li><a href="#tab-style-tables" data-toggle="tab" aria-expanded="true">Estilo de mesas</a></li>
                                            <li><a href="#tab-config-table" data-toggle="tab" aria-expanded="true">Configuraciones Restaurante</a></li>
                                        </ul>
                                    </div>
                                </div>

                            <?php
} elseif ($opcion["valor_opcion"] == "moda") {
            $option = "moda";?>
                                <div class="panel panel-default no-padding no-margin">
                                    <div class="panel-heading">
                                        <div class="panel-title">
                                            <a href="<?php echo site_url('atributos/index'); ?>">
                                                <span class="glyphicon glyphicon-blackboard"></span>
                                                Atributos
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            <?php
} elseif ($opcion["valor_opcion"] == "retail") {
            $option = "retail";
        }
        ;
    }
}
?>


                <div class="panel panel-default no-padding no-margin tab_config">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <img alt="Woocommerce"  src="<?php echo base_url('/uploads/iconos/Negro/'); ?>/icono_negro-17.svg">
                            <a data-toggle="tab" href="#integraciones">
                                Integraciones
                            </a>
                        </div>
                    </div>
                    <div id="collapseFive" class="panel-collapase collapse">
                        <ul>
                            <li><a>Integraciones</a></li>
                        </ul>
                    </div>
                </div>

                <?php
$db_id = $this->session->userdata('db_config_id');
if (($db_id != '8962') && ($db_id != '17911')) {?>
                <div class="panel panel-default no-padding no-margin tab_config">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <img alt="Reiniciar"  src="<?php echo base_url('/uploads/iconos/Negro/'); ?>/icono_negro-17.svg">
                            <a href="<?php echo site_url('restablecer/index'); ?>">
                                Reiniciar sistema
                            </a>
                        </div>
                    </div>
                    <div id="collapseFive" class="panel-collapase collapse">
                        <ul>
                            <li><a>Reiniciar Sistema</a></li>
                        </ul>
                    </div>
                </div>
                <?php }?>
                <div class="panel panel-default no-padding no-margin tab_config">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <?php
if (count($data['plan_cliente'])) {
    foreach ($data['plan_cliente'] as $plan_cliente) {
        $tipo_cliente = $plan_cliente['planes_id'];
        break;
    }
} else {
    $tipo_cliente = 0;
}
?>
                            <input id="plan_cliente_id" type="hidden"  value="<?php echo $tipo_cliente; ?>">
                             <img alt="Licencias"  src="<?php echo base_url('/uploads/iconos/Negro/'); ?>/iconos vendty_Mis licencias.svg">
                            <a href="#tab-licencias" id="link_licencias" data-toggle="tab" >
                                Mis Licencias
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-9 pane-open">
            <?php
$estado = $this->session->flashdata('estado');

if ($estado != '') {
    if ($this->session->flashdata('estado') == 'ok') {?>
                <div class="alert alert-success alert-msg">
            <?php } else {?>
                <div class="alert alert-error alert-msg">
                <?php }
    echo $this->session->flashdata('upload_status');?>
                </div>
             <?php
}?>
            <div class="tab-content">
                <div class="tab-pane fade in" id="tab-facturacion">
                    <div class="row-fluid">
                        <div class="span12">
                            <button id="btn_copy_empresa" class="btn btn-success">Copiar Datos de mi Empresa</button>
                        </div>

                        <?php echo form_open("frontend/configuracion", array("id" => "validate")); ?>
                                <!-- Formulario empresa -->
                                <div class="span6">
                                    <div class="row-form">
                                        <div class="span4"><?php echo custom_lang('sima_name', "Nombre Empresa"); ?>:</div>
                                        <div class="span8"><input type="text"  value="<?php echo $data['factura'][0]['nombre_empresa']; ?>" required name="nombre_empresa" id="nombre_empresa_factura" >
                                                <?php echo form_error('nombre_empresa'); ?>
                                        </div>
                                    </div>

                                    <div class="row-form">
                                        <div class="span4"><?php echo custom_lang('sima_name', "Tipo Documento"); ?>:</div>
                                        <div class="span8">
                                            <select name="tipo_identificacion" id="tipo_identificacion_factura" required data-value="<?php echo set_value('tipo_identificacion', $data['factura'][0]['tipo_identificacion']) ?>">
                                                <?php
if (($data['factura'] == 0)) {
    echo '<option value="" selected >Seleccione</option>';
}

foreach ($data['tipo_identificacion'] as $key => $value) {
    echo '<option value="' . $key . '">' . $value . '</option>';
}
?>
                                            </select>
                                            <?php echo form_error('tipo_identificacion'); ?>
                                        </div>
                                    </div>

                                    <div class="row-form">
                                        <div class="span4"><?php echo custom_lang('sima_identificacion_empresa', "N° Identificación"); ?>:</div>
                                        <div class="span8"><input type="text"  value="<?php echo $data['factura'][0]['numero_identificacion']; ?>" required id="numero_identificacion_factura" name="numero_identificacion" >
                                        <?php echo form_error('numero_identificacion'); ?>
                                        </div>
                                    </div>
                                    <div class="row-form">
                                        <div class="span4"><?php echo custom_lang('sima_pais', "Pais"); ?>:</div>
                                        <div class="span8">
                                            <select name="pais_factura" id="pais_factura" required data-value="<?php echo set_value('pais_factura', $data['factura'][0]['pais']) ?>">
                                                <?php
if (($data['factura'] == 0)) {
    echo '<option value="" selected >Seleccione</option>';
}

foreach ($data['paises'] as $key => $value) {
    echo '<option value="' . $value . '">' . $value . '</option>';
}
?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row-form">
                                        <div class="span4"><?php echo custom_lang('sima_pais', "Ciudad"); ?>:</div>
                                        <div class="span8">
                                            <select name="ciudad_factura" id="ciudad_factura" required data-value="<?php echo set_value('ciudad_factura', $data['factura'][0]['ciudad']) ?>">
                                                <?php
if (($data['factura'] == 0)) {
    echo '<option value="" selected >Seleccione</option>';
}
echo '<option value="' . $data['factura'][0]['ciudad'] . '">' . $data['factura'][0]['ciudad'] . '</option>';
?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="span6">
                                    <div class="row-form">
                                        <div class="span4"><?php echo custom_lang('sima_addres', "Direcci&oacute;n"); ?>:</div>
                                        <div class="span8">
                                        <input type="text" value="<?php echo set_value('direccion_factura', $data['factura'][0]['direccion']) ?>" id="direccion_factura" name="direccion_factura" required >
                                            <?php echo form_error('direccion_factura'); ?>
                                        </div>
                                    </div>
                                    <div class="row-form">
                                        <div class="span4"><?php echo custom_lang('sima_name', "Nombre contacto"); ?>:</div>
                                        <div class="span8"><input type="text"  value="<?php echo $data['factura'][0]['contacto']; ?>" required  id="contacto_factura" name="contacto_factura" >
                                                <?php echo form_error('contacto_factura'); ?>
                                        </div>
                                    </div>
                                    <div class="row-form">
                                        <div class="span4"><?php echo custom_lang('sima_phone', "Tel&eacute;fono"); ?>:</div>
                                        <div class="span8"><input type="text" value="<?php echo set_value('telefono_factura', $data['factura'][0]['telefono']); ?>" id="telefono_factura" name="telefono_factura" required >
                                            <?php echo form_error('telefono_factura'); ?>
                                        </div>
                                    </div>
                                    <div class="row-form">
                                        <div class="span4"><?php echo custom_lang('sima_phone', "Correo Electónico"); ?>:</div>
                                        <div class="span8"><input type="text" value="<?php echo set_value('correo_factura', $data['factura'][0]['correo']); ?>" id="correo_factura" name="correo_factura" required >
                                            <?php echo form_error('correo_factura'); ?>
                                        </div>
                                    </div>
                                </div>
                                <!-- Fin -->

                                 <div class="row-fluid datos-facturas">
                                    <div class="alert alert-datos-facturas"  role="alert">
                                        <b>Nota:</b>
                                        <p>Las facturas de pagos en Vendty se generarán de forma automáticas a partir del próximo mes,
                                            por eso te pedimos que actualices tu información tributaria para las próximas facturas.
                                            <br> Muchas Gracias.
                                        </p>
                                        <!--<button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="false" class="error-config">&times;</span>
                                        </button>-->

                                    </div>
                                </div>

                                <div class="span12">
                                    <input type='hidden' name='form' value='facturacion'>
                                </div>
                                <div class="span12">
                                <div class="btn-group">
                                    <div class="span6">
                                        <button class="btn btn-default" type="button" onclick="javascript:location.href='../frontend/configuracion'">Cancelar</button>
                                    </div>
                                    <div class="span6">
                                        <button class="btn btn-success" type="submit" onclick="savelocal()" id="form_update_business">Guardar</button>
                                    </div>
                                </div>
                                </div>
                            </form>
                    </div>
                </div>
                <div class="tab-pane fade in active" id="tab-1">
                    <div class="row-fluid">
                        <?php echo form_open_multipart("frontend/configuracion", array("id" => "validate")); ?>
                                <!-- Formulario empresa -->
                                <div class="span6">
                                    <div class="row-form">
                                        <div class="span4"><?php echo custom_lang('sima_name', "Nombre"); ?>:</div>
                                        <div class="span8"><input type="text"  value="<?php echo $data['empresa']['data']['nombre']; ?>" placeholder="" name="nombre" />
                                                <?php echo form_error('nombre'); ?>
                                        </div>
                                    </div>
                                    <div class="row-form">
                                        <div class="span4"><?php echo custom_lang('sima_name', "Tipo Documento"); ?>:</div>
                                        <div class="span8"><input type="text"  value="<?php echo $data['empresa']['data']['documento']; ?>" placeholder="Tipo de documento" name="documento" />
                                        <?php echo form_error('documento'); ?>
                                        </div>
                                    </div>

                                    <div class="row-form">
                                        <div class="span4"><?php echo custom_lang('sima_identificacion_empresa', "Numero Documento"); ?>:</div>
                                        <div class="span8"><input type="text"  value="<?php echo $data['empresa']['data']['nit']; ?>" placeholder="" name="nit" />
                                        <?php echo form_error('nit'); ?>
                                        </div>
                                    </div>
                                    <div class="row-form">
                                        <div class="span4"><?php echo custom_lang('sima_pais', "Pais"); ?>:</div>
                                        <div class="span8">
                                            <select name="pais" id="pais" data-value="<?php echo set_value('pais', $data['pais']) ?>">
                                                 <?php
foreach ($data['paises'] as $key => $value) {
    echo '<option value="' . $key . '">' . $value . '</option>';
}
?>
                                            </select>

                                        </div>
                                    </div>
                                    <div class="row-form">

                                        <div class="span4"><?php echo custom_lang('sima_money', "Moneda"); ?>:</div>

                                        <div class="span8">
                                            <select name="moneda" id="monedas" data-value="<?php echo $data['empresa']['data']['moneda'] ?>">
                                                    <?php
foreach ($data['moneda'] as $key => $value) {
    echo '<option value="' . $key . '">' . $value . '</option>';
}
?>
                                            </select>

                                        </div>

                                    </div>

                                    <div class="row-form">
                                        <div class="span4"><?php echo custom_lang('sima_type_business', "Tipo de negocio"); ?>:</div>
                                        <div class="span4">
                                                <label class="radio">
                                                    <input type="radio"  name="tipo_negocio" id="tipo_negocio" value="retail" <?php echo ($option == "retail") ? 'checked' : ''; ?>> Retail
                                                    <a id="pop_retail" href="#" data-container="body" data-content="Se aplicara la configuración a la medida de su negocio." rel="popover" data-placement="right" data-original- data-trigger="hover">
                                                        <span class="glyphicon glyphicon-question-sign icon-help-config" aria-hidden="true"></span>
                                                    </a>
                                                </label>

                                                <label  class="radio">
                                                    <input type="radio" class="check_restaurant" name="tipo_negocio" id="tipo_negocio" value="restaurante" <?php echo ($option == "restaurante") ? 'checked' : ''; ?>> Restaurante

                                                    <a id="pop_restaurante" href="#" data-container="body" data-content="Se activaran la opciones de mesas en el modulo vender y en la configuración del sistema." rel="popover" data-placement="right" data-original- data-trigger="hover">
                                                        <span class="glyphicon glyphicon-question-sign icon-help-config" aria-hidden="true"></span>
                                                    </a>
                                                </label>

                                                <label class="radio">
                                                    <input type="radio" name="tipo_negocio" id="tipo_negocio" value="moda" <?php echo ($option == "moda") ? 'checked' : ''; ?>> Moda
                                                    <a id="pop_moda" href="#" data-container="body" data-content="Se activara la opcion Nuevo producto con atributos en el modulo de productos y se activaran los atributos en la configuración del sistema." rel="popover" data-placement="right" data-original- data-trigger="hover">
                                                        <span class="glyphicon glyphicon-question-sign icon-help-config" aria-hidden="true"></span>
                                                    </a>
                                                </label>

                                                <label class="radio hide">
                                                    <input type="radio" name="tipo_negocio" id="tipo_negocio" value="">Actual
                                                </label>
                                        </div>
                                    </div>
                                    <?php if ($option == 'restaurante'): ?>
                                       <!-- <div class="row-form">
                                            <div class="span12 control-check">
                                                <input type="checkbox" name="eliminar_producto_comanda" id="eliminar_producto_comanda" <?php echo ($eliminar_producto_comanda == "si") ? 'checked' : ''; ?>>
                                                <span>Permitir eliminar productos con comanda </span>
                                                <a id="pop_productos_comanda" href="#" data-container="body" data-content="Se activará la opción Eliminar Productos cuando se tome un pedido con comanda." rel="popover" data-placement="right" data-original- data-trigger="hover">
                                                    <span class="glyphicon glyphicon-question-sign icon-help-config" aria-hidden="true"></span>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="row-form">
                                            <div class="span12 control-check">
                                                <input type="checkbox"  name="permitir_formas_pago_pendiente" id="permitir_formas_pago_pendiente" value="permitir_formas_pago_pendiente" <?php echo ($permitir_formas_pago_pendiente == "si") ? 'checked' : ''; ?>>
                                                <span>Permitir Formas de Pagos Pendiente en Facturación </span>
                                                <a id="pop_permitir_pagos_pendientes" href="#" data-container="body" data-content="Se activará la opción para permitir dejar pendientes las formas de pagos en la facturación." rel="popover" data-placement="right" data-original- data-trigger="hover">
                                                    <span class="glyphicon glyphicon-question-sign icon-help-config" aria-hidden="true"></span>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="row-form">
                                            <div class="span12 control-check">
                                                <input type="checkbox"  name="domicilios" id="domicilios" value="domicilios" <?php echo ($domicilios == "si") ? 'checked' : ''; ?>>
                                                <span>Desea activar Domicilios </span>
                                                <a id="pop_domicilios" href="#" data-container="body" data-content="Se activará la opción para permitir dejar pendientes las formas de pagos en la facturación." rel="popover" data-placement="right" data-original- data-trigger="hover">
                                                    <span class="glyphicon glyphicon-question-sign icon-help-config" aria-hidden="true"></span>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="row-form">
                                            <div class="span12 control-check">
                                                <input type="checkbox"  name="quick_service" id="quick_service" value="quick_service" <?php echo (isset($data['quick_service']) && $data['quick_service'] == "si") ? 'checked' : ''; ?>>
                                                <span>Quick service</span>
                                                <a id="pop_quick_service" href="#" data-container="body" data-content="Se activará la opción para vender como quick service." rel="popover" data-placement="right" data-original- data-trigger="hover">
                                                    <span class="glyphicon glyphicon-question-sign icon-help-config" aria-hidden="true"></span>
                                                </a>

                                                <span id="content_quick_service_command" style="display: flex;align-items: center;">
                                                    <input type="checkbox"  name="quick_service_command" id="quick_service_command" value="quick_service_command" <?php echo (isset($data['quick_service_command']) && $data['quick_service_command'] == "si") ? 'checked' : ''; ?>>
                                                    <span>Comanda</span>
                                                    <a id="pop_quick_service_command" href="#" data-container="body" data-content="Generar comanda desde el Quick Service" rel="popover" data-placement="right" data-original- data-trigger="hover">
                                                        <span class="glyphicon glyphicon-question-sign icon-help-config" aria-hidden="true"></span>
                                                    </a>
                                                </span>
                                            </div>
                                        </div>-->
                                    <?php endif;?>

                                    <div class="row-form">
                                        <div class="span12 control-check">
                                            <input type="checkbox"  name="nueva_impresion_rapida" id="nueva_impresion_rapida" value="nueva_impresion_rapida" <?php echo ($nueva_impresion_rapida == "si") ? 'checked' : ''; ?>>
                                            <span>Impresión Rápida</span>
                                            <a id="pop_nueva_impresion_rapida" href="#" data-container="body" data-content="Se activará la opción para imprimir rapidamente cuando se haga una venta." rel="popover" data-placement="right" data-original- data-trigger="hover">
                                                <span class="glyphicon glyphicon-question-sign icon-help-config" aria-hidden="true"></span>
                                            </a>
                                            <a target="_blank" href="https://ayuda.vendty.com/es/articles/3222326-configurar-nueva-impresion-rapida">Cómo imprimo de forma rápida?</a>
                                        </div>
                                    </div>
                                    <div class="row-form">
                                        <div class="span12">
                                            <div class="bs-callout bs-callout-success content-apikey hidden">
                                                 <span class="description-api-key"><span class="title_api_key">API KEY:</span>
                                                 <span class="api_key"><?=$data["apikey"];?></span></span>
                                                 <a class="btn btn-success reiniciar">Reiniciar</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row-form">
                                        <div class="span12 control-check">
                                            <input type="checkbox"  name="enviar_valor_inventario" id="enviar_valor_inventario" value="enviar_valor_inventario" <?php echo ($enviar_valor_inventario == "si") ? 'checked' : ''; ?>>
                                            <span>Desea activar el envío del valor del inventario los últimos de cada mes</span>
                                            <a id="pop_inventario" href="#" data-container="body" data-content="Se activará la opción para enviar en excel el valor del inventario todos los últimos de cada mes." rel="popover" data-placement="right" data-original- data-trigger="hover">
                                                <span class="glyphicon glyphicon-question-sign icon-help-config" aria-hidden="true"></span>
                                            </a>
                                        </div>
                                    </div>
                                    <?php $oculto = ($enviar_valor_inventario == "si") ? '' : 'hidden'?>
                                    <div class="row-form correo_valor_inventario <?=$oculto?>">
                                        <div class="span12 control-check">
                                            <span>El archivo será enviado al correo:</span>
                                            <input type="email"  name="correo_valor_inventario" id="correo_valor_inventario" value="<?php echo (!empty($correo_valor_inventario)) ? $correo_valor_inventario : ''; ?>">
                                        </div>
                                    </div>

                                    <div class="row-form">
                                        <div class="span12 control-check">
                                            <input type="checkbox"  name="stock_historico" id="stock_historico" value="stock_historico" <?php echo ($stock_historico == "si") ? 'checked' : ''; ?>>
                                            <span>Desea activar el envío de las existencias del inventario todos los días</span>
                                            <a id="pop_inventario_historico" href="#" data-container="body" data-content="Se activará la opción para enviar en excel las existencias del inventario todos los días." rel="popover" data-placement="right" data-original- data-trigger="hover">
                                                <span class="glyphicon glyphicon-question-sign icon-help-config" aria-hidden="true"></span>
                                            </a>
                                        </div>
                                    </div>
                                    <?php $oculto = ($stock_historico == "si") ? '' : 'hidden'?>
                                    <div class="row-form correo_stock_historico <?=$oculto?>">
                                        <div class="span12 control-check">
                                            <span>El archivo será enviado al correo:</span>
                                            <input type="email"  name="correo_stock_historico" id="correo_stock_historico" value="<?php echo (!empty($correo_stock_historico)) ? $correo_stock_historico : ''; ?>">
                                        </div>
                                    </div>

                                </div>
                                <div class="span6">
                                    <div class="row-form">
                                        <div class="span4"><?php echo custom_lang('sima_name', "Nombre de contacto"); ?>:</div>
                                        <div class="span8"><input type="text"  value="<?php echo $data['empresa']['data']['contacto']; ?>" placeholder="" name="contacto" />
                                                <?php echo form_error('nombre'); ?>
                                        </div>
                                    </div>
                                    <div class="row-form">
                                        <div class="span4"><?php echo custom_lang('sima_addres', "Direcci&oacute;n"); ?>:</div>
                                        <div class="span8">
                                        <input type="text" value="<?php echo set_value('direccion', $data['empresa']['data']['direccion']) ?>" name="direccion" placeholder="Direcci&oacute;n"/>
                                            <?php echo form_error('direccion'); ?>
                                        </div>
                                    </div>
                                    <div class="row-form">
                                        <div class="span4"><?php echo custom_lang('sima_phone', "Tel&eacute;fono"); ?>:</div>
                                        <div class="span8"><input type="text" value="<?php echo set_value('telefono', $data['empresa']['data']['telefono']); ?>" name="telefono" placeholder="Tel&eacute;fono"/>
                                            <?php echo form_error('telefono'); ?>
                                        </div>
                                    </div>
                                    <div class="row-form">
                                        <div class="span4"><?php echo custom_lang('sima_money', "Zona horaria"); ?>:</div>
                                        <div class="span8">
                                        <select name="zona_horaria" id="zona_horaria" data-value="<?php echo set_value('zona_horaria', $data['empresa']['data']['zona_horaria']) ?>">
                                            <option value="">seleccionar</option>
                                            <?php
foreach ($data['timezones'] as $key => $value) {
    echo '<option value="' . $key . '">' . $value . '</option>';
}
?>
                                        </select>
                                        </div>
                                    </div>
                                    <div class="row-form">
                                        <div class="span4"><?php echo custom_lang('sima_decimales', "Numero decimales"); ?>:</div>
                                        <div class="span8">
                                            <input type="number" min="0" max="3" name="decimales" value="<?php echo $data['decimales'] ?>">
                                        </div>
                                    </div>

                                     <div class="row-form">
                                        <div class="span4"><?php echo custom_lang('sima_avatar', "Logotipo"); ?>:<br/>
                                        </div>
                                        <div class="span8">
                                            <div class="span6 logo" style="margin-bottom: 0px !Important;">
                                                <?php
$esconder = "";
if (!empty($data['empresa']['data']['logotipo'])):
    $esconder = "hidden";
    ?>
	                                                <ul class="thumbnails">
	                                                    <li class="span12">
	                                                        <a class="thumbnail">
	                                                        <img src="<?php echo base_url("uploads/" . $data['empresa']['data']['logotipo']); ?>" alt="logotipo" height="100%" width="100%"/>
	                                                        </a>

	                                                    </li>
	                                                </ul>
	                                                     <div>
	                                                        <button class="btn btn-success eliminar_imagen" type="button">Eliminar</button>
	                                                    </div>
	                                                <?php endif;?>
                                            </div>
                                            <div class="span12">
                                            <input type="hidden" name="eliminar_logo" id="eliminar_logo" value="0">
                                            <div class="input-append file">
                                                <input type="file" name="logotipo" id="logotipo" accept="image/x-png,image/jpeg" onchange="readURL(this);"/>
                                                <button  data-toggle="modal" data-target="#exampleModal" class="btn btn-success <?=$esconder?>" type="button"><?php echo custom_lang('sima_search', "Buscar en Mi PC"); ?></button>
                                            </div>

                                            <span class="bottom">Archivos permitidos : JPG | Peso maximo: 10KB | Tamaño: 150 x 150 (px) </span>
                                            Reduce tu imagen <a href="https://www.reducirfotos.com/" target="_blank">aqui</a>
                                            <?php echo $data['data']['upload_error']; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Fin -->

                                <div class="span12">
                                    <input type='hidden' name='form' value='empresa'>
                                </div>



                                <div class="span12">
                                    <div class="btn-group">
                                        <div class="span6">
                                            <button class="btn btn-default" type="button" onclick="javascript:location.href='../frontend/configuracion'">Cancelar</button>
                                        </div>
                                        <div class="span6">
                                            <button class="btn btn-success" type="submit" id="form_update_business" onclick="savelocal()">Guardar</button>
                                        </div>
                                    </div>
                                </div>

                            </form>


                    </div>
                </div>

                <div class="tab-pane fade" id="tab-2">
                    <div class="row-fluid">
                        <?php echo form_open_multipart("frontend/configuracion", array("id" => "validate")); ?>
                            <div class="row-form">
                                <div class="span4"><?php echo custom_lang('sima_plantilla', "Tamaño"); ?>:</div>
                                <div class="span8">
                                <?php
echo form_dropdown("plantilla_general", array('media_carta' => 'Media Carta', 'tirilla' => 'Tirilla'), set_value('plantilla_general', $data['plantilla_general']), " id='cotizacion'");
?>
                                </div>
                            </div>

                            <div class="row-form">
                                <div class="span4"><?php echo custom_lang('paypal_email', "Plantilla de Factura"); ?>:</div>
                                <div class="span8">
                                    <?php
echo form_dropdown(
    "plantilla_pos",
    array(
        'ticket' => 'Ticket (Tirilla)',
        'ticket_con_descuento' => 'Ticket con Descuento Total (Tirilla)',
        'ticket_decimales' => 'Ticket con decimales (Tirilla)',
        'ticket_decimales_simple' => 'Ticket con decimales Simple (Tirilla)',
        'ticket_decimals' => 'Invoice with decimals',
        'ticket_honduras' => 'Ticket Honduras (Tirilla)',
        'ticket_2' => 'Ticket plus (Tirilla) ',
        'ticket_58N' => 'Ticket 58mm (Tirilla) ',
        'ticket_58N3' => 'Ticket 58mm (Tirilla) Información Cliente',
        'ticket_cafeterias' => 'Ticket Cafetería (Tirilla)',
        'ticket_cafeterias_decimales' => 'Ticket Cafetería Decimales (Tirilla)',
        'ticket_internacional' => 'Ticket Internacional (Tirilla)',
        'ticket_internacional_precioiva' => 'Ticket Internacional Precio con IVA (Tirilla)',
        'ticket_promocion' => 'Ticket Promoción (Tirilla)',
        'ticket_promocion_nuevo' => 'Ticket Promoción Nuevo (Tirilla)',
        'ticket_promocion_decimal' => 'Ticket Promoción Decimal (Tirilla)',
        'general' => 'Estándar (Carta)',
        'general_2' => 'Estándar 2 (Carta)',
        'moderna' => 'Moderna (Media carta)',
        'moderna_decimales' => 'Moderna Decimales (Media carta)',
        'moderna_izq' => 'Moderno Logo Izq (Media carta)',
        'moderna_izq_discriminado_iva' => 'Moderno Logo Izq Discriminando Iva (Media carta)',
        'moderna_codibarras' => 'Codigo de Barras (Media carta)',
        'moderna_logo_redondo' => 'Moderna logo redondo (Media carta)',
        'moderna_ingles' => 'Moderna en inglés (Carta)',
        'moderna_completa_ingles' => 'Clásica en inglés (Carta)',
        'modelo_factura_clasica' => 'Factura Clásica',
        'modelo_factura_clasica_descuento_total' => 'Factura Clásica con Descuento Total',
        'modelo_factura_clasica2' => 'Factura Clásica Impuestos Discriminados',
        'modelo_impresora_factura' => 'Factura Impresora (Matricial)',
        'ticket_productos_atributos' => 'Ticket producto con atributos (Tirilla)',
        'ticket_atributos_nuevo' => 'Ticket producto nuevo atributo (Tirilla)',
        'ticket_distribuidor' => 'Ticket Distribuidor',
        'ticket_dist_att' => 'Ticket Distribuidor Atributos',
        'ticket_imei' => 'Ticket Serial/Imei',
        'ticket_base_impuesto' => 'Ticket Base Impuesto (Tirilla)',
    ),
    $data['empresa']['data']['plantilla'],
    " id='plantilla_factura'"
);
?>
                                    <?php echo form_error('plantilla'); ?>
                                </div>
                            </div>
                            <div class="row-form">
                                <div class="span4"><?php echo custom_lang('paypal_email', "Titulo del documento"); ?>:</div>
                                <div class="span8"><input type="text" value="<?php echo set_value('titulo_venta', $data['empresa']['data']['titulo_venta']); ?>" name="titulo_venta" placeholder=""/>
                                    <?php echo form_error('paypal_email'); ?>
                                </div>
                            </div>
                            <div class="row-form">
                                <div class="span4"><?php echo custom_lang('paypal_email', "Cabecera"); ?>:</div>
                                <div class="span8">
                                    <textarea id="header" style="height: 200px;" name="header"><?php echo set_value('header', $data['cabecera']); ?></textarea>
                                </div>

                            </div>
                            <div class="row-form">
                                <div class="span4"><?php echo custom_lang('terms', "Términos"); ?>:</div>
                                <div class="span8">
                                    <textarea id="terms" style="height: 200px;" name="terms"><?php echo set_value('terms', $data['terminos']); ?></textarea>
                                </div>

                            </div>
                            <div class="span12">
                                <input type='hidden' name='form' value='impresion'>
                            </div>
                            <div class="btn-group">

                                <div class="span6">
                                    <button class="btn btn-default" type="button" onclick="javascript:location.href='../frontend/configuracion'">Cancelar</button>
                                </div>
                                <div class="span6">
                                    <button class="btn btn-success" type="submit">Guardar</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="tab-pane fade" id="tab-3">
                    <div class="row-fluid">
                        <?php echo form_open_multipart("frontend/configuracion", array("id" => "validate")); ?>
                            <div class="span6">
                                <div class="block title">
                                    <div class="head">
                                        <h2><?php echo custom_lang('sima_company', "Cotización"); ?></h2>
                                    </div>
                                </div>
                                <div class="row-form">
                                    <div class="span3"><?php echo custom_lang('sima_init_number', "Número de inicio"); ?>:</div>
                                    <div class="span9"><input type="text"  value="<?php echo set_value('numero_presupuesto', $data['numero_presupuesto']); ?>" placeholder="" name="numero_presupuesto" />
                                     <?php echo form_error('numero_presupuesto'); ?>
                                    </div>
                                </div>
                                <div class="row-form">
                                    <div class="span3"><?php echo custom_lang('sima_prefix', "Prefijo"); ?>:</div>
                                    <div class="span9"><input type="text" value="<?php echo set_value('prefijo_presupuesto', $data['prefijo_presupuesto']); ?>" name="prefijo_presupuesto"/>
                                        <?php echo form_error('prefijo_presupuesto'); ?>
                                    </div>
                                </div>

                            </div>
                            <div class="span6">
                                <div class="block title">
                                    <div class="head">
                                        <h2><?php echo custom_lang('sima_company', "Nota de credito"); ?></h2>
                                    </div>
                                </div>
                                <div class="row-form">

                                    <div class="span3"><?php echo custom_lang('sima_init_number', "Número de inicio"); ?>:</div>

                                    <div class="span9"><input type="text"  value="<?php echo set_value('numero_devolucion', $data['numero_devolucion']); ?>" placeholder="" name="numero_devolucion" />

                                        <?php echo form_error('numero_devolucion'); ?>

                                    </div>

                                </div>
                                <div class="row-form">

                                    <div class="span3"><?php echo custom_lang('sima_prefix', "Prefijo"); ?>:</div>

                                    <div class="span9"><input type="text" value="<?php echo set_value('prefijo_devolucion', $data['prefijo_devolucion']); ?>" name="prefijo_devolucion"/>

                                    <?php echo form_error('prefijo_devolucion'); ?>

                                    </div>

                                </div>
                            </div>
                            <div class="span12">
                                <input type='hidden' name='form' value='numeros'>
                            </div>
                            <div class="btn-group">
                                <div class="span6">
                                    <button class="btn btn-default" type="button" onclick="javascript:location.href='../frontend/configuracion'">Cancelar</button>
                                </div>
                                <div class="span6">
                                    <button class="btn btn-success" type="submit">Guardar</button>
                                </div>
                            </div>
                        </form>
                   </div>
                </div>
                <div class="tab-pane fade" id="tab-4">
                    <div class="row-fluid">
                        <div class="span12">
                            <!--<button id="btn_new_imp" class="btn btn-success">Agregar impuesto</button>-->
                            <a id="btn_new_imp" data-tooltip="Nuevo Impuesto">
                                <img alt="Impuesto" class="btnimagenes" src="<?php echo $this->session->userdata('new_imagenes')['mas_verde']['original'] ?>">
                                <!--<?php echo custom_lang('sima_new_bill', "Nueva venta"); ?>-->
                            </a>
                        </div>
                        <div class="span12">
                            <table id="impuestosTable" class="table aTable" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th width="70%"><?php echo custom_lang('sima_name', "Nombre"); ?></th>
                                        <th  class="TAC"><?php echo custom_lang('', "Predeterminado"); ?></th>
                                        <th width="20%"><?php echo custom_lang('sima_tax_percent', "Porciento"); ?></th>
                                        <th  class="TAC"><?php echo custom_lang('sima_action', "Acciones"); ?></th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>Name</th>
                                        <th>Predeterminado</th>
                                        <th>porcentaje</th>
                                        <th>Acciones</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

                <script>

                    var planes = <?php echo json_encode($data['planes']['planes']) ?>;
                    var planSeleccionado = {};
                    var numeroDeLicencias = 0;

                    function mostrarPlanes(id = false) {
                        /*api_auth = JSON.parse(localStorage.getItem('api_auth'));
                        plan_id = id ? id : api_auth.license.plan.id;
                        total = 0;
                        licencias = licenciasAPagar.split('y')
                        licencias = licencias[1].split('-')
                        numeroDeLicencias = licencias.length;
                        licencias.map(item => {
                            items = item.split('_');
                            total += parseFloat(items[items.length - 1]);
                        })

                        if(plan_id == 1) {
                            $("#panel-planes").show();
                        } else {
                            $("#panel-pagos").show();
                            mostrarSeleccionado(plan_id, total);
                        }*/
                        $("#panel-planes").show();
                        $("#panel-licencias").hide();
                    }

                    function checkLicencia(lic, idplan) {
                        licenciaSeleccionada = lic + "-" + idplan;
                    }

                    function mostrarPagos() {
                        $("#panel-pagos").show();
                        $("#panel-planes").hide();
                    }

                    function cancelarPagos() {
                        $("#panel-pagos").hide();
                        $("#panel-pagos2").hide();
                        $("#panel-planes").hide();
                        $("#panel-response").hide();
                        $("#buttons-recurrent-payment").show();
                        $("#panel-metodos").hide();
                        $("#paypal-button").html('');
                        $("#loading").hide();
                        $("#panel-licencias").show();
                        volverPlanes();
                    }

                    function closeResponse() {
                        $("#panel-response").hide();
                    }

                    function selectPaymentMethod(e) {
                        paymentMethod = $(e).val();
                        switch(paymentMethod){
                            case 'credit-card' :
                                $('#form-cash-payment').hide();
                                $('#form-bank-account').hide();
                                $('#form-credit-card').show();
                            break;
                            case 'transfer' :
                                $('#form-cash-payment').hide();
                                $('#form-bank-account').show();
                                $('#form-credit-card').hide();
                            break;
                            case 'cash' :
                                $('#form-cash-payment').show();
                                $('#form-bank-account').hide();
                                $('#form-credit-card').hide();
                            break;
                        }

                    }

                    function pagoBancario() {
                        action="<?=site_url('frontend/accountPayment')?>"
                        var api_auth = JSON.parse(localStorage.getItem("api_auth"));
                        //console.log(api_auth);
                        renovacion = "<?=site_url('responseCredit')?>";
                        primera = "<?=site_url('responseCreditDos')?>";



                        if(api_auth.license.plan.id == 1) {
                            confirmation = primera;

                        }else {
                            confirmation = renovacion;

                        }

                        licenciaEnviar = licenciasAPagar.replace(/_/g, "-");

                        /**
                         * account-name
                            doc-type
                            client-document-number
                            account-type
                            account-number
                            bank-entity
                         */

                        data = {
                            client: {
                                name: $('#account-name').val(), // vendty
                                last_name: $('#client-last-name').val(), // vendty
                                doc_type: $('#account-doc-type').val(),
                                doc_number: $('#client-document-number').val(),
                                email: api_auth.user.email, // vendty
                                address: api_auth.warehouse.address.address
                            },
                            bankAccount: {
                                account_type: $('#account-type').val(), // vendty
                                account_number: $('#account-number').val(), // vendty
                                bank_entity: $('#bank-entity').val(),
                                person_type: $('#person-type').val()
                            },
                            plan: planSeleccionado,
                            user_id: api_auth.user.id,
                            licencia: licenciaEnviar,
                            numeroDeLicencias,
                            confirmation,
                            test: $('#test').val()
                        };



                        $.ajax({
                            url: action,
                            data:  data, //datos que se envian a traves de ajax
                            type:  'post', //método de envio
                            dataType: "json",
                            success: function(data) {
                            if(data.success==1){
                                    swal({
                                        position: 'center',
                                        type: 'success',
                                        title: 'La licencia fue pagada exitosamente',
                                        showConfirmButton: false,
                                        timer:1500
                                    });
                                }else{
                                    swal({
                                        position: 'center',
                                        type: 'error',
                                        title: 'Hubo un error',
                                        showConfirmButton: false,
                                        timer:1500
                                    });
                                }
                                //console.log(data);
                            },
                            fail: function() {

                            }
                        });


                    }

                    function loadBanks() {

                        $.ajax({
                            url: "https://secure.payco.co/restpagos/pse/bancos.json?public_key=2815e60ed8e00cbfc47180d0d37a7a6c",
                            type:  'get', //método de envio
                            dataType: "json",
                            success: function(response) {
                                //console.log(response);
                                if(response.success==1){
                                    options = [];
                                    response.data.map(item => {
                                        options.push("<option value='"+item.bankCode+"'>"+item.bankName+"</option>");
                                    })
                                    $("#bank-entity").html(options.join(""));
                                }else{
                                    swal({
                                        position: 'center',
                                        type: 'error',
                                        title: data.description,
                                        showConfirmButton: false,
                                        timer:1500
                                    });
                                    setTimeout(function(){
                                        //location.reload(true);
                                    }, 1600);
                                }
                            },
                            fail: function($response) {
                                console.error($response);
                            }
                        });
                    }

                    htmlPagoCorrecto = '<div class="flex-padre col-md-12">'+
                            '<div class="flex-hijo col-md-12"><p class="span6 text-right">Referencia de pago</p><p class="span6 text-left">{ref_epayco}</p>'+
                            '</div><div class="flex-hijo"><p class="span6 text-right">Descripcion</p><p class="span6 text-left">{description}</p>'+
                            '</div><div class="flex-hijo"><p class="span6 text-right">Valor</p><p class="span6 text-left">{value}</p>'+
                            '</div><div class="flex-hijo"><p class="span6 text-right">Estado del pago</p><p class="span6 text-left">{state}</p>'+
                            '</div><div class="flex-hijo"><p class="span6 text-right">Nombre</p><p class="span6 text-left">{name}</p>'+
                            '</div><div class="flex-hijo"><p class="span6 text-right">Documento</p><p class="span6 text-left">{identification}</p>'+
                            '</div></div>';
                    htmlPagoIncorrecto = '<div class="flex-padre col-md-12">'+
                            '<div class="flex-hijo col-md-12"><p class="span6 text-right">Mensaje</p><p class="span6 text-left">{response}</p>'+
                            '</div></div><br>';

                    function mostrarConfirmacion(data) {
                        console.log(data);
                        $("#title-response").text(data.title);
                        $("#description-response").text(data.description);
                        if(data.success) {
                            $("#image-result").html('<div class="swal2-icon swal2-success swal2-animate-success-icon" style="display: flex;"><div class="swal2-success-circular-line-left" style="background-color: rgb(255, 255, 255);"></div><span class="swal2-success-line-tip"></span><span class="swal2-success-line-long"></span><div class="swal2-success-ring"></div><div class="swal2-success-fix" style="background-color: rgb(255, 255, 255);"></div><div class="swal2-success-circular-line-right" style="background-color: rgb(255, 255, 255);"></div></div>');
                            htmlPagoCorrecto = htmlPagoCorrecto.replace('{ref_epayco}',data.data.data.data.ref_payco);
                            htmlPagoCorrecto = htmlPagoCorrecto.replace('{description}',data.data.data.data.descripcion);
                            htmlPagoCorrecto = htmlPagoCorrecto.replace('{value}',data.data.data.data.valor);
                            htmlPagoCorrecto = htmlPagoCorrecto.replace('{state}',data.data.data.data.respuesta);
                            htmlPagoCorrecto = htmlPagoCorrecto.replace('{name}',data.data.data.data.nombres + " " + data.data.data.data.apellidos);
                            htmlPagoCorrecto = htmlPagoCorrecto.replace('{identification}',data.data.data.data.tipo_doc + " " + data.data.data.data.documento);
                            $("#data").html(htmlPagoCorrecto);
                            $("#debit-message").show();
                        } else {
                            $("#image-result").html('<div class="swal2-icon swal2-error swal2-animate-error-icon" style="display: flex;"><span class="swal2-x-mark"><span class="swal2-x-mark-line-left"></span><span class="swal2-x-mark-line-right"></span></span></div>');
                            htmlPagoIncorrecto = htmlPagoIncorrecto.replace('{response}', data.message);
                            $("#data").html(htmlPagoIncorrecto);
                            $("#debit-message").hide();
                        }
                        $("#panel-pagos2").hide();
                        $("#panel-planes").hide();
                        $("#panel-licencias").hide();
                        $("#buttons-recurrent-payment").show();
                        $("#loading").hide();
                        $("#panel-response").show();
                    }

                    function pagoRecurrente() {
                        var idPlanActual = $('#plan_cliente_id').val();
                        var action = "<?=site_url('frontend/creditCardPayment')?>";
                        var renovacion = "<?=site_url('responseCredit')?>";
                        var primera = "<?=site_url('responseCreditDos')?>";
                        var api_auth = JSON.parse(localStorage.getItem("api_auth"));

                        var totalSUM = 0;
                        var referenceCode = 'Licencia Vendty';
                        var n = 0;
                        extra1="";

                        $("tbody tr input:checkbox").each(function () {
                            var getValue = $(this).parent().parent().find("td:eq(6)").html();
                            if ($(this).is(':checked')) {
                                getValue2=$(this).data('id');
                                extra1 +=getValue2+"_";
                                active = true;
                                referenceCode += (n==0) ? $(this).data('id') + "_" + getValue : '-' + $(this).data('id') + "_" + getValue;
                                totalSUM +=Number(getValue);
                                n ++;
                            }
                        });

                        if(extra1!="") {
                            extra1=extra1.substr(0, extra1.length - 1);
                        }

                        var amount = totalSUM;
                        // Update footer
                        $( "#foot-table" ).empty();
                        $('#foot-table').append('Total: ' + amount + '$');
                        var factura=Math.random()+"1238_"+Math.random();

                        dataepa={
                            //Parametros compra (obligatorio)
                            name: "Pago de Licencia",
                            description: referenceCode,
                            invoice: factura,
                            //currency: "cop",
                            currency: currency,
                            amount: amount,
                            extra1: extra1,
                            tax_base: "0",
                            tax: "0",
                            country: "co",
                            lang: "es",
                            external: "true",
                            confirmation: "http://pos.vendty.com/index.php/response",
                            response: "http://pos.vendty.com/index.php/response"
                        }

                        if(idPlanActual == 1) {
                            confirmation = primera;
                            referenceCode = referenceCode.split("_")[0] + "-" + planSeleccionado.id;
                            id_licencia = api_auth.user.db_config.license.idlicencias_empresa
                            referenceCode = 'Licencia Vendty'+id_licencia + "-" + planSeleccionado.id;
                            dataepa.description = referenceCode;
                            //('nuevo');
                        }else {
                            confirmation = renovacion;
                            //console.log('renovacion');
                        }

                        data = {
                            client: {
                                name: $('#card-name').val(), // vendty
                                doc_type: $('#doc-type').val(),
                                doc_number: $('#doc-number').val(),
                                email: api_auth.user.email, //api_auth.user.email, // vendty
                                phone: $('#client-phone').val(), // vendty
                                address: api_auth.warehouse.address.address
                            },
                            creditCard: {
                                card_name: $('#client-name').val(), // vendty
                                card_email: api_auth.user.email, //api_auth.user.email, // vendty
                                card_number: $('#card-number').val(),
                                card_cvc: $('#card-cvc').val(),
                                card_exp_month: $('#card-exp-month').val(),
                                card_exp_year: $('#card-exp-year').val(),
                                card_type: $('#card-type').val()
                            },
                            plan: planSeleccionado,
                            user_id: api_auth.user.id,
                            licencia: referenceCode,
                            numeroDeLicencias,
                            confirmation,
                            test: $('#test').val()
                        };



                        //console.log(data);
                        $("#buttons-recurrent-payment").hide();
                        $("#loading").show();
                        $.ajax({
                            url: action,
                            data:  data, //datos que se envian a traves de ajax
                            type:  'post', //método de envio
                            dataType: "json",
                            success: function(data) {
                                //console.log(data);
                                if(data.success && data.data.data.estado == 'Aceptada'){
                                    mostrarConfirmacion({
                                        success: true,
                                        title:  'La licencia fue pagada exitosamente',
                                        description: "En un plazo de 10 minutos su licencia se actualizara con el pago realizado",
                                        data
                                    })
                                    $("#buttons-recurrent-payment").show();
                                    $("#loading").hide();
                                }else{
                                    console.log(data);
                                    mostrarConfirmacion({
                                        success: false,
                                        title:  'No se ha podido realizar el pago',
                                        description: "Por favor vuelve a realizar el pago en unos minutos si el error persiste comunicate con soporte",
                                        message: data.data.message,
                                    })
                                    $("#buttons-recurrent-payment").show();
                                    $("#loading").hide();
                                }
                            },
                            fail: function($response) {
                                console.error($response);
                            },
                        });
                    }

                    function seleccionarPlan(e){
                        //$('#myModal').modal('show');
                        var text = "Usted acaba de seleccionar el plan ";
                        var valor = $(e).val();
                        var result = valor.split('-');
                        var factura=Math.random()+"1238_"+Math.random();
                        $("#parrafo").empty();
                        $("#tituloPlan").empty();
                        $("#parrafo").append('$' + result[1]);
                        $("#tituloPlan").append(result[2]);
                        $('#divNuevoPlan').show();
                        var referenceCode = 'Licencia Vendty';
                        var total = result[1];
                        referenceCode += $('#licenciaId').val() + "-" + result[0];
                        text += result[2] + " el cual tiene un precio de $" + total + ".";
                        var extra1= "";

                        if(currency=='usd'){
                            extra1=result[3];
                        }

                        dataepa = {
                            //Parametros compra (obligatorio)
                            name: "Pago de Licencia",
                            description: referenceCode,
                            invoice: factura,
                            currency: currency,
                            amount: total,
                            extra1: extra1,
                            tax_base: "0",
                            tax: "0",
                            country: "co",
                            lang: "es",
                            external: "true",
                            confirmation: "http://pos.vendty.com/index.php/responseDos",
                            response: "http://pos.vendty.com/index.php/responseDos"
                        }

                        mostrarMetodos();
                        //alert("id plan "+result[0]);
                        mostrarSeleccionado(result[0]);
                        //ePayco.checkout.configure({key: keyepayco, test: false}).open(dataepa);
                    }

                    function mostrarMetodos() {
                        $("#panel-metodos").show();
                        $("#panel-planes").hide();
                        $("#panel-licencias").hide();
                    }


                    function mostrarFormCreditCard() {
                        var idPlan = $('#plan_cliente_id').val();
                        if(idPlan == 1) {
                            mostrarFormCreditCard2(planSeleccionado.id);
                        } else {
                            nombrePlan = "";
                            $("tbody tr input:checkbox").each(function () {
                                //var getValue = $(this).parent().parent().find("td:eq(6)").html();
                                if ($(this).is(':checked')) {
                                    //console.log($(this).attr('name'));
                                    nombrePlan = $(this).attr('name');
                                }
                            });

                            mostrarSeleccionadoName(nombrePlan);
                            $("#panel-metodos").hide();
                            $("#panel-pagos2").show();
                        }
                    }

                    function mostrarFormCreditCard2(idPlan) {
                        mostrarSeleccionado(idPlan);
                        $("#panel-metodos").hide();
                        $("#panel-pagos2").show();
                    }

                    function nombrePlanCorto(nombre) {
                        $plannombre=nombre;
                        $findme="BASICO MENSUAL";
                        $pos= $plannombre.includes($findme);
                        if ($pos !== false) {
                            $plannombre="BÁSICO";
                        }else{
                            $findme="MENSUAL PYME";
                            $pos= $plannombre.includes($findme);
                            if ($pos !== false) {
                                $plannombre="PYME";
                            }else{
                                $findme="EMPRESARIAL MENSUAL";
                                $pos= $plannombre.includes($findme);
                                if ($pos !== false) {
                                    $plannombre="EMPRESARIAL";
                                }
                                else{
                                    $plannombre="BÁSICO";
                                }
                            }
                        }
                        //console.log($plannombre);
                        return $plannombre;
                    }

                    function mostrarSeleccionadoName(name, total = 0) {

                        planes.map((plan) => {
                            if(plan.nombre_plan == name) {
                                planSeleccionado = plan;
                                //console.log('plan', plan);
                                $('#nombrePlan2').text(nombrePlanCorto(plan.nombre_plan));
                                $('#precioPlan2').html('<span class="dollar">$</span>'+plan.valor_final);
                                $('#cajas2').text(plan.cajas);
                                $('#usuarios2').text(plan.usuarios);
                            }
                        })
                    }

                    function mostrarSeleccionado(iDplan, total = 0) {
                        //cancelaralert("Plan seleccionado" + iDplan);
                        iDplan = parseInt(iDplan);
                        planes.map((plan) => {
                            if(plan.id == iDplan) {
                                planSeleccionado = plan;
                                //console.log('Plan seleccionado',plan);
                                $('#nombrePlan2').text(nombrePlanCorto(plan.nombre_plan));
                                $('#precioPlan2').html('<span class="dollar">$</span>'+ plan.valor_final );
                                $('#cajas2').text(plan.cajas);
                                $('#usuarios2').text(plan.usuarios);
                            }
                        })

                    }

                    function volverPlanes() {
                        $('#form-cash-payment').hide();
                        $('#form-bank-account').hide();
                        //$('#form-credit-card').hide();
                        $("#payment-type").val("0");
                    }

                    //loadBanks();

                    function checkTipoPlan(tipo) {
                        if(tipo == 1) {
                            $("#seccionMensual").show();
                            $("#seccionAnual").hide();
                        } else {
                            $("#seccionMensual").hide();
                            $("#seccionAnual").show();
                        }
                    }
                </script>

                <style>
                    .nuevoPago .texto-derecha{
                        text-align: right;
                    }

                    .nuevoPago .row-form {
                        padding: 2.5px;
                    }

                    .nuevoPago input[type='text'],
                    .nuevoPago input[type='password'],
                    .nuevoPago textarea,
                    .nuevoPago select {
                        font-size: 12px;
                        height: 24px;
                    }

                </style>

                <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,300,600" rel="stylesheet" type="text/css">
                <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
                <link rel="stylesheet prefetch" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">

                <style >
                    #loading {
                        display: inline-block;
                        width: 25px;
                        height: 25px;
                        border: 3px solid #62cb31;
                        border-radius: 50%;
                        border-top-color: #AAFD83;
                        animation: spin 1s ease-in-out infinite;
                        -webkit-animation: spin 1s ease-in-out infinite;
                    }

                    @keyframes spin {
                        to { -webkit-transform: rotate(360deg); }
                    }
                    @-webkit-keyframes spin {
                        to { -webkit-transform: rotate(360deg); }
                    }

                    .nuevoPago .wrapper {
                        position: relative;
                        margin-left: auto;
                        margin-right: auto;
                    }

                    .nuevoPago .package {
                        box-sizing: border-box;
                        width: 250px;
                        /*height: 300px;*/
                        border: 3px solid #e8e8e8;
                        border-radius: 7px;
                        display: inline-block;
                        padding: 24px;
                        text-align: center;
                        float: left;
                        -webkit-transition: margin-top 0.5s linear;
                        transition: margin-top 0.5s linear;
                        position: relative;
                        margin: 11px;
                        margin-top: 20px;
                    }

                    /*.nuevoPago .package:hover {
                        margin-top: -1px;
                        -webkit-transition: margin-top 0.3s linear;
                        transition: margin-top 0.3s linear;
                    }*/

                    .nuevoPago .name {
                        color: #565656;
                        font-weight: 300;
                        font-size: 3rem;
                        margin-top: -5px;
                    }

                    .nuevoPago .price {
                        margin-top: 7px;
                        font-weight: bold;
                        font-size: 18px;
                    }

                    .nuevoPago .price::after {
                        content: "";
                        font-weight: normal;
                    }

                    .nuevoPago hr {
                        background-color: #dedede;
                        border: none;
                        height: 1px;
                    }

                    .nuevoPago .trial {
                        font-size: .9rem;
                        font-weight: 600;
                        padding: 2px 21px 2px 21px;
                        color: #62cb31;
                        border: 1px solid #e4e4e4;
                        display: inline-block;
                        border-radius: 15px;
                        background-color: white;
                        position: relative;
                        bottom: -20px;
                    }

                    .nuevoPago ul {
                        list-style: none;
                        padding: 0;
                        text-align: left;
                        margin-top: 29px;
                    }

                    .nuevoPago li {
                        margin-bottom: 15px;
                    }

                    .nuevoPago .checkIcon {
                        font-family: "FontAwesome";
                        content: "\f00c";
                    }

                    .nuevoPago li::before {
                        font-family: "FontAwesome";
                        content: "\f00c";
                        font-size: 1.3rem;
                        color: #62cb31;
                        margin-right: 3px;
                    }

                    .nuevoPago .brilliant {
                        border-color: #62cb31;
                    }
                    /* Triangle */

                    .nuevoPago .brilliant::before {
                        width: 0;
                        height: 0;
                        border-style: solid;
                        border-width: 64px 64px 0 0;
                        border-color: #62cb31 transparent transparent transparent;
                        position: absolute;
                        left: 0;
                        top: 0;
                        content: "";
                    }

                    .nuevoPago .brilliant::after {
                        font-family: "FontAwesome";
                        content: "\f00c";
                        color: white;
                        position: absolute;
                        left: 9px;
                        top: 6px;
                        text-shadow: 0 0 2px #62cb31;
                        font-size: 1.4rem;
                    }

                    .text-left {
                        text-align: left;
                    }

                    .text-right {
                        text-align: right;
                    }
                    .center-text {
                        text-align: center;
                    }

                    .payment-column {
                        padding: 1em;
                        display: flex;
                        flex-direction: column;
                        align-items: center;
                        border-style: solid;
                        border-width: 3px;
                        border-color: #e8e8e8;
                        border-radius: 7px;
                        width: 200px;
                    }

                    .payment-column-inner {
                        display: flex;
                        flex-direction: column;
                        align-items: center;
                        width: 210px;
                        justify-content: space-around;
                    }

                    .payment-row{
                        display: flex;
                        flex-direction: row;
                        justify-content: space-around;
                        border-style: solid;
                        border-width: 2;
                        border-color: transparent;
                        flex-wrap: wrap;
                    }
                </style>

                <div class="tab-pane fade" id="tab-licencias">

                    <div class="nuevoPago" id="panel-planes" style="display: none">

                        <div class="row">
                            <div class="span12">
                                <h4 style="text-align: center;">SELECCIONA UN PLAN</h4>
                            </div>
                            <div class="span12 control-check" style="justify-content: center;">
                                <div>
                                    <input type="radio" checked name="tipo-plan" id="#tipo-mensual" onclick="checkTipoPlan(1)" value="" >
                                    <span>Plan Mensual</span>
                                </div>
                                <div style="width: 5em"></div>
                                <div>
                                    <input type="radio"  name="tipo-plan" id="#tipo-anual" onclick="checkTipoPlan(2)" value="" >
                                    <span>Plan Anual</span>
                                </div>
                            </div>
                        </div>

                        <div class="wrapper" id="seccionMensual">
                            <div >
                                <?php
$planes = $data['planes']['planes'];
foreach ($planes as $plan) {
    if ((($plan["mostrar"] == 2) || ($plan["mostrar"] == 3)) && ($plan["dias_vigencia"] == 30)) {
        $plannombre = $plan["nombre_plan"];
        $findme = "BASICO MENSUAL";
        $pos = strpos($plannombre, $findme);
        if ($pos !== false) {
            $plannombre = "BÁSICO";
        } else {
            $findme = "MENSUAL PYME";
            $pos = strpos($plannombre, $findme);
            if ($pos !== false) {
                $plannombre = "PYME";
            } else {
                $findme = "EMPRESARIAL MENSUAL";
                $pos = strpos($plannombre, $findme);
                if ($pos !== false) {
                    $plannombre = "EMPRESARIAL";
                } else {
                    $plannombre = "BÁSICO";
                }
            }
        }?>

                                    <div class="package <?=$plan["orden_mostrar"] == 2 ? 'brilliant' : ''?>">
                                        <div class="name"><?=$plannombre?></div>
                                        <div class="price">$<?=number_format($plan["valor_plan"], 0, ',', ".")?></div>
                                        <div class="trial">Mensual</div>
                                        <hr>
                                        <ul>
                                            <li>
                                                Facturas <strong>Ilimitadas</strong>
                                            </li>
                                            <li>
                                                <td>Productos e Inventario</td>
                                            </li>
                                            <li>
                                                <strong><?=$plan['cajas']?></strong> Caja(s)
                                            </li>
                                            <li>
                                                <strong><?=$plan['usuarios']?></strong> Usuario(s)
                                            </li>

                                        </ul>
                                        <button type="button" class="btn btn-success planes" data-users="<?=$plan['usuarios']?>" style=" background-color: #31CC33; width: 150px;" onclick="seleccionarPlan(this)"
                                            value="<?=$plan['id']?>-<?=$plan['valor_plan']?>-<?=$plan['descripcion']?>">
                                            <strong><font size="3px">Seleccionar</font></strong>
                                        </button>
                                    </div>
                                <?php
}
}?>
                            </div>
                        </div>

                        <div class="wrapper" id="seccionAnual" style="display: none">
                            <div >
                                <?php
$planes = $data['planes']['planes'];
foreach ($planes as $plan) {
    if ((($plan["mostrar"] == 1) || ($plan["mostrar"] == 3)) && ($plan["dias_vigencia"] == 365)) {
        $plannombre = $plan["nombre_plan"];
        $findme = "BASICO ANUAL";
        $pos = strpos($plannombre, $findme);
        if ($pos !== false) {
            $plannombre = "BÁSICO";
        } else {
            $findme = "STANDARD ANUAL";
            $pos = strpos($plannombre, $findme);
            if ($pos !== false) {
                $plannombre = "PYME";
            } else {
                $findme = "EMPRESARIAL ANUAL";
                $pos = strpos($plannombre, $findme);
                if ($pos !== false) {
                    $plannombre = "EMPRESARIAL";
                } else {
                    $plannombre = "BÁSICO";
                }
            }
        }?>

                                    <div class="package <?=$plan["orden_mostrar"] == 2 ? 'brilliant' : ''?>">
                                        <div class="name"><?=$plannombre?></div>
                                        <div class="price">$<?=number_format($plan["valor_plan"], 0, ',', ".")?></div>
                                        <div class="trial">Mensual</div>
                                        <hr>
                                        <ul>
                                            <li>
                                                Facturas <strong>Ilimitadas</strong>
                                            </li>
                                            <li>
                                                <td>Productos e Inventario</td>
                                            </li>
                                            <li>
                                                <strong><?=$plan['cajas']?></strong> Caja(s)
                                            </li>
                                            <li>
                                                <strong><?=$plan['usuarios']?></strong> Usuario(s)
                                            </li>

                                        </ul>
                                        <button type="button" class="btn btn-success planes" data-users="<?=$plan['usuarios']?>" style=" background-color: #31CC33; width: 150px;" onclick="seleccionarPlan(this)"
                                            value="<?=$plan['id']?>-<?=$plan['valor_plan']?>-<?=$plan['descripcion']?>">
                                            <strong><font size="3px">Seleccionar</font></strong>
                                        </button>
                                    </div>
                                <?php
}
}?>
                            </div>
                        </div>

                        <div style="text-align: center; padding-top: 2em;">
                            <button class="btn btn-default" onclick="cancelarPagos()" style="margin-top: 2.5em;">Regresar</button>
                        </div>
                    </div>

                    <div class="nuevoPago" id="panel-pagos" style="display: none">
                        <div class="span4" style="padding-top: 2.5em">
                            <div class="package brilliant">
                                <div class="name" id="nombrePlan"></div>
                                <div class="price"><span id="precioPlan"></span></div>
                                <div class="trial">Mensual</div>
                                <hr>
                                <ul>
                                    <li>
                                        Facturas <strong>Ilimitadas</strong>
                                    </li>
                                    <li>
                                        <td>Productos e Inventario</td>
                                    </li>
                                    <li>
                                        <strong id="cajas"></strong> Caja(s)
                                    </li>
                                    <li>
                                        <strong id="usuarios"></strong> Usuario(s)
                                    </li>

                                </ul>
                            </div>
                        </div>

                        <div class="span8" style="padding-top: 2.5em">
                            <div class="row-form">
                                <div class="span5 texto-derecha">Metodo de pago</div>
                                <div class="span6" style="margin: auto;">
                                    <select style="margin-left: 5px;" onchange="selectPaymentMethod(this)" id="payment-type">
                                        <option value="0" selected disabled>Seleccione un metodo de pago</option>
                                        <option value="credit-card">Tarjeta de credito</option>
                                        <option value="transfer">Cuenta bancaria</option>
                                        <option value="cash">Efectivo</option>
                                    </select>
                                </div>
                            </div>

                            <div id="form-bank-account" style="display: none">
                                <div class="row-form">
                                    <div class="span5 texto-derecha">Titular</div>
                                    <div class="span6" style="margin: auto;">
                                        <input type="text" id="account-name" name="account-name" required>
                                    </div>
                                </div>
                                <div class="row-form">
                                    <div class="span5 texto-derecha">Tipo de identificación</div>
                                    <div class="span6" style="margin: auto;">
                                        <select id="account-doc-type"  name="account-doc-type" style="margin-left: 5px;" required>
                                            <option value="0" selected disabled>Seleccione una opcion</option>
                                            <option value="CC">CC - Cédula de ciudadanía</option>
                                            <option value="CE">CE - Cédula de extranjeria</option>
                                            <option value="NIT">NIT - Identificación tributaria</option>
                                            <option value="TI">TI - Tarjeta de identidad</option>
                                            <option value="PPN">PPN - Pasaporte</option>
                                            <option value="DNI">DNI - Documento nacional de identificación</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row-form">
                                    <div class="span5 texto-derecha">Número de documento</div>
                                    <div class="span6" style="margin: auto;">
                                        <input type="text" id="client-document-number" name="client-document-number" required>
                                    </div>
                                </div>
                                <!--div class="row-form">
                                    <div class="span5 texto-derecha">Email</div>
                                    <div class="span6" style="margin: auto;">
                                        <input type="text" id="account-email" name="account-email" required>
                                    </div>
                                </div-->
                                <div class="row-form">
                                    <div class="span5 texto-derecha">Tipo de persona</div>
                                    <div class="span6" style="margin: auto;">
                                        <select id="person-type" id="person-type" style="margin-left: 5px;" required>
                                            <option value="0" selected diabled>Seleccione tipo de persona</option>
                                            <option value="0">Persona natural</option>
                                            <option value="1">Persona jurídica</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row-form">
                                    <div class="span5 texto-derecha">Tipo de cuenta</div>
                                    <div class="span6" style="margin: auto;">
                                        <select id="account-type" id="account-type" style="margin-left: 5px;" required>
                                            <option value="0" selected diabled>Seleccione tipo de cuenta</option>
                                            <option value="ca">Cuenta de ahorros</option>
                                            <option value="cc">Cuenta corriente</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row-form">
                                    <div class="span5 texto-derecha">Número de la cuenta</div>
                                    <div class="span6" style="margin: auto;">
                                        <input type="text" id="account-number" name="account-number" required>
                                    </div>
                                </div>

                                <div class="row-form">
                                    <div class="span5 texto-derecha">Entidad bancaria</div>
                                    <div class="span6" style="margin: auto;">
                                        <select style="margin-left: 5px;" type="text" id="bank-entity" name="bank-entity" required>
                                            <option value="0">Seleccione su banco</option>
                                            <option value="av-villas">BANCO AV VILLAS</option>
                                            <option value="caja-social">BANCO CAJA SOCIAL BCSC</option>
                                            <option value="colpatria">BANCO COLPATRIA</option>
                                            <option value="corpbanca">BANCO CORPBANCA</option>
                                            <option value="davivienda">BANCO DAVIVIENDA</option>
                                            <option value="bogota">BANCO DE BOGOTA</option>
                                            <option value="occidente">BANCO DE OCCIDENTE</option>
                                            <option value="sudameris">BANCO GNB SUDAMERIS</option>
                                            <option value="popular">BANCO POPULAR</option>
                                            <option value="bancolombia">BANCOLOMBIA</option>
                                            <option value="citibank">CITIBANK</option>
                                            <option value="helm">HELM BANK</option>
                                            <option value="hsbc">HSBC</option>
                                        </select>
                                    </div>
                                </div>
                                <div style="padding-left: 5em; padding-right: 5em">
                                    <p style="text-align: justify;font-size: 11px;">
                                        * Al hacer click en el boton pagar autorizas a Vendty a realizar el cobro automatico de acuerdo al plan que has seleccionado.
                                    </p>
                                </div>
                                <div style="text-align: center;" id="buttons-account-payment">
                                    <button onclick="cancelarPagos()" class="btn">Cancelar</button>
                                    <button onclick="pagoBancario()" class="btn btn-success">Pagar</button>
                                </div>
                                <div id="loading" style="display: none"></div>
                            </div>
                        </div>

                    </div>

                    <div class="nuevoPago" id="panel-pagos2" style="display: none">
                        <div class="span4" style="padding-top: 2.5em">
                            <div class="package brilliant">
                                <div class="name" id="nombrePlan2"></div>
                                <div class="price"><span id="precioPlan2"></span></div>
                                <div class="trial">Mensual</div>
                                <hr>
                                <ul>
                                    <li>
                                        Facturas <strong>Ilimitadas</strong>
                                    </li>
                                    <li>
                                        <td>Productos e Inventario</td>
                                    </li>
                                    <li>
                                        <strong id="cajas2"></strong> Caja(s)
                                    </li>
                                    <li>
                                        <strong id="usuarios2"></strong> Usuario(s)
                                    </li>

                                </ul>
                            </div>
                        </div>

                        <div class="span8" style="padding-top: 2.5em">
                            <div id="form-credit-card">
                                <div class="row-form">
                                    <div class="span5 texto-derecha">Titular</div>
                                    <div class="span6" style="margin: auto;">
                                        <input type="text" id="card-name" name="card-name" required>
                                    </div>
                                </div>
                                <div class="row-form">
                                    <div class="span5 texto-derecha">Tipo de identificación</div>
                                    <div class="span6" style="margin: auto;">
                                        <select id="doc-type"  name="doc-type" style="margin-left: 5px;" required>
                                            <option value="0" selected disabled>Seleccione una opcion</option>
                                            <option value="CC">CC - Cédula de ciudadanía</option>
                                            <option value="CE">CE - Cédula de extranjeria</option>
                                            <option value="NIT">NIT - Identificación tributaria</option>
                                            <option value="TI">TI - Tarjeta de identidad</option>
                                            <option value="PPN">PPN - Pasaporte</option>
                                            <option value="DNI">DNI - Documento nacional de identificación</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row-form">
                                    <div class="span5 texto-derecha">Número de documento</div>
                                    <div class="span6" style="margin: auto;">
                                        <input type="text" id="doc-number" name="doc-number" required>
                                    </div>
                                </div>
                                <div class="row-form">
                                    <div class="span5 texto-derecha">Teléfono</div>
                                    <div class="span6" style="margin: auto;">
                                        <input type="text" id="client-phone" value="" placeholder="" name="client-phone" required>
                                    </div>
                                </div>
                                <div class="row-form">
                                    <div class="span5 texto-derecha">Tipo de tarjeta</div>
                                    <div class="span6" style="margin: auto;">
                                        <select id="card-type" name="card-type" style="margin-left: 5px;" required>
                                            <option value="0" selected disabled>Seleccione una opcion</option >
                                            <option value="visa">Visa</option>
                                            <option value="mcard">Master Card</option>
                                            <option value="aex">American Express</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row-form">
                                    <div class="span5 texto-derecha">Número de la tarjeta</div>
                                    <div class="span6" style="margin: auto;">
                                        <input type="text" id="card-number" name="card-number" required>
                                    </div>
                                </div>
                                <div class="row-form">
                                    <div class="span5 texto-derecha">Fecha de expiración</div>
                                    <div class="span2" style="margin: auto;">
                                        <select id="card-exp-month" name="card-exp-month" style="margin-left: 5px;" required>
                                            <option value="01">01</option>
                                            <option value="02">02</option>
                                            <option value="03">03</option>
                                            <option value="04">04</option>
                                            <option value="05">05</option>
                                            <option value="06">06</option>
                                            <option value="07">07</option>
                                            <option value="08">08</option>
                                            <option value="09">09</option>
                                            <option value="10">10</option>
                                            <option value="11">11</option>
                                            <option value="12">12</option>
                                        </select>
                                    </div>
                                    <div class="span1">/</div>
                                    <div class="span2" style="margin: auto;">
                                        <select id="card-exp-year" name="card-exp-year" style="margin-left: 5px;" required>
                                            <?php
                                                echo '<option value="' . date('Y') . '" selected>' . date('Y') . '</option>';

                                                for ($i = date('Y', strtotime('+1 years')); $i <= date('Y', strtotime('+50 years')); $i++) {
                                                    echo '<option value="' . $i . '">' . $i . '</option>';
                                                }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="row-form">
                                    <div class="span5 texto-derecha">Código de seguridad</div>
                                    <div class="span6" style="margin: auto;">
                                        <input type="text" id="card-cvc" name="card-cvc" required>
                                    </div>
                                </div>
                                <div style="text-align: center; padding: 1em">
                                    <img width="60" src="https://369969691f476073508a-60bf0867add971908d4f26a64519c2aa.ssl.cf5.rackcdn.com/logos/franquicia/visa.png" />
                                    <img width="60" src="https://369969691f476073508a-60bf0867add971908d4f26a64519c2aa.ssl.cf5.rackcdn.com/logos/franquicia/mastercard.png"  />
                                    <img width="60" src="https://369969691f476073508a-60bf0867add971908d4f26a64519c2aa.ssl.cf5.rackcdn.com/logos/franquicia/american.png"  />
                                </div>
                                <div style="padding-left: 5em; padding-right: 5em">
                                    <p style="text-align: justify;font-size: 11px;">
                                        * Al hacer click en el boton pagar autorizas a Vendty a realizar el cobro automatico de acuerdo al plan que has seleccionado.
                                    </p>
                                </div>
                                <div style="text-align: center;" id="buttons-recurrent-payment">
                                    <button onclick="cancelarPagos()" class="btn">Cancelar</button>
                                    <button onclick="pagoRecurrente()" class="btn btn-success">Pagar</button>
                                </div>
                                <div id="loading" style="display: none"></div>
                                <div class="span6" style="margin: auto;">
                                    <input id="test" name="test" value="s" type="hidden" required>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="nuevoPago" id="panel-metodos" style="display: none">
                        <div class="span12">
                            <div class="span12 center-text">
                                <h4>Seleccione el metodo de pago</h4>
                            </div>
                            <div class="payment-row" style="padding-top: 1em>">
                                <div class="payment-column">
                                    <div><h4>PSE</h4></div>
                                    <div class="payment-column-inner" style="padding: 0.1em;height: 15em;">
                                        <div>
                                            <img src="https://registro.pse.com.co/PSEUserRegister/assets/logo-pse.png" />
                                        </div>
                                    </div>
                                    <div style="height: 3em;"><button class="btn btn-default" style="margin-right: 0px;margin-top: 0.5em;" id="pagoPSE">Pagar</button></div>
                                </div>
                                <div class="payment-column">
                                    <div><h4 style="text-align: center;">TARJETA DE CREDITO</h4></div>
                                    <div class="payment-column-inner" style="padding: 0.1em;height: 15em;">
                                        <img width="80" src="https://369969691f476073508a-60bf0867add971908d4f26a64519c2aa.ssl.cf5.rackcdn.com/logos/franquicia/visa.png">
                                        <img width="80" src="https://369969691f476073508a-60bf0867add971908d4f26a64519c2aa.ssl.cf5.rackcdn.com/logos/franquicia/mastercard.png">
                                        <img width="80" src="https://369969691f476073508a-60bf0867add971908d4f26a64519c2aa.ssl.cf5.rackcdn.com/logos/franquicia/american.png">
                                    </div>
                                    <div style="height: 3em;"><button class="btn btn-default" style="margin-right: 0px;margin-top: 0.5em;" onclick="mostrarFormCreditCard()">Seleccionar</button></div>
                                </div>
                                <div class="payment-column">
                                    <div><h4>EFECTIVO</h4></div>
                                    <div class="payment-column-inner" style="padding: 0.1em;height: 15em;">
                                        <img width="80" src="https://seeklogo.com/images/B/Baloto-logo-7004A6EB29-seeklogo.com.png" />
                                        <img width="80" src="https://www.efecty.com.co:20009//Resource/image/button/1/eb422e66646abee8ef735c684aeca133.png?v=2" />
                                        <img width="80" src="https://secure.epayco.co/img/standard/franquicias/redservi.png" />
                                    </div>
                                    <div style="height: 3em;"><button class="btn btn-default" style="margin-right: 0px;margin-top: 0.5em;" id="pagoEfectivo">Pagar</button></div>
                                </div>
                                <div class="payment-column">
                                    <div><h4>PAYPAL</h4></div>
                                    <div class="payment-column-inner" style="padding: 0.1em;height: 15em;">
                                        <div>
                                            <img src="https://logosmarcas.com/wp-content/uploads/2018/03/PayPal-logo.png" />
                                        </div>
                                    </div>
                                    <div style="height: 3em;"><div style="margin-right: 0px;margin-top: 0.5em;" id="paypal-button"></div></div>
                                </div>
                            </div>
                            <div style="text-align: center; padding-top: 2em;">
                                <button class="btn btn-default" onclick="cancelarPagos()">Regresar</button>
                            </div>
                        </div>
                    </div>

                    <div class="nuevoPago" id="panel-response" style="display: none; text-align: center;">
                        <h2 id="title-response"></h2>
                        <h4 id="description-response"></h4>

                        <div style="padding-left: 5em; padding-right: 5em">
                            <p style="text-align: center;font-size: 11px; display: none" id="debit-message">
                                Recuerda que el cobro se realizara automaticamente en la fecha establecida en el plan
                            </p>
                        </div>
                        <div id="data"></div>
                        <div id="image-result">
                        </div>
                        <button class="btn btn-default" onclick="cancelarPagos()">Regresar</button>
                    </div>

                    <div id="panel-licencias">
                        <ul class="nav nav-tabs">
                            <li><a href="#licencias" data-toggle="tab">Mis licencias</a></li>
                            <li><a href="#pagos" data-toggle="tab">Mis pagos</a></li>
                            <!--<li><a href="#pagar" data-toggle="tab">Pagar</a></li>-->
                        </ul>

                        <div class="tab-content">
                            <div class="tab-pane active" id="licencias">
                                <!-- Licencias -->
                                <!--div class="span12">
                                    <div class="pull-right text-center">
                                        <?php if ($paisip == "Colombia") {?>
                                            <button type="submit" id="btn_pagar_licencia" class="btn btn-success">Pagar</button>
                                        <?php } else {?>
                                            <input id="btn_pagar_licencia" type=image src="<?php echo base_url("uploads/inicio/safetyfinal.png") ?>" width="100" height="30" style="margin-bottom: 10px;">
                                            <div id="paypal-button" ></div>
                                        <?php }?>
                                    </div>
                                    <div class="clearfix"></div>
                                </div-->
                                <div class="span12">
                                    <div class="pull-right text-center">
                                        <button type="submit" id="btn_pagar_licencia" class="btn btn-success">Pagar</button>
                                    </div>
                                </div>
                                <div class="row-fluid">
                                    <div class="span12">
                                        <table class="table aTable" cellpadding="0" cellspacing="0" width="100%" id="licenciasTable">
                                            <thead>
                                                <tr>
                                                    <th width="15%"><?php echo custom_lang('sima_name', "Nombre"); ?></th>
                                                    <!--th width="20%"><?php echo custom_lang('sima_address', "Dirección"); ?></th-->
                                                    <th width="15%"><?php echo custom_lang('sima_phone', "Tel&eacute;fono"); ?></th>
                                                    <th width="15%"><?php echo custom_lang('sima_period', "Tipo de licencia"); ?></th>
                                                    <th width="12%"><?php echo custom_lang('sima_initial_date', "Fecha inicial"); ?></th>
                                                    <th width="12%"><?php echo custom_lang('sima_finish_date', "Fecha final"); ?></th>
                                                    <th width="10%"><?php echo custom_lang('sima_state', "Estado"); ?></th>
                                                    <th width="10%"><?php echo custom_lang('sima_state', "Monto"); ?></th>
                                                    <th width="15%"> </th>

                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                            <tfoot>
                                            <tr>
                                                <th colspan="7" style="text-align:right" id="foot-table">Total:</th>
                                                <th></th>
                                            </tr>
                                        </tfoot>
                                        </table>
                                    </div>
                                </div>

                            </div>

                            <div class="tab-pane" id="pagos">
                                <div class="row-fluid">
                                    <div class="span12">
                                        <table class="table aTable" cellpadding="0" cellspacing="0" width="100%" id="pagosTable">
                                            <thead>
                                                <tr>
                                                    <th width="20%"><?php echo custom_lang('sima_name', "Forma de Pago"); ?></th>
                                                    <th width="20%"><?php echo custom_lang('sima_address', "Fecha"); ?></th>
                                                    <th width="20%"><?php echo custom_lang('sima_phone', "Monto"); ?></th>
                                                    <th width="10%"><?php echo custom_lang('sima_period', "Descuento"); ?></th>
                                                    <th width="10%"><?php echo custom_lang('sima_initial_date', "Factura"); ?></th>
                                                    <th width="10%"><?php echo custom_lang('sima_state', "Estado"); ?></th>
                                                    <th  class="TAC"><?php echo custom_lang('sima_action', "Acciones"); ?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th width="20%"><?php echo custom_lang('sima_name', "Forma de Pago"); ?></th>
                                                    <th width="20%"><?php echo custom_lang('sima_address', "Fecha"); ?></th>
                                                    <th width="20%"><?php echo custom_lang('sima_phone', "Monto"); ?></th>
                                                    <th width="10%"><?php echo custom_lang('sima_period', "Descuento"); ?></th>
                                                    <th width="10%"><?php echo custom_lang('sima_initial_date', "Factura"); ?></th>
                                                    <th width="10%"><?php echo custom_lang('sima_state', "Estado"); ?></th>
                                                    <th  class="TAC"><?php echo custom_lang('sima_action', "Acciones"); ?></th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="tab-5">
                    <!-- Actualizacion del listado de usuarios-->
                    <div class="span12">
                        <!--<a id="btn_new_user" class="btn btn-success"> <?php echo custom_lang('Nuevo Usuario', "Nuevo usuario"); ?></a>-->
                        <a id="btn_new_user" data-tooltip="Nuevo Usuario">
                            <img alt="Nuevo Usuario" class="btnimagenes" src="<?php echo $this->session->userdata('new_imagenes')['mas_verde']['original'] ?>">
                        </a>
                    </div>
                     <div class="row-fluid">
                        <div class="span12">
                            <table class="table aTable" cellpadding="0" cellspacing="0" width="100%" id="usuariosTable">
                                <thead>
                                    <tr>
                                        <th width="20%"><?php echo custom_lang('sima_name', "Nombre"); ?></th>
                                        <th width="20%"><?php echo custom_lang('sima_email', "Correo electr&oacute;nico"); ?></th>
                                        <th width="10%"><?php echo custom_lang('sima_phone', "Tel&eacute;fono"); ?></th>
                                        <th width="20%"><?php echo custom_lang('sima_rol', "Rol"); ?></th>
                                        <th width="10%"><?php echo custom_lang('sima_admin', "Desactivado"); ?></th>
                                        <th width="10%"><?php echo custom_lang('sima_admin', "Administrador"); ?></th>
                                        <th  class="TAC"><?php echo custom_lang('sima_action', "Acciones"); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                                <tfoot>
                                <tr>
                                    <th><?php echo custom_lang('sima_name', "Nombre"); ?></th>
                                    <th><?php echo custom_lang('sima_email', "Correo electr&oacute;nico"); ?></th>
                                    <th><?php echo custom_lang('sima_phone', "Tel&eacute;fono"); ?></th>
                                    <th><?php echo custom_lang('sima_rol', "Rol"); ?></th>
                                    <th><?php echo custom_lang('sima_admin', "Desactivado"); ?></th>
                                    <th><?php echo custom_lang('sima_admin', "Administrador"); ?></th>
                                    <th  class="TAC"><?php echo custom_lang('sima_action', "Acciones"); ?></th>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>



                </div>
                <div class="tab-pane fade" id="tab-6">
                    <div class="row-fluid">
                        <div class="span12">
                            <!--<a id="btn_new_almacen" class="btn btn-success"> Nuevo Almacen</a>-->
                            <a id="btn_new_almacen" data-tooltip="Nuevo Almacén">
                                <img alt="Nuevo Almacén" class="btnimagenes" src="<?php echo $this->session->userdata('new_imagenes')['mas_verde']['original'] ?>">
                            </a>
                        </div>
                    </div>
                     <div class="row-fluid">
                        <table class="table aTable" cellpadding="0" cellspacing="0" width="100%" id="productosTable">
                            <thead>
                            <tr>
                                <th width="10%"><?php echo custom_lang('sima_image', "Nombre"); ?></th>
                                <th width="20%"><?php echo custom_lang('sima_codigo', "Direccion"); ?></th>
                                <th width="10%"><?php echo custom_lang('sima_name', "Prefijo"); ?></th>
                                <th width="10%"><?php echo custom_lang('price_active', "Numero de inicio"); ?></th>
                                <th width="10%"><?php echo custom_lang('price_active', "Activo"); ?></th>
                                <th width="10%"><?php echo custom_lang('price_active', "Telefono"); ?></th>
                                <th width="10%"><?php echo custom_lang('meta_diaria', "Meta diaria"); ?></th>
                                <th width="10%"><?php echo custom_lang('licencia', "Licencia"); ?></th>
                                <th  class="TAC"><?php echo custom_lang('sima_action', "Acciones"); ?></th>
                            </tr>
                            </thead>
                        <tbody>
                        </tbody>
                            <tfoot>
                            <tr>
                                <th><?php echo custom_lang('sima_image', "Nombre"); ?></th>
                                <th><?php echo custom_lang('sima_codigo', "Direccion"); ?></th>
                                <th><?php echo custom_lang('sima_name', "Prefijo"); ?></th>
                                <th><?php echo custom_lang('price_active', "Numero de inicio"); ?></th>
                                <th><?php echo custom_lang('price_active', "Activo"); ?></th>
                                <th><?php echo custom_lang('price_active', "Telefono"); ?></th>
                                <th><?php echo custom_lang('meta_diaria', "Meta diaria"); ?></th>
                                <th><?php echo custom_lang('licencia', "Licencia"); ?></th>
                                <th><?php echo custom_lang('sima_action', "Acciones"); ?></th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>

                </div>

                <div class="tab-pane fade" id="tab-11">
                    <div class="row-fluid">
                        <div class="span12">
                            <!--<a id="btn_new_bodega" class="btn btn-success">Nueva Bodega</a>-->
                            <a id="btn_new_bodega" data-tooltip="Nueva Bodega">
                                <img alt="Nueva Bodega" class="btnimagenes" src="<?php echo $this->session->userdata('new_imagenes')['mas_verde']['original'] ?>">
                            </a>
                        </div>
                    </div>
                     <div class="row-fluid">
                        <table class="table aTable" cellpadding="0" cellspacing="0" width="100%" id="productosTableBodega">
                            <thead>
                            <tr>
                                <th width="10%"><?php echo custom_lang('sima_image', "Nombre"); ?></th>
                                <th width="20%"><?php echo custom_lang('sima_codigo', "Direccion"); ?></th>
                                <th width="10%"><?php echo custom_lang('price_active', "Activo"); ?></th>
                                <th width="10%"><?php echo custom_lang('price_active', "Ciudad"); ?></th>
                                <th  class="TAC"><?php echo custom_lang('sima_action', "Acciones"); ?></th>
                            </tr>
                            </thead>
                        <tbody>
                        </tbody>
                            <tfoot>
                            <tr>
                                <th><?php echo custom_lang('sima_image', "Nombre"); ?></th>
                                <th><?php echo custom_lang('sima_codigo', "Direccion"); ?></th>
                                <th><?php echo custom_lang('price_active', "Activo"); ?></th>
                                <th><?php echo custom_lang('price_active', "Ciudad"); ?></th>
                                <th><?php echo custom_lang('sima_action', "Acciones"); ?></th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <!-- WOOCOMMERCE -->
                <div class="tab-pane fade" id="woocommerce">
                    <div class="row-fluid">
                        <div class="col-md-10 col-md-offset-1">
                            <div class="form-group">
                                <label for="consumer_key">Consumer Key</label>
                                <input type="text" name="consumer_key" id="consumer_key" v-model="ck" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="consumer_secret">Consumer Secret</label>
                                <input type="text" name="consumer_secret" id="consumer_secret" v-model="cs" class="form-control">
                            </div>
                            <button class="btn btn-success" @click="generate()">Generar</button>
                            <p v-text="message"></p>
                        </div>
                    </div>
                </div>
                <!-- WOOCOMMERCE -->

                <div class="tab-pane fade" id="integraciones">
                    <div id="panel-licencias">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#integracion_puntos_leal" data-toggle="tab">Puntos Leal</a></li>
                        </ul>

                        <div class="tab-content">
                            <div class="tab-pane active" id="integracion_puntos_leal">
                                <?php echo form_open_multipart("frontend/configuracion", array("id" => "validate")); ?>
                                    <div class="row-form">
                                        <div class="span12 control-check">
                                            <input type="checkbox"  name="puntos_leal" id="puntos_leal" value="puntos_leal" <?php echo ($puntos_leal == "si") ? 'checked' : ''; ?>>
                                            <span>Activar Puntos Leal</span>
                                            <a id="pop_puntos_leal" href="#" data-container="body" data-content="Active esta opción solo si usted se encuenta afiliado a Puntos Leal. Para más información https://www.puntosleal.com/." rel="popover" data-placement="right" data-original- data-trigger="hover">
                                                <span class="glyphicon glyphicon-question-sign icon-help-config" aria-hidden="true"></span>
                                            </a>
                                        </div>
                                    </div>
                                    <?php $oculto = ($puntos_leal == "si") ? '' : 'hidden'?>
                                    <div class="row-form usuario_puntos_leal <?=$oculto?>">
                                        <div class="span4">Usuario:</div>
                                        <div class="span8"><input type="text" value="<?php echo (!empty($usuario_puntos_leal)) ? $usuario_puntos_leal : ''; ?>" id="usuario_puntos_leal" placeholder="Usuario Puntos Leal" name="usuario_puntos_leal" />
                                        </div>
                                    </div>
                                    <div class="row-form contraseña_puntos_leal <?=$oculto?>">
                                        <div class="span4">Contraseña:</div>
                                        <div class="span8"><input type="password" value="<?php echo (!empty($contraseña_puntos_leal)) ? $contraseña_puntos_leal : ''; ?>" id="contraseña_puntos_leal" placeholder="Contraseña Puntos Leal" name="contraseña_puntos_leal" />
                                        </div>
                                    </div>
                                    <div class="span12">
                                        <input type='hidden' name='form' value='puntos_leal'>
                                    </div>
                                    <div class="span12">
                                        <div class="btn-group">
                                            <div class="span6">
                                                <button class="btn btn-default" type="button" onclick="javascript:location.href='../frontend/configuracion'">Cancelar</button>
                                            </div>
                                            <div class="span6">
                                                <button class="btn btn-success" type="submit">Guardar</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- WOOCOMMERCE -->

                <div class="tab-pane fade" id="integraciones">
                    <div id="panel-licencias">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#integracion_puntos_leal" data-toggle="tab">Puntos Leal</a></li>
                        </ul>

                        <div class="tab-content">
                            <div class="tab-pane active" id="integracion_puntos_leal">
                                <?php echo form_open_multipart("frontend/configuracion", array("id" => "validate")); ?>
                                    <div class="row-form">
                                        <div class="span12 control-check">
                                            <input type="checkbox"  name="puntos_leal" id="puntos_leal" value="puntos_leal" <?php echo ($puntos_leal == "si") ? 'checked' : ''; ?>>
                                            <span>Activar Puntos Leal</span>
                                            <a id="pop_puntos_leal" href="#" data-container="body" data-content="Active esta opción solo si usted se encuenta afiliado a Puntos Leal. Para más información https://www.puntosleal.com/." rel="popover" data-placement="right" data-original- data-trigger="hover">
                                                <span class="glyphicon glyphicon-question-sign icon-help-config" aria-hidden="true"></span>
                                            </a>
                                        </div>
                                    </div>
                                    <?php $oculto = ($puntos_leal == "si") ? '' : 'hidden'?>
                                    <div class="row-form usuario_puntos_leal <?=$oculto?>">
                                        <div class="span4">Usuario:</div>
                                        <div class="span8"><input type="text" value="<?php echo (!empty($usuario_puntos_leal)) ? $usuario_puntos_leal : ''; ?>" id="usuario_puntos_leal" placeholder="Usuario Puntos Leal" name="usuario_puntos_leal" />
                                        </div>
                                    </div>
                                    <div class="row-form contraseña_puntos_leal <?=$oculto?>">
                                        <div class="span4">Contraseña:</div>
                                        <div class="span8"><input type="password" value="<?php echo (!empty($contraseña_puntos_leal)) ? $contraseña_puntos_leal : ''; ?>" id="contraseña_puntos_leal" placeholder="Contraseña Puntos Leal" name="contraseña_puntos_leal" />
                                        </div>
                                    </div>
                                    <div class="span12">
                                        <input type='hidden' name='form' value='puntos_leal'>
                                    </div>
                                    <div class="span12">
                                        <div class="btn-group">
                                            <div class="span6">
                                                <button class="btn btn-default" type="button" onclick="javascript:location.href='../frontend/configuracion'">Cancelar</button>
                                            </div>
                                            <div class="span6">
                                                <button class="btn btn-success" type="submit">Guardar</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="tab-7">
                    <div class="row-fluid">
                        <div class="span6">
                            <div class="widgets"  style="padding:10%;">
                                <div class="swidget blue text-center">
                                    <div class="icon">
                                        <span class="ico-upload"></span>
                                    </div>
                                    <div class="bottom">
                                        <div class="text" style="color:#000;">Producto</div>
                                    </div>
                                </div>
                                <h4>
                                    Bienvenido siga los siguientes pasos para actualizar los productos desde un archivo Excel.
                                </h4>
                                <div class="alert alert-warning">
                                    <p>Al momento de actualizar los productos no se deben dejar campos sin valor, en caso de no editar un producto por favor eliminarlo del archivo o poner el mismo valor que traia por defecto.</p>
                                </div>
                                <div class="alert alert-warning">
                                    <p>
                                    Tenga en cuenta que al actualizar el precio de venta de un producto no se realizara automáticamente un recalculo en el libro de precios.
                                    </p>
                                </div>
                                <form action="<?=site_url('productos/importar_base_productos')?>" enctype="multipart/form-data" method="post">
                                <div class="span12">
                                    1. Seleccione los campos que desea actualizar y luego descargue la plantilla.
                                    <br><br>
                                    <select multiple name="campos" id="campos" class="multiple">
                                        <option value="Codigo">Codigo</option>
                                        <option value="Precio compra">Precio Compra</option>
                                        <option value="Precio venta">Precio Venta</option>
                                        <option value="Stock minimo">Stock Mínimo</option>
                                        <option value="Stock maximo">Stock Máximo</option>
                                        <option value="Impuesto">Impuesto</option>
                                        <option value="Descripcion">Descripción</option>
                                        <option value="Activo">Activo</option>
                                        <option value="Fecha vencimiento">Fecha Vencimiento</option>
                                        <option value="Venta negativo">Venta en Negativo</option>
                                        <option value="Proveedor">Proveedor</option>
                                        <option value="Tienda">Tienda</option>
                                        <option value="Categoria">Categoria</option>
                                    </select>
                                </div>
                                <div class="span12">
                                    <br><br>
                                    <p>2. Descargue la plantilla</p>

                                    <!--<a id="btn_exportar" data-url="<?=site_url('productos/exportar_base_productos')?>" href="<?=site_url('productos/exportar_base_productos')?>">
                                        <div class="icon">
                                            <span class="ico-download"></span>
                                        </div>
                                        Descargar archivo
                                    </a>-->
                                    <a id="btn_exportar" data-tooltip="Descargar Archivo" data-url="<?=site_url('productos/exportar_base_productos')?>" href="<?=site_url('productos/exportar_base_productos')?>">
                                        <img alt="Descargar Archivo" class="btnimagenes" src="<?php echo $this->session->userdata('new_imagenes')['exportar_excel_verde']['original'] ?>">
                                    </a>
                                </div>
                                <div class="span12">
                                    <br><br>
                                <p>3. Una vez se finalice la edición del archivo seleccionelo y haga click en el botón guardar.</p>
                                    <div class="row-fluid">
                                        <div class="span12">
                                            <div class="input-append file">
                                                <input type="file" name="archivo"/>
                                                <button class="btn btn-success" type="button"><?php echo custom_lang('sima_search', "Seleccione Archivo"); ?></button>
                                            </div>
                                        </div>
                                    </div>
                                        <br>
                                        <a href="<?php echo site_url('configuracion/carga_de_datos'); ?>" class="btn btn-default">Regresar</a>
                                        <input type="submit" class="btn btn-success" value="Guardar">
                                </div>
                                </form>
                            </div>
                        </div>

                        <?php if ($option == "moda") {?>
                        <div class="span6" >
                            <div class="widgets" style="padding:10%;">
                                <div class="swidget blue text-center">
                                    <div class="icon">
                                        <span class="ico-file"></span>
                                    </div>
                                    <div class="bottom">
                                        <div class="text" style="color:#000;">Con atributos</div>
                                    </div>
                                </div>
                                <h4>
                                    Bienvenido siga los siguientes pasos para importar productos con atributos desde un archivo Excel.
                                </h4>
                                <div class="span12">
                                    1. Descargue la plantilla
                                <br><br>
                                    <a id="btn_exportar" data-url="<?=site_url('productos/exportar_base_productos_con_atributos')?>" href="<?=site_url('productos/exportar_base_productos_con_atributos')?>">
                                        <div class="icon">
                                            <span class="ico-download"></span>
                                        </div>
                                        Descargar archivo
                                    </a>
                                </div>
                                <form action="<?=site_url('productos/importar_productos_con_atributos')?>" enctype="multipart/form-data" method="post" id="validate_atributos">
                                <div class="span12">
                                    2. Una vez se finalice la edición del archivo seleccionelo y haga click en el botón enviar.
                                    <br><br>
                                    <div class="row-fluid">
                                        <div class="span12">
                                            <div class="input-append file">
                                                <input type="file" name="archivo"/>
                                                <button class="btn btn-success" type="button"><?php echo custom_lang('sima_search', "Seleccione Archivo"); ?></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <a href="<?=site_url('configuracion/carga_de_datos')?>" id="regresar" class="btn btn-default">Regresar</a>
                                <input type="submit" class="btn btn-success" id="guardar_archivo" value="Enviar">
                                </form>
                            </div>
                        </div>
                            <?php }?>

                    </div>
                </div>
                <div class="tab-pane fade" id="tab-upload-images">
                    <div class="row-fluid">
                        <div class="span6">
                            <div class="widgets"  style="padding:10%;">
                                <div class="swidget blue text-center">
                                    <div class="icon">
                                        <span class="ico-upload"></span>
                                    </div>
                                    <div class="bottom">
                                        <div class="text" style="color:#000;">Subir imágenes</div>
                                    </div>
                                </div>
                                <h4>
                                    Bienvenido cargue un zip con las imágenes.
                                </h4>
                                <form action="<?=site_url('productos/upload_zip_photo')?>" enctype="multipart/form-data" method="post">
                                    <div class="span12">
                                        <br><br>
                                        <p>Seleccione el archivo y haga click en el botón guardar.</p>
                                        <div class="row-fluid">
                                            <div class="span12">
                                                <div class="input-append file">
                                                    <input type="file" name="zip_file"/>
                                                    <button class="btn btn-success" type="button"><?php echo custom_lang('sima_search', "Seleccione Archivo"); ?></button>
                                                </div>
                                            </div>
                                        </div>
                                        <br>
                                        <input type="submit" class="btn btn-success" value="Guardar">
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="tab-8">
                    <div class="row-fluid">
                        <div class="alert alert-error">
                            Seleccione los modulos que desea reiniciar, tenga en cuenta que:<br>
                            -Al elegir productos se debera reiniciar todo ha excepcion de clientes y proveedores.<br>
                            -Al elegir ventas se reiniciara tambien los cierres de caja (cierres de caja aliados al almacén selecionado).<br>
                            -Al elegir inventarios se reiniciara los movimientos de inventario (movimientos de inventario aliados almacén selecionado).<br>
                            -Al elegir ordenes de compra de reiniciran los movimientos de inventario de tipo entrada_compra (movimientos de inventario aliados almacén selecionado).
                        </div>
                    </div>
                    <form id="reinicio">
                        <div class="row">
                    3        <div id="camposOpcionales" class="col-md-6">
                                <div class="center changeColor">
                                    <code>Clientes</code>
                                </div>
                                <div class="contListas grp2 contListasSwitch">
                                    <div class="well">
                                        <div>
                                            <div class="listasCont tablaR">
                                                <div class="t" >
                                                    <div class="c">
                                                        <input value="1" name="data[clientes]" id="cli" type="checkbox" class="js-switch" /><div class="c">Todos los almacenes</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="camposOpcionales" class="col-md-6">
                                <div class="center changeColor">
                                    <code>Proveedores</code>
                                </div>
                                <div class="contListas grp2 contListasSwitch">
                                    <div class="well">
                                        <div>
                                            <div class="listasCont tablaR">
                                                <div class="t" >
                                                    <div class="c">
                                                        <input value="1" name="data[proveedores]" id="proveedores" type="checkbox" class="js-switch" /><div class="c">Todos los almacenes</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div id="camposOpcionales" class="col-md-6">
                                <div class="center changeColor">
                                    <code>Productos</code>
                                </div>
                                <div class="contListas grp2 contListasSwitch">
                                    <div class="well">
                                        <div>
                                            <div class="listasCont tablaR">
                                                <div class="t" >
                                                    <div class="c">
                                                        <input value="1" name="data[productos]" id="pro" type="checkbox" class="js-switch" /><div class="c">Todos los almacenes</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div id="camposOpcionales" class="col-md-6">
                                <div class="center changeColor">
                                    <code>Inventarios</code>
                                </div>
                                <div class="contListas grp2 contListasSwitch">
                                    <div class="well">
                                        <div>
                                            <div class="listasCont tablaR">
                                                <?php
$i = 0;
foreach ($data['almacen'] as $key => $a) {
    ?>
                                                    <div class="t" >
                                                        <div class="c">
                                                            <input data-titulo="Inventarios" data-almacen="<?php echo $a ?>" value="<?php echo $key; ?>" name="data[inventario][]" id="inv<?php echo $i ?>" type="checkbox" class="js-switch" /><div class="c"><?php echo $a ?></div>
                                                        </div>
                                                    </div>
                                                    <?php
$i++;
}
?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="camposOpcionales" class="col-md-6">
                                <div class="center changeColor">
                                    <code>Movimientos en inventario</code>
                                </div>
                                <div class="contListas grp2 contListasSwitch">
                                    <div class="well">
                                        <div>
                                            <div class="listasCont tablaR">
                                                <?php
$i = 0;
foreach ($data['almacen'] as $key => $a) {
    ?>
                                                    <div class="t" >
                                                        <div class="c">
                                                            <input data-titulo="Movimientos en Inventario" data-almacen="<?php echo $a ?>" value="<?php echo $key ?>" name="data[movimientos][]" id="mov<?php echo $i ?>" type="checkbox" class="js-switch" /><div class="c"><?php echo $a ?></div>
                                                        </div>
                                                    </div>
                                                    <?php
$i++;
}
?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div id="camposOpcionales" class="col-md-6">
                                <div class="center">
                                    <code>Ordenes de compra</code>
                                </div>
                                <div class="contListas grp2 contListasSwitch">
                                    <div class="well">
                                        <div>
                                            <div class="listasCont tablaR">
                                                <?php
foreach ($data['almacen'] as $key => $a) {
    ?>
                                                    <div class="t" >
                                                        <div class="c">
                                                            <input data-titulo="Ordenes de Compra" data-almacen="<?php echo $a ?>" value="<?php echo $key ?>" name="data[ordenes][]" id="ord<?php echo $i ?>" type="checkbox" class="js-switch" /><div class="c"><?php echo $a ?></div>
                                                        </div>
                                                    </div>
                                                    <?php
$i++;
}
?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="camposOpcionales" class="col-md-6">
                                <div class="center changeColor">
                                    <code>Ventas</code>
                                </div>
                                <div class="contListas grp2 contListasSwitch">
                                    <div class="well">
                                        <div>
                                            <div class="listasCont tablaR">
                                                <?php
foreach ($data['almacen'] as $key => $a) {
    ?>
                                                    <div class="t" >
                                                        <div class="c">
                                                            <input data-titulo="Ventas" data-almacen="<?php echo $a ?>" value="<?php echo $key ?>" name="data[ventas][]" id="ven<?php echo $i ?>" type="checkbox" class="js-switch" /><div class="c"><?php echo $a ?></div>
                                                        </div>
                                                    </div>
                                                    <?php
$i++;
}
?>
                                                <!--<div class="t" >
                                                    <div class="c">
                                                        <input  value="1" name="data[ventas]" id="ven" type="checkbox" class="js-switch" /><div class="c">Todos los almacenes</div>
                                                    </div>
                                                </div>-->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row-fluid">
                            <br>

                            <a href="<?php echo site_url('frontend/configuracion') ?>" class="btn btn-default">Volver</a>
                            <input type="submit" class="btn btn-success" value="Reiniciar">
                        </div>
                    </form>
                    <!-- Fin del panel 7-->
                    <div id="dialog-confirmacion-form"  title="<?php echo custom_lang('sima_pay_information', "Reiniciar Modulos"); ?>">
                        <form id="confirmacion-form">
                            <div class="row-form">
                                <div class="span5">Ingrese el email y password del administrador para continuar con el proceso</div>
                            </div>
                            <div class="row-form" class="data-fluid">
                                <div class="span2"><?php echo custom_lang('sima_pay_value', "Email"); ?>:</div>
                                <div class="span3">
                                    <input type="text" name="email" id="email"/>
                                </div>
                            </div>
                            <div class="row-form">
                                <div class="span2"><?php echo custom_lang('sima_password', "Password"); ?>:</div>
                                <div class="span3">
                                    <input type="password" name="password" id="password"/>
                                </div>
                            </div>
                            <br />
                            <div align="center">
                                <input type="submit" value="Reiniciar" id="reiniciarSubmit" class="btn btn-primary"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <input type="button" value="Cancelar"  id="cancelar" class="btn btn-warning"/>
                            </div>
                        </form>
                    </div>
                    <div id="dialog-muestra-form"  title="<?php echo custom_lang('sima_pay_information', "Reiniciar Modulos"); ?>">
                        <form id="muestra-form">
                            <div class="row-form">
                                <div class="span5">Se reiniciaran los siguientes modulos</div>
                            </div>
                            <div class="muestra"></div>
                            <br />
                            <div align="center">
                                <input type="submit" value="Continuar"  class="btn btn-primary"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <input type="button" value="Cancelar"  id="cancelar" class="btn btn-warning"/>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="tab-pane fade" id="tab-9">
                    <div class="row-fluid">
                        <div class="span12">
                            <!--<a id="btn_new_rol"href="#" class="btn btn-success"><?php echo custom_lang('sima_new_sales_man', "Nueva Rol"); ?></a>-->
                            <a id="btn_new_rol" data-tooltip="Nuevo Rol">
                                <img alt="Nuevo Rol" class="btnimagenes" src="<?php echo $this->session->userdata('new_imagenes')['mas_verde']['original'] ?>">
                            </a>
                        </div>
                    </div>
                    <div class="row-fluid">
                        <div class="span12">
                            <table class="table aTable" cellpadding="0" cellspacing="0" width="100%" id="rolesTable">
                                <thead>
                                    <tr>
                                    <th width="20%"><?php echo custom_lang('sima_name', "Nombre"); ?></th>
                                    <th width="70%"><?php echo custom_lang('sima_description', "Descripcion"); ?></th>
                                    <th  class="TAC"><?php echo custom_lang('sima_action', "Acciones"); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                                <tfoot>
                                    <tr>
                                    <th><?php echo custom_lang('sima_name', "Nombre"); ?></th>
                                    <th><?php echo custom_lang('sima_description', "Descripcion"); ?></th>
                                    <th><?php echo custom_lang('sima_action', "Acciones"); ?></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade content-box" id="tab-10">
                    <div class="row-fluid">
                        <div class="col-md-12">
                            <!--<a id="btn_new_caja" href="#" class="btn btn-success"><?php echo custom_lang('sima_new_sales_man', "Nueva caja"); ?></a>
                            <a id="btn_new_caja_cierre" href="#" class="btn btn-success"><?php echo custom_lang('sima_new_sales_man', "Configurar cierre de caja"); ?></a>-->
                            <div class="col-md-2">
                                <a id="btn_new_caja" data-tooltip="Nueva Caja">
                                    <img alt="Nueva Caja" class="btnimagenes" src="<?php echo $this->session->userdata('new_imagenes')['mas_verde']['original'] ?>">
                                </a>
                            </div>
                            <div class="col-md-2">
                                <a id="btn_new_caja_cierre" data-tooltip="Configurar Caja">
                                    <img alt="Cierre" class="btnimagenes" src="<?php echo $this->session->userdata('new_imagenes')['configuracion_verde']['original'] ?>">
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="row-fluid">
                        <div class="span12">
                            <table class="table aTable" cellpadding="0" cellspacing="0" width="100%" id="proveedoresTable">
                            <thead>
                            <tr>
                                <th><?php echo custom_lang('sima_name_comercial', "id"); ?></th>
                                <th><?php echo custom_lang('sima_name_comercial', "Nombre"); ?></th>
                                <th><?php echo custom_lang('sima_nif', "Almacen"); ?></th>
                                <th  class="TAC"><?php echo custom_lang('sima_action', "Acciones"); ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                            <tr>
                                <th><?php echo custom_lang('sima_name_comercial', "id"); ?></th>
                                <th><?php echo custom_lang('sima_name_comercial', "Nombre"); ?></th>
                                <th><?php echo custom_lang('sima_reason', "Almacen"); ?></th>
                                <th  class="TAC"><?php echo custom_lang('sima_action', "Acciones"); ?></th>
                            </tr>
                            </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="tab-style-tables">
                    <div class="row">
                        <div class="col-md-12">
                            <h3>Estilo de mesas</h3>
                            <hr>
                            <br>
                            <p>Selecciona tu estilo de mesas</p>
                            <br>
                        </div>
                        <div class="col-md-2 ">
                            <div class="content-table <?=(get_option('table_selected') == 1) ? 'active-table' : '';?>" data-id="1">
                                <img src="<?=base_url('uploads/tables/mesa1_gris.svg')?>" alt="Mesa 1">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="content-table <?=(get_option('table_selected') == 2) ? 'active-table' : '';?>" data-id="2">
                                <img src="<?=base_url('uploads/tables/mesa2_gris.svg')?>" alt="Mesa 2">
                            </div>
                        </div>
                        <div class="col-md-2 ">
                             <div class="content-table <?=(get_option('table_selected') == 3) ? 'active-table' : '';?>" data-id="3">
                                 <img src="<?=base_url('uploads/tables/mesa3_gris.svg')?>" alt="Mesa 3">
                            </div>
                        </div>
                        <div class="col-md-2 ">
                             <div class="content-table <?=(get_option('table_selected') == 4) ? 'active-table' : '';?>" data-id="4">
                                 <img src="<?=base_url('uploads/tables/mesa4_gris.svg')?>" alt="Mesa 4">
                            </div>
                        </div>
                    </div>
                    <p></p>
                </div>

                <div class="tab-pane fade" id="tab-config-table">
                    <div class="row">
                        <div class="col-md-12">
                            <h3>Configuraciones Restaurante</h3>
                        </div>
                        <?php echo form_open_multipart("frontend/configuracion", array("id" => "validate")); ?>
                            <div class="span12">
                                <input type='hidden' name='form' value='config_restaurante'>
                            </div>
                            <?php if ($option == 'restaurante'): ?>
                                <div class="row-form" id="seccion_propinas">
                                <!--<div class="row-form">-->
                                    <input type="hidden" name="tipo_negocio2" id="tipo_negocio2" value="<?=$option?>" />
                                    <label class="check propina <?php echo ($option == 'restaurante') ? '' : 'hide'; ?>" style="margin-left: -15px;">
                                        <div class="form-row align-items-center">
                                            <div v-bind:class="{ 'col-md-12': !checked, 'col-sm-4 my-4': checked }">
                                                <input type="checkbox" name="propina" id="propina" value="propina" <?php echo ($propina == "si") ? 'checked' : ''; ?>> Propina:
                                            </div>
                                            <div class="col-sm-5 my-4" style="margin-left: -44px;">
                                                <input type="number" v-if="checked" min="0" name="propina_defaul_value" id="propina_defaul_value" v-model="propina_defecto" style="width: 55px;height: 20px;"> <small class="text-muted" v-if="checked">%</small>
                                            </div>
                                            <div v-if="checked" class="col-md-12">
                                                <small class="text-muted">(Este valor se puede modificar al momento de pagar)</small>
                                            </div>
                                        </div>
                                    </label>
                                    <div><small class="text-muted">(Este valor se puede modificar al momento de pagar)</small></div>
                                </div>
                                <div class="row-form">
                                    <div class="span12 control-check check cierre_caja_mesas_abiertas">
                                        <input type="checkbox"  name="cierre_caja_mesas_abiertas" id="cierre_caja_mesas_abiertas" value="cierre_caja_mesas_abiertas" <?php echo ($cierre_caja_mesas_abiertas == "si" || $cierre_caja_mesas_abiertas == '') ? 'checked' : ''; ?>>
                                        <span>Cerrar caja con mesas abiertas</span>
                                    </div>
                                </div>
                                <div class="row-form">
                                    <div class="span12 control-check">
                                        <input type="checkbox" name="eliminar_producto_comanda" id="eliminar_producto_comanda" <?php echo ($eliminar_producto_comanda == "si") ? 'checked' : ''; ?>>
                                        <span>Permitir eliminar productos con comanda </span>
                                        <a id="pop_productos_comanda" href="#" data-container="body" data-content="Se activará la opción Eliminar Productos cuando se tome un pedido con comanda." rel="popover" data-placement="right" data-original- data-trigger="hover">
                                            <span class="glyphicon glyphicon-question-sign icon-help-config" aria-hidden="true"></span>
                                        </a>
                                    </div>
                                </div>
                                <div class="row-form">
                                    <div class="span12 control-check">
                                        <input type="checkbox"  name="permitir_formas_pago_pendiente" id="permitir_formas_pago_pendiente" value="permitir_formas_pago_pendiente" <?php echo ($permitir_formas_pago_pendiente == "si") ? 'checked' : ''; ?>>
                                        <span>Permitir Formas de Pagos Pendiente en Facturación </span>
                                        <a id="pop_permitir_pagos_pendientes" href="#" data-container="body" data-content="Se activará la opción para permitir dejar pendientes las formas de pagos en la facturación." rel="popover" data-placement="right" data-original- data-trigger="hover">
                                            <span class="glyphicon glyphicon-question-sign icon-help-config" aria-hidden="true"></span>
                                        </a>
                                    </div>
                                </div>
                                <div class="row-form">
                                    <div class="span12 control-check">
                                        <input type="checkbox"  name="quick_service" id="quick_service" value="quick_service" <?php echo (isset($data['quick_service']) && $data['quick_service'] == "si") ? 'checked' : ''; ?>>
                                        <span>Quick service</span>
                                        <a id="pop_quick_service" href="#" data-container="body" data-content="Se activará la opción para vender como quick service." rel="popover" data-placement="right" data-original- data-trigger="hover">
                                            <span class="glyphicon glyphicon-question-sign icon-help-config" aria-hidden="true"></span>
                                        </a>

                                        <span id="content_quick_service_command" style="display: flex;align-items: center;">
                                            <input type="checkbox"  name="quick_service_command" id="quick_service_command" value="quick_service_command" <?php echo (isset($data['quick_service_command']) && $data['quick_service_command'] == "si") ? 'checked' : ''; ?>>
                                            <span>Comanda Fisica</span>
                                            <a id="pop_quick_service_command" href="#" data-container="body" data-content="Generar comanda fisica desde el Quick Service" rel="popover" data-placement="right" data-original- data-trigger="hover">
                                                <span class="glyphicon glyphicon-question-sign icon-help-config" aria-hidden="true"></span>
                                            </a>
                                        </span>
                                        <span id="content_comanda_virtual" style="display: flex;align-items: center;">
                                            <input type="checkbox"  name="comanda_virtual" id="comanda_virtual" value="comanda_virtual" <?php echo ($comanda_virtual == "si") ? 'checked' : ''; ?>>
                                            <span>Comanda Virtual </span>
                                            <a id="pop_comanda_virtual" href="#" data-container="body" data-content="Generar comanda virtual desde el Quick Service." rel="popover" data-placement="right" data-original- data-trigger="hover">
                                                <span class="glyphicon glyphicon-question-sign icon-help-config" aria-hidden="true"></span>
                                            </a>
                                        </span>
                                        <span id="content_domicilios" style="display: flex;align-items: center;">
                                            <input type="checkbox"  name="domicilios" id="domicilios" value="domicilios" <?php echo ($domicilios == "si") ? 'checked' : ''; ?>>
                                            <span>Domicilios </span>
                                            <a id="pop_domicilios" href="#" data-container="body" data-content="Se activará la opción de domicilios en el Quick Service." rel="popover" data-placement="right" data-original- data-trigger="hover">
                                                <span class="glyphicon glyphicon-question-sign icon-help-config" aria-hidden="true"></span>
                                            </a>
                                        </span>
                                        <!--<a target="_blank" href="https://help.vendty.com/">Cómo vendo como Quick service?</a>-->
                                    </div>
                                </div>
                                <div class="row-form">

                                </div>
                            <?php endif;?>
                            <div class="span12">
                                <div class="btn-group">
                                    <div class="span6">
                                        <button class="btn btn-default" type="button" onclick="javascript:location.href='../frontend/configuracion'">Cancelar</button>
                                    </div>
                                    <div class="span6">
                                        <button class="btn btn-success" type="submit" id="form_update_business" onclick="savelocal()">Guardar</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div> <!--fin-tab-config-table-->
            </div>
        </div>
    </div>
</div>
<?php //print_r($data['info_factura'][0]); die();?>
 <!-- Modal -->
        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="overflow-y:visible; left:-17%">
            <div class="modal-dialog modal-lg" role="document" style="margin: 0px auto !important;">
                <div class="modal-content">
                    <div class="modal-header" style="padding:15px;">
                        <h4 class="modal-title" id="myModalLabel">Información de Facturación</h4>
                        <span class="modal-title">La informacíon corresponde a tu identificación tributaria en tu país</span>
                    </div>
                    <form class="form-horizontal" id="formu_factura" action="<?=site_url("frontend/configuracion")?>"  method="post" >
                        <div class="modal-body">
                            <div class="alert alert-error hidden" id="mensaje_error"></div>
                                <input type="hidden" class="form-control" id="idempresafm"  name="idempresa" value="<?php echo $data['info_factura'][0]['id_empresa_cliente'] ?>" required>
                                <input type='hidden' name='form' value='facturacion'>
                                <input type='hidden' name='epayco' value='1'>
                                <div class="form-group">
                                    <label for="message-text" class="col-sm-3  control-label">Tipo Identificación: <span>*</span></label>
                                    <div class="col-sm-7">
                                        <select name="tipo_identificacion" id="tipo_identificacion_facturafm" required data-value="<?php echo set_value('tipo_identificacion', $data['info_factura'][0]['tipo_identificacion']) ?>">
                                            <?php
if ((empty($data['info_factura'][0]['tipo_identificacion']))) {
    echo '<option value="" selected >Seleccione</option>';
}

foreach ($data['info_factura_tipo_identificacion'] as $key => $value) {
    echo '<option value="' . $key . '">' . $value . '</option>';
}
?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="message-text" class="col-sm-3  control-label">Número de Identificación: <span>*</span></label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" id="numero_identificacionfm"  name="numero_identificacion" value="<?php echo $data['info_factura'][0]['numero_identificacion'] ?>" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="recipient-name" class="col-sm-3  control-label">Nombre/Razón Social: <span>*</span></label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" id="nombreempresafm"  name="nombre_empresa" value="<?php echo $data['info_factura'][0]['nombre_empresa'] ?>" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="message-text" class="col-sm-3  control-label">Dirección: <span>*</span></label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" id="direccion_empresafm"  name="direccion_factura" value="<?php echo $data['info_factura'][0]['direccion'] ?>" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="message-text" class="col-sm-3  control-label">Email: <span>*</span></label>
                                    <div class="col-sm-7">
                                        <input type="email" class="form-control" id="emailfm"  name="correo_factura" value="<?php echo $data['info_factura'][0]['correo'] ?>" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="message-text" class="col-sm-3  control-label">Nombre Contacto:</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" id="contacto_facturafm"  name="contacto_factura" value="<?php echo $data['info_factura'][0]['contacto'] ?>" >
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="message-text" class="col-sm-3  control-label">Teléfono:</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" id="telefonofm"  name="telefono_factura" value="<?php echo $data['info_factura'][0]['telefono'] ?>" >
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="message-text" class="col-sm-3  control-label">País: <span>*</span></label>
                                    <div class="col-sm-7">
                                        <select name="pais_factura" id="pais_facturafm" required data-value="<?php echo set_value('pais_factura', $data['info_factura'][0]['pais']) ?>">
                                                <?php
$selected = "";
if (empty($info_factura[0]['pais'])) {
    $info_factura[0]['pais'] = "Colombia";
}
/*if(($data['info_factura'][0]['pais']==0)){
echo '<option value="" selected >Seleccione</option>';
}*/

foreach ($data['info_factura_pais'] as $key => $value) {
    if ($data['info_factura'][0]["pais"] == $key) {
        $selected = "selected";
    } else {
        $selected = "";
    }
    echo "<option value='$key' $selected >$value</option>";
}
?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="message-text" class="col-sm-3  control-label">Cuidad/Provincia: <span>*</span></label>
                                    <div class="col-sm-7">
                                       <select name="ciudad_factura" id="ciudad_facturafm" required data-value="<?php echo set_value('ciudad_facturafm', $data['factura'][0]['ciudad']) ?>">
                                            <?php
if (($data['info_factura'][0]['ciudad'] == 0)) {
    echo '<option value="" selected >Seleccione</option>';
}
echo '<option value="' . $data['info_factura'][0]['ciudad'] . '">' . $data['info_factura'][0]['ciudad'] . '</option>';
?>
                                        </select>
                                    </div>
                                </div>
                        </div>
                        <div class="modal-footer">
                            <?php
if (!empty($data['info_factura'][0]['tipo_identificacion'])) {
    $nombrebtn = "Confirmar y Comprar";
} else {
    $nombrebtn = "Guardar y Comprar";
}
?>
                            <input type="submit" class="btn btn-success" value='<?=$nombrebtn?>' />
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <!-- modal-->

<div class="social">
		<ul>
			<li><a id="modal-click-vimeo" href="#myModalvideovimeo"  class="glyphicon glyphicon-play-circle"></a></li>
		</ul>
	</div>
     <!-- vimeo-->
    <div id="myModalvideovimeo" class="modal fade">
    <div style="padding:56.25% 0 0 0;position:relative;">
            <iframe id="cartoonVideovimeo" src="" style="position:absolute;top:0;left:0;width:100%;height:100%;" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
        </div>
    </div>




    <div class="modal fade"  id="modal-box" tabindex="-1" role="dialog">
        <div class="" role="document">
            <div class="">
                <div>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="right: 13px;position: absolute;"><span aria-hidden="true">&times;</span></button>
                    <h4 class="text-center p-5">Editar caja</h4>
                </div>

                <div class="col-md-10 col-md-offset-1 form-group">
                    <input type="hidden" class="form-control" id="input-box-id" value="">
                    <input type="hidden" class="form-control" id="input-box-store" value="">
                    <input type="text" class="form-control" id="input-box-name" value="">
                </div>

                <div class="col-md-10 col-md-offset-1">
                    <button type="button" class="btn btn-success mb-10 pull-right" id="saveBox">Editar caja</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->



    <!-- Modal Abonar factura -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div id="modalInternet" class="modal-dialog modal-center">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" style=" text-align: center; color: rgba(0,0,0,0.7);padding: 5px;">Recortar logo</h4>
                </div>

                <div class="modal-body">
                    <div class="container">
                        <p>vista previa</p>
                        <div style="overflow: hidden; width: 150px; height: 150px; padding-top: 1em;" class="preview"></div>
                        <div class="input-append file">
                            <input type="file" name="logotipo" id="logotipo" accept="image/x-png,image/gif,image/jpeg" onchange="readURL(this);"/>
                            <button class="btn btn-success" type="button"><?php echo custom_lang('sima_search', "Buscar en Mi PC"); ?></button>
                        </div>
                        <div style="text-align: -webkit-center; padding-top: 1em;">
                            <img style="padding-top: 1em;" id="image">
                        </div>
                    </div>
                </div>

                <div class="modal-footer" style="">
                    <button id="btnNoOffline" type="button" class="btn btn-default" data-dismiss="modal" style="padding: 5px 20px 5px 20px;"> Cancelar </button>
                    <button id="btnGuardarAbono" type="button" class="btn btn-success" data-dismiss="modal" style="padding: 5px 20px 5px 20px;"> Guardar </button>
                </div>

            </div>
        </div>
    </div>
    <!-- END MODAL -->

    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.8/dist/vue.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.18.0/axios.min.js"></script>
    <script>
        var app = new Vue({
            el: '#woocommerce',
            data: {
                ck: '',
                cs: '',
                message: ''
            },
            methods: {
                generate: function () {
                    this.message = 'Estamos generando tus credenciales para Woocommerce.';
                    axios.get('<?php echo site_url('woocommerce/generate_credentials') ?>').then(res => {
                        data = res.data;
                        this.ck = data.ck;
                        this.cs = data.cs;

                        this.message = '';
                    });
                }
            }
        });

        function savelocal(){
            if($('#propina_defaul_value').val()){
                localStorage.porcentage_default = $('#propina_defaul_value').val();
            }else{
                localStorage.porcentage_default = 0;
            }
        }


        var seccion_propinas = new Vue({
            el: '#seccion_propinas',
            data:  {
                test: 'ejemplo',
                checked: true,
                propina_defecto: localStorage.porcentage_default ? Number(localStorage.porcentage_default) : 10
            },
            computed: function() {
                //valor por defecto del porcentaje 0
                if(!localStorage.porcentage_default)
                {
                    localStorage.porcentage_default = 10;
                }
                $('#propina').change(function() {
                    if($('#propina').is(":checked")){
                    this.checked = true;
                    }else{
                        this.checked = false;
                    }
                })

                if($('#propina').is(":checked")){
                    this.checked = true;
                }else{
                    this.checked = false;
                }
            },
        });

        if($('#propina').is(":checked")){
            seccion_propinas._data.checked = true;
        }else{
            seccion_propinas._data.checked = false;
        }

        $('#propina').change(function() {
            if($('#propina').is(":checked")){
                seccion_propinas._data.checked  = true;
            }else{
                seccion_propinas._data.checked = false;
            }
        })
    </script>

    <script>

        function loadModalBox(id){
            let url = "<?=site_url('caja/getBox');?>/"+id;
            $.get(url,function(data){
                let dates = JSON.parse(data);
                //console.log(dates);
                $("#input-box-id").val(dates.id);
                $("#input-box-name").val(dates.nombre);
                $("#input-box-store").val(dates.id_Almacen);
                $("#modal-box").modal('show');
            })
        }

        $("#saveBox").click(function(){
            let url = "<?=site_url('caja/saveBox');?>";
            let id = $("#input-box-id").val();
            let name = $("#input-box-name").val();
            let store = $("#input-box-store").val();

            $.post(url,{
                id: id,
                name: name,
                store: store
            },function(data){
                let dates = JSON.parse(data);
                switch(dates.message){
                    case 'duplicate' :
                        swal({
                            position: 'center',
                            type: 'error',
                            title: 'Error, el nombre de la caja ya se encuentra en el almacen',
                            showConfirmButton: false
                        });
                    break;

                    case 'error' :
                        swal({
                            position: 'center',
                            type: 'error',
                            title: 'Error al editar caja',
                            showConfirmButton: false
                        });
                    break;

                    case 'success' :
                        swal({
                            position: 'center',
                            type: 'success',
                            title: 'Caja editada correctamente',
                            showConfirmButton: false,
                            timer:1500
                        });
                        setTimeout(function(){
                            location.reload(true);
                        }, 1600);
                    break;
                }
            })
        })

    </script>

<script>
     $("#cartoonVideovimeo").attr('src','https://player.vimeo.com/video/266923686?color=ffffff&title=0&byline=0&portrait=0');

    $("#modal-click-vimeo").click(function(e){
        e.preventDefault();

        var id_pane = '';
        $("#myModalvideovimeo").modal('show');

        $(".pane-open").each(function(index,element){
            var pane = $(this).find('.in');
            id_pane = pane.attr('id');
        })

        $('.tab_config').each(function(index,element){

            var collapse = $(this).find('.in');
            var active = collapse.find('.active');
            var src = active.find('a').data('src');
            var id_active = active.find('a').data('pane');

            if(id_active == id_pane){
                $("#cartoonVideovimeo").attr('src',src);
            }
        })

    })

</script>
<script type="text/javascript" src="https://checkout.epayco.co/checkout.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/cropper/4.0.0/cropper.min.js"></script>

<script src="/public/js/app/md5.js"></script>
<script type="text/javascript">
    var logo_blob;

    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                cropper.destroy();
                cropper.replace(e.target.result);
                //$('#image').attr('src', e.target.result);
            };

            reader.readAsDataURL(input.files[0]);
            //(input.files[0]);


        }
    }
    var cropper;

    var ippais='<?php echo $paisip ?>';
    var currency="cop";

    if(ippais!="Colombia"){
        currency='usd';
    }else{
        currency="cop";
    }

    var amounttotales=0;
    var referenceCodetotales=0;
    var referenceCode = "";
    var epaycooption=1;
    var keyepayco = 'a9743da1bac57f18aeef6b484a2dec95';
    var keyepayco2 = '2815e60ed8e00cbfc47180d0d37a7a6c';
$(document).ready(function(){

    $('#btnGuardarAbono').click(function(event){
        event.preventDefault();
        cropper.getCroppedCanvas({
            width: 300,
            height: 300,
            minWidth: 150,
            minHeight: 150,
            maxWidth: 300,
            maxHeight: 300,
            fillColor: '#fff',
            imageSmoothingEnabled: false
        }).toBlob((blob) => {
            var formData = new FormData($('#validate')[0]);
            formData.append('croppedImage', blob);

            $.ajax({
                url: "<?php echo site_url("frontend/change_logo"); ?>",
                data: formData,
                type: 'POST',
                dataType : 'json',
                success: function(data){
                    if(data.status){
                        location.reload();
                    }
                },
                processData:false,
                contentType: false,
                cache: false
            });
        });
        return false;
    });
    //Jeisson Rodriguez - 25/06/2019
    var $image = $('#image');

    $image.cropper({
        aspectRatio: 640 / 640,
        resizable: false,
        preview: '.preview',
        data: {
            width: 150,
            height: 150,
        },
        cropend: function(event) {
            console.log(event.detail.width);
            /*
            cropper.getCroppedCanvas({
                width: 150,
                height: 150,
                minWidth: 150,
                minHeight: 150,
                maxWidth: 150,
                maxHeight: 150,
                fillColor: '#fff',
                imageSmoothingEnabled: false
            }).toBlob((blob) => {
                var formData = new FormData();
                formData.append('croppedImage', blob);

                $.ajax("<?php echo site_url("frontend/configuracion"); ?>", {
                    method: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function () {
                    console.log('Upload success');
                    },
                    error: function () {
                    console.log('Upload error');
                    }
                });
            });*/

        }
    });

    // Get the Cropper.js instance after initialized
    cropper = $image.data('cropper');

    if($("#impresion_rapida").prop('checked')){
        $(".content-apikey").removeClass('hidden');
    }

    $('#btn_pagar').on('click',function(e){
        e.preventDefault();
        var d = new Date();
        var n = d.getTime();
        if(sumar()){
            $('#form_payment').submit();
        }else{
            alert("No es posible realizar el pago seleccione al menos una licencia");
        }
        //
    });


    var referenceCode = "";
    $('#btn_pagar_licencia').on('click',function(e){
        var idPlan = $('#plan_cliente_id').val();
        var totalSUM = 0;
        referenceCode = 'Licencia Vendty';
        var n = 0;
        extra1="";
        var puedePagar = false;

        $("tbody tr input:checkbox").each(function () {
            var getValue = $(this).parent().parent().find("td:eq(6)").html();
            if ($(this).is(':checked')) {
                puedePagar = true;
                getValue2=$(this).data('id');
                extra1 +=getValue2+"_";
                active = true;
                referenceCode += (n==0) ? $(this).data('id') + "_" + getValue : '-' + $(this).data('id') + "_" + getValue;
                totalSUM +=Number(getValue);
                n ++;
            }
        });

        if(extra1!="") {
            extra1=extra1.substr(0, extra1.length - 1);
        }

        var amount = totalSUM;
        // Update footer
        $( "#foot-table" ).empty();
        $('#foot-table').append('Total: ' + amount + '$');
        var factura=Math.random()+"1238_"+Math.random();

        dataepa={
            //Parametros compra (obligatorio)
            name: "Pago de Licencia",
            description: referenceCode,
            invoice: factura,
            currency: currency,
            amount: amount,
            extra1: extra1,
            tax_base: "0",
            tax: "0",
            country: "co",
            lang: "es",
            external: "true",
            confirmation: "http://pos.vendty.com/index.php/response",
            response: "http://pos.vendty.com/index.php/response"
        }
        //console.log("bendita licencia", referenceCode);
        licenciasAPagar = referenceCode;


        if(idPlan == 1) {
            //alert('licencia 1');
            mostrarPlanes();
        } else {
            //alert('licencia '+ idPlan);
            if(puedePagar) {
                mostrarMetodos();
            } else {
                swal({
                    position: 'center',
                    type: 'warning',
                    title: 'Debe seleccionar un licencia para realizar el pago',
                    showConfirmButton: true,
                });
            }
        }
        //mostrarPlanes();
        //ePayco.checkout.configure({key: keyepayco, test: false}).open(dataepa);
        pagarLicenciapaypal();

    });

    $('#pagoPSE').on('click',function(e){
        //('pago pse');
        var idPlanActual = $('#plan_cliente_id').val();
        if(idPlanActual == 1) {
            api_auth = JSON.parse(localStorage.getItem('api_auth'));
            licenciaAlmacen = api_auth.user.db_config.license.idlicencias_empresa;
            referenceCode = dataepa.description;
            referenceCode = 'Licencia Vendty' + licenciaAlmacen+ "-" + referenceCode.split('-')[1];
            dataepa.description = referenceCode;
            //console.log(dataepa);
            ePayco.checkout.configure({key: keyepayco2, test: false}).open(dataepa);
        }else {
            if(!$.isEmptyObject(dataepa)) {
                ePayco.checkout.configure({key: keyepayco2, test: false}).open(dataepa);
            }
        }
    })

    $('#pagoEfectivo').on('click',function(e){
        //console.log('pago efectivo');
        var idPlanActual = $('#plan_cliente_id').val();
        if(idPlanActual == 1) {
            api_auth = JSON.parse(localStorage.getItem('api_auth'));
            licenciaAlmacen = api_auth.user.db_config.license.idlicencias_empresa;
            referenceCode = dataepa.description;
            referenceCode = 'Licencia Vendty' + licenciaAlmacen+ "-" + referenceCode.split('-')[1];
            dataepa.description = referenceCode;
            //console.log(dataepa);
            ePayco.checkout.configure({key: keyepayco, test: false}).open(dataepa);
        }else {
            if(!$.isEmptyObject(dataepa)) {
                ePayco.checkout.configure({key: keyepayco, test: false}).open(dataepa);
            }
        }
    })

    //$('#btn_pagar_licencia_paypal').on('click',function(e){
    function pagarLicenciapaypal(){
        epaycooption=0;
        var totalSUM = 0;
        var referenceCode = 'Licencia Vendty';
        var n = 0;

        $("tbody tr input:checkbox").each(function () {
            var getValue = $(this).parent().parent().find("td:eq(6)").html();
            amount = $(this).data('id').split("_")[1];
            if ($(this).is(':checked')) {
                active = true;
                referenceCode += (n==0) ? $(this).data('id') + "_" + getValue : '-' + $(this).data('id') + "_" + getValue;
                totalSUM +=Number(amount);
                n ++;
            }
        });

        var amount = totalSUM;
        amounttotales=amount;
        referenceCodetotales=referenceCode;

        // Update footer
        $( "#foot-table" ).empty();
        $('#foot-table').append('Total: ' + amount + '$');
        var factura=Math.random()+"1238_"+Math.random();

        if(amount>0){
            //$('#myModal').modal('show');
            //paypal
            paypal.Button.render({
                // Configure environment
                //env: 'sandbox',
                env: 'production',
                client: {
                    //sandbox: 'AZDxjDScFpQtjWTOUtWKbyN_bDt4OgqaF4eYXlewfBP4-8aqX3PiV8e1GWU6liB2CUXlkA59kJXE7M6R',
                    //sandbox: 'AcSRYJUbpgZvmkBDQHn7v9WzZtJCQMhUX0RwFKFHNQo-yDxagdwHHlqxSJ1P6LLBtB31h8nSwFa4LJFM',//desarrollo
                    //sandbox: 'Aa2EMMw-BiUCVpwm1l28zklLP2IjeZVoOkiN9uo8u4eRYIleS7uWpSre0DL7toqBYrQgBYP4PY2Sixz9',//facilitador
                    production: 'AUsz8hM_W5fTD4QmgE3GG9hk5jQ2I9Doc_TNW7pbgb10D1_PLURGjQDTwRSvanqLF6_E7u-9gZHmf97o'//arnulfoospino
                },

                locale: 'es_CO',
                style: {
                    label: 'paypal',
                    size:  'small', // small | medium | large | responsive
                    shape: 'rect',   // pill | rect
                    color: 'silver'   // gold | blue | silver | black
                },
                // Enable Pay Now checkout flow (optional)
                commit: true,

                // Set up a payment
                payment: function(data, actions) {
                    return actions.payment.create({
                        transactions: [{
                        amount: {
                            total: amounttotales,
                            currency: 'USD'
                        },
                        description: referenceCodetotales,

                        }],
                    });
                },
                // Execute the payment
                onAuthorize: function(data, actions) {
                //onAuthorize: function(payload) {
                    return actions.payment.execute().then(function(data) {
                    //return actions.payment.get().then(function(data) {
                        var shipping = data.payer.payer_info.shipping_address;
                        var status = data.state;
                        // Show a confirmation message to the buyer
                        if(status=="approved"){

                            $.ajax({
                                url: "<?php echo site_url("frontend/responsepaypal") ?>",
                                data:  {'data':data,'referencia':referenceCodetotales,'total':amounttotales}, //datos que se envian a traves de ajax
                                type:  'post', //método de envio
                                dataType: "json",
                                success: function(data) {
                                    if(data.success==1){
                                        swal({
                                            position: 'center',
                                            type: 'success',
                                            title: 'La licencia fue pagada exitosamente',
                                            showConfirmButton: false,
                                            timer:1500
                                        });
                                        setTimeout(function(){
                                            location.reload(true);
                                        }, 1600);
                                    }else{
                                        swal({
                                            position: 'center',
                                            type: 'error',
                                            title: 'Hubo un error',
                                            showConfirmButton: false,
                                            timer:1500
                                        });
                                        setTimeout(function(){
                                            location.reload(true);
                                        }, 1600);
                                    }
                                }
                            });
                        }

                    });
                },
                onError: function (err) {
                    // Show an error page here, when an error occurs
                    //window.alert('Hubo un error');
                    swal({
                        position: 'center',
                        type: 'error',
                        title: 'Disculpe, hubo un error al realizar la transacción, intente más tarde',
                        showConfirmButton: false,
                        timer:1500
                    });
                }
            }, '#paypal-button');
        }
    }

    $("#link_licencias").click(function (e) {
        //$('#myModal').modal('show');
        $('#myModal').modal({
            keyboard: false,
            backdrop:'static'
        });

    });

    //veifico los datos
    $("#formu_factura").submit(function (e) {
        e.preventDefault();
        nombre=$.trim($("#nombreempresafm").val());
        direccion=$.trim($("#direccion_empresafm").val());
        /*telefono=$.trim($("#telefonofm").val());
        contacto=$.trim($("#contacto_facturafm").val());*/
        email=$.trim($("#emailfm").val());
        tipo_identificacion=$.trim($("#tipo_identificacion_facturafm").val());
        numero_identificacion=$.trim($("#numero_identificacionfm").val());
        pais=$.trim($("#pais_facturafm").val());
        ciudad=$.trim($("#ciudad_facturafm").val());
        if((nombre!="")&&(direccion!="")&&(email!="")&&(tipo_identificacion!="")&&(numero_identificacion!="")&&(pais!="")&&(ciudad!="")){

            url=$(this).attr('action');
            $("#mensaje_error").addClass("hidden");
            $("#mensaje_error").html("");
            $.ajax({
                url: url,
                type:  'post', //método de envio
                dataType: "json",
                data:  $("#formu_factura").serialize(),
                success: function(data) {
                    //console.log(data);
                    if(data.success==1){
                        if(ippais!="Colombia"){
                            pagarLicenciapaypal();
                        }
                        $('#myModal').modal('hide');
                        //alert(epaycooption);
                        //epayco
                       /* if(epaycooption==1){
                            ePayco.checkout.configure({key: keyepayco, test: false}).open(dataepa);
                        }else{

                        }*/
                    }else{
                        $("#mensaje_error").removeClass("hidden");
                        $("#mensaje_error").html("Todos los campos son obligatorios");
                    }
                }
            });

        }else{
            $("#mensaje_error").removeClass("hidden");
            $("#mensaje_error").html("Todos los campos son obligatorios");
        }
    });

    $('.radio').css('display', '');
    $('.checker').css('display', '');


    wEditor = $("#wysiwyg").cleditor({width:"100%", height:"300px"});

    $("#header").cleditor({height:"200px"});

    $("#terms").cleditor({height:"200px"});

    //$("#almacen").change(function () {
    //var almacen = $(document).on('#almacen');

    $(document).on('change','#slt_almacen',function(){

        var almacen = $(this).val();
        bodega=0;

        //verifico si es bodega
        $.ajax({
            url: "<?php echo site_url("almacenes/get_Bodega") ?>",
            data: {"almacen" : almacen},
            type:'POST',
            success: function(data) {
                bodega=data.success;
                $.post("<?php echo site_url("usuarios/almacen_caja"); ?>", {
                    almacen: almacen,
                }, function (data) {
                    $(document).find("#caja").html(data);
                    if(bodega==1){
                        $(document).find("#caja").val(0);
                        $(document).find("#caja").prop('disabled',true);

                    }else{
                        $(document).find("#caja").prop('disabled',false);
                    }
                });
            }
        });


        $.post("<?php echo site_url("usuarios/almacen_caja"); ?>", {
            almacen: almacen
        }, function (data) {
            $(document).find("#caja").html(data);
        });
    });

    /*$("#validate").submit(function () {
        var result = validaFields( $(this) );

        return result;
    });*/

    $('select.multiple').mousedown(function(e){
		    e.preventDefault();
			var select = this;
		    var scroll = select.scrollTop;
		    e.target.selected = !e.target.selected;
		    setTimeout(function(){select.scrollTop = scroll;}, 0);
		    $(select).focus();
		    agregarcampo();
	}).mousemove(function(e){e.preventDefault()});


    var agregarcampo = function()
    {
        var vars = '';
        var href = $('#btn_exportar').data('url');
        $('select.multiple option:selected').each(function(i, e){
            vars += $(e).val()+'|';
        });

        $('#btn_exportar').prop('href', href+'/'+vars);
    }


    if($('.alert-msg').length)
    {
            $('.alert-msg ').show();
            setTimeout(function(){
                    $('.alert-msg').hide();
            }, 15000); //<-- redirect after 5 secs
    }
    //$('.confirm-div').hide();
    ///<?php if ($this->session->flashdata('msg')) {?>
    //alert("ad");
     //   $('.confirm-div').html('<?php echo $this->session->flashdata('upload_status'); ?>').show();
    //});
    //<?php }?>

        // Convertimos a Switchery
         defaults = {
            color             : '#69d65a'
          , secondaryColor    : '#f4f8f9'
          , jackColor         : '#fff'
          , jackSecondaryColor: null
          , className         : 'switchery'
          , disabled          : false
          , disabledOpacity   : 0.5
          , speed             : '0.1s'
          , size              : 'default'
        }
        var opcObj = {};
        $('.contListasSwitch input[type=checkbox]').each(function(){
                // Obtenemos el id de cada input
                var elementId = $(this).attr("id");

                // convertimos el check a switchery
                new Switchery( $('#'+elementId)[0], defaults );

                // almacenamos el elemento dom en el array opcObj
                opcObj[ elementId ] = $(this);
        });
        $("#dialog-confirmacion-form").dialog({
            autoOpen: false,
            height: 275,
            width: 570,
            modal: true,
        });
        $("#dialog-muestra-form").dialog({
            autoOpen: false,
            height: 400,
            width: 570,
            modal: true,
        });

         function muestra()
    {
        $("#dialog-muestra-form").dialog("open");
        var html = "<div class='row-form'><div class='col-md-4'>Modulo<hr></div><div class='col-md-8'>Almacen<hr></div></div>";
        if($('input#cli').prop("checked") == true)
        {
            html = "<div class='row-form'><div class='col-md-4'><code align=right>Clientes:</code></div><div class='col-md-8'>Todos los Almacenes</div></div>";
        }
        if($('input#proveedores').prop("checked") == true)
        {
            html += "<div class='row-form'><div class='col-md-4'><code>Proveedores:</code></div><div class='col-md-8'>Todos los Almacenes</div></div>";
        }
        if($('input#pro').prop("checked") == true)
        {
            html += "<div class='row-form'><div class='col-md-4'><code>Productos:</code></div><div class='col-md-8'>Todos los Almacenes</div></div>";
        }
        /*if($('input#ven').prop("checked") == true)
        {
            html += "<div class='row-form'><div class='col-md-4'><code>Ventas:</code></div><div class='col-md-8'>Todos los Almacenes</div></div>";
        } */
        var modulo = "";
        $('form#reinicio').find('input').each(function(i,e){
            if(i >2)
            {
                if($(e).prop("checked") == true)
                {
                    moduloActual = $(e).attr("data-titulo");
                    almacen = $(e).attr("data-almacen");
                    if(moduloActual != modulo)
                    {
                        html += "<div class='row-form'><div class='col-md-4'><code>"+moduloActual+":</code></div><div class='col-md-8'>"+almacen+"</div></div>";
                        modulo = moduloActual;
                    }else
                    {
                        html += "<div class='row-form'><div class='col-md-4'></div><div class='col-md-8'>"+almacen+"</div></div>";
                    }
                }

            }
        });
        $("#dialog-muestra-form").find("div.muestra").empty();
        $("#dialog-muestra-form").find("div.muestra").append(html);
    }
    $(document).on('submit','form#reinicio',function(event){
        event.preventDefault();
        var seleccionado = false;
        $('.contListasSwitch input[type=checkbox]').each(function(i,e){
            if($(e).prop('checked') == true)
            {
                seleccionado = true;
            }
        });
        if(seleccionado == true)
        {
            if(confirm("¿Esta seguro que desea reiniciar la informacion seleccionada?"))
            {
                //if($('input#pro').prop("checked") == true && confirm("Se a seleccionado productos, recuerde que al elegir productos se reiniciaran todos los modulos menos clientes y proveedores,¿Esta seguro de reiniciar los modulos seleccionados?"))
                //{
                  //  muestra();

                    $.post
                    (
                        "<?php echo site_url("restablecer/resetEmail") ?>",
                        $('form').serialize(),
                        function(data){
                    if(data.resp == 1)
                    {
                        alert("Los modulos elegidos han sido reiniciados");
                        location.href = "<?php echo site_url("frontend/configuracion") ?>";
                    }else if(data.resp == 2)
                    {
                            alert("El email y password no coinciden");
                            $("#reiniciarSubmit").removeAttr("disabled");
                    }
                    },'json');
                //}
                //muestra();
            }
        }else{
            alert("No a seleccionado ningun modulo para reinicio");
        }
    });
    $(document).on('submit','form#muestra-form',function(event){
       event.preventDefault();
       $("#dialog-muestra-form").dialog("close");
       $("#dialog-confirmacion-form").dialog("open");
    });
    $(document).on('submit','form#confirmacion-form',function(event){
        event.preventDefault();
        $("#reiniciarSubmit").attr("disabled","disabled");
        $.post
        (
           "<?php echo site_url("restablecer/resetEmail") ?>",
            $('form').serialize(),
            function(data){
                if(data.resp == 1)
                {
                    alert("Los modulos elegidos han sido reiniciados");
                    location.href = "<?php echo site_url("frontend/configuracion") ?>";
                }else if(data.resp == 2)
                {
                    alert("El email y password no coinciden");
                    $("#reiniciarSubmit").removeAttr("disabled");
                }
            },'json'
        );
    });
    $(document).on('click','input#cancelar',function(){
        $(this).parents("form").parent().dialog("close");
    });

    //obtenemos los impuestos via ajax

    var impuestoTable = $(document).find('#impuestosTable'),
        btn_new_impuesto = $(document).find('#btn_new_imp');
    impuestoTable.dataTable( {
            "bProcessing": true,
            "bServerSide": true,
            "sAjaxSource": "<?php echo site_url("impuestos/get_ajax_data"); ?>",
            "sPaginationType": "full_numbers",
            "iDisplayLength": 5, "aLengthMenu": [5,10,25,50,100],
            "aoColumnDefs" : [
                {
                    "bSortable": false, "aTargets": [ 1 ], "bSearchable": false,
                    "mRender": function ( data, type, row ) {
                        predeterminado = data == false ? 'NO' : 'SI';
                        return predeterminado;
                    }
                },
                { "bSortable": false, "aTargets": [ 3 ], "bSearchable": false,
                    "mRender": function ( data, type, row ) {
                        var buttons = "";
                        var buttons = '<a href="<?php echo site_url("impuestos/editar/"); ?>/'+data+'" class="button default acciones"><div class="icon"><img alt="editar" data-cambiar="<?php echo $this->session->userdata('new_imagenes')['editar']['cambio'] ?>" data-original="<?php echo $this->session->userdata('new_imagenes')['editar']['original'] ?>" class="iconacciones" src="<?php echo $this->session->userdata('new_imagenes')['editar']['original'] ?>"></div></a>';
                        return buttons;
                    }
                }
            ]
    });

    var impuestoDialog = impuestoDialog || (function ($) {
        	var $dialog = $(
            '<div class="modal fade" data-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true" style="padding-top:5%; overflow-y:visible;">' +
            '<div class="modal-dialog modal-m">' +
            '<div class="modal-content">' +
                '<div class="modal-header" style="padding:15px;"><h4>Agregar nuevo Impuesto</h4></div>' +
                '<div class="modal-body">' +
                  '<form id="frm_imp" method="post" action="<?php echo site_url("frontend/configuracion") ?>" accept-charset="utf-8" id="validate">'+
                  '<input type="hidden" name="form" value="impuestos">'+
                  '  <div class="form-group">'+
                  '      <label for="recipient-name" class="control-label">Nombre:</label>'+
                  '      <input type="text" class="form-control" name="nombre">'+
                  '  </div>'+
                  '<div class="form-group">'+
                  '      <label for="message-text" class="control-label">Porciento:</label>'+
                  '      <input class="form-control" name="porciento"/>'+
                  ' </div>'+
                  '<div class="form-group">'+
                    '<label for="">Desea que este sea su impuesto predeterminado</label><br>'+
                        '<label class="switch">'+
                        '<input type="checkbox" name="predeterminado">'+
                        '<span class="slider round"></span>'+
                        '</label>'+
                  '</div>'+

                  '<div class="btn-group" style="padding:2%;">'+
                  '<button data-dismiss="modal" class="btn btn-default" type="button">Cerrar</button>'+
                  '<button class="btn btn-success" type="submit">Enviar</button>'+
                  '</div>'+
                '    </form>'+
                '</div>'+
            '</div></div></div>');
            return {
		        show: function (message, options) {
                    	var settings = $.extend({
                        dialogSize: 'm',
                        progressType: '',
                        onHide: null // This callback runs after the dialog was hidden
                    }, options);
                    $dialog.modal();
                },
                hide: function () {
                    $dialog.modal('hide');
                }
            }
    })($);

    btn_new_impuesto.on('click',function(){
        impuestoDialog.show();
    });


    //Tabla de licencias
    var id_usuario = <?php echo $this->ion_auth->get_user_id(); ?>;
    $('#licenciasTable').dataTable({
        "bProcessing": true,
        "sAjaxSource": "<?php echo site_url("licencias/get_ajax_data"); ?>",
        "fnRowCallback": function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
            var d = new Date();
            mes=d.getMonth()+1;
            mes=mes<9?"0"+mes:mes;
            var datestring = d.getFullYear() +"-"+ mes + "-"+ d.getDate();
            var api = this.api();
            var total = 0;
            if(aData[4] == ''){
            var intVal = function ( i ) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '')*1 :
                    typeof i === 'number' ?
                        i : 0;
                };

                if(aData[4] > datestring) {
                // Total over all pages
                total = api
                    .column( 6 )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );
                pageTotal = api
                    .column( 6, { page: 'current'} )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );
                }
            }
            /*if(total > 0)
                $('#btn_pagar').html('Pagar: '+total);
            else
                $('#btn_pagar').attr('disabled','disabled');*/


            $('td', nRow).css('background', aData[4] < datestring ? '#fff291' : '#f2f2f2');
                return nRow;

        },
        "fnFooterCallback": function ( row, aData, start, end, display ) {
            var api = this.api(), data;

            // Remove the formatting to get integer data for summation
            var intVal = function ( i ) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '')*1 :
                    typeof i === 'number' ?
                        i : 0;
            };

            // Total over all pages
            total = api
                .column( 6 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
            pageTotal = api
                .column( 6, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );

           // $('#btn_pagar').html('Pagar: '+total);
        },
        "sPaginationType": "full_numbers",
        "iDisplayLength": 5, "aLengthMenu": [5,10,25,50,100],
        "order": [[ 4, "asc" ],[ 6, "desc" ]],
        "aoColumnDefs" : [
            { "bSortable": false, "aTargets": [ 7 ], "bSearchable": false,
                "mRender": function ( data, type, row ) {
                    var d = new Date();
                    mes=d.getMonth()+1;
                    mes=mes<9?"0"+mes:mes;
                    var datestring = d.getFullYear() +"-"+ mes + "-"+ d.getDate();
                    var checket = '';
                    if(row[5] =='Inactiva')
                        checket = 'checked';
                        var buttons = '';
                        if(data !=id_usuario){

                            buttons += '<input name="'+row[2]+'" data-id="'+data+"_"+row[8]+'" type="checkbox" '+checket+'>';
                        }

                    return buttons;
                }
            }
        ]
    });
    /*
    //pagos Facturas Asociadas
    $('#pagosTable').dataTable({
        "bProcessing": true,
        "sAjaxSource": "<?php echo site_url("licencias/get_ajax_data"); ?>",
        "fnRowCallback": function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
            var d = new Date();
            mes=d.getMonth()+1;
            mes=mes<9?"0"+mes:mes;
            var datestring = d.getFullYear() +"-"+ mes + "-"+ d.getDate();
            var api = this.api();
            var total = 0;
            if(aData[4] == ''){
            var intVal = function ( i ) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '')*1 :
                    typeof i === 'number' ?
                        i : 0;
                };

                if(aData[4] > datestring) {
                // Total over all pages
                total = api
                    .column( 6 )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );
                pageTotal = api
                    .column( 6, { page: 'current'} )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );
                }
            }

            $('td', nRow).css('background', aData[4] < datestring ? '#fff291' : '#f2f2f2');
                return nRow;

        },
        "fnFooterCallback": function ( row, aData, start, end, display ) {
            var api = this.api(), data;

            // Remove the formatting to get integer data for summation
            var intVal = function ( i ) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '')*1 :
                    typeof i === 'number' ?
                        i : 0;
            };

            // Total over all pages
            total = api
                .column( 6 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
            pageTotal = api
                .column( 6, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );

           // $('#btn_pagar').html('Pagar: '+total);
        },
        "sPaginationType": "full_numbers",
        "iDisplayLength": 5, "aLengthMenu": [5,10,25,50,100],
        "order": [[ 4, "asc" ],[ 6, "desc" ]],
        "aoColumnDefs" : [
            { "bSortable": false, "aTargets": [ 7 ], "bSearchable": false,
                "mRender": function ( data, type, row ) {
                    console.log();
                    var d = new Date();
                    mes=d.getMonth()+1;
                    mes=mes<9?"0"+mes:mes;
                    var datestring = d.getFullYear() +"-"+ mes + "-"+ d.getDate();
                    var checket = '';
                    if(row[5] =='Inactiva')
                        checket = 'checked';
                        var buttons = '';
                        if(data !=id_usuario){
                            buttons += '<input data-id="'+data+'" type="checkbox" '+checket+'>';
                        }
                    return buttons;
                }
            }
        ]
    });*/

    $(window).load(function() {
        sumar();
    });

    $('#licenciasTable tbody').on('click', 'input', function () {
        sumar();
    });

    function rand_code(chars, lon){
        code = "";
        for (x=0; x < lon; x++){
            rand = Math.floor(Math.random()*chars.length);
            code += chars.substr(rand, 1);
        }
        return code;
    }

    function sumar(){
        var totalSUM=0;
        var active = false;
        var referenceCode = 'Licencia Vendty';
        var referenceCode2 = 'Licencia Vendty';
        var apiKey = "4Vj8eK4rloUd272L48hsrarnUA";
        var merchanId = '508029';
        var currency = 'COP';
        var amount = 0;
        var signature;
        var n = 0;
        var i;
        $('#referenceCode').val('');

        $("tbody tr input:checkbox").each(function () {
            var getValue = $(this).parent().parent().find("td:eq(6)").html();
            if ($(this).is(':checked')) {
                i = rand_code("abcdefghijklmnopqABCDEFGHIJKLMNOPQ", 3);
                active = true;
                referenceCode += (n==0) ? i + '-' + $(this).data('id') : ',' + i + '-' + $(this).data('id');
                referenceCode2 += (n==0) ? $(this).data('id') + "_" + getValue : '-' + $(this).data('id') + "_" + getValue;
                //var filteresValue=getValue.replace(/\,/g, '');
                totalSUM +=Number(getValue);
                n ++;
            }
        });

        amount = totalSUM;
        referenceCodetotales=referenceCode2;
        amounttotales = totalSUM;

        if(ippais!="Colombia"){
            if(amounttotales==0){
                $("#paypal-button").addClass("hidden");
                $("#btn_pagar_licencia").addClass("hidden");
            }else{
                $("#paypal-button").removeClass("hidden");
                $("#btn_pagar_licencia").removeClass("hidden");
            }
        }

        $('#amount').val(amount);
        // Update footer
        $( "#foot-table" ).empty();
        $('#foot-table').append('Total: ' + amount + '$');

        //signature = String(CryptoJS.MD5("4Vj8eK4rloUd272L48hsrarnUA"   + "~" + merchanId + "~" + referenceCode + "~" + amount + "~" + currency));

        if(active){
            $('#referenceCode').val(referenceCode);
            //$('#signature').val(signature);
            return true;
        }else{
            $('#referenceCode').val();
            //$('#signature').val();
            return false;
        }

    }

    //Tabla de usuarios
    var id_usuario = <?php echo $this->ion_auth->get_user_id(); ?>;
    $('#usuariosTable').dataTable({
            "bProcessing": true,
            "sAjaxSource": "<?php echo site_url("usuarios/get_ajax_data"); ?>",
            "sPaginationType": "full_numbers",
            "iDisplayLength": 5, "aLengthMenu": [5,10,25,50,100],
            "order": [[ 5, "asc" ]],
            "aoColumnDefs" : [
                    { "bSortable": false, "aTargets": [ 5 ], "bSearchable": false,
                    "mRender": function ( data, type, row ) {
                        result = 'No';
                        if(data == 't'){
                            result = "Si";
                        }
                        return result;
                    }
                },
                { "bSortable": false, "aTargets": [ 4 ], "bSearchable": false,
                    "mRender": function ( data, type, row ) {
                        result = 'No';
                        if(data == '0'){
                            result = "Si";
                        }
                        return result;
                    }
                }
                ,{ "bSortable": false, "aTargets": [ 6 ], "bSearchable": false,
                    "mRender": function ( data, type, row ) {
                        if(row[4]==1){
                            var buttons = '<a href="<?php echo site_url("usuarios/editar/"); ?>/'+data+'" class="button default acciones"><div class="icon"><img alt="editar" data-cambiar="<?php echo $this->session->userdata('new_imagenes')['editar']['cambio'] ?>" data-original="<?php echo $this->session->userdata('new_imagenes')['editar']['original'] ?>" class="iconacciones" src="<?php echo $this->session->userdata('new_imagenes')['editar']['original'] ?>"></div></a>';
                            if(data !=id_usuario){
                                buttons += '<a href="<?php echo site_url("usuarios/eliminar/"); ?>/'+data+'" onclick="if(confirm(\'<?php echo custom_lang('sima_delete_question', "Esta seguro que desea eliminar el registro?"); ?>\')){return true;}else{return false;}" class="button red acciones"><div class="icon"><img alt="Eliminar" data-cambiar="<?php echo $this->session->userdata('new_imagenes')['eliminar']['cambio'] ?>" data-original="<?php echo $this->session->userdata('new_imagenes')['eliminar']['original'] ?>" class="iconacciones" src="<?php echo $this->session->userdata('new_imagenes')['eliminar']['original'] ?>"></div></a>';
                            }
                        }else{
                            var buttons ="";
                        }
                        return buttons;
                    }
                }
            ]
    });
    //Modal de Usuarios
    var usuariosDialog = usuariosDialog || (function ($) {
        var $dusuario = $('<div class="modal fade" data-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true" style="overflow-y:visible;">' +
        '<div class="modal-dialog modal-m">' +
        '<div class="modal-content">' +
            '<div class="modal-header" style="padding:15px"><h4>Agregar nuevo usuario</h4></div>' +
            '<div class="modal-body">' +
                '<form id="frm_usuario" method="post" action="<?php echo site_url("usuarios/nuevo") ?>" accept-charset="utf-8" id="validate">'+
                '<input type="hidden" name="form" value="usuarios">'+
                '<div class="form-group">'+
                '   <div class="row">'+
                '      <div class="col-md-6">'+
                '       <label class="control-label">Nombre</label>'+
                '            <input placeholder="Nombre Completo" name="first_name" id="first_name" class="form-control" type="text">'+
                '                   <label class="label_error error_first_name hide">error</label>'+
                '        </div>'+
                '        <div class="col-md-6">'+
                '       <label class="control-label">Apellido</label>'+
                '            <input placeholder="Apellidos" name="last_name" id="last_name" class="form-control" type="text">'+
                '               <label class="label_error error_last_name hide">error</label>'+
                '        </div>'+
                '    </div>'+
                '</div>'+
                '<div class="form-group">'+
                '   <div class="row">'+
                '      <div class="col-md-6">'+
                '       <label class="control-label">Compañia</label>'+
                '            <input placeholder="Razon social" name="company" id="company" class="form-control" type="text">'+
                '                   <label class="label_error error_company hide">error</label>'+
                '        </div>'+
                '        <div class="col-md-6">'+
                '       <label class="control-label">Correo Electronico</label>'+
                '            <input placeholder="Email" name="email" id="email" class="form-control" type="text">'+
                '                   <label class="label_error error_email hide">error</label>'+
                '        </div>'+
                '    </div>'+
                '</div>'+
                '<div class="form-group">'+
                '   <div class="row">'+
                '      <div class="col-md-6">'+
                '       <label class="control-label">Clave (Minimo 8 caracteres)</label>'+
                '            <input type="password" placeholder="Clave" name="password" id="password" class="form-control" type="text">'+
                '                   <label class="label_error error_password hide">error</label>'+
                '        </div>'+
                '        <div class="col-md-6">'+
                '       <label class="control-label">Confirmar Clave</label>'+
                '            <input type="password" placeholder="Confirmar clave" name="password_confirm" id="password_confirm" class="form-control" type="text">'+
                '                   <label class="label_error error_password_confirm hide">error</label>'+
                '        </div>'+
                '    </div>'+
                '</div>'+
                '<div class="form-group">'+
                '   <div class="row">'+
                '      <div class="col-md-6">'+
                '       <label class="control-label">Telefono</label>'+
                '            <input placeholder="Numero de telefono" name="phone1" id="phone1" class="form-control" type="text">'+
                '                   <label class="label_error error_phone1 hide">error</label>'+
                '        </div>'+
                '    </div>'+
                '</div>'+
                '<div class="form-group">'+
                '   <div class="row">'+
                '      <div class="col-md-6">'+
                '       <label class="control-label">Almacen</label>'+
                '        <select name="almacen" id="slt_almacen" class="form-control required">'+
                '            <option value>Seleccione un Almacen</option>'+
                '            <?php $usu = 0;
foreach ($data["almacen"] as $key => $val) {
    // if(isset($data["definecrear"][$key])){
    // if($data["definecrear"][$key]['usuarios']==1){
    $usu++;?>'+
                '            <option value="<?php echo $key ?>"><?php echo $val; ?></option>'+
                '            <?php /*}}*/
}?>'+
                '        </select>'+
                '                   <label class="label_error error_almacen hide">error</label>'+
                '        </div>'+
                '        <div class="col-md-6">'+
                '        <label class="control-label">Caja</label>'+
                '        <select name="caja" id="caja" class="form-control required">'+
                '            <option value>Seleccione una caja</option>'+
                '        </select>'+
                '                   <label class="label_error error_caja hide">error</label>'+
                '        </div>'+
                '    </div>'+
                '</div>'+
                '<div class="form-group">'+
                '   <div class="row">'+
                '      <div class="col-md-6">'+
                '       <label class="control-label">Rol</label>'+
                '        <select name="rol_id" id="rol_id" class="form-control required">'+
                '            <option value="">Seleccione un Rol</option>'+
                '        <?php foreach ($data['roles'] as $key => $un_rol) {?>'+
                '                <option value="<?php echo $key ?>"><?php echo $un_rol ?></option>'+
                '        <?php }?>'+
                '        </select>'+
                '                   <label class="label_error error_rol_id hide">error</label>'+
                '        </div>'+
                '        <div class="col-md-6">'+
                '   <div class="row">'+
                '                    <div class="col-xs-12 ">'+
            '                           <label class="control-label">Tipo de Usuario</label>'+
                '<br/>' +        
                '                        <label class="">'+
                '                            <?php echo form_radio('is_admin', 't', ''); ?> <?php echo custom_lang('sima_is_asmin', "Administrador (Puede configurar el sistema)"); ?>'+
                '                        </label>'+
                '                        <label class="">'+
                '                            <?php echo form_radio('is_admin', 's', true); ?> <?php echo custom_lang('sima_is_asmin', "Ver solo información del almacen asigando (Informes e inventario) "); ?>'+
                '                        </label>'+
                '                        <label class="">'+
                '                            <?php echo form_radio('is_admin', 'a', ''); ?> <?php echo custom_lang('sima_is_asmin', "Ver información de todos los almacenes (Informes, inventario, cierres de caja)"); ?>'+
                '                        </label>'+
                '                    </div>'+
            '                    </div>'+
                '        </div>'+
                '    </div>'+
                '</div>'+
                <?php /*if (isset($data["tipo_negocio"]) && $data["tipo_negocio"] == "restaurante") {?>
                '<div class="form-group">'+
                '   <div class="row">'+
                '        <div class="col-md-6">'+
                '           <div class="col-xs-12 ">'+
                '       <label class="control-label">Será estación de Pedido?</label><br>'+
                '               <label class="radio-inline" id="radio_click_event">'+
                '                   <?php echo form_radio('estacion_pedido', '1', ''); ?> <?php echo custom_lang('sima_is_asmin', "si"); ?>'+
                '                   <?php echo form_error('estacion_pedido'); ?>'+
                '               </label>'+
                '               <label class="radio-inline">'+
                '                   <?php echo form_radio('estacion_pedido', '0', ''); ?> <?php echo custom_lang('sima_is_asmin', "no"); ?>'+
                '                   <?php echo form_error('estacion_pedido'); ?>'+
                '               </label>'+
                '           </div>'+
                '        </div>'+
                '    </div>'+
                '</div>'+
                <?php } */ ?>
                '<div class="btn-group" style="padding:2%;">'+
                '<button data-dismiss="modal" class="btn btn-default" type="button">Cancelar</button>'+
                '<button class="btn btn-success" type="submit" id="new-user">Guardar</button>'+
                '</div>'+
            '    </form>'+
            '</div>'+
        '</div></div></div>');
        return {
            show: function (message, options) {
                $dusuario.find("#radio_click_event").click(function (){
                    Swal.fire({
                    type: 'info',
                    title: '¿Estas seguro de activar esta funcionalidad?',
                    html: '<p style="font-size: 15px;">Esta funcionalidad estará asociada para los meseros, ellos solo podrán ver el modulo de toma pedidos y para ingresar a este debe crear cada mesero desde el modulo de contacto-Mesoneros y en este modulo le darán un código de 4 dígitos para ingresar al sistema.<p>',
                    showCloseButton: true,
                    })
                });
                    var settings = $.extend({
                    dialogSize: 'm',
                    progressType: '',
                    onHide: null // This callback runs after the dialog was hidden
                }, options);
                usu='<?=$usu?>';
                if(usu==0){
                    alert("Lo sentimos, Tu licencia no te permite crear más usuarios.");
                    $dusuario.modal('hide');
                }else{
                    $dusuario.modal();
                }
                $dusuario.find("#new-user").click(function(e){
                    e.preventDefault();
                    if(validate_required($dusuario.find("#first_name"), 'El campo nombre es requerido!','error_first_name')){
                        if(validate_required($dusuario.find("#last_name"), 'El campo apellido es requerido!','error_last_name')){
                            if(validate_required($dusuario.find("#company"), 'El campo compañia es requerido!','error_company')){
                                if(validate_required($dusuario.find("#email"), 'El campo email es requerido!','error_email')){
                                    if(validate_email($dusuario.find("#email"),'error_email')){
                                        if(validate_required($dusuario.find("#password"), 'El campo clave es requerido!','error_password')){
                                            if(validate_length($dusuario.find("#password"),'8','clave','error_password')){
                                                if(validate_required($dusuario.find("#password_confirm"), 'El campo confirmar clave es requerido!','error_password_confirm')){
                                                    if(validate_password($dusuario.find("#password"),$dusuario.find("#password_confirm"),'error_password_confirm')){
                                                        if(validate_required($dusuario.find("#phone1"), 'El campo teléfono es requerido!','error_phone1')){
                                                            if(validate_required($dusuario.find("#slt_almacen"), 'El campo almacen es requerido!','error_almacen')){
                                                                if($dusuario.find("#caja")==0){
                                                                    if(validate_required($dusuario.find("#rol_id"), 'El campo rol es requerido!','error_rol_id')){
                                                                        $("#new-user").prop('disabled',true);
                                                                        $dusuario.find("#frm_usuario").submit();
                                                                    }
                                                                }else{
                                                                    if(validate_required($dusuario.find("#caja"), 'El campo caja es requerido!','error_caja')){
                                                                        if(validate_required($dusuario.find("#rol_id"), 'El campo rol es requerido!','error_rol_id')){
                                                                            $("#new-user").prop('disabled',true);
                                                                            $dusuario.find("#frm_usuario").submit();
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }


                });
            },
            hide: function () {
                $dusuario.modal('hide');
            }
        }
    })($);
    $(document).on('click','#btn_new_user',function(){
        //console.log("asdad");
        usuariosDialog.show();
    });

    /*
     * Listado de Roles
     */
    var $btn_new_rol = $('#btn_new_rol');
        $('#rolesTable').dataTable( {
            "bProcessing": true,
            "bServerSide": true,
            "sAjaxSource": "<?php echo site_url("roles/get_ajax_data"); ?>",
            "sPaginationType": "full_numbers",
            "iDisplayLength": 5, "aLengthMenu": [5,10,25,50,100],
            "aoColumnDefs" : [
                    { "bSortable": false, "aTargets": [ 2 ], "bSearchable": false,
                    "mRender": function ( data, type, row ) {
                        var buttons = '<a href="<?php echo site_url("roles/editar/"); ?>/'+data+'" class="button default acciones"><div class="icon"><img alt="editar" data-cambiar="<?php echo $this->session->userdata('new_imagenes')['editar']['cambio'] ?>" data-original="<?php echo $this->session->userdata('new_imagenes')['editar']['original'] ?>" class="iconacciones" src="<?php echo $this->session->userdata('new_imagenes')['editar']['original'] ?>"></div></a>';
                            buttons += '<a href="<?php echo site_url("roles/eliminar/"); ?>/'+data+'" class="eliminarRol button red acciones"><div class="icon"><img alt="Eliminar" data-cambiar="<?php echo $this->session->userdata('new_imagenes')['eliminar']['cambio'] ?>" data-original="<?php echo $this->session->userdata('new_imagenes')['eliminar']['original'] ?>" class="iconacciones" src="<?php echo $this->session->userdata('new_imagenes')['eliminar']['original'] ?>"></div></a>';
                        return buttons;
                    }
                }
            ]
        });

    var rolDialog = rolDialog || (function ($) {
    var $dialog = $(
        '<div class="modal fade" data-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true" style="overflow-y:visible;">' +
        '<div class="modal-dialog modal-m">' +
        '<div class="modal-content">' +
            '<div class="modal-header" style="padding:15px"><h4>Agregar nuevo rol</h4></div>' +
            '<div class="modal-body">' +
                '<form id="frm_rol" method="post" action="<?php echo site_url("roles/nuevo") ?>" accept-charset="utf-8" id="validate">'+
                '<input type="hidden" name="form" value="caja">'+
                '  <div class="form-group">'+
                '      <label for="recipient-name" class="control-label">Nombre:</label>'+
                '      <input type="text" class="form-control" name="nombre_rol">'+
                '  </div>'+
                '<div class="form-group">'+
                '      <label for="message-text" class="control-label">Descripcion:</label>'+
                '        <textarea name="descripcion"></textarea>'+
                ' </div>'+
                '<div class="form-group">'+
                '      <label for="message-text" class="control-label">Permisos:</label>'+
                '       <select name="permisos[]" id="ms" multiple="multiple"> '+
                '        <?php foreach ($data["permisos"] as $key => $un_rol) {?>'+
                '                <option value="<?php echo $key ?>"><?php echo $un_rol ?></option>'+
                '        <?php }?>'+
                '      </select>'+
                ' </div>'+
                '<div class="btn-group" style="padding:2%;">'+
                '<button data-dismiss="modal" class="btn btn-default" type="button">Cancelar</button>'+
                '<button id="btn_rol" class="btn btn-success" type="submit">Guardar</button>'+
                '</div>'+
            '    </form>'+
            '</div>'+
        '</div></div></div>');
    return {
        show: function (message, options) {
                var settings = $.extend({
                dialogSize: 'm',
                progressType: '',
                onHide: null // This callback runs after the dialog was hidden
            }, options);
            $dialog.find("#ms").multiSelect();
            $dialog.modal();
            $dialog.find('#btn_rol').on('click',function(){
                $('#btn_rol').prop('disabled',true);
                $('#frm_rol').submit();
            });
        },
        hide: function () {
            $dialog.modal('hide');
        }
    }
    })($);

     $btn_new_rol.on('click',function(){
        rolDialog.show();
    });

    /*
     * Listado de Cajas
     */
    var oTable,$btn_new_caja = $('#btn_new_caja');
    var oTable,$btn_new_caja_cierre = $('#btn_new_caja_cierre');
    oTable = $('#proveedoresTable').dataTable( {
            "aaSorting": [[ 3, "desc" ]],
            "sAjaxSource": "<?php echo site_url("caja/get_ajax_data"); ?>",
            "sPaginationType": "full_numbers",
            "iDisplayLength": 5, "aLengthMenu": [5,10,25,50,100],
            "aoColumnDefs" : [
                { "bSortable": false, "aTargets": [ 3 ], "bSearchable": false,
                    "mRender": function ( data, type, row ) {
                            var buttons = '<a data-tooltip="Editar" onclick="loadModalBox('+data+')" class="button default acciones"><div class="icon"><img alt="Editar" data-cambiar="<?php echo $this->session->userdata('new_imagenes')['editar']['cambio'] ?>" data-original="<?php echo $this->session->userdata('new_imagenes')['editar']['original'] ?>" class="iconacciones" src="<?php echo $this->session->userdata('new_imagenes')['editar']['original'] ?>"></div></a>';
                        return buttons;
                    }
                }
            ]
    });
        //Modal de caja
    var cajaDialog = cajaDialog || (function ($) {
        var $dialog = $(
        '<div class="modal fade" data-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true" style="overflow-y:visible;">' +
        '<div class="modal-dialog modal-m">' +
        '<div class="modal-content">' +
            '<div class="modal-header" style="padding:15px;"><h4>Agregar Caja</h4></div>' +
            '<div class="modal-body">' +
                '<form id="frm_cajas" method="post" action="<?php echo site_url("frontend/configuracion") ?>" accept-charset="utf-8" id="validate">'+
                '<input type="hidden" name="form" value="caja">'+
                '  <div class="form-group">'+
                '      <label for="recipient-name" class="control-label">Nombre:</label>'+
                '      <input type="text" class="form-control" name="nombre">'+
                '  </div>'+
                '<div class="form-group">'+
                '      <label for="message-text" class="control-label">Almacen:</label>'+
                '        <select name="almacen" id="slt_almacen" class="form-control required">'+
                '            <option value>Seleccione un Almacen</option>'+
                '            <?php
$cajas = 0;
foreach ($data["almacen"] as $key => $val):
    if (isset($data["definecrear"][$key])) {
        if ($data["definecrear"][$key]['cajas'] == 1) {
            ?>'+
	                '            <option value="<?php echo $key ?>"><?php echo $val; ?></option>'+
	                '            <?php $cajas++;
        }
    }
endforeach?>'+
                '        </select>'+
                ' </div>'+
                '<div class="btn-group" style="padding:2%;">'+
                '<button data-dismiss="modal" class="btn btn-default" type="button">Cancelar</button>'+
                '<button id="btn_cajas" class="btn btn-success" type="submit">Guardar</button>'+
                '</div>'+
            '    </form>'+
            '</div>'+
        '</div></div></div>');
        return {
            show: function (message, options) {
                    var settings = $.extend({
                    dialogSize: 'm',
                    progressType: '',
                    onHide: null // This callback runs after the dialog was hidden
                }, options);
                caja='<?=$cajas?>';
                if(caja==0){
                    alert("Lo sentimos, Tu licencia no te permite crear más cajas.");
                     $dialog.modal('hide');
                }else{
                    $dialog.modal();
                    $dialog.find('#btn_cajas').on('click',function(){
                    //console.log("asdad");
                    $('#btn_cajas').prop('disabled',true);
                    $('#frm_cajas').submit();
                });
                }
            },
            hide: function () {
                $dialog.modal('hide');
            }
        }
    })($);

    /////Configuracion del cierre de caja
        //Modal de cierre de caja
    var caja_cierreDialog = caja_cierreDialog || (function ($) {
        var $dialog = $(
        '<div class="modal fade" data-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true" style="overflow-y:visible;">' +
        '<div class="modal-dialog modal-m">' +
        '<div class="modal-content">' +
            '<div class="modal-header" style="padding:15px;"><h4>Cierre de Caja</h4></div>' +
            '<div class="modal-body">' +
                '<form id="frm_imp" method="post" action="<?php echo site_url("frontend/configuracion") ?>" accept-charset="utf-8" id="validate">'+
                '<input type="hidden" name="form" value="caja">'+
                '  <div class="form-group">'+
                '      <label for="recipient-name" class="control-label">Aperturar la caja para vender:</label>'+
                '      <select name="valor_caja" id="valor_caja">'+
                '               <option value="no" <?php echo "no" == $valor_caja ? "selected" : ""; ?> >no</option>'+
                '               <option value="si" <?php echo "si" == $valor_caja ? "selected" : ""; ?> >si</option>'+
                '      </select>'+
                '  </div>'+
                '  <div class="form-group cierre_automatico">'+
                '      <label for="recipient-name" class="control-label">Cierre de caja automático:</label>'+
                '      <select name="cierre_automatico" id="cierre_automatico">'+
                '               <option value="2" <?php echo "0" == $cierre_caja ? "selected" : ""; ?> >no</option>'+
                '               <option value="1" <?php echo "1" == $cierre_caja ? "selected" : ""; ?> >si</option>'+
                '      </select>'+
                '  </div>'+
                '<div class="btn-group" style="padding:2%;">'+
                '<button data-dismiss="modal" class="btn btn-default" type="button">Cancelar</button>'+
                '<button class="btn btn-success" type="submit">Guardar</button>'+
                '</div>'+
            '    </form>'+
            '</div>'+
        '</div></div></div>');
        return {
            show: function (message, options) {
                    var settings = $.extend({
                    dialogSize: 'm',
                    progressType: '',
                    onHide: null // This callback runs after the dialog was hidden
                }, options);

                $dialog.modal();
            },
            hide: function () {
                $dialog.modal('hide');
            }
        }
    })($);

    $btn_new_caja.on('click',function(){
        cajaDialog.show();
    });

    $btn_new_caja_cierre.on('click',function(){
        caja_cierreDialog.show();
    });

    //Listado de Almacenes

    $('#productosTable').dataTable( {
                "bProcessing": true,
                "bServerSide": true,
                "sAjaxSource": "<?php echo site_url("almacenes/get_ajax_data"); ?>",
                "sPaginationType": "full_numbers",
                "iDisplayLength": 5, "aLengthMenu": [5,10,25,50,100],
                "aoColumnDefs" : [
                    { "bSortable": false, "aTargets": [ 4 ], "bSearchable": false,
                        "mRender": function ( data, type, row ) {
                            var text = "Si";
                            if(data != "1"){
                                text = "No";
                            }
                            return text;
                        }
                    },
                    { "bSortable": false, "aTargets": [ 7 ], "bSearchable": false,
                        "mRender": function ( data, type, row ) {
                            var text = "Activa";
                            if(data == 0){
                                text = "Suspendida";
                            }
                            return text;
                        }
                    }
                    ,{ "bSortable": false, "aTargets": [ 8 ], "bSearchable": false,
                        "mRender": function ( data, type, row ) {
                            var buttons = "";
                                if(row[7] == 1) {
                                    buttons += '<a href="<?php echo site_url("almacenes/editar/"); ?>/'+data+'" class="button default acciones"><div class="icon"><img alt="editar" data-cambiar="<?php echo $this->session->userdata('new_imagenes')['editar']['cambio'] ?>" data-original="<?php echo $this->session->userdata('new_imagenes')['editar']['original'] ?>" class="iconacciones" src="<?php echo $this->session->userdata('new_imagenes')['editar']['original'] ?>"></div></a>';
                                }
                                buttons += '<a href="<?php echo site_url("almacenes/eliminar/"); ?>/'+data+'" onclick="if(confirm(\'<?php echo custom_lang('sima_delete_question', "Esta seguro que desea eliminar el registro?"); ?>\')){return true;}else{return false;}" class="button red acciones"><div class="icon"><img alt="Eliminar" data-cambiar="<?php echo $this->session->userdata('new_imagenes')['eliminar']['cambio'] ?>" data-original="<?php echo $this->session->userdata('new_imagenes')['eliminar']['original'] ?>" class="iconacciones" src="<?php echo $this->session->userdata('new_imagenes')['eliminar']['original'] ?>"></div></a>';


                            return buttons;
                        }
                    }
                ]
        });

        var $btn_new_almacen = $('#btn_new_almacen');
        var almacenDialog = almacenDialog || (function ($) {
            var $dialog = $(
            '<div class="modal fade"  tabindex="-1" role="dialog" aria-hidden="true" style="overflow-y:visible;">' +
            '<div class="modal-dialog modal-m">' +
            '<div class="modal-content">' +
                '<div class="modal-header" style="padding:15px;"><h4>Agregar Almacén</h4></div>' +
                '<div class="modal-body">' +
                    '<form id="frm_almacenes" method="post" action="<?=site_url("almacenes/nuevo")?>" accept-charset="utf-8" id="validate">'+
                    '<input type="hidden" name="form" value="almacenes">'+
                    '  <div class="form-group">'+
                    '      <label for="recipient-name" class="control-label">Resolución:</label>'+
                    '      <input type="text"  value="" placeholder="Numero Resolucion" name="resolucion_factura" />'+
                    '  </div>'+
                    '<div class="form-group">'+
                    '      <label for="message-text" class="control-label">Nit:</label>'+
                    '        <input type="text"  value="" placeholder="NIT" name="nit" id="nit" class="f-electronica" />'+
                    ' </div>'+
                    '<div class="form-group">'+
                    '      <label for="message-text" class="control-label">Nombre:</label>'+
                    '      <input type="text"  value="" placeholder="Nombre de Almacen" name="nombre" />'+
                    ' </div>'+
                    '<div class="form-group">'+
                    '      <label for="message-text" class="control-label">Direccion del Almacen:</label>'+
                    '      <input type="text"  value="" placeholder="Direccion del Almacen" name="direccion" />'+
                    ' </div>'+
                    '<div class="form-group">'+
                    '      <label for="message-text" class="control-label">Prefijo:</label>'+
                    '      <input type="text"  value="" placeholder="Prefijo" name="prefijo" />'+
                    ' </div>'+
                    '<div class="form-group">'+
                    '      <label for="message-text" class="control-label">Consecutivo:</label>'+
                    '      <input type="text"  value="" placeholder="Consecutivo" name="consecutivo" />'+
                    ' </div>'+
                    '<div class="form-group">'+
                    '      <label for="message-text" class="control-label">Numero Final:</label>'+
                    '      <input type="text"  value="" placeholder="Numero Final" name="numero_fin" />'+
                    ' </div>'+
                    '<div class="form-group">'+
                    '      <label for="message-text" class="control-label">Fecha vencimiento:</label>'+
                    '      <input type="text"  value="" placeholder="Fecha vencimiento" name="fecha_vencimiento" class="datepicker" />'+
                    ' </div>'+
                     '<div class="form-group">'+
                    '      <label for="message-text" class="control-label">Avisame cuando llegue al numero:</label>'+
                    '      <input type="text"  value="" placeholder="Avisarme cuando llegue al numero" name="numero_alerta" />'+
                    ' </div>'+
                     '<div class="form-group">'+
                    '      <label for="message-text" class="control-label">Avisame cuando falten:</label>'+
                    '      <select name="fecha_alerta" value="">'+
                    '            <option value="7" >7 Dias</option>'+
                    '            <option value="15">15 Dias</option>'+
                    '            <option value="30">30 Dias</option>'+
                    '        </select>'+
                    ' </div>'+
                     '<div class="form-group">'+
                    '      <label for="message-text" class="control-label">Telefono:</label>'+
                    '      <input type="text"  value="" placeholder="Numero de Telefono" name="telefono" />'+
                    ' </div>'+
                    '<div class="form-group">'+
                    '      <label for="message-text" class="control-label">Meta diaria:</label>'+
                    '      <input type="text"  value="<?php echo set_value('meta_diaria'); ?>" placeholder="Ingrese monto" name="meta_diaria" />'+
                    ' </div>'+
                    '<div class="form-group">'+
                    '      <label for="message-text" class="control-label">Activar Consecutivo Cierre Caja:</label>'+
                    '      <input id="activar_consecutivo_cierre_caja"  name="activar_consecutivo_cierre_caja" type="checkbox" value="" <?php echo "1" == set_value('activar_consecutivo_cierre_caja') ? "checked='checked'" : ""; ?> />'+
                    ' </div>'+
                    '<div class="form-group consecutivo_cierre_caja hidden">'+
                    '      <label for="message-text" class="control-label">Consecutivo Cierre Caja:</label>'+
                    '      <input type="text"  value="<?php echo set_value('consecutivo_cierre_caja'); ?>" placeholder="Ingrese Consecutivo Cierre Caja" name="consecutivo_cierre_caja" />'+
                    ' </div>'+
                    '<div class="form-group">'+
                    '      <label for="message-text" class="control-label">País:</label>'+
                    '      <select id="pais_almacen" name="pais_almacen">'<?php foreach ($data["paises"] as $value => $pais): ?>+
                    '          <option value="<?php echo $pais; ?>"><?php echo $pais; ?></option>'<?php endforeach;?>+
                    '      </select>'+
                    ' </div>'+
                    '<div class="form-group">'+
                    '      <label for="message-text" class="control-label">Ciudad:</label>'+
                    '      <select id="provincia" name="provincia">'+
                    '      <option value="">Seleccione ciudad</option>'+
                    '      </select>'+
                    ' </div>'+
                    <?php
if ($data["tipo_negocio"] == "restaurante") {
    ?>
                    '<div class="form-group">'+
                    '      <label for="message-text" class="control-label">Consecutivo Orden Restaurante:</label>'+
                    '      <input type="text"  value="<?php echo set_value('consecutivo_orden_restaurante'); ?>" placeholder="Ingrese Consecutivo de orden de Restaurante" name="consecutivo_orden_restaurante" />'+
                    ' </div>'+
                    '<div class="form-group">'+
                    '      <label for="message-text" class="control-label">Reiniciar Consecutivo Orden Restaurante:</label>'+
                    '      <input type="text"  value="<?php echo set_value('reiniciar_consecutivo_orden_restaurante'); ?>" placeholder="Reiniciar Consecutivo Orden de Restaurante cuando llegue al número" name="reiniciar_consecutivo_orden_restaurante" />'+
                    ' </div>'+
                        <?php
}?>
                    '<div class="form-group">'+
                    '      <label for="message-text" class="control-label">Activo:</label>'+
                    '      <input name="activo" type="checkbox" value="" <?php echo "1" == set_value('activo') ? "checked='checked'" : ""; ?> />'+
                    ' </div>'+
                    '<div class="form-group">'+
                    '      <label for="facturacion-electronica" class="control-label">Facturación electrónica:</label>'+
                    '      <input id="facturacion-electronica" name="facturacion-electronica" type="checkbox" value="" <?php echo "1" == set_value('facturacion-electronica') ? "checked='checked'" : ""; ?> />'+
                    ' </div>'+
                    '<div id="facturacion-electronica-campos">'+
                    '   <div class="form-group">'+
                    '       <label for="numero-autorizacion-dian" class="control-label">Número de autorización de la DIAN:</label>'+
                    '       <input id="numero-autorizacion-dian" name="numero_autorizacion_dian" type="text" class="f-electronica" />'+
                    '   </div>'+
                    '   <div class="form-group>'+
                    '       <label for="regimen-fiscal" class="control-label">Régimen fiscal:</label>'+
                    '       <select name="regimen_fiscal" id="regimen-fiscal">'+
                    '          <option value="simple">Regimen simple de tributacion</option>'+
                    '           <option value="ordinario" selected="">Régimen común</option>'+
                    '       </select>'+
                    '   </div>'+
                    '   <div class="form-group>'+
                    '       <label for="facturacion-electronica" class="control-label">Prefijo DIAN:</label>' +
                            '<input id="prefijo-dian" name="prefijo_dian" type="text" class="f-electronica" />' +
                    '   </div>'+
                    '   <div class="form-group>'+
                    '       <label for="facturacion-electronica" class="control-label">Consecutivo inicial:</label>'+
                    '       <input id="consecutivo-desde" name="consecutivo_desde" type="number" class="f-electronica col-md-12" />'+
                    '   </div>'+
                    '   <div class="form-group>'+
                    '       <label for="facturacion-electronica" class="control-label">Consecutivo actual:</label>'+
                    '       <input id="consecutivo-actual" name="consecutivo_actual" type="number" class="f-electronica col-md-12" />'+
                    '   </div>'+
                    '   <div class="form-group>'+
                    '       <label for="facturacion-electronica" class="control-label">Consecutivo final:</label>'+
                    '       <input id="consecutivo-hasta" name="consecutivo_hasta" type="number" class="f-electronica col-md-12" />'+
                    '   </div>'+
                    '   <div class="form-group>'+
                    '       <label for="facturacion-electronica" class="control-label">Fecha inicial:</label>'+
                    '       <input id="fecha-desde" name="fecha_desde" type="date" class="f-electronica col-md-12" />'+
                    '   </div>'+
                    '   <div class="form-group>'+
                    '       <label for="facturacion-electronica" class="control-label">Fecha hasta:</label>'+
                    '       <input id="fecha-hasta" name="fecha_hasta" type="date" class="f-electronica col-md-12" />'+
                    '   </div>'+
                    '</div>'+
                    '<div class="btn-group" style="padding:2%;">'+
                        '<button data-dismiss="modal" class="btn btn-default" type="button">Cancelar</button>'+
                        '<button id="btn_almacenes" class="btn btn-success" type="button">Guardar</button>'+
                    '</div>'+
                '    </form>'+
                '</div>'+
            '</div></div></div>');

        return {
            show: function (message, options) {
                    var settings = $.extend({
                    dialogSize: 'm',
                    progressType: '',
                    onHide: null // This callback runs after the dialog was hidden
                }, options);
                $dialog.modal();
                $dialog.find('.datepicker').datepicker();
                $dialog.find('#btn_almacenes').on('click',function(){
                    //console.log("asdad");
                    $('#btn_almacenes').prop('disabled',true);

                    $('#frm_almacenes').on('submit', function(e) {
                        if($dialog.find("#facturacion-electronica").is(':checked')) {
                            $dialog.find(".f-electronica").each(function(){
                                if($(this).val() == "" && $(this)[0].name !== 'prefijo_dian') {
                                    alertPrevent(e, 'Debe llenar todos los campos para activar la facturación electrónica.');
                                }
                            });
                            if(!$("#nit").val().match('(^[0-9]+-{1}[0-9]{1})')) {
                                alertPrevent(e, 'Debe digitar el NIT con digito de verificación');
                            }
                            const date1 = $dialog.find('#fecha-desde').val();
                            const date2 = $dialog.find('#fecha-hasta').val();
                            if(date1 > date2) {
                                alertPrevent(e, 'La fecha inicial, no puede ser mayor a la fecha final.');
                            }
                            const consecutivo1 = parseInt($dialog.find('#consecutivo-desde').val());
                            const consecutivo2 = parseInt($dialog.find('#consecutivo-hasta').val());

                            if(consecutivo1 > consecutivo2) {
                                alertPrevent(e, 'El consecutivo inicial no puede ser mayor al consecutivo final.');
                            }
                        }
                    });

                    $('#frm_almacenes').submit();
                });

                const facturacionElectronicaCheck = $dialog.find('#facturacion-electronica');
                const facturacionElectronicaCampos = $dialog.find('#facturacion-electronica-campos');
                facturacionElectronicaCampos.hide();
                facturacionElectronicaCheck.change(function() {
                    if(facturacionElectronicaCheck.is(':checked')) {
                        facturacionElectronicaCampos.show();
                    } else {
                        facturacionElectronicaCampos.hide();
                    }
                });

                $dialog.find("#pais_almacen").change(function(e){
                    load_provincias_from_pais_almacen($("#pais_almacen").val());
                })
                var pais_almacen = $("#pais_almacen").val();
                if(pais_almacen != ""){
                    load_provincias_from_pais_almacen("Colombia");
                }

                function load_provincias_from_pais_almacen(pais){
                    $.ajax({

                        url: "<?php echo site_url("frontend/load_provincias_from_pais") ?>",
                        data: {"pais" : pais},
                        dataType: "json",
                        success: function(data) {
                            $("#provincia").html('');
                            $.each(data, function(index, element){
                                provincia = "<?php echo set_value('provincia'); ?>"
                                sel = provincia == element[0] ? "selected='selectted'" : '';
                            $dialog.find('#provincia').append("<option value='"+ element[0] +"' "+sel+">"+ element[0] +"</option>");

                            });
                        }
                    });
                }

                $dialog.find("#activar_consecutivo_cierre_caja").change(function(e){
                    if($(this).prop('checked')){
                        $(".consecutivo_cierre_caja").removeClass('hidden');
                    }else{
                        $(".consecutivo_cierre_caja").addClass('hidden');
                    }
                })

            },
            hide: function () {
                $dialog.modal('hide');
            }
        }
    })($);


    /********Bodegas**********/
     //Listado de Bodegas
     $('#productosTableBodega').dataTable( {
                "bProcessing": true,
                "bServerSide": true,
                "sAjaxSource": "<?php echo site_url("bodegas/get_ajax_data"); ?>",
                "sPaginationType": "full_numbers",
                "iDisplayLength": 5, "aLengthMenu": [5,10,25,50,100],
                "aoColumnDefs" : [
                        { "bSortable": false, "aTargets": [ 2 ], "bSearchable": false,
                        "mRender": function ( data, type, row ) {
                            var text = "Si";
                            if(data != "1"){
                                text = "No";
                            }
                            return text;
                        }
                    }
                    ,{ "bSortable": false, "aTargets": [ 4 ], "bSearchable": false,
                        "mRender": function ( data, type, row ) {
                            var buttons = '<a href="<?php echo site_url("bodegas/editar/"); ?>/'+data+'" class="button default acciones"><div class="icon"><img alt="editar" data-cambiar="<?php echo $this->session->userdata('new_imagenes')['editar']['cambio'] ?>" data-original="<?php echo $this->session->userdata('new_imagenes')['editar']['original'] ?>" class="iconacciones" src="<?php echo $this->session->userdata('new_imagenes')['editar']['original'] ?>"></div></a>';
                                buttons += '<a href="<?php echo site_url("bodegas/eliminar/"); ?>/'+data+'" onclick="if(confirm(\'<?php echo custom_lang('sima_delete_question', "Esta seguro que desea eliminar el registro?"); ?>\')){return true;}else{return false;}" class="button red acciones"><div class="icon"><img alt="Eliminar" data-cambiar="<?php echo $this->session->userdata('new_imagenes')['eliminar']['cambio'] ?>" data-original="<?php echo $this->session->userdata('new_imagenes')['eliminar']['original'] ?>" class="iconacciones" src="<?php echo $this->session->userdata('new_imagenes')['eliminar']['original'] ?>"></div></a>';

                            return buttons;
                        }
                    }
                ]
        });
    var $btn_new_bodega = $('#btn_new_bodega');
        var bodegaDialog = bodegaDialog || (function ($) {
            var $dialog = $(
            '<div class="modal fade"  tabindex="-1" role="dialog" aria-hidden="true" style="overflow-y:visible;">' +
            '<div class="modal-dialog modal-m">' +
            '<div class="modal-content">' +
                '<div class="modal-header" style="padding:15px;"><h4>Agregar Bodega</h4></div>' +
                '<div class="modal-body">' +
                    '<form id="frm_bodegas" method="post" action="<?=site_url("bodegas/nuevo")?>" accept-charset="utf-8" id="validate">'+
                    '<input type="hidden" name="form" value="bodegas">'+
                    '<div class="form-group">'+
                    '      <label for="message-text" class="control-label">Nombre:</label>'+
                    '      <input type="text"  value="" placeholder="Nombre de la Bodega" name="nombre" />'+
                    ' </div>'+
                    '<div class="form-group">'+
                    '      <label for="message-text" class="control-label">Direccion de la Bodega:</label>'+
                    '      <input type="text"  value="" placeholder="Direccion del Bodega" name="direccion" />'+
                    ' </div>'+
                    '<div class="form-group">'+
                    '      <label for="message-text" class="control-label">País:</label>'+
                    '      <select id="pais_bodega" name="pais_bodega">'<?php foreach ($data["paises"] as $value => $pais): ?>+
                    '          <option value="<?php echo $pais; ?>"><?php echo $pais; ?></option>'<?php endforeach;?>+
                    '      </select>'+
                    ' </div>'+
                    '<div class="form-group">'+
                    '      <label for="message-text" class="control-label">Ciudad:</label>'+
                    '      <select id="provincia_bodega" name="provincia">'+
                    '      <option value="">Seleccione</option>'+
                    '      </select>'+
                    ' </div>'+
                    '<div class="form-group">'+
                    '      <label for="message-text" class="control-label">Activo:</label>'+
                    '      <input name="activo" type="checkbox" value="" <?php echo "1" == set_value('activo') ? "checked='checked'" : ""; ?> />'+
                    ' </div>'+
                    '<div class="btn-group" style="padding:2%;">'+
                        '<button data-dismiss="modal" class="btn btn-default" type="button">Cancelar</button>'+
                        '<button id="btn_bodegas" class="btn btn-success" type="button">Guardar</button>'+
                    '</div>'+
                '    </form>'+
                '</div>'+
            '</div></div></div>');

        return {
            show: function (message, options) {
                    var settings = $.extend({
                    dialogSize: 'm',
                    progressType: '',
                    onHide: null // This callback runs after the dialog was hidden
                }, options);

                bodega='<?=$data['definecrear'][$primero]['bodegas']?>';

                if(bodega==0){
                    alert("Lo sentimos, Tu licencia no te permite crear más bodegas.");
                     $dialog.modal('hide');
                }else{
                    $dialog.modal();
                }
                $dialog.find('.datepicker').datepicker();
                $dialog.find('#btn_bodegas').on('click',function(){
                    //console.log("asdad");
                    $('#btn_bodegas').prop('disabled',true);
                    $('#frm_bodegas').submit();
                });

                $dialog.find("#pais_bodega").change(function(e){
                    load_provincias_from_pais_bodega($("#pais_bodega").val());
                })
                var pais_bodega = $("#pais_bodega").val();

                if(pais_bodega == ""){
                    load_provincias_from_pais_almacen("Colombia");
                }else{
                    load_provincias_from_pais_almacen(pais_bodega);
                }

                function load_provincias_from_pais_bodega(pais){
                    $.ajax({

                        url: "<?php echo site_url("frontend/load_provincias_from_pais") ?>",
                        data: {"pais" : pais},
                        dataType: "json",
                        success: function(data) {
                            $("#provincia_bodega").html('');
                            $.each(data, function(index, element){
                                provincia = "<?php echo set_value('provincia_bodega'); ?>"
                                sel = provincia == element[0] ? "selected='selectted'" : '';
                            $dialog.find('#provincia_bodega').append("<option value='"+ element[0] +"' "+sel+">"+ element[0] +"</option>");

                            });
                        }
                    });
                }
            },
            hide: function () {
                $dialog.modal('hide');
            }
        }
    })($);

    $btn_new_almacen.on('click',function(){
        almacenDialog.show();
    });

    $btn_new_bodega.on('click',function(){
        bodegaDialog.show();
    });
});

    /*Bootstrap settings*/
    $('#pop_retail').popover();
    $('#pop_restaurante').popover();
    $('#pop_moda').popover();
    $('#pop_productos_comanda').popover();
    $('#pop_permitir_pagos_pendientes').popover();
    $('#pop_impresion_rapida').popover();
    $('#pop_nueva_impresion_rapida').popover();
    $("#pop_quick_service").popover();
    $("#pop_domicilios").popover();
    $("#pop_comanda_virtual").popover();
    $("#pop_quick_service_command").popover();
    $('#pop_inventario').popover();
    $('#pop_inventario_historico').popover();
    $('#pop_puntos_leal').popover();

    $(document).on('change','#valor_caja',function(){
        valor_caja=$(this).attr("value");
        if(valor_caja=='si'){
            $(".cierre_automatico").css('display','block');
        }else{
            $(".cierre_automatico").css('display','none');
        }
    });

    $("form #tipo_negocio").each(function(){
        $(this).click(function(){
            tipo_negocio = $(this).attr("value")
            if(tipo_negocio =="restaurante"){
                $(".propina").removeClass("hide");
                $(".cierre_caja_mesas_abiertas").removeClass("hide");
            }else{
                $(".propina").addClass("hide");
                $(".cierre_caja_mesas_abiertas").addClass("hide");
            }

            tipo_negocio = $(this).attr("value")
            if(tipo_negocio =="retail"){
                $(".plan_separe").removeClass("hide");
            }else{
                $(".plan_separe").addClass("hide");
            }
        })
    })

    $("#quick_service").click(function(){
        if($(this).attr('checked') == 'checked'){
            $("#content_domicilios").removeClass("hide");
            $("#content_comanda_virtual").removeClass("hide");
            $("#content_quick_service_command").removeClass("hide");
        }else{
            $("#content_domicilios").addClass("hide");
            $("#content_comanda_virtual").addClass("hide");
            $("#content_quick_service_command").addClass("hide");
        }

    })

    var tipo_negocio = null;
    var tipo_negocio_actual = "";

    $("form #tipo_negocio").each(function(){
        if($(this).attr("checked") == "checked"){
            tipo_negocio_actual = $(this).attr("value");
        }
    })

    $("#form_update_business").click(function(e){
        e.preventDefault();
        if($("#eliminar_producto_comanda").attr("checked") == "checked"){
            $("#eliminar_producto_comanda").attr("value","si");
        }else{
            $("#eliminar_producto_comanda").attr("value","no");
        }

        $("form #tipo_negocio").each(function(){
            if($(this).attr("checked") == "checked"){
                tipo_negocio = $(this).attr("value");
            }
        })

        if(tipo_negocio == null){
            alert("Debe seleccionar un tipo de negocio");
        }else if(tipo_negocio == tipo_negocio_actual){
            $("#validate").submit();
        }else{
            var cambio_negocio = confirm("Esta seguro que desea cambiar su tipo de negocio a "+tipo_negocio);
            if(cambio_negocio){
            $("#validate").submit();
            }
        }
    });

    function validate_required(element,message,label){
        if((element).val() == ""){
            element.focus();
            $("."+label+"").html(message);
            $("."+label+"").addClass('show');
            return false;
        }else{
            $("."+label+"").removeClass('show');
            return true;
        }
    }

    function validate_length(element,length,camp_name,label){
        if(element.val().length < length){
            element.focus();
            $("."+label+"").html('El campo '+camp_name+' debe ser de minimo '+length+' caracteres.');
            $("."+label+"").addClass('show');
            return false;
        }  else{
            $("."+label+"").removeClass('show');
            return true;
        }
    }

    function validate_email(element,label) {
        var email = element.val();
        var pattern =/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
        if (pattern.test(email)){
            $("."+label+"").removeClass('show');
            return true;
        }else{
            element.focus();
            $("."+label+"").html('Correo eléctronico invalido');
            $("."+label+"").addClass('show');
            return false;
        }
    }

    function validate_password(password,confirm_password,label) {
        if(password.val() != confirm_password.val()){
            $("."+label+"").html('Las claves no coinciden. Verifique');
            $("."+label+"").addClass('show');
            return false;
        }  else{
            $("."+label+"").removeClass('show');
            return true;
        }
    }

    var sele='<?=$data["selecionado"];?>';
    switch (sele) {
        case '1':
            $('#collapseOne').addClass('in');
            $('#tab-1').removeClass('active');
            $('#tab-facturacion').addClass('active');
            break;
        case '2':
            $('#collapseOne').addClass('in');
            $('#tab-1').removeClass('active');
            $('#tab-2').addClass('in active');
            break;
         case '3':
            $('#collapseOne').removeClass('in');
            $('#collapseThree').addClass('in');
            $('#tab-1').removeClass('active');
            $('#tab-10').addClass('active in');
            break;

        default:
            break;
    }

    var pais = '<?php echo $data['factura'][0]['pais'] ?>';
    if (pais != "") {
        load_provincias_from_pais(pais);
    }

    $("#pais_factura").change(function(e){
        var pais = $("#pais_factura").val();
            if(pais != ""){
                load_provincias_from_pais(pais);
            }
    });

    $("#pais_facturafm").change(function () {
        load_provincias_from_paism($(this).val());
    });

    var paisfm = '<?php echo $data['info_factura'][0]['pais'] ?>';
    if (paisfm != "") {
        load_provincias_from_paism(paisfm);
    }else{
        load_provincias_from_paism("Colombia");
    }

    $("#btn_copy_empresa").click(function(e){
        nombre_empresa_factura="<?php echo $data['empresa']['data']['nombre']; ?>";
        $('#nombre_empresa_factura').val(nombre_empresa_factura);
        direccion_factura="<?php echo $data['empresa']['data']['direccion']; ?>";
        $('#direccion_factura').val(direccion_factura);
        telefono_factura="<?php echo $data['empresa']['data']['telefono']; ?>";
        $('#telefono_factura').val(telefono_factura);
        numero_identificacion_factura="<?php echo $data['empresa']['data']['nit']; ?>";
        $('#numero_identificacion_factura').val(numero_identificacion_factura);
        tipo_identificacion_factura="<?php echo $data['empresa']['data']['documento']; ?>";
        $('#tipo_identificacion_factura').val(tipo_identificacion_factura);
        contacto_factura="<?php echo $data['empresa']['data']['contacto']; ?>";
        $('#contacto_factura').val(contacto_factura);
    });

    function load_provincias_from_pais(pais) {
        $.ajax({
            url: "<?php echo site_url("frontend/load_provincias_from_pais") ?>",
            //data: {"pais" : 'Colombia'},
            data: {"pais" : pais},
            dataType: "json",
            success: function(data) {
                $("#ciudad_factura").html('');
                $.each(data, function(index, element){
                    provincia = "<?php echo $data['factura'][0]['ciudad'] ?>";
                    sel = provincia == element[0] ? "selected='selectted'" : '';
                    $('#ciudad_factura').append("<option value='"+ element[0] +"' "+sel+">"+ element[0] +"</option>");

                });
            }
        });
    }

    function load_provincias_from_paism(pais) {
        $.ajax({
            url: "<?php echo site_url("frontend/load_provincias_from_pais") ?>",
            //data: {"pais" : 'Colombia'},
            data: {"pais" : pais},
            dataType: "json",
            success: function(data) {
                $("#ciudad_facturafm").html('');
                $.each(data, function(index, element){
                    provincia = "<?php echo set_value('ciudad_facturafm'); ?>";
                    sel = provincia == element[0] ? "selected='selectted'" : '';
                    $('#ciudad_facturafm').append("<option value='"+ element[0] +"' "+sel+">"+ element[0] +"</option>");

                });
            }
        });
    }

    $("#validate_atributos").submit(function(e){
        $('#guardar_archivo').attr("disabled", true);
        $('#regresar').attr("disabled", true);
    });

    $(".eliminar_imagen").click(function(e){
        //logotipo
        $(".logo").html('');
        $("#eliminar_logo").val('1');

    });

   $('#impresion_rapida').change(function() {
        if($(this).prop('checked')){
            $(".content-apikey").removeClass('hidden');
        }else{
            $(".content-apikey").addClass('hidden');
        }
    })

   $('#enviar_valor_inventario').change(function() {
        if($(this).prop('checked')){
            $(".correo_valor_inventario").removeClass('hidden');
        }else{
            $(".correo_valor_inventario").addClass('hidden');
        }
    })

   $('#stock_historico').change(function() {
        if($(this).prop('checked')){
            $(".correo_stock_historico").removeClass('hidden');
        }else{
            $(".correo_stock_historico").addClass('hidden');
        }
    })

   $('#puntos_leal').change(function() {
        if($(this).prop('checked')){
            $(".usuario_puntos_leal").removeClass('hidden');
            $(".contraseña_puntos_leal").removeClass('hidden');
        }else{
            $(".usuario_puntos_leal").addClass('hidden');
            $(".contraseña_puntos_leal").addClass('hidden');
        }
    })

    /** tables selected */
    $("#tab-style-tables .content-table").each(function(index,element){
        $(this).click(function(){
            let url = "<?=site_url('frontend/update_table_selected')?>";
            $("#tab-style-tables .content-table").each(function(index,element){
                $(this).removeClass("active-table");
            });
            $(this).addClass("active-table");
            $.post(url,{
                table_selected : $(this).data('id')
            },function(data){
                console.log(data);
            })
        })
    })
</script>

<script>
    $(".reiniciar").click(function(e){
        e.preventDefault();
        let url = "<?=site_url('frontend/reiniciar')?>";
        $.get(url,function(data){
            if(data == 1){
                alert("La impresión rápida se ha reiniciado con exito");
            }else{
                alert("Error al reiniciar la impresión rápida");
            }
        });
    });

    function alertPrevent(e, message) {
        swal({
            type: 'error',
            title: 'Lo sentimos',
            text: message
        })
        $("#btn_almacenes").prop('disabled', false);
        e.preventDefault();
    }
</script>

<div id="validate-popup"></div>

<style>
    #validate-popup {
        display: none;
        width: 100%;
        height: 100%;
        background-image: url('<?php echo base_url('public/img/step.png') ?>');
        background-repeat: no-repeat;
        background-position: calc(100% - 155px) 6%;
        background-color: rgba(0, 0, 0, 0.5);
        position: fixed;
        left: 0;
        top: 0;
        z-index: 9999;
    }
</style>
