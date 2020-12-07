<?php

class Caja extends CI_Controller {

    var $dbConnection;

    function __construct() {

            parent::__construct();

            

            $usuario = $this->session->userdata('usuario');

            $clave = $this->session->userdata('clave');

            $servidor = $this->session->userdata('servidor');

            $base_dato = $this->session->userdata('base_dato');



            $dns = "mysql://$usuario:$clave@$servidor/$base_dato";

            $this->dbConnection = $this->load->database($dns, true);           

            $this->load->model("Caja_model",'Caja');

            $this->Caja->initialize($this->dbConnection);
			

            $this->load->model("miempresa_model",'miempresa');

            $this->miempresa->initialize($this->dbConnection);


            $this->load->model("vendedores_model",'vendedores');

            $this->vendedores->initialize($this->dbConnection);


            $this->load->model("pagos_model",'pagos');

            $this->pagos->initialize($this->dbConnection);

            

            /*$this->load->model("clientes_model",'clientes');

            $this->clientes->initialize($this->dbConnection);*/


            $this->load->model("clientes_model",'clientes');

            $this->clientes->initialize($this->dbConnection);

            $this->load->model("productos_model",'productos');

            $this->productos->initialize($this->dbConnection);


            $this->load->model("categorias_model",'categorias');

            $this->categorias->initialize($this->dbConnection);


            $this->load->model("impuestos_model",'impuestos');

            $this->impuestos->initialize($this->dbConnection);

		        $this->load->model("pais_provincia_model",'pais_provincia'); 

            $this->load->model("facturas_model",'facturas');

            $this->facturas->initialize($this->dbConnection);
        
           $this->load->model("almacenes_model",'almacen'); 
		   
            $this->almacen->initialize($this->dbConnection);	            			


            $this->load->library('pagination');

            $this->form_validation->set_error_delimiters('<p class="text-error">', '</p>');

            $idioma = $this->session->userdata('idioma');

            $this->lang->load('sima', $idioma);

        }      

 
	public function index($offset = 0)

	{	

       if (!$this->ion_auth->logged_in()){

			redirect('auth', 'refresh');
         }
           $this->layout->template('member')->show('caja/index');

	}

	public function listado_cierres()

	{	

       if (!$this->ion_auth->logged_in()){

			redirect('auth', 'refresh');
         }
           $this->layout->template('member')->show('caja/listado_cierres');

	} 

    public function listado_cierres_productos($id_cierre)

    {   
       if (!$this->ion_auth->logged_in()){

            redirect('auth', 'refresh');
         }
           $this->layout->template('member')->show('caja/listado_cierres_productos',compact("id_cierre"));

    }
 
    public function categorias_cierres($id)

    {   

       if (!$this->ion_auth->logged_in()){

            redirect('auth', 'refresh');
         }
           $this->layout->template('member')->show('caja/categorias_cierres',compact("id"));

    }

    public function productos_cierres($id)

    {   

       if (!$this->ion_auth->logged_in()){

            redirect('auth', 'refresh');
         }
           $this->layout->template('member')->show('caja/productos_cierres',compact("id"));

    }
 
	public function movimientos_cierres($id)

	{	

       if (!$this->ion_auth->logged_in()){

			redirect('auth', 'refresh');
         }
           $this->layout->template('member')->show('caja/movimientos_cierres',compact("id"));

	} 
 
	public function cerrar_caja()

	{

       if (!$this->ion_auth->logged_in()) { redirect('auth', 'refresh'); }
      
	  if ($this->input->post('total')){

                    $data = array(

                        'total_egresos' => $this->input->post('egresos')
                       ,'total_ingresos' => $this->input->post('ingresos')
					   ,'total_cierre' => $this->input->post('total')
                       ,'hora_cierre' => date('H:i:s')
                    );

                    $this->Caja->cerrar_caja_final($data);	  
	  
                    $this->session->unset_userdata('caja');

                    $this->session->set_flashdata('message', custom_lang('sima_category_created_message', 'Se ha cerrado correctamente'));

                    redirect('ventas/index');	   
	  }
		 
	   $data['almacen'] = $this->almacen->get_all('0');	
	   $data['caja'] = $this->Caja->get_all('0');	
        $this->layout->template('member')->show('caja/cierre', 
		array('data' => $this->Caja->cierre_caja(), 'data1' => $data)
		);
				
	} 

