<div class="page-header">    
    <div class="icon">
        <img alt="Cliente" class="iconimg" src="<?php echo $this->session->userdata('new_imagenes')['titulo_contactos']['original'] ?>">        
    </div>
     <h1 class="sub-title"><?php echo custom_lang("Cliente", $title);?></h1>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="col-md-6">              
            <div class="col-md-2">              
                <a href="<?php echo site_url("administracion_vendty/distribuidores/churm")?>" data-tooltip="Volver atrás">                       
                    <img alt="Volver atrás" class="btnimagenes" src="<?php echo $this->session->userdata('new_imagenes')['devolver_verde']['original'] ?>">                                                     
                </a>            
            </div>
        </div>
        <div class="col-md-6 btnizquierda"> 
            <div class="col-md-2 col-md-offset-10">
                <a href="<?php echo site_url("administracion_vendty/distribuidores/cargar_cliente_churm/".$url)?>" data-tooltip="Exportar a Excel">                       
                    <img alt="Exportar a Excel" class="btnimagenes" src="<?php echo $this->session->userdata('new_imagenes')['exportar_excel_verde']['original'] ?>">                                                     
                </a>
            </div>
        </div>
        <hr>
    </div>
</div>
<?php 
    $anterior=0;
    $class="";

?>
<style>
    .body .content .table tr.red td {    
        background-color: #5ca745 !important;
    }
</style>
<div class="table-responsive">
  <table class="table" id="table-clientes">
    <thead>
        <tr>            
            <th>id_licencia</th> 
            <th>Fecha Activación</th>           
            <th>Fecha Inicio</th>
            <th>Fecha Vencimiento</th>
            <th>Nombre Empresa</th>
            <th>Correo Electrónico</th>
            <th>Suscripción</th>            
            <th>Valor plan</th>
            <th>Días Vigencia</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach($clientes as $cliente){?>        
        <tr class="<?= $class ?>">
            <td><?php echo $cliente->idlicencias_empresa;?></td>            
            <td><?php echo $cliente->fecha_activacion;?></td>
            <td><?php echo $cliente->fecha_inicio_licencia;?></td>
            <td><?php echo $cliente->fecha_vencimiento;?></td>
            <td><?php echo $cliente->nombre_empresa;?></td>
            <td><?php echo $cliente->email;?></td>
            <td><?php echo $cliente->nombre_plan;?></td>            
            <td><?php echo $cliente->valor_final;?></td>
            <td><?php echo $cliente->dias_vigencia;?></td>
        </tr>
    <?php } ?>
    <tbody>
    <tfoot>
        <tr>
            <th>id_licencia</th> 
            <th>Fecha Activación</th>           
            <th>Fecha Inicio</th>
            <th>Fecha Vencimiento</th>
            <th>Nombre Empresa</th>
            <th>Correo Electrónico</th>
            <th>Suscripción</th>            
            <th>Valor plan</th>
            <th>Días Vigencia</th>
        </tr>
    </tfoot>
  </table>

</div>

<script>
    $(document).ready( function () {
        $('#table-clientes').DataTable();
    } );
</script>