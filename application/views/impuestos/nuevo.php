<style>
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
</style>
<div class="page-header">    
    <div class="icon">
        <img alt="Impuestos" class="iconimg" src="<?php echo $this->session->userdata('new_imagenes')['titulo_impuestos']['original'] ?>">        
    </div>
     <h1 class="sub-title"><?php echo custom_lang("Impuestos", "Impuestos");?></h1>
</div>

<div class="block title">
    <div class="head">
        <h2><?php echo custom_lang('sima_tax_new', "Nuevo Impuesto");?></h2>          
    </div>
</div>

<div class="row-fluid">

    <div class="span6">

        <div class="block">

        <div class="data-fluid">

            <?php echo form_open("impuestos/nuevo", array("id" =>"validate"));?>

            <div class="row-form">

                <div class="span3"><?php echo custom_lang('sima_name', "Nombre");?>:</div>

                <div class="span9"><input type="text"  value="<?php echo set_value('nombre'); ?>" placeholder="" name="nombre" />

                        <?php echo form_error('nombre'); ?>

                </div>

            </div>

            <div class="row-form">

                <div class="span3"><?php echo custom_lang('sima_tax_percent', "Porciento");?>:</div>

                <div class="span9"><input type="text" value="<?php echo set_value('porciento'); ?>" name="porciento" placeholder=""/>

                    <?php echo form_error('porciento'); ?>

                </div>

            </div>

            <div class="row-form">
                <div class="span12"><?php echo custom_lang('', "Seleccione la casilla si desea que este impuesto sea predeterminado:");?>:</div>
                <div class="span4">
                    <!--label class="switch">
                        <input type="checkbox" name="predeterminado" class="switchery switchery-default">
                        <span class="slider round"></span>
                    </label-->
                    &nbsp;
                    <input type="checkbox" name="predeterminado" >
                </div>
            </div>
            

            <div class="toolbar bottom tar">
                <div>
                    <button class="btn btn-default"  type="button" onclick="javascript:location.href='index'"><?php echo custom_lang('sima_cancel', "Cancelar");?></button>
                    <button class="btn btn-success" type="submit"><?php echo custom_lang("sima_submit", "Guardar");?></button>
                </div>

            </div>

        </div>

        </form>

    </div>

    </div>

</div>