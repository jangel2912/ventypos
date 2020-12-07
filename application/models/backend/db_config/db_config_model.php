<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Db_config
 *
 * @author Locho
 */
class Db_config_model extends CI_Model {
    //put your code here
    public function __construct()
    {
        parent::__construct();
        // Your own constructor code
    }
    
    public function get_all()
    {
         $query = $this->db->query("select * from db_config");
         return $query->result();
    }
    
    public function add($data)
    {   
        //date_default_timezone_set($timezone_identifier);
        $this->db->insert('db_config', $data); 
        return $this->db->insert_id();
    }
    
    public function delete($id){
        $this->db->where('id', $id);
        $this->db->delete("db_config");	
    }
    
     public function get_active_db($id){
         $query = $this->db->query("select id from db_config order by DESC");
         return $query->row()->id;	
    }
    
    public function get_combo_array()
    {
        $data = array();
        $query = "select id, CONCAT(servidor, '-', usuario) as server from db_config";
        foreach ($this->db->query($query)->result() as $value) {
            $data[$value->id] = $value->server;
        }
        
        return $data;
        
    }
}
?>