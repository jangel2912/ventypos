<div id="moda_nueva_licencia" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myModalLabel">Nueva licencia</h3>
  </div>
  <div class="modal-body">
   <?php echo form_open('administracion_vendty/administracion_clientes/activar_licencia_cliente',array('id'=>'f_activar_licencia_cliente')); ?>

    <div class="row">
      <div class="col-md-6">
        <div class="form-group">
          <label class="col-md-6"><?php echo custom_lang('plan','Plan') ?></label>
          <select name="s_plan" id="s_plan" class="form-control chosen-select" >
            <option value="">Seleccione</option>
            <?php foreach ($data['planes'] as $key => $value) { ?>
                <option data-vigencia="<?php echo $value->dias_vigencia ?>" data-valor_plan="<?php echo $value->valor_plan ?>" data-iva="<?php echo $value->iva_plan ?>" data-total_plan="<?php echo $value->valor_final ?>" value="<?php echo $value->id ?>"><?php echo $value->nombre_plan ?></option>
            <?php } ?>
          </select>
        </div>  
      </div>
      <div class="col-md-6">
        <div class="form-group">
          <label class="col-md-6"><?php echo custom_lang('almacen','Almacen') ?></label>
          <select name="s_almacen" id="s_almacen" class="form-control chosen-select" >
             <option value="">Seleccione</option>
            <?php foreach ($data['almacenes'] as $key => $value) { ?>
                <option value="<?php echo $value->id ?>"><?php echo $value->nombre ?></option>
            <?php } ?>
          </select>
        </div>  
      </div>   
    </div>
    <div class="row">
     <div class="col-md-12">
        <center><h2><?php echo custom_lang('datos_pago','Datos del pago') ?></h2></center>
     </div>
    </div>
    <div class="row">
      <div class="col-md-6">
        <div class="form-group">
          <label class="col-md-6"><?php echo custom_lang('forma_pago','Forma de pago') ?></label>
          <select name="s_forma_pago" id="s_forma_pago" class="form-control chosen-select">
            <option value="">Seleccione</option>
            <?php foreach ($data['formas_pago'] as $key => $value) { ?>
              <option value="<?php echo $value->idformas_pago ?>"><?php echo $value->nombre_forma ?></option>
            <?php } ?>
          </select> 
        </div>  
      </div>
      <div class="col-md-6">
        <div class="form-group">
          <label class="col-md-6"><?php echo custom_lang('fecha_pago','Fecha Pago') ?></label>
          <input type="text" name="t_fecha_pago" class="form-control datepicker-input">
        </div>
      </div>
    </div>
    <div class="row">  
      <div class="col-md-6">
        <div class="form-group">
          <label class="col-md-12"><?php echo custom_lang('observacion_adicional_pago','Observacion adicional del pago') ?></label>
          <textarea name="ta_observacion_adicional_pago" class="form-control"></textarea>
        </div>
      </div>
    </div>
    
    <div class="row">
      <div id="div_mensajes">
        
      </div>
    </div>
    <?php echo form_close(); ?>
  </div>
  <div class="modal-footer">
    <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
    <button id="btn_enviar_licencia" class="btn btn-primary">guardar nueva licencia</button>
  </div>
</div>
<script type="text/javascript">
  $("#f_activar_licencia_cliente").on('submit',function(evt){
    evt.preventDefault();
  });

  $(".datepicker-input").datepicker({dateFormat: 'yy-mm-dd'});
  $("#btn_enviar_licencia").on('click',function(){
     
      $.ajax({
          type: "post",
          url: $("#f_activar_licencia_cliente").attr('action'),
          data: $("#f_activar_licencia_cliente").serialize(),  
          dataType: "json",       
          success: function(result){
            $("#div_mensajes").removeClass();            
            if(result.success){
              $("#div_mensajes").addClass('alert alert-success');
              $("#div_mensajes").html('Se ha creado la nueva licencia');
            }else{
              $("#div_mensajes").addClass('alert alert-error');  
              $("#div_mensajes").html(result.message);

            }
          }
      });
  });
</script>