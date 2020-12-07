<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Fixture
 * Clase para poblar la base de datos
 *
 * @author Locho
 */

class Fixture extends CI_Controller {

        var $dbConnection;
        
        public function __construct()
        {
            parent::__construct();
            
                $usuario = $this->session->userdata('usuario');
                $clave = $this->session->userdata('clave');
                $servidor = $this->session->userdata('servidor');
                $base_dato = $this->session->userdata('base_dato');
                
                $this->dns = "mysql://$usuario:$clave@$servidor/$base_dato";
                $this->dbConnection = $this->load->database($this->dns, true);
                
                $this->load->model('fixture/Fixture_model', 'fixture');
                $this->fixture->initialize($this->dbConnection);
        }
    
	public function index(){    
            $this->layout->show('fixture/index.php', array('test' => "Variable de prueba"));
	}
        
        public function insertar_productos(){
            $this->benchmark->mark('start');
                $this->fixture->insertar_productos();
            $this->benchmark->mark('end');
            $this->layout->show('fixture/insertar_producto');
        }
        
        public function insertar_clientes(){
            $this->benchmark->mark('start');
                $this->fixture->insertar_clientes();
            $this->benchmark->mark('end');
            $this->layout->show('fixture/insertar_producto');
        }
        
        public function insertar_proveedores(){
            $this->benchmark->mark('start');
                $this->fixture->insertar_proveedores();
            $this->benchmark->mark('end');
            $this->layout->show('fixture/insertar_producto');
        }
        
        public function insertar_factura(){
            $this->benchmark->mark('start');
                $this->fixture->insertar_factura();
            $this->benchmark->mark('end');
            $this->layout->show('fixture/insertar_producto');
        }
        
        /*Query Test*/
        public function comprobar_query()
        {
            $this->benchmark->mark('start');
            //0.3240      $query = "SELECT * FROM facturas f, clientes c WHERE f.id_cliente = c.id_cliente AND f.estado = '0' ORDER BY f.id_factura DESC";
             //0.3199   $query = "SELECT * FROM facturas f Inner Join clientes c On f.id_cliente = c.id_cliente where f.estado = '0' ORDER BY f.id_factura DESC";
                $result = $this->db->query($query);
                
                $count = count($result->result());
            $this->benchmark->mark('end');
            $this->layout->show('fixture/insertar_producto', array('count' => $count));
        }
}?>