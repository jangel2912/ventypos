?php

// Proyecto: Sistema Facturacion

// Version: 1.0

// Programador: Jorge Linares

// Framework: Codeigniter

// Clase: Productos



class Pingredientes_model extends CI_Model{

	var $connection;

	// Constructor

	public function __construct()

	{

		parent::__construct();		

	}

        

        public function initialize($connection)

        {

            $this->connection = $connection;

        }

	
	

	public function add($data, $usuario)

	{	

                $this->connection->insert("producto", $data);

                $id = $this->connection->insert_id();

                

                //$almacenes = $this->connection->query('select * from almacen');

                $data_stock_actual = array();

                $data_stock_diario = array();

                foreach ($_POST['Stock'] as $key => $value) {

                    

                    $data_stock_actual[] = array(

                            'almacen_id' => $key,

                            'producto_id' => $id,

                            'unidades' => $value

                        );

                    

                    if($value > 0){

                        $data_stock_diario[] =  array(

                                'producto_id' =>$id

                              , 'almacen_id' => $key

                              , 'fecha' => date('Y-m-d')

                              , 'unidad' => $value

                              , 'precio' => $data['precio_venta']

                              //, 'cod_documento' => "Adicion"

                              ,'usuario' => $usuario

                              , 'razon' => 'E'

                            );

                    }

                }

                

                $this->connection->insert_batch('stock_actual', $data_stock_actual);

                $this->connection->insert_batch('stock_diario', $data_stock_diario);

                /*echo $this->connection->last_query();

                die;*/

                //$array_datos['id_producto'] = $this->connection->insert_id();

                return $this->connection->insert_id();

	}

	

	public function update($data, $usuario)

	{			

		$this->connection->where('id', $data['id']);

		$this->connection->update("producto", $data);

                $data_stock_diario = array();

                foreach ($_POST['Stock'] as $key => $value) {

                    if($value > 0){

                       $values_actual =  $this->connection->where('almacen_id', $key)

                            ->where('producto_id', $data['id'])

                            ->get('stock_actual')->row();

                        

                        $this->connection->where('almacen_id', $key)

                            ->where('producto_id', $data['id'])

                            ->update('stock_actual', array('unidades' => $value + $values_actual->unidades ));

                    

                        $data_stock_diario[] =  array(

                                'producto_id' =>$data['id']

                              , 'almacen_id' => $key

                              , 'fecha' => date('Y-m-d')

                              , 'unidad' => $value

                              , 'precio' => $data['precio_venta']

                              //, 'cod_documento' => "Actualizacion"

                              ,'usuario' => $usuario

                              , 'razon' => 'E'

                            );

                    }

                }

                $this->connection->insert_batch('stock_diario', $data_stock_diario);  

	}

	

	public function delete($id)

	{	

		$this->connection->where('id', $id);

		$this->connection->delete("producto");	

	}

        

        public function excel(){

            //$this->connection->select("id_producto, nombre, descripcion, precio, nombre_impuesto, porciento");

            //$this->connection->from("productof");

            //$this->connection->join('impuesto', 'impuesto.id_impuesto = productof.id_impuesto');

            $str_query = "select * from producto inner join impuesto i on  producto.impuesto = i.id_impuesto";

            

            $query = $this->connection->query($str_query);

            return $query->result();

        }

        

        public function excel_exist($nombre, $precio){

           

           $this->connection->where("nombre", $nombre);

           $this->connection->where("precio", $precio);

           $this->connection->from("producto");

           $this->connection->select("*");

            

            $query = $this->connection->get();

            if($query->num_rows() > 0){

                return true;

            }

            else {

                return false;

            }   

        }


    public function get_term_existencias($q, $almacen)

    {   

            $sql = "SELECT p.id, s.unidades, p.codigo, p.nombre, p.precio_compra from producto p inner join stock_actual s on s.producto_id = p.id WHERE (p.nombre LIKE '%$q%' or p.codigo LIKE '%$q%') and s.almacen_id = $almacen LIMIT 0,30";

            $query = $this->connection->query($sql);

            return $query->result_array();

    }

        

        public function excel_add($array_datos){

            $query = "INSERT INTO `producto` (`nombre`, `descripcion`, `precio`, `id_impuesto`) VALUES ('".$array_datos['nombre']."', '".$array_datos['descripcion']."', ".$array_datos['precio'].", ".$array_datos['id_impuesto'].");";

            $this->connection->query($query);

        }

}

?>