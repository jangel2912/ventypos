<style>

    .not-padding{padding:0px;}
    .not-margin{margin:0px;}
    .ml-25{margin-left:25px;}
    .mt-10{margin-top:10px;}
    .mb-20{margin-bottom: 20px;}
    .not-padding-left{padding-left:0px;}
    .gray{color:gray;}
    .border-right{border-right: solid 1px lightgray;}
    .activaciones{margin-bottom:10px;}
    .item-border{border-right: solid 1px lightgray;}
    .day-value{color: #5cb85c; font-size:20px;}
    .neto-value{color: #5cb85c; font-size:20px;}
    .title_plan{text-transform: uppercase; font-size:12px;font-style: italic;}
    .item-estadistica{border-radius: 5px 5px; border: solid 1px #e0e0e0; color: #333; padding-top:5px; padding-bottom:5px; margin-bottom: 5px;}
    .item-estadistica .text-pago{border-bottom: solid 1px #5cb85c;margin-bottom:10px;}
    .item-estadistica span{color: black; font-size:16px; }
    #container {width:100%;min-width: 310px;max-width: 1200px;height: 300px;margin: 0 auto;}
    .mayus{text-transform: uppercase;}

    .count-graphs{font-size:20px; color: green;}
    .icon-index{height: 14px; margin-right:3px;}

    .item-result-static{padding-top:25px; box-sizing:border-box;}
    .item-result-static h5{text-align:center; color:#5cb85c; text-transform: uppercase;font-family: sans-serif;padding-bottom: 4px;border-bottom: solid 1px lightgray;}
    .item-results{width: 110px;  margin: 0 auto; height: 110px;border: solid 1px lightgray;border-radius: 50%;text-align: center;vertical-align: middle;display: flex;align-items: center;}
    .item-results .content-result{width:100%;}
    .item-results .content-result p{font-size:20px; font-weight:bold;}
    .item-bottom{margin-left:27px;}
    /*.item-result-static:hover > .item-results .content-result{color:#fff; transition: 1s; cursor:pointer;}*/
    .item-result-static:hover > .item-results{border:solid 2px;border-color: #5cb85c; cursor:pointer; }
    
    .item-result-static-2{float:left;}
    .item-result-static-2 h5{text-align:center; color:#5cb85c; text-transform: uppercase;font-family: sans-serif;padding-bottom: 4px;}
    .item-results-2{width: 85px; float: left; height: 85px;border: solid 1px lightgray;border-radius: 50%;text-align: center;vertical-align: middle;display: flex;align-items: center;}
    .item-results-2 .content-result{width:100%;}
    .item-results-2 .content-result p{font-size:14px; font-weight:bold;}
    .item-results-2 .content-result span{font-size:11px;}
    .item-bottom-2{margin-left:0px;}
    .link-circle{color: #333;text-decoration: none;}
</style>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <h4>Dashboard de Información</h4>
            <div class="col-md-12 pull-right text-right">    
                <img class="icon-index" src="<?php echo base_url().'public/img/play.png'?>" alt=""><a class="first" href="<?php echo site_url("administracion_vendty/distribuidores/inicio"); ?>">Primeros pasos</a>
                <hr>
            </div>
            <br>
        </div>
    </div>
        
    <div class="row">
        <div class="col-md-6 graficas">

            <div class="col-md-6 col-md-offset-3 not-padding">
  
                <div id="reportrange" class="form-control " style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                    <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
                    <span></span> <b class="caret"></b>
                </div>
                <br>
            </div>
            <br>
            <div id="container"></div>
            <div class="col-md-12" style="margin-top: 21px;font-size: 11px;">
                <div class="col-md-4 text-center">SUSCRIPCIONES<br>
                    <span class="count-graphs suscripciones"><span>
                </div>
                <div class="col-md-4 text-center">ACTIVOS<br>
                    <span class="count-graphs activos"><span>
                </div>
                <div class="col-md-4 text-center">SUSPENDIDOS<br>
                    <span class="count-graphs suspendidos"><span>
                </div>
            </div>

        </div>

        <div class="col-md-6 estadisticas">
            <!-- Ingresos Netos -->
           
            <div class="row">

                <h4 class="title_plan text-center"><b>ESTE MES</b></h4>
                <div class="col-md-12 not-padding">
                    <div class="col-md-6 not-padding">
                        <div class="item-result-static " data-toggle="" data-placement="top" data-original- data-trigger="hover"  data-content="Estadistica que muestra cuantas licencias se han comprado en este mes.">
                            <h5>Nuevos Mensuales</h5>
                            <a class="link-circle enlace-nuevos-mensuales" href="<?php echo site_url('administracion_vendty/empresas/cargar_clientes/nuevos_mensuales')?>">
                                <div class="item-results animated fadeInDown">
                                    <div class="content-result">
                                        <p class="total_vencidos data-nuevos-mensuales"></p>
                                        <span class="total_pagos_vencidos data-nuevos-mensuales-pagos"></span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>

                    <div class="col-md-6 not-padding">
                         <div class="item-result-static " data-toggle="" data-placement="top" data-original- data-trigger="hover"  data-content="Estadistica que muestra cuantas licencias se han comprado en este mes.">
                            <h5>Nuevos Anuales</h5>
                            <a class="link-circle enlace-nuevos-anuales" href="<?php echo site_url('administracion_vendty/empresas/cargar_clientes/nuevos_anuales')?>">
                                <div class="item-results animated fadeInDown">
                                    <div class="content-result">
                                        <p class="total_vencidos data-nuevos-anuales"></p>
                                        <span class="total_pagos_vencidos data-nuevos-anuales-pagos"></span>
                                    </div>
                                </div>
                            </a>
                        </div>
                   </div>
                
                </div>

                <div class="col-md-12 not-padding">
                   <div class="col-md-6 not-padding">
                        <div class="item-result-static" data-toggle="" data-placement="top" data-original- data-trigger="hover" data-content="Estadistica que muestra cuantas licencias estan vencidas al dia de hoy.">
                                <h5>Total Licencias Mensuales</h5>
                                <a class="link-circle enlace-licencias-mensual" href="<?php echo site_url('administracion_vendty/empresas/cargar_clientes/ver_licencias_mensuales/null/null')?>">
                                    <div class="item-results animated fadeInDown">
                                        <div class="content-result">
                                            <p class="total_licencias data-licencias-mensuales"><?php echo count($data["licencias_mensuales"]);?></p>
                                            <span class="total_pagos_licencias data-licencias-mensuales-pagos"><?php //echo number_format($data["vencidos_pagos_mensual"]);?></span>
                                        </div>
                                    </div>
                                </a>
                        </div>

                    </div>

                    <div class="col-md-6 not-padding">
                        <div class="item-result-static" data-toggle="" data-placement="top" data-original- data-trigger="hover" data-content="Estadistica que muestra cuantas licencias estan vencidas al dia de hoy.">
                                <h5>Total Licencias Anuales</h5>
                                <a class="link-circle enlace-licencias-anual" href="<?php echo site_url('administracion_vendty/empresas/cargar_clientes/ver_licencias_anuales/null/null')?>">
                                    <div class="item-results animated fadeInDown">
                                        <div class="content-result">
                                            <p class="total_licencias data-licencias-anuales"><?php echo count($data["licencias_anuales"]);?></p>
                                            <span class="total_pagos_licencias data-licencias-anuales-pagos"></span>
                                        </div>
                                    </div>
                                </a>
                        </div>
                    </div>
                   
                </div>
            </div>
        </div>
    </div>

    <hr>
    <div class="row">
        <div class="col-md-12 not-padding">
            <div class="col-md-6  mb-20">
                <h4 class="text-center title_plan"><b>Licencias Mensuales Renovación</b></h4>
                <div class="col-md-2">
                    <div class="item-result-static-2 item-bottom-2" data-toggle="" data-placement="top" data-original- data-trigger="hover"  data-content="Estadistica que muestra cuantas licencias con PLAN MENSUAL se deben renovar para el mes actual.">
                        <h6 class="text-center"><b>Total</b></h6>
                        <!--<a class="link-circle" href="<?php echo site_url('administracion_vendty/empresas/cargar_clientes/por_renovar_mensual')?>">-->
                            <div class="item-results-2 animated fadeInDown">
                            <div class="content-result">
                                    <p class="total_vencidos data-total-mensuales"></p>
                                    <span class="total_pagos_vencidos data-total-mensuales-pagos"></span>
                                </div>
                            </div>
                        <!--</a>-->
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="item-result-static-2" data-toggle="" data-placement="top" data-original- data-trigger="hover"  data-content="Estadistica que muestra cuantas licencias con PLAN MENSUAL se pagaron en el mes actual.">
                            <h6 class="text-center"><b>Pagadas</b></h6>
                            <a class="link-circle enlace-pagados-mensual" href="<?php echo site_url('administracion_vendty/empresas/cargar_clientes/pagadas_mensual')?>">
                                <div class="item-results-2 animated fadeInDown">
                                    <div class="content-result">
                                        <p class="total_vencidos data-renovaciones-mensuales"></p>
                                        <span class="total_pagos_vencidos data-renovaciones-mensuales-pagos"></span>
                                    </div>
                                </div>
                            </a>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="item-result-static-2" data-toggle="" data-placement="top" data-original- data-trigger="hover"  data-content="Estadistica que muestra cuantas licencias con PLAN MENSUAL se pagaron en el mes actual.">
                            <h6 class="text-center"><b>Suspendidas</b></h6>
                            <a class="link-circle enlace-suspendidos-mensual" href="<?php echo site_url('administracion_vendty/empresas/cargar_clientes/vencidos_mensual')?>">
                                <div class="item-results-2 animated fadeInDown">
                                    <div class="content-result">
                                        <p class="total_vencidos data-suspendidos-mensuales"></p>
                                        <span class="total_pagos_vencidos data-suspendidos-mensuales-pagos"></span>
                                    </div>
                                </div>
                            </a>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="item-result-static-2 item-bottom-2" data-toggle="" data-placement="top" data-original- data-trigger="hover"  data-content="Estadistica que muestra cuantas licencias con PLAN MENSUAL se deben renovar para el mes actual.">
                        <h6 class="text-center"><b>Por renovar</b></h6>
                        <a class="link-circle enlace-por-renovar-mensual" href="<?php echo site_url('administracion_vendty/empresas/cargar_clientes/por_renovar_mensual')?>">
                            <div class="item-results-2 animated fadeInDown">
                            <div class="content-result">
                                    <p class="total_vencidos data-por-renovar-mensuales"></p>
                                    <span class="total_pagos_vencidos data-por-renovar-mensuales-pagos"></span>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="item-result-static-2 item-bottom-2" data-toggle="" data-placement="top" data-original- data-trigger="hover"  data-content="Estadistica que muestra cuantas licencias con PLAN MENSUAL se deben renovar para el mes actual.">
                        <h6 class="text-center"><b>Pagadas antes</b></h6>
                        <a class="link-circle enlace-pagados-antes-mensual" href="<?php echo site_url('administracion_vendty/empresas/cargar_clientes/por_renovar_mensual')?>">
                            <div class="item-results-2 animated fadeInDown">
                            <div class="content-result">
                                    <p class="total_vencidos data-pagados-antes-mensuales"></p>
                                    <span class="pagados_antes_mensuales_pagos data-pagados-antes-mensuales-pagos"></span>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <h4 class="text-center title_plan"><b>Licencias Anuales Renovación</b></h4>
                <div class="col-md-2">
                    <div class="item-result-static-2 item-bottom-2" data-toggle="" data-placement="top" data-original- data-trigger="hover"  data-content="Estadistica que muestra cuantas licencias con PLAN ANUAL se deben renovar para el mes actual.">
                        <h6 class="text-center"><b>Total</b></h6>
                        <!--<a class="link-circle" href="<?php echo site_url('administracion_vendty/empresas/cargar_clientes/por_renovar_anual')?>">   --> 
                            <div class="item-results-2 animated fadeInDown">
                                <div class="content-result">
                                    <p class="total_vencidos data-total-anuales"></p>
                                    <span class="total_pagos_vencidos data-total-anuales-pagos"></span>
                                </div>
                            </div>
                        <!-- </a>-->
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="item-result-static-2" data-toggle="" data-placement="top" data-original- data-trigger="hover"  data-content="Estadistica que muestra cuantas licencias con PLAN ANUAL se pagaron en el mes actual.">
                            <h6 class="text-center"><b>Pagadas</b></h6>
                            <a class="link-circle enlace-pagados-anual" href="<?php echo site_url('administracion_vendty/empresas/cargar_clientes/pagadas_anual')?>">    
                                <div class="item-results-2 animated fadeInDown">
                                    <div class="content-result">
                                    <p class="total_vencidos data-renovaciones-anuales"></p>
                                        <span class="total_pagos_vencidos data-renovaciones-anuales-pagos"></span>
                                    </div>
                                </div>
                            </a>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="item-result-static-2 item-bottom-2" data-toggle="" data-placement="top" data-original- data-trigger="hover"  data-content="Estadistica que muestra cuantas licencias con PLAN ANUAL se deben renovar para el mes actual.">
                        <h6 class="text-center"><b>Suspendidas</b></h6>
                        <a class="link-circle enlace-suspendidos-anual" href="<?php echo site_url('administracion_vendty/empresas/cargar_clientes/vencidos_anual')?>">    
                            <div class="item-results-2 animated fadeInDown">
                                <div class="content-result">
                                    <p class="total_vencidos data-suspendidos-anuales"></p>
                                    <span class="total_pagos_vencidos data-suspendidos-anuales-pagos"></span>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="item-result-static-2 item-bottom-2" data-toggle="" data-placement="top" data-original- data-trigger="hover"  data-content="Estadistica que muestra cuantas licencias con PLAN ANUAL se deben renovar para el mes actual.">
                        <h6 class="text-center"><b>Por renovar</b></h6>
                        <a class="link-circle enlace-por-renovar-anual" href="<?php echo site_url('administracion_vendty/empresas/cargar_clientes/por_renovar_anual')?>">    
                            <div class="item-results-2 animated fadeInDown">
                                <div class="content-result">
                                    <p class="total_vencidos data-por-renovar-anuales"></p>
                                    <span class="total_pagos_vencidos data-por-renovar-anuales-pagos"></span>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="item-result-static-2 item-bottom-2" data-toggle="" data-placement="top" data-original- data-trigger="hover"  data-content="Estadistica que muestra cuantas licencias con PLAN ANUAL se deben renovar para el mes actual.">
                        <h6 class="text-center"><b>Pagadas antes</b></h6>
                        <a class="link-circle enlace-pagados-antes-anual" href="<?php echo site_url('administracion_vendty/empresas/cargar_clientes/por_renovar_anual')?>">    
                            <div class="item-results-2 animated fadeInDown">
                                <div class="content-result">
                                    <p class="total_vencidos data-pagados-antes-anuales"></p>
                                    <span class="pagados_antes_anual_pagos data-pagados-antes-anuales-pagos"></span>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-md-12 pull-right not-padding">
            <h4 class="mayus title_plan text-center"><b>Últimos 18 meses</b></h4>
            <?php 
            if(count($data["total_pagos_por_mes"]) > 0){
            ?>
                <div class="multiple-items">  
                <?php foreach(array_reverse($data["total_pagos_por_mes"]) as $pago){ ?>                                       
                    <div>                   
                        <a href="<?php echo site_url('administracion_vendty/empresas/cargar_clientes/todos_los_pagados/'.$pago['mes'].'/'.$pago['mes1'])?>">                                    
                            <div class="col-md-12 item-estadistica mayus">
                                <div class="text-pago"><?php echo $pago['mes'];?></div>                               
                                <span><?php echo '$'.number_format($pago['total']);?></span>
                            </div>                   
                        </a>
                    </div>                   
                    <?php } ?>                         
                </div>  
            <?php } ?>

            <br>
                    <?php /*
                    if(count($data["total_pagos_por_mes"]) > 0){
                        foreach($data["total_pagos_por_mes"] as $pago){ ?>                        
                        <a href="<?php echo site_url('administracion_vendty/empresas/cargar_clientes/todos_los_pagados/'.$pago['mes'].'/'.$pago['mes1'])?>">
                        <div class="col-md-2 text-center not-padding not-margin">
                            <div class="col-md-12 item-estadistica mayus">
                                <div class="text-pago"><?php echo $pago['mes'];?></div>
                                <!--<span><?php echo '$'.number_format($pago['valor_plan']);?></span>-->
                                <span><?php echo '$'.number_format($pago['total']);?></span>
                            </div>
                        </div>
                        </a>
                    <?php } } */?>

            <div clas="col-md-12" style="clear: both;padding-top: 10px; text-align:center;">
                <span class="neto-value">Total: <?php echo ($data["total_pagos"] <= 0)? '$0' : '$'.number_format($data["total_pagos"])  ?></span>  <br>
            </div>
        </div>
    </div>
    <hr>

</div>

<script type="text/javascript">

$('.multiple-items').slick({
        infinite: true,
        slidesToShow: 5,
        slidesToScroll: 5,
        //prevArrow: '<div class="slick-prev"><img style="width: 50px; height: 50px;" src="<?php echo base_url();?>uploads/mesas/flechaizquierdaverdegruesa.png" alt="prev"></div>',
        //nextArrow: '<div class="slick-next"><img style="width: 50px; height: 50px;" src="<?php echo base_url();?>uploads/mesas/flechaderechaverdegruesa.png" alt="ext"></div>'
    });	

    function cargar_grafica(data_grafica){

        Highcharts.chart('container', {
            title: {
                text: 'Gráfica de Suscripciones - Activos Nuevo - Suspenciones'
            },

            subtitle: {
                text: 'Distribuidores Vendty POS'
            },
            xAxis: {                
                categories: data_grafica.categories
           
            },
            yAxis: {
                title: {
                    text: 'Cantidad'
                }
            },
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'middle'
            },

            series: [{
                name: 'Suscripciones',
                //data:[70, 140, 9.5, 14.5, 18.4, 21.5, 25.2, 26.5, 23.3, 18.3, 13.9, 9.6]
                data: data_grafica.array_suscripciones
                }, {
                    name: 'Activaciones',
                    //data:[3.9, 4.2, 5.7, 6.5, 11.9, 15.2, 17.0, 16.6, 14.2, 9.3, 6.6, 4.8]
                    data: data_grafica.array_activos
                }, {
                    name: 'Suspendidas',
                    //data:[1.9, 1.2, 2.7, 8.5, 11.9, 11.2, 7.0, 8.6, 14.2, 10.3, 6.6, 4.8]
                    data: data_grafica.array_suspendidos
                }
            ],

            responsive: {
                rules: [{
                    condition: {
                        maxWidth: 700
                    },
                    chartOptions: {
                        legend: {
                            layout: 'horizontal',
                            align: 'center',
                            verticalAlign: 'bottom'
                        }
                    }
                }]
            }

            });
      
    }

$(function() {

    var start = moment();
    var f = new Date();

    var dias_mes = daysInMonth(f.getMonth() +1,f.getFullYear());
   // var dias_mes = f.getDate();
    var end = moment().startOf('month');
    var i = 0;
    //var band=0;

    console.log(dias_mes);
    //var start = (f.getDate() + "-" + (f.getMonth() +1) + "-" + f.getFullYear());

   // var start = new Date(getYear(), getMonth(), 01);
   // var end = moment();
    
    function cb(start, end) {

       // $('#reportrange span').html( start.format('MMMM 01, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        if(i == 0){
            var fecha_inicio = start.format('YYYY-MM-1');
            var fecha_fin = end.format('YYYY-MM-'+dias_mes);
            //band=1;
            i++;
        }
        else{
            var fecha_inicio = start.format('YYYY-MM-DD');
            var fecha_fin = end.format('YYYY-MM-DD');
            //band=0;
        }          
        
        $('#reportrange span').html( fecha_inicio+ ' - ' + fecha_fin);
       
        $.post("<?php echo site_url('administracion_vendty/distribuidores/get_ajax_data_clientes_by_graphics')?>",{
           /* fecha_inicio: (band==1)? fecha_inicio:fecha_inicio,
            fecha_fin:(band==1)? fecha_inicio:fecha_fin*/
            fecha_inicio:fecha_inicio,
            fecha_fin:fecha_fin
        },function(data){
            /*if(band==1){
                band=0;             
            }       */     
            cargar_grafica(data);
            $(".suscripciones").html(data.suscripciones);
            $(".activos").html(data.activos);
            $(".suspendidos").html(data.suspendidos);
            actualizar_estadisticas(data,fecha_inicio,fecha_fin);
        })
    }

    $('#reportrange').daterangepicker({
        startDate: start,
        endDate: end,
        ranges: {
           'Hoy': [moment(), moment()],
           'Ayer': [moment().subtract(1, 'days'), moment()],
           'Esta semana': [moment().startOf('week'), moment()],
           'Última semana': [moment().startOf('week').subtract(7, 'days'), moment().startOf('week'),],
           'Este mes': [moment().startOf('month'), moment()],
           'Último mes': [moment().startOf('month').subtract(1, 'month'), moment().startOf('month').subtract(1, 'day'),]
           //'Ultimos 3 meses': [moment().subtract(3,'month'), moment()],
           //'Ultimos 6 meses': [moment().subtract(6, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
    }, cb);
    
    cb(start, end);   
});

function actualizar_estadisticas(data,fecha_inicio,fecha_fin){

    console.log(fecha_inicio);
    console.log(fecha_fin);
    console.log(data);
    /*Nuevos*/ 
   $(".data-nuevos-mensuales").html(data.nuevos_mensuales.length);
   $(".data-nuevos-mensuales-pagos").html(data.nuevos_mensuales_pagos);
   $(".data-nuevos-anuales").html(data.nuevos_anuales.length);
   $(".data-nuevos-anuales-pagos").html(data.nuevos_anuales_pagos);
   /*Vencidos*/ 
   $(".data-vencidos-mensuales").html(data.mensuales_vencidos.length);
   $(".data-vencidos-mensuales-pagos").html(data.mensuales_vencidos_pagos);
   $(".data-vencidos-anuales").html(data.anuales_vencidos.length);
   $(".data-vencidos-anuales-pagos").html(data.anuales_vencidos_pagos);

   /*Renovaciones*/
   /* Mensuales */
   $(".data-renovaciones-mensuales").html(data.mensuales_pagados.cantidad_licencias);
   $(".data-renovaciones-mensuales-pagos").html(data.mensuales_pagados.total_pagos);

   $(".data-suspendidos-mensuales").html(data.mensuales_vencidos.length);
   $(".data-suspendidos-mensuales-pagos").html(data.mensuales_vencidos_pagos);

   $(".data-por-renovar-mensuales").html(data.mensuales_por_renovar.cantidad_licencias);
   $(".data-por-renovar-mensuales-pagos").html(data.mensuales_por_renovar.total_pagos);

   $(".data-total-mensuales").html(data.total_mensuales);
   $(".data-total-mensuales-pagos").html(data.total_mensuales_pagos);

   /* Anuales */
   $(".data-renovaciones-anuales").html(data.anuales_pagados.cantidad_licencias);
   $(".data-renovaciones-anuales-pagos").html(data.anuales_pagados.total_pagos);

   $(".data-suspendidos-anuales").html(data.anuales_vencidos.length);
   $(".data-suspendidos-anuales-pagos").html(data.anuales_vencidos_pagos);

   $(".data-por-renovar-anuales").html(data.anuales_por_renovar.cantidad_licencias);
   $(".data-por-renovar-anuales-pagos").html(data.anuales_por_renovar.total_pagos);

   $(".data-pagados-antes-mensuales").html(data.pagados_antes_m);
   $(".data-pagados-antes-mensuales-pagos").html(data.pagados_antes_m_pagos);
  
   $(".data-pagados-antes-anuales").html(data.pagados_antes_anual);
   $(".data-pagados-antes-anuales-pagos").html(data.pagados_antes_anual);

   $(".data-total-anuales").html(data.total_anuales);
   $(".data-total-anuales-pagos").html(data.total_anuales_pagos);

    /*Actualizamos los enlaces*/
    <?php $link =  site_url('administracion_vendty/empresas/cargar_clientes/'); ?>
    $(".enlace-nuevos-mensuales").attr('href','<?php echo $link; ?>/nuevos_mensuales/'+fecha_inicio+'/'+fecha_fin);
    $(".enlace-nuevos-anuales").attr('href','<?php echo $link; ?>/nuevos_anuales/'+fecha_inicio+'/'+fecha_fin); 
    /*
    $(".enlace-vencidos-mensual").attr('href','<?php echo $link; ?>/ver_licencias_mensuales/');
    $(".enlace-vencidos-anual").attr('href','<?php echo $link; ?>/ver_licencias_anuales/');*/
    
    $(".enlace-pagados-mensual").attr('href','<?php echo $link; ?>/pagadas_mensual/'+fecha_inicio+'/'+fecha_fin);
    $(".enlace-suspendidos-mensual").attr('href','<?php echo $link; ?>/vencidos_mensual/'+fecha_inicio+'/'+fecha_fin);
    $(".enlace-por-renovar-mensual").attr('href','<?php echo $link; ?>/por_renovar_mensual/'+fecha_inicio+'/'+fecha_fin);
    $(".enlace-pagados-antes-mensual").attr('href','<?php echo $link; ?>/pagado_antes_mensuales/'+fecha_inicio+'/'+fecha_fin);

    $(".enlace-pagados-anual").attr('href','<?php echo $link; ?>/pagadas_anual/'+fecha_inicio+'/'+fecha_fin);
    $(".enlace-suspendidos-anual").attr('href','<?php echo $link; ?>/vencidos_anual/'+fecha_inicio+'/'+fecha_fin);
    $(".enlace-por-renovar-anual").attr('href','<?php echo $link; ?>/por_renovar_anual/'+fecha_inicio+'/'+fecha_fin); 
    $(".enlace-pagados-antes-anual").attr('href','<?php echo $link; ?>/pagado_antes_anuales/'+fecha_inicio+'/'+fecha_fin);
    $(".enlace-pagados").attr('href','<?php echo $link; ?>/por_renovar_anual/'+fecha_inicio+'/'+fecha_fin); 
    +fecha_inicio+'/'+fecha_fin
}

function daysInMonth(month, year) {
  return new Date(year || new Date().getFullYear(), month, 0).getDate();
}


</script>