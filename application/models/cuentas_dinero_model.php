<?php

class Cuentas_dinero_model extends CI_Model

{

	var $connection;

	// Constructor

	public function __construct(){

		parent::__construct();

	}

	

    public function initialize($connection) {

        $this->connection = $connection;

    }

        public function get_ajax_data(){
		
        $sql = "SELECT *  FROM cuentas_dinero  ORDER BY id desc ";
        $data = array();
	    foreach ($this->connection->query($sql)->result() as $value) {
            $sql1 = "SELECT *  FROM almacen where id = '$value->id_almacen' ";
	        foreach ($this->connection->query($sql1)->result() as $value1) {
			   $almacen = $value1->nombre;
			}
            $data[] = array(
                $value->id,			
                $value->nombre,
                $almacen,
                $value->id
            );
        }
        return array(
            'aaData' => $data
        );

        }


	public function add($data){	

            $this->connection->insert("cuentas_dinero",$data);
				
	}		

	public function editar($data, $id){	

		$this->connection->where('id', $id);

		$this->connection->update("cuentas_dinero",$data);
				
	}		

	public function apertura_cierre_caja($data){	

            $this->connection->insert("cierres_caja",$data);
			$this->session->set_userdata('caja', 'abierta');
	          return	$id = $this->connection->insert_id();		
	}

	public function movimiento_cierre_caja($data){	

            $this->connection->insert("movimientos_cierre_caja",$data);
	      return	$id = $this->connection->insert_id();		
	}

	public function get_by_id($id = 0){
         $query = $this->connection->query("SELECT * FROM  cuentas_dinero WHERE id = '".$id."'");
         return $query->row_array();								
    }

	public function get_all($offset){
	    $query = $this->connection->query("SELECT * FROM cuentas_dinero");
		return $query->result();
	}

}

?>