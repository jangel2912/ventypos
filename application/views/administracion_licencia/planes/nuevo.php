<div class="page-header">
    <div class="icon">
        <span class="ico-box"></span>
    </div>
    <h1><?php echo custom_lang("", "Planes");?></h1>
</div>
<div class="block title">
    <div class="head">
        <h2><?php echo custom_lang('sima_edit_product', "Nuevo Plan");?></h2>    
    </div>
</div>
<style>
    .ui-datepicker{
        background: #FFF;
    }
    input{
        width: 100%;
    }
    select{
        margin-left: 5px;
    }
    .tooltip {
    display:inline-block;
    position:relative;
    border-bottom:1px dotted #666;
    text-align:left;
}

.tooltip .top {
    min-width:200px; 
    top:-20px;
    left:50%;
    transform:translate(-50%, -100%);
    padding:10px 20px;
    color:#444444;
    background-color:#EEEEEE;
    font-weight:normal;
    font-size:13px;
    border-radius:8px;
    position:absolute;
    z-index:99999999;
    box-sizing:border-box;
    box-shadow:0 1px 8px rgba(0,0,0,0.5);
    display:none;
}

.tooltip:hover .top {
    display:block;
}

.tooltip .top i {
    position:absolute;
    top:100%;
    left:50%;
    margin-left:-12px;
    width:24px;
    height:12px;
    overflow:hidden;
}

