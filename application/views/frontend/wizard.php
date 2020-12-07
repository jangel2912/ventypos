<div class="panel">
    <div class="panel-heading" style="text-align: center">
        <h3 class="panel-title">Empieza a utilizar Vendty en tan solo 3 pasos!
        </h3>
    </div>
    <div class="row row-lg top-steps text-center " style="text-align: center;">
        <div class="col-lg-3 form-horizontal">
            <h4>1. Datos Iniciales.</h4>
            <img src="<?= base_url() ?>/public/img/backgrounds/paso1.fw.png"/><br>
        </div>
        <div class="col-lg-1 arrow-one" >
            <img src="<?= base_url() ?>/public/img/backgrounds/arrow_right_white.png"/>
        </div>
        <div class="col-lg-3 form-horizontal">
            <h4>2. Selecciona Base de Datos.</h4>
            <img src="<?= base_url() ?>/public/img/backgrounds/paso2.fw.png"/>
        </div>
        <div class="col-lg-1 arrow-one">
            <img src="<?= base_url() ?>/public/img/backgrounds/arrow_right_white.png"/>
        </div>
        <div class="col-lg-4 form-horizontal">
            <h4>3. Empieza a vender.</h4>
            <img src="<?= base_url() ?>/public/img/backgrounds/paso3.fw.png"/>
        </div>
    </div>
</div>
<br>
<form id="setNewUserData" autocomplete="off" novalidate="novalidate" class="fv-form fv-form-bootstrap" accept-charset="utf-8" enctype="multipart/form-data">
    <div class="panel">
        <div class="panel-heading">
            <h3 class="panel-title">1. Datos Iniciales
                <span class="panel-desc">Ingresa la información de tu Empresa </span>
            </h3>
        </div>
        <div class="panel-body">
            <div class="row row-lg">
                <div class="col-lg-6 form-horizontal">
                    <div class="form-group">
                        <label class="col-lg-12 col-sm-3 control-label">Nombre de tu Empresa
                            <span class="required">*</span>
                        </label>
                        <div class=" col-lg-12 col-sm-9">
                            <input type="text" class="form-control" name="nombre" placeholder="Nombre Comercial" required="" data-fv-field="username">
                            <small class="help-block" data-fv-validator="notEmpty" data-fv-for="username" data-fv-result="NOT_VALIDATED" style="display: none;">The username is required</small><small class="help-block" data-fv-validator="stringLength" data-fv-for="username" data-fv-result="NOT_VALIDATED" style="display: none;">Please enter a value with valid length</small><small class="help-block" data-fv-validator="regexp" data-fv-for="username" data-fv-result="NOT_VALIDATED" style="display: none;">Please enter a value matching the pattern</small></div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-12 col-sm-3 control-label">NIT de tu Empresa
                            <span class="required">*</span>
                        </label>
                        <div class=" col-lg-12 col-sm-9">
                            <input type="text" class="form-control" name="nit" placeholder="Número de Identificación" required="" data-fv-field="username">
                            <small class="help-block" data-fv-validator="notEmpty" data-fv-for="username" data-fv-result="NOT_VALIDATED" style="display: none;">The username is required</small><small class="help-block" data-fv-validator="stringLength" data-fv-for="username" data-fv-result="NOT_VALIDATED" style="display: none;">Please enter a value with valid length</small><small class="help-block" data-fv-validator="regexp" data-fv-for="username" data-fv-result="NOT_VALIDATED" style="display: none;">Please enter a value matching the pattern</small></div>
                    </div>
                </div>
                <div class="col-lg-6 form-horizontal">
                    <div class="form-group">
                        <label class="col-lg-12 col-sm-3 control-label">Selecciona el logotipo de tu empresa
                            <span class="required">*</span>
                        </label>
                        <div class="col-lg-12 col-sm-9" >
                            <fieldset class="well">
                                <div id="contBtnLogoInput">
                                    <span id="btnLogoInput" class="btn btn-default">
                                        <i class="icon wb-upload" aria-hidden="true"></i>                                                    
                                    </span>                                              
                                    <input id="inputLogo" type="file" name="logo">
                                </div>
                                <div class="imageDrag">
                                    <img onerror="ImgError(this)"  id="previewImg" src="<?php echo base_url(); ?>/public/img/productos/product-dummy.png?v2.0">
                                </div>
                                <div class="row-fluid">&nbsp;</div>

                            </fieldset>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <br>
    <div class="panel">
        <div class="panel-heading">
            <h3 class="panel-title">2. Selecciona Base de Datos.
                <span class="panel-desc">Empieza a vender con una lista de productos que nosotros te precargamos</span>
            </h3>
        </div>
        <div class="panel-body">
            <div class="panel-body container-fluid">
                <!-- Example Pricing List -->
                <div class="example-wrap">
                    <div class="example">
                        <div class="row" style="text-align: center">
                            <div class="col-lg-1">

                            </div>
                            <div class="col-sm-6 col-lg-2">
                                <label for="inputDataBase1">
                                    <h4>Moda</h4>
                                    <img src="<?= base_url() ?>public/img/backgrounds/bd_moda.jpg"/>
                                </label>
                                <div class="radio-custom radio-inline">
                                    <input type="radio" id="inputDataBase1" name="base_datos" value="1">
                                    <label></label>
                                </div>
                            </div>
                            <div class="col-sm-6 col-lg-2">
                                <label for="inputDataBase2">
                                    <h4>Comidas</h4>
                                    <img src="<?= base_url() ?>public/img/backgrounds/bd_comidas.jpg"/>
                                </label>
                                <div class="radio-custom radio-inline">
                                    <input type="radio" id="inputDataBase2" name="base_datos" value="2"> 
                                    <label></label>
                                </div>
                            </div>
                            <div class="col-sm-6 col-lg-2">
                                <label for="inputDataBase3">
                                    <h4>Mini Mercados</h4>
                                    <img src="<?= base_url() ?>public/img/backgrounds/bd_minimercados.jpg"/>
                                </label>

                                <div class="radio-custom radio-inline">
                                    <input type="radio" id="inputDataBase3" name="base_datos" value="3"> 
                                    <label></label>
                                </div>
                            </div>
                            <div class="col-sm-6 col-lg-2">
                                <label for="inputDataBase4">
                                    <h4>Droguerías</h4>
                                    <img src="<?= base_url() ?>public/img/backgrounds/bd_droguerias.jpg"/>
                                </label>
                                <div class="radio-custom radio-inline">
                                    <input type="radio" id="inputDataBase4" name="base_datos" value="4"> 
                                    <label></label>
                                </div>
                            </div>
                            <div class="col-sm-6 col-lg-2">
                                <label for="inputDataBase5">
                                    <h4>Retail General</h4>
                                    <img src="<?= base_url() ?>public/img/backgrounds/bd_retailgeneral.jpg"/>
                                </label>

                                <div class="radio-custom radio-inline">
                                    <input type="radio" id="inputDataBase5" name="base_datos" value="5"> 
                                    <label></label>
                                </div>

                            </div>
                        </div>
                        <label for="inputDataBase6">
                            <h4>Ninguno</h4>
                        </label>

                        <div class="radio-custom radio-inline">
                            <input type="radio" id="inputDataBase6" name="base_datos" value="6"> 
                            <label></label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <br>
    <div class="panel">
        <div class="panel-heading">
            <h3 class="panel-title">3. Empieza a vender!
                <span class="panel-desc"></span>
            </h3>
        </div>
        <div class="panel-body">
            <div class="example-wrap col-lg-6">
                <h4 class="example-title">Guardar cambios y empezar a vender.</h4>
                <div class="example example-buttons col-lg-6">
                    <button type="submit" class="btn btn-primary btn-block btn-round ">
                        <i class="icon icon-shopping-cart icon-white" aria-hidden="true"></i>
                        Empieza a Vender!
                    </button>
                </div>
            </div>
            <div class="example-wrap col-lg-6">
                <iframe width="460" height="260" src="https://www.youtube.com/embed/BlthV4HJvss" frameborder="0" allowfullscreen></iframe>
            </div>
        </div>
    </div>
