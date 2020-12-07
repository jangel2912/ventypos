<?php
class Usuario_almacen_model extends CI_Model {

    var $connection;

    public function __construct() {

        parent::__construct();
    }

    public function initialize($connection) {

        $this->connection = $connection;
    }
    
    public function editarUsuario($data =false,$is_admin,$almacenDefecto)
    {
        if($data != false)
        {

            $existeAlmacen = $this->connection->get_where('almacen',array('id'=>$almacenDefecto),'');
            //if($existeAlmacen->num_rows() > 0){
                $usuarioAlmacen = $this->connection->get_where('usuario_almacen',array('usuario_id'=>$data['usuario_id']))->row();
                if(count($usuarioAlmacen) != 0 && $data['almacen_id'] != -1)
                {
                    $this->connection->where('usuario_id',$data['usuario_id'])
                            ->update('usuario_almacen',$data);
                }else if(count($usuarioAlmacen) == 0 && $data['almacen_id'] != -1)
                {
                    $this->connection->insert('usuario_almacen',$data);
                }
                
                if($almacenDefecto != false && count($usuarioAlmacen) != 0 && $data['almacen_id'] == -1)
                {
                    $data['almacen_id'] = $almacenDefecto;
                    $this->connection->where('usuario_id',$data['usuario_id'])
                            ->update('usuario_almacen',$data);
                }else if($almacenDefecto != false && count($usuarioAlmacen) == 0 && $data['almacen_id'] == -1)
                {
                    $data['almacen_id'] = $almacenDefecto;
                    $this->connection->insert('usuario_almacen',$data);
                }
           // }
        }
    }

}