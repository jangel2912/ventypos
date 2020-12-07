<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of paypal
 *
 * @author Locho
 */
class Paypal extends CI_Controller {
    //put your code here
    public function __construct()
    {
        parent::__construct();
        // Your own constructor code
    }
    
        public function paypal_pay($id, $id_facura){
             $this->db->where('users.id', $id);
             $this->db->select('servidor, base_dato, usuario, clave');
             $this->db->from('users');
             $this->db->join('db_config', 'db_config.id=users.db_config_id');
             $result = $this->db->get();
             $this->db->limit(1);
             if($result->num_rows() > 0){
                
                $usuario = $result->row()->usuario;
                $clave = $result->row()->clave;
                $servidor = $result->row()->servidor;
                $base_dato = $result->row()->base_dato;

                $dns = "mysql://$usuario:$clave@$servidor/$base_dato";
                $dbConnection = $this->load->database($dns, true);

                $this->load->model("facturas_model",'facturas');
                $this->facturas->initialize($dbConnection);
                
                $this->load->model("miempresa_model",'miempresa');
                $this->miempresa->initialize($dbConnection);
                $datos_empresa = $this->miempresa->get_data_empresa();

                $data    = $this->facturas->get_by_id($id_facura);
                
                $data['user_id'] = $id;
                $data['data'] = $data;
                $data['datos_empresa'] = $datos_empresa;
                
                $this->load->view('paypal/paypal_pay', array('data' => $data));
             }
             else{
                 $this->session->set_flashdata('message', "Usted no tiene acceso al recurso solicitado");
                 redirect('frontend/acceso_limitado');
             }
        }
        
        public function success(){
            $data = array();
            $data['code'] = $this->input->post('item_number');
            $data['email'] = $this->input->post('payer_email');
            $data['first_name'] = $this->input->post('first_name');
            $data['payment_status'] = $this->input->post('payment_status');
            $this->layout->template('login')->show('paypal/success',array('data' => $data));
        }
        
        public function notify(){
            $data = array();
            $data['code'] = $this->input->post('item_number');
            $data['email'] = $this->input->post('payer_email');
            $data['first_name'] = $this->input->post('first_name');
            $data['payment_status'] = $this->input->post('payment_status');
            
            $id = $this->input->post('custom');
            
            
            if($data['payment_status'] == "Completed"){
                $this->db->where('users.id', $id);
                $this->db->select('servidor, base_dato, usuario, clave');
                $this->db->from('users');
                $this->db->join('db_config', 'db_config.id=users.db_config_id');
                $result = $this->db->get();
                $this->db->limit(1);
                if($result->num_rows() > 0){
                    $usuario = $result->row()->usuario;
                    $clave = $result->row()->clave;
                    $servidor = $result->row()->servidor;
                    $base_dato = $result->row()->base_dato;

                    $dns = "mysql://$usuario:$clave@$servidor/$base_dato";
                    $dbConnection = $this->load->database($dns, true);

                    $this->load->model("facturas_model",'facturas');
                    $this->facturas->initialize($dbConnection);

                    $this->facturas->paypal_update($data['code'], 1);
                }
            }
            $this->layout->template('login')->show('paypal/success',array('data' => $data));
        }
        
        public function cancel(){
            $this->layout->template('login')->show('paypal/cancel');
        }
    
}
?>