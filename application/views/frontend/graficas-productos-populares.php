<html>
	<head>
		<title>Bar Chart</title>
		<script src="http://www.chartjs.org/assets/Chart.min.js"></script>
	</head>
	<body>
		<div style="width: 559px; height: 349px;">
			<canvas id="canvas" height="580" width="610"></canvas>

		</div>


	<script>
        <?php

            $data_graph = "";
           $data1 = "";
            $count = 0;
			$nom = "";

            foreach (array_reverse($data['productos_relevantes']) as $value) {
                 
				$nom = substr($value['nombre_producto'], 0, 40);
				 
                if($count++ > 0){
                      
                    $data_graph .= ",[ '{$nom}']";
                echo    $data1 .= ",[ '{$value['count_productos']}']";
                }

                else{

                    $data_graph .= "['{$nom}']";
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

	</script>
	</body>
</html>
