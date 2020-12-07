<?php

class Usuarios_model  extends CI_Model{

    
    public $connection;
    
	// Constructor

	public function __construct()

	{

		parent::__construct();		

	}

    public function initialize($connection)
    {
        $this->connection = $connection;
    }   

	public function get_total()

	{

		$query = $this->db->query("SELECT count(*) as cantidad FROM users");

		return $query->row()->cantidad;								

    }
    
    public function get_users_almacen($id_almacen)
	{
		$query = $this->connection->query("SELECT COUNT(*) as cantidad FROM usuario_almacen ua INNER JOIN vendty2.users u ON u.id=ua.usuario_id WHERE ua.almacen_id=$id_almacen and active=1");
		return $query->row()->cantidad;		
    }
            

        public function get_ajax_data(){
		
        $db_config_id = $this->session->userdata('db_config_id');
        $sql = "SELECT username, email, phone, rol_id, is_admin, id, created_on, active FROM users WHERE db_config_id = $db_config_id ORDER BY created_on DESC"; 
        
        $data = array();
        foreach ($this->db->query($sql)->result() as $value) {
          $nombre_rol = '';
        $sql1 = "SELECT nombre_rol FROM rol WHERE id_rol = '$value->rol_id'"; 
        foreach ($this->connection->query($sql1)->result() as $value1) {
		      $nombre_rol = $value1->nombre_rol;
        }		
		
            $data[] = array(
                $value->username,
                $value->email,
                $value->phone,
                $nombre_rol,
                $value->active,
                $value->is_admin,
                $value->id,
                $value->created_on
            );
        }
        return array(
            'aaData' => $data
        );
		
        }

        

        public function get_total_active(){

            $query = $this->db->query("SELECT count(*) as cantidad FROM users where active = 1");

            return $query->row()->cantidad;		

        }

        

        public function get_total_deactive(){

            $query = $this->db->query("SELECT count(*) as cantidad FROM users where active = 0");

            return $query->row()->cantidad;		

        }



        public function get_total_tenant($db_config_id)

	{

		$query = $this->db->query("SELECT count(*) as cantidad FROM users where db_config_id = $db_config_id");

		return $query->row()->cantidad;

	}

	

	public function get_all($offset)

	{

		$db_config_id = $this->session->userdata('db_config_id');

		$query = $this->db->query("SELECT * FROM users where db_config_id = $db_config_id ORDER BY username DESC limit $offset, 8");

		return $query->result();

	}

        

        public function eliminar($id){

            $db_config_id = $this->session->userdata('db_config_id');
            $this->db->where('id', $id);
            $this->db->where('db_config_id', $db_config_id);
            $this->db->delete('users');

            //eliminar de usuario_almacen
            $this->connection->where('usuario_id', $id);
            $this->connection->delete('usuario_almacen');

        }

        public function getId($id)
        {
            $query = $this->db->get_where("users",array('id'=>$id));
            return $query->row(); 
        }
        
        public function getAlmacenDefecto($id)
        {
            $user = $this->getId($id);
            if(count($user) != 0)
            {
                $conf = $this->db->get_where('db_config',array('id'=>$user->db_config_id))->row();
                if(count($conf) != 0)
                {
                    return $conf->almacen;
                }
            }
            
            return false;
        }
        
        public function validaEmail( $email ){
            $db_config_id = $this->session->userdata('db_config_id');
            $query = $this->db->query("SELECT * 
                                         FROM users 
                                        WHERE db_config_id = {$db_config_id}
                                          AND email = '{$email}'");
             return (int)$query->num_rows();           
        }

        public function get_id_config_email($email)
        {
            $query = $this->db->get_where("users",array('email'=>$email));
            return $query->row(); 
        }

        public function get_id_user_admin($limit,$where)
        {
            if(!empty($limit)){
                $query = $this->db->limit($limit);
            }

            $this->db->select('*');                       
            $this->db->where($where);
            $query=$this->db->get('users')->result_array();            
            return $query; 
        }

}



?>