	public function nuevo(){	

        if (!$this->ion_auth->logged_in()){

			redirect('auth', 'refresh');

		}

		if ($this->input->post('nombre')){

                    $data = array(

                        'nombre' => $this->input->post('nombre')

                        ,'id_Almacen' => $this->input->post('almacen')

                    );

                    $this->Caja->add($data);

                    $this->session->set_flashdata('message', custom_lang('sima_category_created_message', 'Caja se ha creado correctamente'));

                    redirect('caja/index');

            }
                   
	            $data['almacen'] = $this->almacen->get_all('0');
                $this->layout->template('member')->show('caja/nuevo', array('data1' => $data));

	}
	
	public function editar($id){	

        if (!$this->ion_auth->logged_in()){

			redirect('auth', 'refresh');

		}

		if ($this->input->post('nombre')){

                    $data = array(

                        'nombre' => $this->input->post('nombre')

                        ,'id_Almacen' => $this->input->post('almacen')

                    );

                    $this->Caja->editar($data, $id);

                    $this->session->set_flashdata('message', custom_lang('sima_category_created_message', 'Caja se ha editado correctamente'));

                    redirect('caja/index');

            }
                    $data = array();  
	            $data1['almacen'] = $this->almacen->get_all('0');
				$data['data']  = $this->Caja->get_by_id($id);
                $this->layout->template('member')->show('caja/editar', array('data1' => $data1, 'data' => $data));

	}
            
	public function get_ajax_data(){

            $this->output->set_content_type('application/json')->set_output(json_encode($this->Caja->get_ajax_data()));

    }

	public function get_ajax_data_listado_cierre(){

            $this->output->set_content_type('application/json')->set_output(json_encode($this->Caja->get_ajax_data_listado_cierre()));

    }

    public function get_ajax_data_listado_cierre_productos($id_cierre){

            $this->output->set_content_type('application/json')->set_output(json_encode($this->Caja->get_ajax_data_listado_cierre_productos($id_cierre)));

    }

	public function get_ajax_data_movimientos_cierre($id){

            $this->output->set_content_type('application/json')->set_output(json_encode($this->Caja->get_ajax_data_movimientos_cierre($id)));

    }

    public function imprimir_cierre_productos ($id_cierre, $fecha, $hora_apertura, $hora_cierre) {
        if (!$this->ion_auth->logged_in()){
            redirect('auth', 'refresh');
        }


        $this->load->model("miempresa_model",'mi_empresa');
        $this->mi_empresa->initialize($this->dbConnection);
        $data_empresa = $this->mi_empresa->get_data_empresa();

        $data = array (
                "cierres_productos" => $this->Caja->get_ajax_data_cierre_productos($id_cierre, $fecha, $hora_apertura, $hora_cierre)
                ,'data_empresa' =>  $data_empresa
            );

        $this->layout->template('ajax')->show('caja/imprimir_cierre_productos', array("data" => $data));
    }

    public function imprimir_cierre_categorias ($id_cierre, $fecha, $hora_apertura, $hora_cierre) {
        if (!$this->ion_auth->logged_in()){
            redirect('auth', 'refresh');
        }

        $this->load->model("miempresa_model",'mi_empresa');
        $this->mi_empresa->initialize($this->dbConnection);
        $data_empresa = $this->mi_empresa->get_data_empresa();

        $data = array (
                "cierres_productos" => $this->Caja->get_ajax_data_cierre_categorias($id_cierre, $fecha, $hora_apertura, $hora_cierre)
                ,'data_empresa' =>  $data_empresa
            );

        $this->layout->template('ajax')->show('caja/imprimir_cierre_categorias', array("data" => $data));
    }

   public function re_apertura($id=NULL){
       
     $this->session->set_userdata('caja', $id);
     redirect("caja/cerrar_caja/");
  }

   //apertura
  
