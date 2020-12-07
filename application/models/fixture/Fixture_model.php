<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Fixture
 *
 * @author Locho
 */
class Fixture_model extends CI_Model {
    
    var $connection;
    
    public function __construct()
    {
        parent::__construct();
        // Your own constructor code
    }
    
      public function initialize($connection){
            $this->connection = $connection;
        } 
    
    function insertar_productos()
    {
        for($i = 1; $i <= 10000; $i++){
            $data = array(
                'nombre' => "Producto $i" ,
                'descripcion' => "Descripcion Producto $i" ,
                'precio' => rand(1, 10000)
            );

            $this->connection->insert('productos', $data); 
        }
    }
    
    function insertar_clientes()
    {
        $entidad_bancaria = array("VISA", "MASTER CARD", "PAY PAL");
        for($i = 1; $i <= 20000; $i++){
            $data = array(
                'id_provincia' => rand(53, 54) ,
                'razon_social' => "Razon Social $i" ,
                'nombre_comercial' => "Nombre Comercial $i",
                'entidad_bancaria' => $entidad_bancaria[rand(0, 2)],
                'nif_cif' => rand(11111111111, 99999999999),
                'contacto' => "Contacto $i",
                'email' => "email$i@gmail.com"
            );

            $this->connection->insert('clientes', $data); 
        }
    }
    
    function insertar_proveedores()
    {
        $entidad_bancaria = array("VISA", "MASTER CARD", "PAY PAL");
        for($i = 1; $i <= 20000; $i++){
            $data = array(
                'id_provincia' => rand(53, 54) ,
                'razon_social' => "Razon Social $i" ,
                'nombre_comercial' => "Nombre Comercial $i",
                'entidad_bancaria' => $entidad_bancaria[rand(0, 2)],
                'nif_cif' => rand(11111111111, 99999999999),
                'contacto' => "Contacto $i",
                'email' => "email$i@gmail.com"
            );

            $this->connection->insert('proveedores', $data); 
        }
    }
    
    public function insertar_factura(){
         for($i=1; $i <= 10000; $i++)
         {
             $id_factura = $i;
             $numero = $this->get_max_cod();
             
             /*SUMA * 0.18*/
             $cantidad_productos = rand(1, 5);
             $query_productos = $this->db->query("select * from productos order by rand() limit $cantidad_productos");
             $data = array();
             $precio_total = 0;
             
             foreach ($query_productos->result() as $value) {
                 $cantidad_a_llevar = rand(1, 10);
                 $data[] = array(
                     'id_factura' => $id_factura,
                     'descripcion' => $value->nombre,
                     'precio' => $value->precio,
                     'cantidad' => $cantidad_a_llevar
                 );
                 
                 $precio_total += $value->precio * $cantidad_a_llevar;
             }
             
             $array_datos_factura = array(
                    "id_factura" => $id_factura,
                    "id_cliente" 	   	    => rand(1, 10000),
                    "numero"				=> $this->codigo($numero->cod),
                    "monto"					=> ($precio_total + ($precio_total * 0.18)),
                    "fecha"  		    	=> date('d/m/y'),
                    "estado"  		    	=> 0
            );
		
		$this->db->insert("facturas", $array_datos_factura);
                $this->db->insert_batch("facturas_detalles", $data);
		
         }
    }
    public function get_max_cod()
    {
            $query = $this->db->query("SELECT MAX(RIGHT(numero,6)) cod FROM  facturas");
            return $query->row(); 								
    }
        
        
    function codigo($cod=''){
            if($cod == '')
            {
                    return 'F000001';
            }else
            {
                    $dig     = ((int)$cod + 1);
                    $ceros   = (6 - strlen($dig));
                    $new_cod = str_repeat("0",$ceros).$dig;

                    return 'F'.$new_cod;
            }
    }
}

?>

