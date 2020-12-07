<?php

/* 
 * Modelo de franquicias.
 */

class Franquicias_model extends CI_Model{
    
    /* 
     * Constructor
     */
    public function __construct(){

        parent::__construct();		
    }
    
    /* 
     * Total de franquicias existentes.
     */
    public function get_total(){
        
        $query = $this->db->query("SELECT count(*) cantidad FROM franquicias");
        return $query->row()->cantidad;								
    }
    
    /*
     * Obtener todas las franquicias
     */
    public function get_franquicias(){
        
        $sql = "SELECT * FROM franquicias WHERE email_usuario='" . $this->session->userdata('email') . "'";
        
        $data = array();
        
        foreach ($this->db->query($sql)->result() as $value) {
            
            // Obtengo datos del usuario por su email.
            $userdata = $this->get_userdata($value->email_franquicia);
            // Valido que el email exista.
            if($userdata){
                // Agrego elementos al listado.
                $data[] = array(
                    'nombre' => $userdata['nombre'],
                    'email_franquicia' => $value->email_franquicia,
                    'nombre_empresa' => $userdata['nombre_empresa'],
                    'telefono_empresa' => $userdata['telefono_empresa'],
                    'nit' => $userdata['nit'],
                    'id' => $value->id,
                    'id_proveedor' => $value->id_proveedor,
                    'activo' => $value->activo
                );
            }
        }
        return $data;
    }
    
    /* 
     * Obtengo datos para enviar por ajax
     */
    public function get_ajax_data(){
        
        $sql = "SELECT * FROM franquicias WHERE email_usuario='" . $this->session->userdata('email') . "'";
        
        $data = array();
        
        foreach ($this->db->query($sql)->result() as $value) {
            
            // Obtengo datos del usuario por su email.
            $userdata = $this->get_userdata($value->email_franquicia);
            // Valido que el email exista.
            if($userdata){
                // Agrego elementos al listado.
                $data[] = array(
                    $userdata['nombre'],
                    $value->email_franquicia,
                    $userdata['nombre_empresa'],
                    $userdata['telefono_empresa'],
                    $userdata['nit'],
                    $value->id
                );
            }
        }
        
        return array('aaData' => $data);
    }
    
    /*
     * Eliminar franquicia
     * $id : Id de la franquicia a eliminar.
     */
    public function eliminar($id){
        
        $this->db->where('id', $id);
        $this->db->delete("franquicias");
    }
    
    /*
     * Obtener datos de un usuario
     * $email : Email del usuario para obtener sus datos. 
     */
    public function get_userdata($email){
        
        $user = $this->get_user_by_email($email);
        
        if($user){
            
            $user_db_connection = $this->get_user_db_connection($user->db_config_id);
            
            $user_options = $this->get_user_options(array('nombre_empresa', 'telefono_empresa', 'nit'), $user_db_connection);
            
            return array(
                'nombre' => $user->first_name . ' ' . $user->last_name,
                'nombre_empresa' => $user_options['nombre_empresa'],
                'telefono_empresa' => $user_options['telefono_empresa'],
                'nit' => $user_options['nit']
            );
        }
        
        return NULL;
    }
    
    /*
     * Obtener objeto conección para un usuario.
     * $db_config_id : id de configuracion en tabla db_config.
     */
    public function get_user_db_connection($db_config_id){
        
        $sql = "SELECT * FROM db_config WHERE id=$db_config_id";
        
        $result = $this->db->query($sql)->result();
        
        $db_config = count($result) > 0 ? $result[0] : NULL;
        
        if($db_config){
            
            $usuario = $db_config->usuario;
            $clave = $db_config->clave;
            $servidor = $db_config->servidor;
            $base_dato = $db_config->base_dato;
            $dns = "mysql://$usuario:$clave@$servidor/$base_dato";
            
            return $this->load->database($dns, true);
        }
        
        return  NULL;
    }
    
    /*
     * Obtener objeto conección para un usuario por el id de la franquicia.
     * $id_franquicia : Id de la franquicia.
     */
    public function get_user_db_connection_by_id_franquicia($id_franquicia){
        
        // Obtengo objeto franquicia por su id.
        $franquicia = $this->get_franquicia($id_franquicia);
        // Obtengo el usuario franquiciado por su email en la franquicia.
        $usuariofranquicia = $this->get_user_by_email($franquicia->email_franquicia);
        //var_dump($this->get_user_db_connection($usuariofranquicia->db_config_id));die();
        // Retorno el objeto coneccion para ese usuario.
        return  $this->get_user_db_connection($usuariofranquicia->db_config_id);
    }
    
    /*
     * Obtener opciones de un usuario.
     * $options : array() de opciones.
     * $db_connection : objeto coneccion del usuario.
     */
    public function get_user_options($options, $db_connection){
        
        $result = array();
        
        foreach ($options as $o) {
            
            $option = $this->get_user_option($o, $db_connection);
            $result[$o] = $option ? $option->valor_opcion : '';
        }
        
        return $result;
    }
    
    /*
     * Obtener opcion de un usuario.
     * $options : nombre_opcion en tabla opciones.
     * $db_connection : objeto coneccion del usuario.
     */
    public function get_user_option($option, $db_connection){
        
        $sql = "SELECT * FROM opciones WHERE nombre_opcion='" . $option . "'";
        $result = $db_connection->query($sql)->result();
        return count($result) > 0 ? $result[0] : NULL;
    }
    
    /* 
     * Obtener datos de franquisia por id
     */
    public function get_franquicia($id_franquicia){
        
        $sql = "SELECT * FROM franquicias WHERE id=$id_franquicia";
        $result = $this->db->query($sql)->result();
        return count($result) > 0 ? $result[0] : NULL;
    }
    
    /* 
     * Obtener datos de un usuario por su email.
     */
    public function get_user_by_email($email){
        
        $sql = "SELECT * FROM users WHERE email='" . $email . "'";
        $result = $this->db->query($sql)->result();
        return count($result) > 0 ? $result[0] : NULL ;
    }
    
}