	public function apertura($id=NULL){
    
      /*var_dump($this->db->get('venta'));  */

       $this->load->model("miempresa_model",'mi_empresa');

       $this->mi_empresa->initialize($this->dbConnection);

       $data_empresa = $this->mi_empresa->get_data_empresa();
	   
	    $is_admin = $this->session->userdata('is_admin');
		 $username = $this->session->userdata('username');
		 $db_config_id = $this->session->userdata('db_config_id');
		 
                $user = $this->db->query("SELECT id FROM users where username = '".$username."' and db_config_id = '".$db_config_id."' limit 1")->result();
                 foreach ($user as $dat) {
                   $id_user = $dat->id;
				  }	
				
			    $user = $this->dbConnection->query("SELECT ualma.id_Caja, a.id  
                                                    FROM usuario_almacen AS ualma
                                                    INNER JOIN almacen AS a ON  a.id=ualma.almacen_id
                                                    WHERE usuario_id = '".$id_user."' limit 1")->result();
                 

                foreach ($user as $dat) {
                   $id_Caja = $dat->id_Caja;
                   $id_almacen = $dat->id;
				 }	

		 //agregar producto
		 if(isset($_POST['fecha'])){
		 
	      $data = array(

          'fecha' => $_POST['fecha'],
		  
		  'hora_apertura' =>  date('H:i:s'),
		  
		  'hora_cierre' =>  '',
		  
		  'id_Usuario' => $id_user,
		  
          'id_Caja' =>  $id_Caja,

          'id_Almacen' => $_POST['almacen'],
		  
		  'total_egresos' => '',
		  
		  'total_ingresos' => '',
		 
		  'total_cierre' => ''

          );
		  
	  
        $id = $this->Caja->apertura_cierre_caja($data);
	  

        if($this->input->post('foma_pago')){
		   for($contx=0;$contx<count($this->input->post('foma_pago'));$contx++){
		   
		 $array_datos = array(

			"Id_cierre"  => $id,
              "hora_movimiento"  => date('H:i:s'),
             "id_usuario"  => $id_user,			  
             "tipo_movimiento"  => 'entrada_apertura',			  
             "valor"  => $this->input->post('valor')[$contx],			  
             "forma_pago"  => $this->input->post('foma_pago')[$contx],	
			 "numero"  => ''	
			 		  
		);
		     $this->Caja->movimiento_cierre_caja($array_datos);
			 
		   }
		}
    		   
		   $this->session->set_flashdata('message', custom_lang('sima_bill_created_message', "Se ha iniciado correctamente la apertura"));

           redirect("ventas/nuevo/");
		} 
		 $data = array();
		 
	     //$data['almacen'] = $this->almacen->get_all('0');
         $data['almacen']=$this->almacen->get_by_id($id_almacen);

        

	     $data['forma_pago'] = $this->db->query("SELECT mostrar_opcion, valor_opcion FROM opciones  where nombre_opcion = 'tipo_pago' order by id_opcion asc")->result();
	
        $this->layout->template('ventas')
		->css(array(base_url("/public/css/stylesheets.css"),base_url('public/css/multiselect/multiselect.css')))
		->show('caja/apertura', array('data1' => $data, 'data' => $data, 'id' => $id));
    
  }



    public function imprimir_cierre_caja($id=null){
		
		$empresa = $this->miempresa->get_data_empresa();
	
	    $data = array(

         'data_empresa' =>  $empresa

    );
			
				$caja_1 = $this->Caja->get_listado_cierre($id);
				
				$caja_2 = $this->Caja->get_movimientos_cierre_entradas_ventas($id);
				
				$caja_4 = $this->Caja->get_movimientos_cierre_salidas($id);
				
				$caja_5 = $this->Caja->get_movimientos_all($id);
				
				$caja_8 = $this->Caja->get_movimientos_impuestos($id);
				
				$caja_6 = $this->Caja->get_movimientos_cierre_salidas_si_no($id);
				
				$caja_7 = $this->Caja->get_facturas_ultpri($id);
				
				$caja_8 = $this->Caja->get_movimientos_cierre_entradas_apertura($id);
				
				$cierres_salidas = $this->Caja->cierre_caja($id);
				
				$base = $this->Caja->base_iva($id, 'base');
			    $iva = $this->Caja->base_iva($id, 'iva');
	
		        foreach ($caja_1 as $value2) {
				
				$fecha =    $value2['fecha'];              
				$hora_apertura  =   $value2['hora_apertura'];
				$hora_cierre   = $value2['hora_cierre'];
				$username	=  $value2['username'];
                $nombre_caja = $value2['nombre_caja'];
                $almacen = $value2['almacen'];
                $total_cierre =  $value2['total_cierre'];
			    $total_egresos = $value2['total_egresos'];
                $total_ingresos =  $value2['total_ingresos'];
				
                $id = $value2['id'];
				
				}	

     require_once APPPATH.'libraries/tcpdf/tcpdf_import.php';

    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, "LETTER", true, 'UTF-8', false);

    $pdf->setPrintHeader(false);

    $pdf->setPrintFooter(false);

    $pdf->AddPage('P', "LETTER");   
	
	if($empresa["data"]['nombre'] == 'ALMACEN LA TUERCA'){
	  $resolucion = ' <tr><td align="center"><b>Res DIAN No 10000055307 2015/06/05<br>
desde No 1 al 500000 factura POS Vendty.com</b></td> </tr>';
	}
	   

$html = '
<table border="0" cellspacing="1" cellpadding="3" style=" font-size:9px" >
    <tr>
        <td align="center"><b>'.$empresa["data"]['nombre'].'</b></td>
    </tr>
    '.$resolucion.'
    <tr>
        <td align="center"><b>Cierre de Caja No. '.$id.'</b></td>
    </tr>	
    <tr>
        <td align="center">Fecha: <b>'.$fecha.'</b> &nbsp;&nbsp;&nbsp; Hora de Apertura: <b>'.$hora_apertura.'</b> - Hora de Cierre: <b>'.$hora_cierre.'</b></td>
    </tr>	
	<tr>
        <td align="center">Usuario: <b>'.$username.'</b> &nbsp;&nbsp;&nbsp; Caja: <b>'.$nombre_caja.'</b> &nbsp;&nbsp;&nbsp; Almacen: <b>'.$almacen.'</b>  </td>
    </tr>	

    <tr>
        <td align="center">'.$caja_7.'</td>
    </tr>	
</table>';
$html .= '<hr>';
	
$html .= '
<table border="0" cellspacing="1" cellpadding="3" style=" font-size:9px" >
                        <tr>		
							<th align="left"><b>Cantidad</b></th>							
                            <th align="left"><b>Forma de pago</b></th>
							 <th align="right"><b>Valor</b></th>
                        </tr>
';
/*
if($caja_6 == 'si'){
 foreach ($caja_2 as $value){
   foreach ($caja_4 as $value1){
 			$formpago2=str_replace("_"," ",$value["forma_pago"]);
			$formpago2=ucfirst($formpago2);
			
 			$formpago1=str_replace("_"," ",$value1["forma_pago"]);
			$formpago1=ucfirst($formpago1);	
						  
 if($formpago1 == $formpago2){
         $html .= '							
                        <tr>
                            <td align="left">
                                '.($value["cantidad_ingresos"] + $value1["cantidad_ingresos"]).'
                            </td>
                            <td align="right">
                               $ '.number_format(($value['total_ingresos'] - $value1['total_ingresos'])).'
                            </td>													
                            <td align="right">
                                '.$formpago1.'
							</td>
                        </tr>
         '; 
 if($formpago1 == 'Efectivo' ){ $cambio = 1; }else{ $cambio = 'Efectivo'; }
 if($formpago1 == 'Tarjeta debito' ){ $cambio1 = 1; }else{ $cambio1 = 'Tarjeta debito'; }
 if($formpago1 == 'Credito' ){ $cambio2 = 1; }else{ $cambio2 = 'Credito'; } 
 if($formpago1 == 'Saldo a Favor' ){ $cambio3 = 1; }else{ $cambio3 = 'Saldo a Favor'; } 
 if($formpago1 == 'Tarjeta credito' ){ $cambio4 = 1; }else{ $cambio4 = 'Tarjeta credito'; } 
  
 
}	


					  
 }	 
	 
if($formpago2 == $cambio ){
         $html .= '							
                        <tr>
                            <td align="left">
                                '.($value["cantidad_ingresos"]).'
                            </td>
                            <td align="right">
                               $ '.number_format(($value['total_ingresos'])).'
                            </td>													
                            <td align="right">
                                '.$formpago2.'
							</td>
                        </tr>
         '; 
}
if($formpago2 == $cambio1 ){
         $html .= '							
                        <tr>
                            <td align="left">
                                '.($value["cantidad_ingresos"]).'
                            </td>
                            <td align="right">
                               $ '.number_format(($value['total_ingresos'])).'
                            </td>													
                            <td align="right">
                               '.$formpago2.'
							</td>
                        </tr>
         '; 
}
if($formpago2 == $cambio2 ){
         $html .= '							
                        <tr>
                            <td align="left">
                                '.($value["cantidad_ingresos"]).'
                            </td>
                            <td align="right">
                               $ '.number_format(($value['total_ingresos'])).'
                            </td>													
                            <td align="right">
                               '.$formpago2.'
							</td>
                        </tr>
         '; 
}
if($formpago2 == $cambio3 ){
         $html .= '							
                        <tr>
                            <td align="left">
                                '.($value["cantidad_ingresos"]).'
                            </td>
                            <td align="right">
                               $ '.number_format(($value['total_ingresos'])).'
                            </td>													
                            <td align="right">
                               '.$formpago2.'
							</td>
                        </tr>
         '; 
}
if($formpago2 == $cambio4 ){
         $html .= '							
                        <tr>
                            <td align="left">
                                '.($value["cantidad_ingresos"]).'
                            </td>
                            <td align="right">
                               $ '.number_format(($value['total_ingresos'])).'
                            </td>													
                            <td align="right">
                               '.$formpago2.'
							</td>
                        </tr>
         '; 
}

	 
}	

}
else{
*/
   foreach ($caja_2 as $value){
 			$formpago2=str_replace("_"," ",$value["forma_pago"]);
			$formpago2=ucfirst($formpago2);
						  

         $html .= '							
                        <tr>
                            <td align="left">
                                '.($value["cantidad_ingresos"]).'
                            </td>												
                            <td align="left">
                                '.$formpago2.'
							</td>
							<td align="right">
                               $ '.number_format(($value['total_ingresos'])).'
                            </td>	
                        </tr>
            '; 
	  }
//}
         $html .= '							
                        <tr>
                            <td align="left">
                               
                            </td>												
                            <td align="left">
                               Base
							</td>
							<td align="right">
                               $ '.number_format($base).'
                            </td>	
                        </tr>
            '; 	

         $html .= '							
                        <tr>
                            <td align="left">
                               
                            </td>												
                            <td align="left">
                                IVA
							</td>
							<td align="right">
                               $ '.number_format($iva).'
                            </td>	
                        </tr>
            '; 	

     $total_apertura = 0;
   foreach ($caja_8 as $value){
        $total_apertura +=  $value['total_ingresos'];
    }
						  
     $html .= '							
                        <tr>
                            <td align="left">
                              
                            </td>												
                            <td align="left">
                                Total de apertura
							</td>
							<td align="right">
                               $ '.number_format(($total_apertura)).'
                            </td>	
                        </tr>
            '; 
	

 foreach ($cierres_salidas['pago_gastos'] as $value1){
         $html .= '							
                        <tr>
                            <td align="left">
                               
                            </td>												
                            <td align="left">
                                Total gastos
							</td>
							<td align="right">
                               $ '.number_format($value1->total).'
                            </td>	
                        </tr>
            '; 	
}
 foreach ($cierres_salidas['pago_recibidos'] as $value1){
         $html .= '							
                        <tr>
                            <td align="left">
                               
                            </td>												
                            <td align="left">
                                Total de pagos a creditos
							</td>
							<td align="right">
                               $ '.number_format($value1->total).'
                            </td>	
                        </tr>
            '; 	
} 
foreach ($cierres_salidas['pago_proveedores'] as $value1){
         $html .= '							
                        <tr>
                            <td align="left">
                               
                            </td>												
                            <td align="left">
                               Total de pagos a proveedores
							</td>
							<td align="right">
                               $ '.number_format($value1->total).'
                            </td>	
                        </tr>
            '; 	
}

         $html .= '							
                        <tr>
                            <td align="left">
                               
                            </td>												
                            <td align="left">
                              Subtotal de Ingresos
							</td>
							<td align="right">
                               $ '.$total_ingresos.'
                            </td>	
                        </tr>
            '; 
         $html .= '							
                        <tr>
                            <td align="left">
                               
                            </td>												
                            <td align="left">
                              Subtotal de Egresos
							</td>
							<td align="right">
                               $ '.$total_egresos.'
                            </td>	
                        </tr>
            '; 			
				
         $html .= '							
                        <tr>
                            <td align="left">
                               
                            </td>												
                            <td align="left">
                              Total del cierre
							</td>
							<td align="right">
                               $ '.$total_cierre.'
                            </td>	
                        </tr>
            '; 
		
$html .= '	</table>';	
$html .= '<hr>';
$html .= '
<table border="0" cellspacing="1" cellpadding="3" style=" font-size:9px" >
                        <tr>	
							<th align="right" width="58%"></th>
							<th align="left" width="20%" ><b>Impuesto</b></th>							
                            <th align="right" width="22%"><b>Valor</b></th>	
                        </tr>
';
$total_descuento = 0; $total_impuesto = 0;  $total_valor = 0; 
 foreach ($caja_8 as $value){
         $html .= '							
                        <tr>
						<td align="right"></td>
                            <td align="left">'.$value["impuesto"].' </td>
							<td align="right">$ '.number_format($value["total_precio_venta"]).' </td>
                        </tr>
         ';  			  
}	
$html .= '	</table>';	
$html .= '<hr>';	
$html .= '
<table border="0" cellspacing="1" cellpadding="3" style=" font-size:9px" >
                        <tr>		
							<th align="left"><b>Numero</b></th>							
                            <th align="left"><b>Hora</b></th>	
							<th align="left"><b>Usuario</b></th>							
                            <th align="left"><b>Forma de pago</b></th>
							<th align="left"><b>Descuentos</b></th>
							<th align="left"><b>Impuestos</b></th>
							<th align="right"><b>Valor</b></th>
                        </tr>
';
$total_descuento = 0; $total_impuesto = 0;  $total_valor = 0; 
 foreach ($caja_5 as $value){
         $html .= '							
                        <tr>
                            <td align="left">'.$value["numero"].' </td>
							<td align="left">'.$value["hora_movimiento"].' </td>
							<td align="left">'.$value["username"].' </td>
							<td align="left">'.$value["forma_pago"].' </td>
							<td align="left">$ '.$value["total_descuento"].' </td>
							<td align="left">$ '.$value["impuesto"].' </td>
							<td align="right">$ '.number_format($value["valor"]).' </td>
                        </tr>
         ';  
$total_descuento += $value["total_descuento"]; $total_impuesto += $value["impuesto"];  $total_valor += $value["valor"]; 				  
}	
$html .= '	</table>';	
$html .= '<hr>';
         $html .= '		
<table border="0" cellspacing="1" cellpadding="3" style=" font-size:9px" >		 					
                        <tr>
                            <td align="left"> </td>
							<td align="left"></td>
							<td align="left"> </td>
							<td align="left">Totales</td>
							<td align="left">$ '.number_format($total_descuento).' </td>
							<td align="left">$ '.number_format($total_impuesto).' </td>
							<td align="right">$ '.number_format($total_valor).' </td>
                        </tr>
         ';   	
$html .= '	</table>';
$html .= '<hr>';
			

      ob_clean();
	  
      $pdf->writeHTML($html, true, false, true, false, '');

      $pdf->Output('cuadre de caja.pdf', 'I');
  


  }


}



?>