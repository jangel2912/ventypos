            <?php $permisos = $this->session->userdata('permisos');

                    $is_admin = $this->session->userdata('is_admin');

                    if(in_array("1000", $permisos) || $is_admin == 't'){ ?>

<div class="page-header">
    <?php 
	$total = '0';
	if($data['meta_diaria']['total_ventas'] >= $data['meta_diaria']['meta_almacen']){ 
	     $total = $data['meta_diaria']['meta_almacen']; 
	}
	if($data['meta_diaria']['total_ventas'] <= $data['meta_diaria']['meta_almacen']){ 
	    $total = $data['meta_diaria']['total_ventas']; 
	}
	
    ?>
</div>
<div class="row-fluid"> 

         


<div class="span7">

        <div class="block">

            <div class="head green">

                <h2>Productos populares</h2>

            </div>

            <div class="data">
     <form action="<?php echo site_url("frontend/get_ajax_productos_relevantes");?>" method="POST" target="iframe2" name="enviar"  id="myForm">
	  
<?php 
		 $is_admin = $this->session->userdata('is_admin');
		 $username = $this->session->userdata('username');	

if( $is_admin == 't' || $is_admin == 'a'){ //administrador
	echo "<select  name='almacen' >";    
    echo "<option value='0'>Todos los Almacenes</option>";    
    foreach($data1['almacen'] as $f){
        if($f->id == $this->input->post('almacen')){
            $selected = " selected=selected ";
        } else {
            $selected = "";
        }        
        echo "<option $selected value=" . $f->id . ">" . $f->nombre . "</option>";
    }    
    echo "</select>";

  }	
    ?> 

           <div class="span5"> <input type="text" value="Fecha Desde" name="fecha_desde" id="desde_relevantes" size="5"></div>
          <div class="span5"> <input type="text" value="Fecha Hasta" name="fecha_hasta" id="hasta_relevantes" size="5" onclick = "this.form.action = '<?php echo site_url("frontend/get_ajax_productos_relevantes");?>', this.form.target = 'iframe2' "></div><br /><br /><br />
		  <iframe name="iframe2" src="<?php echo site_url("frontend/get_ajax_productos_relevantes");?>" style="width: 562px; height: 610px;"  marginheight="0" marginwidth="0" noresize scrolling="No" frameborder="0"></iframe>

	
					<div style="width:570px" align="center"><center>
				<div class="btn-group" style="text-align:center">

                                        <button class="btn" type="submit" onclick = "this.form.action = '<?php echo site_url("frontend/excel_productos_populares");?>', this.form.target = '_blank' " />Excel Productos Populares</button>


                                    </div>
									</center>
					</div>			
        </form>
		
            </div>

        </div> 

    </div>    <div class="span4">

        <div class="block">

            <div class="head yellow">

                <h2>Meta diaria en ventas: <?php echo number_format($data['meta_diaria']['total_ventas']);?></h2>

            </div>

            <div class="data">
                <?php 
		 $is_admin = $this->session->userdata('is_admin');
		 $username = $this->session->userdata('username');	
			
  if( $is_admin == 't' || $is_admin == 'a'){ //administrador
	echo "<select  name='almacen' id='meta_diaria'>";    
    echo "<option value='0'>Todos los Almacenes</option>";    
    foreach($data1['almacen'] as $f){
        if($f->id == $this->input->post('almacen')){
            $selected = " selected=selected ";
        } else {
            $selected = "";
        }        
        echo "<option $selected value=" . $f->id . ">" . $f->nombre . "</option>";
    }    
    echo "</select>";

  }	
    ?> 

                <div id="meta_div_container" style="height:200px; width:300px;">

                    

                </div>
         
            </div>

        </div> 

    </div>

  <!-- <ol id="tour" style="display:none; ">
                    <li data-target="#Configuracion"  data-angle="160" data-options="distance:5"> <h2>Paso 1:</h2> Desde aquí configuras el sistema <span> proximo </span> </li>
                    <li data-target="#Productos" data-angle="190" data-options="distance:5"><h2>Paso 2:</h2> Crea tus productos desde aquí <span></span></li>
                    <li data-target="#Ventas" data-angle="190" data-options="distance:5"><h2>Paso 3:</h2> Gestiona todas tus ventas <span></span></li>
                    <li data-target="#Informes" data-angle="160" data-options="distance:5"><h2>Paso 4:</h2> Desde aquí puede realizar los informes <span></span></li>
                   
                </ol> -->

<h1><span></span></h1>
    <div class="span4">

        <div class="block">

            <div class="head blue">

                <h2>Utilidad</h2>

            </div>

            <div class="data">
 <div class="span5">  <input type="text" value="Fecha Desde" name="fecha" id="desde" size="5"></div>
<div class="span5">  <input type="text" value="Fecha Hasta" name="fecha" id="hasta" size="5"></div><br /><br /><br />

 
 

                <div id="utilidad_container1"  style="height:300px; width:300px;">

                    

                </div>

            </div>

        </div> 

    </div>

  


<div class="modal fade"  id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style=" z-index: 99999; display: none;">
  <div class="modal-dialog" role="document" >
    <div class="modal-content" style="z-index:99999">
      <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"> x </span></button>
        <div style="  margin-left:10px; margin-top:18px; margin-bottom:10px; width:520px; z-index:9999; height:180px; background-image:url(http://vendty.com/wp-content/uploads/2015/07/fondo_demo_formulario.jpg);">

<div style="margin:70px 20px 0px 10px; width:283px; float:right">
<div style="width:283; margin-bottom:0px; float:right;">
<h1 style="text-align:right; font-size:24px; color:#68af27; font-weight:500; font-family: 'Open Sans', sans-serif;"><b>Gracias por usar Vendty</h1>
</div>

<div style="width:260px; float:right;   margin-top: -10px;">
<p style="font-family: 'Open Sans', sans-serif; font-size:14px; text-align:right; font-weight:100; color:#666">  Si estás interesado en el software, aplica al formulario para contactarte.   </p>
</div>

</div>



</div>
      </div>


     <!--  <div class="modal-body" >

<form action='https://crm.zoho.com/crm/WebToLeadForm' name=WebToLeads303651000000624056 method='POST' onSubmit='javascript:document.charset="UTF-8"; return checkMandatery()' accept-charset='UTF-8'> 

 <input type='text' style='display:none;' name='xnQsjsdp' value='jZc-ARvRC7Q$'/>  
 <input type='hidden' name='zc_gad' id='zc_gad' value=''/> 
 <input type='text' style='display:none;' name='xmIwtLD' value='mV9P0u8tfuMKvw5Ju4-9agB9X0hHrsFE'/> 
 <input type='text'  style='display:none;' name='actionType' value='TGVhZHM='/> 
 <input type='text' style='display:none;' name='returnURL' value='http&#x3a;&#x2f;&#x2f;vendty.com&#x2f;vendty-demo&#x2f;index.php&#x2f;frontend&#x2f;' />

        

          <div class="form-group" style=" z-index: 999999; ">
            <label for="recipient-name" class="control-label"> <b> Nombre y Apellido:</label>
            <input type="text" class="form-control" id="recipient-name" name="Last Name"  >
          </div>

            <div class="form-group" style=" z-index: 999999; " >
            <label for="recipient-name" class="control-label"> <b> Email:</label>
            <input type="text" class="form-control input-lg" id="recipient-email" name="Email"    >
          </div>


            <div class="form-group" style=" z-index: 999999; ">
            <label for="recipient-name" class="control-label" > <b>Celular:</label>
            <input type="text" class="form-control input-lg" id="recipient-mobile" name="Mobile" >
          </div>


          <input type='hidden' style='width:250px;'  maxlength='100' name='Designation'  value='VendtyTour'/>

        
          <div class="form-group">
           
          </div>
            <div class="modal-footer">
  
       <center> <button type="submit" id="submit1"  class="btn btn-primary btn-lg" style="width:100px; background-color: #68AF27; height: 50px; font-size: 18px; font-family: 'Open Sans', sans-serif; ">Enviar</button>
        
        <!-- type="button" <!--type="submit" id="submit1"  -->
      <!--</div> -->
        <script> 
    var mndFileds = new Array('Email','Mobile');
    var fldLangVal = new Array('Email','Mobile');


    function reloadImg() {
        if (document.getElementById('imgid').src.indexOf('&d') !== -1) {
            document.getElementById('imgid').src = document.getElementById('imgid').src.substring(0, document.getElementById('imgid').src.indexOf('&d')) + '&d' + new Date().getTime();
        } else {
            document.getElementById('imgid').src = document.getElementById('imgid').src + '&d' + new Date().getTime();
        }
    }

    function checkMandatery() {
        var name = '';
        var email = '';
        for (i = 0; i < mndFileds.length; i++) {
            var fieldObj = document.forms['WebToLeads303651000000624056'][mndFileds[i]];
            if (fieldObj) {
                if (((fieldObj.value).replace(/^\s+|\s+$/g, '')).length == 0) {
                    alert(fldLangVal[i] + ' no puede estar vacío');
                    fieldObj.focus();
                    return false;
                } else if (fieldObj.nodeName == 'SELECT') {
                    if (fieldObj.options[fieldObj.selectedIndex].value == '-None-') {
                        alert(fldLangVal[i] + ' no puede ser nulo');
                        fieldObj.focus();
                        return false;
                    }
                } else if (fieldObj.type == 'checkbox') {
                    if (fieldObj.checked == false) {
                        alert('Please accept  ' + fldLangVal[i]);
                        fieldObj.focus();
                        return false;
                    }
                }
                try {
                    if (fieldObj.name == 'Last Name') {
                        name = fieldObj.value;
                    }
                } catch (e) {}
            }
        }
        try {
            if ($zoho) {
                var firstnameObj = document.forms['WebToLeads303651000000624056']['First Name'];
                if (firstnameObj) {
                    name = firstnameObj.value + ' ' + name;
                }
                $zoho.salesiq.visitor.name(name);
                var emailObj = document.forms['WebToLeads303651000000624056']['Email'];
                if (emailObj) {
                    email = emailObj.value;
                    $zoho.salesiq.visitor.email(email);
                }
            }
        } catch (e) {}
    }
</script>

</form>
      </div>
    
    </div>
  </div>
</div>
	
</div>
            <?php } ?>

            <!-- Solicitar Cotizacion -->

           <!--  <div  id="tabs">
            <img border='0' src="<?php echo  base_url()."public/img/boton_coti.png"; ?>" title='Solicita tu Cotización'/>
            </div>  -->


          


            <script>
              /*
              (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
              (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
              m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
              })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

              ga('create', 'UA-48767385-1', 'auto');
              ga('send', 'pageview');
              /*

            </script>

            <script>


/*

var p3chat = p3chat || [];
p3chat.push(['_setAccount', '081335174']);
p3chat.push(['_trackPage']);
(function(d, t, id) {
    if (d.getElementById(id)) return;
    var e = d.createElement(t),
        s = d.getElementsByTagName(t)[0];
    e.src = "//p3chat.com/dist/p3.js";
    e.id = id;
    s.parentNode.insertBefore(e, s);
}(document, 'script', 'p3chat-snippet'));
*/
</script>



  <script type="text/javascript">

 
