<div class="page-header">    
    <div class="icon">
        <img alt="Churm" class="iconimg" src="<?php echo $this->session->userdata('new_imagenes')['titulo_contactos']['original'] ?>">        
    </div>
     <h1 class="sub-title"><?php echo custom_lang("Churm", 'Churm');?></h1>
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
                <a href="<?php echo site_url("administracion_vendty/distribuidores/descargar_excel_churm/")?>" data-tooltip="Exportar a Excel">                       
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
    
    <table id="tablePreview" class="table table-striped">
        <tbody>
            <tr>
                <td><b>Mes</b></td>
                <?php 
                    foreach ($data['churm'] as $key => $value) { ?>
                        <td><b><?=$key?></b></td>
                <?php
                    }
                ?>       
            </tr>
            <tr>
                <td><b>Activos Nuevos</b></td>
                <?php 
                    foreach ($data['churm'] as $key => $value) { ?>
                        <td><a target="_blank" href="<?php echo site_url('administracion_vendty/distribuidores/cargar_cliente_churm/').'/nuevos/'.$value['fecha'] ?>"><?=$value['activos']?></a></td>                                     
                <?php
                    }
                ?>    
            </tr>
            <tr>
                <td><b>No Renovados</b></td>
                 <?php 
                    foreach ($data['churm'] as $key => $value) { ?>
                        <td><a target="_blank" href="<?php echo site_url('administracion_vendty/distribuidores/cargar_cliente_churm/').'/vencidos/'.$value['fecha'] ?>"><?=$value['cant_no_renovaron_actual']?></a></td>                                     
                <?php
                    }
                ?>    
            </tr>
            <tr>
                <td><b>Churm %</b></td>
                <?php 
                    foreach ($data['churm'] as $key => $value) { ?>
                        <td><?=$value['churm']?></td>                                     
                <?php
                    }
                ?>    
            </tr>
        </tbody>
    </table> 
</div>

<script>
    $(document).ready( function () {
        $('#table-clientes').DataTable();
    } );
</script>