</form>
<style>
    .arrow-one {
        margin-top: 10%;
    }
</style>
<script>

    $('#setNewUserData').submit(function (e) {
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: '<?php echo site_url("frontend/save_config_first_user"); ?>',
            data: new FormData(this),
            processData: false,
            contentType: false,
            success: function (data) {
                if (data.res == 'error') {
                    alert(data.error);
                } else if (data.res == 'success'){
                    alert(data.success);
                    window.location = "<?php echo base_url(); ?>index.php/ventas/nuevo?var=navegador";
                }
            },
            error: function (xhr, textStatus, errorThrown) {
                alert(textStatus + " : " + errorThrown);
            }
        });
    });
    
//=========================================================================
//  IMAGE INPUT FILE PREVIEW
//=========================================================================
    var reloadPreview = function (inputFile) {

        var file = inputFile.files[0];
        var imagefile = file.type;
        var match = ["image/jpeg", "image/png", "image/jpg"];
        if (!((imagefile == match[0]) || (imagefile == match[1]) || (imagefile == match[2]))) {
            $('#previewing').attr('src', 'noimage.png');
            return false;
        } else {
            var reader = new FileReader();
            reader.onload = imageIsLoaded;
            reader.readAsDataURL(inputFile.files[0]);
        }
    }

    function imageIsLoaded(e) {
        $('#previewImg').attr('src', e.target.result);
        $('.avatar img').attr('src', "" + e.target.result + "");
    }
    ;
//=========================================================================
//  >>>>>> IMAGE INPUT FILE PREVIEW FIN
//=========================================================================
</script>