<?php 
	class Gestion_sesion_vendedor{ 

		
		function index(){ 			
			//instanciamos al objeto codeigniter 
			$CI =& get_instance(); 
			// obtenemos el nombre del controlador en el que estamos
			$controlador = $CI->router->class;
			$function = $CI->router->fetch_method();
			
			$es_estacion_pedido =(isset($CI->session->userdata['es_estacion_pedido']))?$CI->session->userdata['es_estacion_pedido']:0;
			
			if($es_estacion_pedido==1){	
				if($controlador=="frontend"){
					redirect("tomaPedidos");
				}						
			}
		} 

		function cerrar_sesion_vendedor(){
			$CI =& get_instance(); 
			// obtenemos el nombre del controlador en el que estamos
			$controlador = $CI->router->class;
			$function = $CI->router->fetch_method();
			
			$es_estacion_pedido =(isset($CI->session->userdata['es_estacion_pedido']))?$CI->session->userdata['es_estacion_pedido']:0;
			
			if($es_estacion_pedido==1){		
				if(($controlador=="auth")&&(($function=="logout")||($function=="login"))){
					
					$bd=(isset($CI->session->userdata['base_dato']))?$CI->session->userdata['base_dato']:"";
					$ven=(isset($CI->session->userdata['vendedor_estacion_actual_id']))?$CI->session->userdata['vendedor_estacion_actual_id']:0;
					
					if((!empty($bd))&&(!empty($ven))){
						$CI->db->where('id', $ven);
						$CI->db->set('sesion_estacion',0);
						$CI->db->update("$bd.vendedor");						
					}					
				}				
			}
		}
	} 
?>
