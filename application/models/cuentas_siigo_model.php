<?php

class Cuentas_siigo_model extends CI_Model {
    
    public $connection;
    
    public function __construct()
    {
        parent::__construct();
        // Your own constructor code
    }
    
    public function initialize($connection)
    {
        $this->connection = $connection;
    }
    
    public function existeCS($db)
    {
        $sql = "SHOW TABLES WHERE Tables_in_$db = 'cuentas_siigo'";
        $existe = $this->connection->query($sql)->result();
        if(count($existe) != 0)
        {
            return true;
        }  else {
            return false;
        }
    }
    
    public function crearTabla()
    {
        $sql = "CREATE TABLE cuentas_siigo (
	id INT (11),
	nombre VARCHAR (300),
	codigo1 VARCHAR (15),
	codigo2 VARCHAR (15),
	codigo3 VARCHAR (15),
	codigo4 VARCHAR (15),
	codigo5 VARCHAR (15),
	codigo6 VARCHAR (15),
	letra VARCHAR (15)
        );";
        //echo $sql;
        $this->connection->query($sql);
        
        $sql = "INSERT INTO cuentas_siigo VALUES ('1','ventas ','000','41','35','38','00','00','C'),
            ('2','impuestos','000','24','08','05','01','00','C'),
            ('3','descuento','000','41','75','15','00','00','D'),
            ('4','forma de pago','000','11','05','05','00','00','D'),
            ('5','inventario','000','14','35','01','00','00','D')";
        $this->connection->query($sql);
        //echo "<br>".$sql;
        return $this->connection->get('cuentas_siigo');
    }
    
    public function getTipoMovimientoContable(){     
       
       $sqlData = "SELECT * FROM cuentas_siigo where id <>1 and id <>2 and id <>3"; //die();       
       return  $sqlDato = $this->connection->query($sqlData)->result(); //print_r($sqlDato); die();
       // return  $sqlDato->result();
        
    }  
    
    public function modificarCodigo($id,$data)
    {
        $this->connection->where("id",$id);
        $this->connection->update("cuentas_siigo",$data);
    }
}