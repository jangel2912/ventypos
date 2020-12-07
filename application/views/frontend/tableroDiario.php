<div class="page-header">
    <div class="icon">
        <span class="ico-monitor"></span>
    </div>
    <h1><?php echo custom_lang("Inicio", "Inicio");?><small><?php echo $this->config->item('site_title');?></small></h1>
</div>
<div class="block title">
    <div class="head">
        <h2>Tablero diario</h2>                                          
    </div>
</div>
<div class="row-fluid">                                                            
    <div class="span12">
	
<div class="span5">
 <div class="block">	
	<div class="head">
		<div class="icon">
			<span class="ico-chart-4"></span>
		</div>
		<h2>Utilidad del d&iacute;a</h2>
	</div>
	<div class="data-fluid">
		<div id="chart-3" style="height: 300px;"></div>
	</div>
</div>	
</div>
<div class="span5">
	<div class="block">	
		<div class="head">
			<div class="icon">
				<span class="ico-chart-4"></span>
			</div><h2>Venta d&iacute;a</h2>
		</div>
		<div class="data-fluid">
			<div id="chart-4" style="height: 300px;"></div>   
		</div>
	</div>
</div>
<div class="span5">
	<div class="block">	
	<div class="head">
		<div class="icon">
		<span class="ico-chart-4"></span>
		</div>
		<h2>Tac&oacute;metro utilidad diaria</h2>
	</div>
	<div class="data-fluid">
		<div id="chart-2" style="height: 300px;"></div>
	</div>
</div>
</div>
<div class="span5">
		<div class="block">	
			<div class="head">
				<div class="icon">
				<span class="ico-chart-4"></span>
				</div>
				<h2>Venta diaria vs mes anterior</h2>
				</div>
		<div class="data-fluid">
			<div id="main_chart" style="height: 300px;"></div>
		</div>	
		</div> 
</div>
    </div>
</div>