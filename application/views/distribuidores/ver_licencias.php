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
                <a href="<?php echo site_url("administracion_vendty/empresas/index")?>" data-tooltip="Volver atrás">                       
                    <img alt="Volver atrás" class="btnimagenes" src="<?php echo $this->session->userdata('new_imagenes')['devolver_verde']['original'] ?>">                                                     
                </a>            
            </div>
        </div>
        <div class="col-md-6 btnizquierda"> 
            <div class="col-md-2 col-md-offset-10">
                <a href="<?php echo site_url("administracion_vendty/empresas/descargar_excel/".$url)?>" data-tooltip="Exportar a Excel">                       
                    <img alt="Exportar a Excel" class="btnimagenes" src="<?php echo $this->session->userdata('new_imagenes')['exportar_excel_verde']['original'] ?>">                                                     
                </a>
            </div>
        </div>
        <hr>
    </div>
</div>

<div class="table-responsive">
  <table class="table" id="table-clientes">
    <thead>
        <tr>
            <th>Id Licencia</th>            
            <th>Fecha Activación</th>
            <th>Fecha Inicio</th>
            <th>Fecha Vencimiento</th>
            <th>Nombre Plan</th>
            <th>Valor Plan</th>
            <th>Días Vigencias</th>
            <th>Nombre Empresa</th>
            <th>Correo Electrónico</th>
            <th>Distribuidor</th>
            <th>Vendedor</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach($clientes as $cliente){?>
        <tr>
            <td><?php echo $cliente->idlicencias_empresa;?></td>            
            <td><?php echo $cliente->fecha_activacion;?></td>
            <td><?php echo $cliente->fecha_inicio_licencia;?></td>
            <td><?php echo $cliente->fecha_vencimiento;?></td>
            <td><?php echo $cliente->nombre_plan;?></td>
            <td><?php echo '$'.number_format($cliente->valor_plan);?></td>
            <td><?php echo $cliente->dias_vigencia;?></td>
            <td><?php echo $cliente->nombre_empresa;?></td>            
            <td><?php echo $cliente->email;?></td>            
            <td><?php echo $cliente->nombre_distribuidor;?></td>
            <td><?php echo $cliente->vendedor;?></td>
        </tr>
    <?php } ?>
    <tbody>
    <tfoot>
        <tr>
            <th>Id Licencia</th>            
            <th>Fecha Activación</th>
            <th>Fecha Inicio</th>
            <th>Fecha Vencimiento</th>
            <th>Nombre Plan</th>
            <th>Valor Plan</th>
            <th>Días Vigencias</th>
            <th>Nombre Empresa</th>
            <th>Correo Electrónico</th>
            <th>Distribuidor</th>
            <th>Vendedor</th>
        </tr>
    </tfoot>
  </table>

</div>

<script>
    $(document).ready( function () {
        $('#table-clientes').DataTable();
    } );
</script>