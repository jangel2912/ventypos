<div class="page-header">
    <div class="icon">
        <span class="ico-file"></span>
    </div>
    <h1><?php echo custom_lang("Carga_de_datos", "Carga y actualización masiva de datos");?></h1>
</div>
<div class="row-fluid">
	<div class="span12">
		<br>
	</div>
</div>
<div class="tabbable">
	<ul class="nav nav-tabs" style="margin-left:0px;">
		<li class="active">
			<a href="#tab1" data-toggle="tab">Productos</a>
		</li>
	</ul>
	<div class="tab-content">
		<div class="tab-pane active" id="tab1">
			<div class="row-fluid">
				<div class="span12">
				</div>
			</div>
			<div class="row-fluid">
				<div class="span4">
					<a href="<?= site_url('configuracion/carga/actualizacion_productos') ?>">Actualización de productos</a><br>
					<a href="<?= site_url('configuracion/carga/productos_con_atributos') ?>">Productos con atributos</a><br>
				</div>
			</div>
		</div>
		<div class="tab-pane" id="tab2">
			<div class="row-fluid">
				
			</div>
		</div>
	</div>
</div>
<div class="row-fluid">
	<br>
	<a href="<?= site_url('frontend/configuracion') ?>" class="btn btn-default">Regresar</a>
</div>