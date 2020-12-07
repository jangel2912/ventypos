<?php
Class Webpay_model extends CI_Model
{
    var $connection;

    // Constructor

    public function __construct() {

        parent::__construct();
    }

    public function datosConexion($id)
    {
        return $this->db->get_where('db_config',array('id'=>$id))->row();        
    } 
    
    public function initialize($connection) {

        $this->connection = $connection;
    }
    
    public function nuevoTransaccion($data=false)
    {
        if($data!=false)
        {
            $this->connection->insert('webpay',$data);
        }
    }
    
    public function existeWebpay($db)
    {
        $sql = "SHOW TABLES WHERE Tables_in_$db = 'webpay'";
        $existe = $this->connection->query($sql)->result();
        if(count($existe) == 0)
        {
            $sql = "CREATE TABLE `webpay`(  
                `id` INT NOT NULL AUTO_INCREMENT,
                `transaction_id` VARCHAR(100),
                `transaction_datetime` VARCHAR(50),
                `valor` DOUBLE(10,2),
                `json` VARCHAR(3000),
                `aleatorio` VARCHAR(22),
                `id_pago` INT(11) NOT NULL,
                `estado` TINYINT,
                `venta_id` INT(11) NULL,
                PRIMARY KEY (`id`)
              );";
              
            $this->connection->query($sql);
        }
    }
    
    public function ultimoId()
    {
        $id = $this->connection->select('id')
            ->order_by('id','Desc')
            ->limit(1)
            ->get('webpay')->row();
        if(count($id)== 0)
        {
            return 0;
        }
        
        return $id->id;
    }
    
    public function getPendiente($aleatorio)
    {
        $result = $this->connection->get_where('webpay',array('estado'=>2,'aleatorio'=>$aleatorio))->row();
        if(count($result)==0)
        {    
            return 0;
        }
        return $result->id;
    }
    
    public function updateEstado($id,$estado)
    {
        $this->connection->where('id',$id)
            ->update('webpay',array('estado'=>$estado));
    }
    
    public function asignarFactura($aleatorio,$venta)
    {
        $this->connection->where('aleatorio',$aleatorio)
                ->where('estado',1)
                ->update('webpay',array('id_pago'=>$venta));
    }
}