/*
      $(window).load(function(){




              setTimeout(function () {
              
               PopUp('show');
              
              //PopUp('hide');
              // $('#exampleModal').modal('show');

              }, 20000);
          

      });
            */
   //formulario
        

    
             function PopUp(hideOrshow) {
                if (hideOrshow == 'hide') {
                  
                   document.getElementById('exampleModal').style.display = "none";


              }
              else  if(localStorage.getItem("popupWasShown") == null) {

                
                      $('#exampleModal').modal('show');
                  localStorage.setItem("popupWasShown",1);
                  document.getElementById('exampleModal').removeAttribute('style');

              }

             
          }


             function hideNow(e) {
              if (e.target.id == 'exampleModal') document.getElementById('exampleModal').style.display = 'none';
          }


      $(function(){
        $('#tour').crumble();


      });

  </script>



<script type="text/javascript">

   $(document).ready(function(){
   /*
        $('#tabs').click(function(){
            $('#exampleModal').modal('show');
         });
*/
        plot4 = $.jqplot('meta_div_container',[[<?php echo $total; ?>]],{

            seriesDefaults: {

                renderer: $.jqplot.MeterGaugeRenderer,

                rendererOptions: {
              <?php if($is_admin == 't' || $is_admin == 'a'){ ?>
                    label: 'Todos los almacenes',
              <?php } ?>
              <?php if($is_admin != 't' && $is_admin != 'a'){ ?>
                    label: '<?php echo $data['meta_diaria']['nom_alm'] ?>',
              <?php } ?>			  
                    labelPosition: 'bottom',

                    labelHeightAdjust: -5,

                    intervalOuterRadius: 82,

              <?php 
			  
			 $primer = round($data['meta_diaria']['meta_almacen'] / 3);
			 $segunda = round(($data['meta_diaria']['meta_almacen'] / 3) * 2);
			 $tercera = round($data['meta_diaria']['meta_almacen']); 
			 
			  ?>

                    ticks: [<?php echo '0, '.$primer.', '.$segunda.','.$tercera; ?>],

                    intervals: [<?php echo ($data['meta_diaria']['meta_almacen'] * 28 / 100) .', '. ($data['meta_diaria']['meta_almacen'] * 74 / 100). ', '. $data['meta_diaria']['meta_almacen']; ?>],

                    intervalColors:['#cc6666', '#E7E658', '#66cc66']


                }

            }

        });

        



    <?php 

        $pie = "";

        $count = 0;

        foreach ($data['margen_utilidad'] as $value) {

            $margen = number_format($value['margen_utilidad']);

            if($count++ > 0){

              $pie .= ", ['{$value['nombre']}({$margen})', {$value['margen_utilidad']}]";

            }

            else{

          $pie .= "['{$value['nombre']}({$margen})', {$value['margen_utilidad']}]";
                                           
            }

        }

    ?>    

    var s1 = [['Sony',7], ['Samsumg',13.3], ['LG',14.7], ['Vizio',5.2], ['Insignia', 1.2]];
      var data = [
    <?php echo $pie; ?>
  ];
     var plot8 = $.jqplot('utilidad_container1', [[<?php echo $pie; ?>]], {

            grid: {

                drawBorder: false,

                drawGridlines: false,

                background: '#ffffff',

                shadow:false

            },

            axesDefaults: {



            },

            seriesDefaults:{

                renderer:$.jqplot.PieRenderer,

                rendererOptions: {

                    showDataLabels: true

                }

            },

            legend: {

                show: true,

                rendererOptions: {

                    numberRows: 1

                },

                location: 's'

            }

        }); 


        $('#meta_diaria').change(function(){

            $('#meta_div_container').empty();

            

            $.ajax({

                 url: '<?php echo site_url('frontend/get_ajax_meta_diaria');?>'

                ,data: {almacen: $('#meta_diaria').val()}

                ,success: function(data){;
                  
	var total = '0';
	if(parseInt(data.total_ventas) >= parseInt(data.meta_almacen)){ 
	    total = data.meta_almacen; 
	}
	if(parseInt(data.total_ventas) <= parseInt(data.meta_almacen)){ 
	   total = parseInt(data.total_ventas); 
	}  
                    plot4 = $.jqplot('meta_div_container',[[total]],{

                        seriesDefaults: {

                            renderer: $.jqplot.MeterGaugeRenderer,

                            rendererOptions: {

                                label: $('#meta_diaria').val() == '0' ? "Todos los almacenes" : $("#meta_diaria option:selected").text(),

                                labelPosition: 'bottom',

                                labelHeightAdjust: -5,

                                intervalOuterRadius: 85,

                                ticks: [0, Math.round(data.meta_almacen / 3), Math.round((data.meta_almacen / 3) * 2), data.meta_almacen],

                                intervals: [Math.round(data.meta_almacen * 28 / 100), Math.round(data.meta_almacen * 75 / 100), data.meta_almacen],
    /*
	 ticks: [<?php '0, '. ceil($data['meta_diaria']['meta_almacen']). ', '. ceil($data['meta_diaria']['meta_almacen'] / 4).', '.$data['meta_diaria']['meta_almacen']; ?>],
     intervals: [<?php echo ceil($data['meta_diaria']['meta_almacen'] * 28 / 100) .', '. ceil($data['meta_diaria']['meta_almacen'] * 75 / 100). ', '. $data['meta_diaria']['meta_almacen']; ?>],
	 */
                                intervalColors:['#cc6666', '#E7E658', '#66cc66']

                            }

                        }

                    });

                }

            }); 

        });
/*
   $( "#desde_relevantes" ).datepicker({

             dateFormat: 'yy-mm-dd'

        });

	$( "#hasta_relevantes" ).datepicker({

             dateFormat: 'yy-mm-dd'

        });

       $('#hasta_relevantes').change(function(){

             $('#productos_relevantes_container').empty();

            

            $.ajax({

                 url: '<?php echo site_url('frontend/get_ajax_productos_relevantes');?>'

                ,data: {almacen: $('#productos_populares').val(), fecha_desde: $('#desde_relevantes').val(), fecha_hasta: $('#hasta_relevantes').val()}

                ,success: function(data){

                    var js_data = [];

                    $.each(data, function(index, element){

                        js_data.push([element.count_productos, element.nombre_producto]);

                    });

//document.write($('#productos_populares').val());

                    plot5 = $.jqplot('productos_relevantes_container', [js_data.reverse()], {

                        captureRightClick: true,

                        seriesDefaults:{

                            renderer:$.jqplot.BarRenderer,

                            shadowAngle: 135,

                            rendererOptions: {

                                barDirection: 'horizontal',

                                highlightMouseDown: true   

                            },

                            pointLabels: {show: true, formatString: '%d'}

                        },

                        axes: {

                            yaxis: {

                                renderer: $.jqplot.CategoryAxisRenderer

                            }

                        }

                    });

                }

            }); 

       }); 
*/
   $( "#desde" ).datepicker({

             dateFormat: 'yy-mm-dd'

        });

	$( "#hasta" ).datepicker({

             dateFormat: 'yy-mm-dd'

        });

         $('#hasta').change(function(){

             $('#utilidad_container1').empty();

            

            $.ajax({

                 url: '<?php echo site_url('frontend/get_ajax_utilidad_general');?>'

                ,data: {fecha_desde: $('#desde').val(), fecha_hasta: $('#hasta').val()}

                ,success: function(data){

                    var js_data = [];

                    $.each(data, function(index, element){

                        js_data.push([element.almacen_nombre+'{'+element.margen_utilidad+'}', element.margen_utilidad]);

                    });

//document.write(js_data.reverse());

        //        plot8 = $.jqplot('utilidad_container1', [js_data.reverse()], {

  var plot8 = jQuery.jqplot ('utilidad_container1', [js_data.reverse()], 
    {
      seriesDefaults: {
        renderer: jQuery.jqplot.PieRenderer, 
        rendererOptions: {
          // Turn off filling of slices.
          fill: false,
          showDataLabels: true, 
          // Add a margin to seperate the slices.
          sliceMargin: 4, 
          // stroke the slices with a little thicker line.
          lineWidth: 5
        }
      }, 
      legend: { show:true, location: 'e' }
    }
  );
              

                }

            }); 

       }); 

//--------------------------------------------------------------------------------------------	   
        <?php

            $data_graph = "";
           $data1 = "";
            $count = 0;

            foreach (array_reverse($data['productos_relevantes']) as $value) {

                if($count++ > 0){

                    $data_graph .= ",[ '{$value['nombre_producto']}']";
                echo    $data1 .= ",[ '{$value['count_productos']}']";
                }

                else{

                    $data_graph .= "['{$value['nombre_producto']}']";
                 echo   $data1 .= "[ '{$value['count_productos']}']";
                }

            }

        ?>	   
	   
 	var randomScalingFactor = function(){ return Math.round(Math.random()*100)};

	var barChartData = {
		labels : [<?php echo $data_graph;?>],
		datasets : [
        {
            label: "My First dataset",
            fillColor: "rgba(0, 125, 150, 1)",
            strokeColor: "rgba(220,220,220,0.8)",
            highlightFill: "rgba(220,220,220,0.75)",
            highlightStroke: "rgba(220,220,220,1)",
            data: [<?php echo $data1;?>]
        }
		]

	}
	window.onload = function(){
		var ctx = document.getElementById("canvas").getContext("2d");
		window.myBar = new Chart(ctx).Bar(barChartData, {
			responsive : true
		});
	} 	   
	   

  $( "#desde_relevantes" ).datepicker({

             dateFormat: 'yy-mm-dd'

        });

	$( "#hasta_relevantes" ).datepicker({

             dateFormat: 'yy-mm-dd'

        });

       $('#hasta_relevantes').change(function(){

           document.getElementById("myForm").submit();
		   
       }); 

//-------------------------------------------------------------------------------------------	   
	          

   }); 




  

</script>