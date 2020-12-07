<div class="page-header">
    <div class="icon">
        <span class="ico-box"></span>
    </div>
    <h1><?php echo custom_lang("Informes", "Informes");?></h1>
</div>
<div class="block title">
    <div class="head">
        <h2><?php echo custom_lang('cuadrecaja', "Gr&aacute;fica de Ventas por Almac&eacute;n");?></h2>                                          
    </div>
</div>
<div class="row-fluid">
    <div class="span12">
        <div class="block">
            <?php
				    $is_admin = $this->session->userdata('is_admin');
		 $username = $this->session->userdata('username');	
                $message = $this->session->flashdata('message');
                if(!empty($message)):?>
                <div class="alert alert-success">
                    <?php echo $message;?>
                </div>
            <?php endif; ?>  
                <form action="<?php echo site_url("informes/grafica_ventas_almacen_data");?>" method="POST">
                <table>
                    <tr>
                        <td width="15%">Fecha Inicial : <input type="text" name="dateinicial" value="<?php echo $this->input->post('dateinicial');?>" class="datepicker"/>  </td>
                        <td width="15%">Fecha Final : <input type="text" name="datefinal" value="<?php echo $this->input->post('datefinal');?>" class="datepicker"/>   </td>
	
                        <td width="30%"><br/> <input type="submit" value="Consultar" class="btn btn-success"/></td>
                    </tr>
                </table>
            </form>
            </div>
        </div>
											
    <div class="span12 block">
        <?php if(isset($fechafinal) && !empty($fechainicial)){?>

		
        </div>
    </div>          


         <script src='http://static.fusioncharts.com/code/latest/fusioncharts.charts.js?cacheBust=56' type="text/javascript"></script>
        <script src='http://static.fusioncharts.com/code/latest/fusioncharts.js?cacheBust=56' type="text/javascript"></script>
<script>		
		FusionCharts.ready(function () {
    var topStores = new FusionCharts({
        type: 'bar2d',
        renderAt: 'chart-container',
        width: '1100',
        height: '1500',
        dataFormat: 'json',
        dataSource: {
            "chart": {
                "caption": "Gráfica de Ventas por Almacén",
                "subCaption": "",
                "yAxisName": "",
                "numberPrefix": " $ ",
                "paletteColors": "#0075c2",
                "bgColor": "#ffffff",
                "showBorder": "0",
                "showCanvasBorder": "0",
                "usePlotGradientColor": "0",
                "plotBorderAlpha": "10",
                "placeValuesInside": "3",
                "valueFontColor": "#ffffff",
                "showAxisLines": "1",
                "axisLineAlpha": "25",
                "divLineAlpha": "10",
                "alignCaptionWithCanvas": "0",
                "showAlternateVGridColor": "0",
                "captionFontSize": "28",
                "subcaptionFontSize": "24",
                "subcaptionFontBold": "0",
                "toolTipColor": "#ffffff",
                "toolTipBorderThickness": "0",
                "toolTipBgColor": "#000000",
                "toolTipBgAlpha": "80",
                "toolTipBorderRadius": "2",
                "toolTipPadding": "8"
            },
            
            "data": [
		    <?php arsort($data['total_ventas']); $i=0;	 foreach($data['total_ventas'] as $value){ ?>	
                {
                    "label": "<?php echo $value['alm_nom'];?>",
                    "value": <?php echo round($value['total_precio_venta']); ?>,
                },
			 <?php $i++; } ?>	
            ]
        }
    })
    .render();
});
</script>
		
          <div id="chart-container">FusionCharts will render here</div>
		
				  
		 <?php } ?>				
