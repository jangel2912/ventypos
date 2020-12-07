<?php
class Envio_tienda_model extends CI_Model
{
    var $connection;
    // Constructor

    public function __construct(){
        parent::__construct();
    }    

    public function initialize($connection) {
        $this->connection = $connection;
    }
    
    public function existeTabla($db)
    {
        $sql = "SHOW TABLES WHERE Tables_in_$db = 'envio_tienda'";
        $existe = $this->connection->query($sql)->result();
        if(count($existe) == 0)
        {
            $sql = "CREATE TABLE `envio_tienda`(  
                    `id` INT(11) NOT NULL AUTO_INCREMENT,
                    `nombre` VARCHAR(255),
                    `valor` INT(20),
                    `activo` SMALLINT(1) DEFAULT 1,
                    PRIMARY KEY (`id`)
                    );
                ";
            $this->connection->query($sql);
        }
    }
    
    public function getAll()
    {
        return $this->connection->get("envio_tienda")->result();
    }
    
    public function insertar($data = false)
    {
        if($data)
        {
            $this->connection->insert('envio_tienda',$data);
        }
    }
    
    public function modificar($data = false)
    {
        if($data)
        {
            $this->connection->where('id',$data['id'])->update('envio_tienda',$data);
        }
    }
    
    public function eliminar($id = false)
    {
        if($id)
        {
            $this->connection->where('id',$id)->delete('envio_tienda');
            return 1;
        }
        return 0;
    }
            
}