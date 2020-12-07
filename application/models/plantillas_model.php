    <?php

class Plantillas_model extends CI_Model{
    
    public function __construct() {
        parent::__construct();
    }
    
    public function getAll($attr = false)
    {
        if($attr)
        {
            $data = $this->db->get('plantillas');
            return $data->result();
        }
        
        $data = $this->db->get_where('plantillas',array('producto_atributo' => 0));
        return $data->result();
    }
    
    function get_by_tipo_negocio($tipo_negocio){
        $data = $this->db->get_where('plantillas',array('tipo_negocio' => $tipo_negocio));
        return $data->result();
    }
}