.tooltip .top i::after {
    content:'';
    position:absolute;
    width:12px;
    height:12px;
    left:50%;
    transform:translate(-50%,-50%) rotate(45deg);
    background-color:#EEEEEE;
    box-shadow:0 1px 8px rgba(0,0,0,0.5);
}
</style>
<div class="row-fluid">
    <?php echo form_open_multipart("administracion_vendty/planes/nuevo/", array("id" =>"validate"));?>
        <div class="span12">
            <div class="span6">
                <div class="block">
                    <div class="data-fluid">                
                            <div class="row-form">
                                <div class="span3"><?php echo custom_lang('sima_name', "Nombre Plan");?>:</div>
                                <div class="span9">
                                    <input type="text" name="descripcion_plan" id="descripcion_plan" /> 
                                    <?php echo form_error('descripcion_plan'); ?>
                                </div>
                            </div>                    
                            <div class="row-form">
                                <div class="span3"><?php echo custom_lang('sima_codigo', "Valor Plan");?>:</div>
                                <div class="span9">
                                    <input type="number" name="valor_plan" id="valor_plan" />                                            
                                    <?php echo form_error('valor_plan'); ?>
                                </div>
                            </div>                    
                            <div class="row-form">
                                <div class="span3"><?php echo custom_lang('sima_codigo', "Valor Final");?>:</div>
                                <div class="span9">
                                    <input type="number" name="valor_final" id="valor_final" />                                            
                                    <?php echo form_error('valor_final'); ?>
                                </div>
                            </div>                     
                            <div class="row-form">
                                <div class="span3"><?php echo custom_lang('sima_codigo', "Mostrar");?>:</div>
                                <div class="span9">
                                    <input type="number" name="mostrar" id="mostrar" />                                                                            
                                    <?php echo form_error('mostrar'); ?>
                                </div>
                            </div>                     
                            <div class="row-form">
                                <div class="span3"><?php echo custom_lang('sima_name', "Tipo Plan");?>:</div>
                                <div class="span9">
                                <select name="tipo_plan"  id="tipo_plan">
                                        <?php
                                            $data['tipo_plan']=array(
                                                '1'=>'Básico',
                                                '2'=>'Pyme',
                                                '3'=>'Empresarial'
                                            );
                                            foreach ($data['tipo_plan'] as $key => $value) {
                                            ?>
                                                <option value="<?= $key ?>"><?= $value ?></option>
                                            <?php
                                            }
                                        ?>                                           
                                    </select>   
                            <?php echo form_error('tipo_plan'); ?>
                                </div>
                            </div>
                    </div>
                </div>
            </div>
            <div class="span6">
                <div class="block">
                    <div class="data-fluid">                
                            <div class="row-form">
                                <div class="span3"><?php echo custom_lang('sima_name', "Dias de Vigencias");?>:</div>
                                <div class="span9">
                                <select name="dias_vigencia"  id="dias_vigencia">
                                        <?php
                                            $data['dias_vigencia']=array(30,90,180,365);
                                            foreach($data['dias_vigencia'] as $value){ ?>
                                                <option value="<?= $value ?>"><?= $value." días";?></option>
                                            <?php
                                            }
                                        ?>                                           
                                    </select>   
                            <?php echo form_error('dias_vigencia'); ?>
                                </div>
                            </div>                    
                            <div class="row-form">
                                <div class="span3"><?php echo custom_lang('sima_codigo', "Iva Plan");?>:</div>
                                <div class="span9">
                                    <input type="number" name="iva_plan" id="iva_plan" />                                            
                                    <?php echo form_error('iva_plan'); ?>
                                </div>
                            </div>                    
                            <div class="row-form">
                                <div class="span3"><?php echo custom_lang('sima_codigo', "Valor en Dólares");?>:</div>
                                <div class="span9">
                                    <input type="number" name="valor_plan_dolares" id="valor_plan_dolares" />                                            
                                    <?php echo form_error('valor_plan_dolares'); ?>
                                </div>
                            </div>                     
                            <div class="row-form">
                                <div class="span3"><?php echo custom_lang('sima_codigo', "Orden a Mostrar");?>:</div>
                                <div class="span9">
                                    <input type="number" name="orden_mostrar" id="orden_mostrar" />                                            
                                    <?php echo form_error('mostrar'); ?>
                                </div>
                            </div>                         
                            <div class="row-form">
                                <div class="span3"><?php echo custom_lang('sima_codigo', "Promocion");?>:</div>
                                <div class="span9">
                                    <input type="number" name="promocion" id="promocion" /> 
                                    <?php echo form_error('promocion'); ?>
                                </div>
                            </div>                                                           
                    </div>
                </div>
            </div>   
        </div>
        <div class="block title">
            <div class="head">
                <h2><?php echo custom_lang('sima_edit_product', "Detalle del Plan");?></h2>    
            </div>
        </div>
        <div class="span12" style="margin-left: auto;">
            <div class="span6">
                <div class="block">
                    <div class="data-fluid">                
                        <div class="row-form">
                            <div class="span3"><?php echo custom_lang('sima_name', "Cantidad Bodegas");?>:</div>
                            <div class="span9">
                                <input type="number" name="bodegas" id="bodegas" /> 
                                <?php echo form_error('bodegas'); ?>
                            </div>
                        </div>   
                        <div class="row-form">
                            <div class="span3"><?php echo custom_lang('sima_name', "Cantidad Usuarios");?>:</div>
                            <div class="span9">
                                <input type="number" name="usuarios" id="usuarios" /> 
                                <?php echo form_error('usuarios'); ?>
                            </div>
                        </div>   
                        <div class="row-form">
                            <div class="span3"><?php echo custom_lang('sima_name', "Cantidad Cajas");?>:</div>
                            <div class="span9">
                                <input type="number" name="cajas" id="cajas" /> 
                                <?php echo form_error('cajas'); ?>
                            </div>
                        </div>   
                    </div>   
                </div>   
            </div> 
        </div>
        <div class="span12">
            <div class="toolbar bottom tar">
                <div>
                    <button class="btn btn-default"  type="button" onclick="javascript:location.href='../index'"><?php echo custom_lang('sima_cancel', "Cancelar");?></button>
                    <button class="btn btn-success" type="submit"><?php echo custom_lang("sima_submit", "Guardar");?></button>
                </div>
            </div>
        </div>
    </form>
</div>
