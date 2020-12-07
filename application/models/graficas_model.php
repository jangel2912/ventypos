<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Graficas_model extends CI_Model {

    

    public $connection;

    

    public function __construct()

    {

        parent::__construct();

        // Your own constructor code

    }

    

    public function initialize($connection)

    {

        $this->connection = $connection;

    }

    

    public function get_meta_diaria($almacen = 0){

        

        $filtro = "";
           $nom_alm = "";
        $filtro_margen = "";

	    $is_admin = $this->session->userdata('is_admin');
		 $username = $this->session->userdata('username'); 

      if( $is_admin == 't' || $is_admin == 'a'){ //administrador
	     if($almacen == '0'){
		    $condition = '';  $filtro = "";
		 }else{  $condition = " and venta.almacen_id = $almacen "; $filtro = " where id= $almacen ";  }	
		 
        $get_sum_almacenes_meta = "select IFNULL(sum(almacen.meta_diaria), 0) as meta_almacen from almacen  $filtro";
		
		$hoy = date("Y-m-d"); 
		
       $total_ventas = "SELECT IFNULL(sum(total_venta),0) as total_venta from venta  where venta.id>0 and estado = '0' and date(fecha) = '".$hoy."'  $condition";
	   
	   $margen_utilidad = "SELECT IFNULL(sum(margen_utilidad),0) as margen_utilidad from detalle_venta inner join venta on venta.id = detalle_venta.venta_id  where venta.almacen_id = $almacen";
      
	  }
      if( $is_admin != 't' && $is_admin != 'a'){  //usuario
        //------------------------------------------------ almacen usuario  
		 $db_config_id = $this->session->userdata('db_config_id');
		  $id_user='';
		  $almacen='';	
                $user = $this->db->query("SELECT id FROM users where username = '".$username."' and db_config_id = '".$db_config_id."' limit 1")->result();
                 foreach ($user as $dat) {
                   $id_user = $dat->id;
                 }	
				
			$user = $this->connection->query("SELECT almacen_id FROM usuario_almacen where usuario_id = '".$id_user."' limit 1")->result();
                 foreach ($user as $dat) {
                   $almacen = $dat->almacen_id;
                 }	
				
			$user = $this->connection->query("SELECT nombre FROM almacen where id = '".$almacen."' limit 1")->result();
                 foreach ($user as $dat) {
                   $nom_alm = $dat->nombre;
                 }					 
	  //---------------------------------------------
        $get_sum_almacenes_meta = "select IFNULL(sum(almacen.meta_diaria), 0) as meta_almacen from almacen  where id = $almacen ";
        $margen_utilidad = "SELECT IFNULL(sum(margen_utilidad),0) as margen_utilidad from detalle_venta inner join venta on venta.id = detalle_venta.venta_id  where venta.almacen_id = $almacen";
		$hoy = date("Y-m-d"); 
		$total_ventas = "SELECT IFNULL(sum(total_venta),0) as total_venta from venta  where venta.id>0 and estado = '0' and date(fecha) = '".$hoy."'  and venta.almacen_id = $almacen";
      }        

        $result = array(

            'meta_almacen' => $this->connection->query($get_sum_almacenes_meta)->row()->meta_almacen

            ,'margen_utilidad' => $this->connection->query($margen_utilidad)->row()->margen_utilidad
			
			,'total_ventas' => $this->connection->query($total_ventas)->row()->total_venta
			
			,'nom_alm' => $nom_alm

        );

        

        return $result;

        

    }

    

    public function get_productos_relevantes($almacen = 0, $desde = 0, $hasta = 0){

        $filtro = "";

        if($desde != 0 ){
     $nuevahasta = strtotime('+1 day',strtotime($hasta));
     $nuevahasta = date('Y-m-j',$nuevahasta);
            $filtro = " and venta.fecha >= '".$desde."'";
		   $filtro .= " and venta.fecha <= '".$nuevahasta."'";
		   if($almacen != 0 ){
		   $filtro .= "  and venta.almacen_id = ".$almacen;
		   }
        }
	    $is_admin = $this->session->userdata('is_admin');
		 $username = $this->session->userdata('username');  
if( $is_admin == 't' || $is_admin == 'a'){ //administrador
	     if($almacen == '0'){
		    $condition = '';
		 }else{  $condition = " and  almacen_id = '$almacen' ";  }	
        $query_relevantes = "SELECT count(`nombre_producto`) as count_productos, nombre_producto FROM `detalle_venta` inner join venta on detalle_venta.venta_id = venta.id  where venta.estado='0' $condition  $filtro group by `nombre_producto` ORDER BY `count_productos`  DESC limit 10";
  }
  if( $is_admin != 't' && $is_admin != 'a'){  //usuario
        //------------------------------------------------ almacen usuario  
		 $db_config_id = $this->session->userdata('db_config_id');
		  $id_user='';
		  $almacen='';	
                $user = $this->db->query("SELECT id FROM users where username = '".$username."' and db_config_id = '".$db_config_id."' limit 1")->result();
                 foreach ($user as $dat) {
                   $id_user = $dat->id;
                 }	
				
			$user = $this->connection->query("SELECT almacen_id FROM usuario_almacen where usuario_id = '".$id_user."' limit 1")->result();
                 foreach ($user as $dat) {
                   $almacen = $dat->almacen_id;
                 }	
	  //---------------------------------------------	  
        $query_relevantes = "SELECT count(`nombre_producto`) as count_productos, nombre_producto FROM `detalle_venta` inner join venta on detalle_venta.venta_id = venta.id  where venta.estado='0' and venta.almacen_id = $almacen $filtro group by `nombre_producto` ORDER BY `count_productos`  DESC limit 10";
  }  
  
        return $this->connection->query($query_relevantes)->result_array();

    }


    public function excel_productos_relevantes( $desde = 0, $hasta = 0, $almacen = 0){

        $filtro = "";

        if($desde != 0 ){
            $nuevahasta = strtotime('+1 day',strtotime($hasta));
             $nuevahasta = date('Y-m-j',$nuevahasta);
            $filtro = " and venta.fecha >= '".$desde."'";
		   $filtro .= " and venta.fecha <= '".$nuevahasta."'";
		   if($almacen != 0 ){
		   $filtro .= "  and venta.almacen_id = ".$almacen;
		   }
        }

	    $is_admin = $this->session->userdata('is_admin');
		 $username = $this->session->userdata('username');  
if( $is_admin == 't' || $is_admin == 'a'){ //administrador
	     if($almacen == '0'){
		    $condition = '';
		 }else{  $condition = " and  almacen_id = '$almacen' ";  }	
      $query_relevantes = "SELECT count(`nombre_producto`) as count_productos, 
	  nombre_producto , nombre
		FROM `detalle_venta` inner join venta on detalle_venta.venta_id = venta.id 
		inner join almacen on almacen.id = venta.almacen_id   
		where venta.estado='0' $condition  $filtro  group by `nombre_producto` ORDER BY `count_productos` desc ";		 
  }
  if( $is_admin != 't' && $is_admin != 'a'){  //usuario
        //------------------------------------------------ almacen usuario  
		 $db_config_id = $this->session->userdata('db_config_id');
		  $id_user='';
		  $almacen='';	
                $user = $this->db->query("SELECT id FROM users where username = '".$username."' and db_config_id = '".$db_config_id."' limit 1")->result();
                 foreach ($user as $dat) {
                   $id_user = $dat->id;
                 }	
				
			$user = $this->connection->query("SELECT almacen_id FROM usuario_almacen where usuario_id = '".$id_user."' limit 1")->result();
                 foreach ($user as $dat) {
                   $almacen = $dat->almacen_id;
                 }	
	  //---------------------------------------------
      $query_relevantes = "SELECT count(`nombre_producto`) as count_productos, 
	  nombre_producto , nombre
		FROM `detalle_venta` inner join venta on detalle_venta.venta_id = venta.id 
		inner join almacen on almacen.id = venta.almacen_id   
		where venta.estado='0' and venta.almacen_id = $almacen  $filtro  group by `nombre_producto` ORDER BY `count_productos` desc ";
  }		
		
        $productos_populares  = array();
       $productos_populares= $this->connection->query($query_relevantes)->result();
	   
          return array(
                'productos' => $productos_populares
            );
    }    

    public function get_utilidad_almacen($almacen = 0){


	    $is_admin = $this->session->userdata('is_admin');
		 $username = $this->session->userdata('username');  
if( $is_admin == 't' || $is_admin == 'a'){ //administrador
	     if($almacen == '0'){
		    $condition = '';
		 }else{  $condition = " and  venta.almacen_id = '$almacen' ";  }	
        $get_utilidad = "SELECT  sum(detalle_venta.`margen_utilidad`) as margen_utilidad, almacen.nombre  FROM `detalle_venta` Inner Join venta on venta.id = detalle_venta.venta_id inner join almacen on almacen.id = venta.almacen_id where venta.estado='0' $condition  group by almacen.id ";		 
  }
  if( $is_admin != 't' && $is_admin != 'a'){  //usuario
        //------------------------------------------------ almacen usuario  
		 $db_config_id = $this->session->userdata('db_config_id');
		  $id_user='';
		  $almacen='';	
                $user = $this->db->query("SELECT id FROM users where username = '".$username."' and db_config_id = '".$db_config_id."' limit 1")->result();
                 foreach ($user as $dat) {
                   $id_user = $dat->id;
                 }	
				
			$user = $this->connection->query("SELECT almacen_id FROM usuario_almacen where usuario_id = '".$id_user."' limit 1")->result();
                 foreach ($user as $dat) {
                   $almacen = $dat->almacen_id;
                 }	
	  //---------------------------------------------
        $get_utilidad = "SELECT  sum(detalle_venta.`margen_utilidad`) as margen_utilidad, almacen.nombre  FROM `detalle_venta` Inner Join venta on venta.id = detalle_venta.venta_id inner join almacen on almacen.id = venta.almacen_id where venta.estado='0' and   venta.almacen_id = $almacen group by almacen.id  ";
  }		        



        return $this->connection->query($get_utilidad)->result_array();

        

    }

    public function get_utilidad_general($desde = 0, $hasta = 0){


        $filtro = "";

        $filtro_margen = "";

        if($hasta != 0){

     $nuevahasta = strtotime('+1 day',strtotime($hasta));
     $nuevahasta = date('Y-m-j',$nuevahasta);
            $filtro = " and venta.fecha >= '".$desde."'";
			
			$filtro .= " and venta.fecha <= '".$nuevahasta."'";

          //  $filtro_margen = " where venta.almacen_id = $almacen";

        }

	    $is_admin = $this->session->userdata('is_admin');
		 $username = $this->session->userdata('username');  
    if( $is_admin == 't' || $is_admin == 'a'){ //administrador
       
        $get_utilidad = "SELECT  sum(detalle_venta.`margen_utilidad`) as margen_utilidad, almacen.nombre as almacen_nombre  FROM `detalle_venta` Inner Join venta on venta.id = detalle_venta.venta_id inner join almacen on almacen.id = venta.almacen_id  where venta.estado='0' $filtro group by almacen.id  ";
	  }	
  if( $is_admin != 't' && $is_admin != 'a'){  //usuario
        //------------------------------------------------ almacen usuario  
		 $db_config_id = $this->session->userdata('db_config_id');
		  $id_user='';
		  $almacen='';	
                $user = $this->db->query("SELECT id FROM users where username = '".$username."' and db_config_id = '".$db_config_id."' limit 1")->result();
                 foreach ($user as $dat) {
                   $id_user = $dat->id;
                 }	
				
			$user = $this->connection->query("SELECT almacen_id FROM usuario_almacen where usuario_id = '".$id_user."' limit 1")->result();
                 foreach ($user as $dat) {
                   $almacen = $dat->almacen_id;
                 }	
	  //---------------------------------------------
        $get_utilidad = "SELECT  sum(detalle_venta.`margen_utilidad`) as margen_utilidad, almacen.nombre as almacen_nombre  FROM `detalle_venta` Inner Join venta on venta.id = detalle_venta.venta_id inner join almacen on almacen.id = venta.almacen_id  where venta.estado='0' and venta.almacen_id = $almacen $filtro  group by almacen.id  ";
	  }			

        return $this->connection->query($get_utilidad)->result_array();

    }



}

?>

