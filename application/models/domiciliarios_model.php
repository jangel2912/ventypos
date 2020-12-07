<?php


class Domiciliarios_model extends CI_Model
{
	var $connection; 
    public function __construct()
    {
        parent::__construct();
    }

    public function initialize($connection){
        $this->connection = $connection;
    } 
   
    public function get_domiciliarios(){
        $this->connection->select("*");
        $this->connection->from("domiciliarios");
        $result = $this->connection->get()->result_array();       
        return $result;
    }

    //Jeisson Rodriguez (17/07/2019)
    public function get_domiciliarios_activos(){
        $this->connection->select("*");
        $this->connection->from("domiciliarios");
        $this->connection->where('activo', '1');
        $result = $this->connection->get()->result_array();       
        return $result;
    }
    //fin


    public function get_domiciliario($where){
        
        $this->connection->where($where);
        $this->connection->select("*");
        $this->connection->from("domiciliarios");
        $result = $this->connection->get()->result_array();       
        return $result;
    }  

    public function get_tipo_domiciliario(){
        $this->connection->select("*");
        $this->connection->from("tipo_domiciliarios");
        $result = $this->connection->get()->result_array();       
        return $result;
    }       
    
    public function add($data){        
        $this->connection->insert("domiciliarios",$data);
        $id=$this->connection->insert_id();               
        return $id;
    }   
    
    public function add_domicilio($data){        
        $this->connection->insert("domicilio",$data);
        $id=$this->connection->insert_id();               
        return $id;
    }   

    public function update($data,$where){      
        $this->connection->where($where);
        $this->connection->update("domiciliarios",$data);
        //id=$this->connection->last_query();               
        //return $id;
    }   
           
    function crear_domiciliarios(){    
        $db=$this->session->userdata('base_dato');   
        //tipo domiciliarios
        $sql = "SHOW TABLES WHERE Tables_in_$db = 'tipo_domiciliarios'";      
        $existe = $this->connection->query($sql)->result();
        if(count($existe) == 0)
        {
            $sql = "CREATE TABLE `tipo_domiciliarios` (
                    `id` int(11) NOT NULL AUTO_INCREMENT,                    
                    `descripcion` VARCHAR(250) NOT NULL,
                    PRIMARY KEY (`id`)
                )";
            $this->connection->query($sql);
            $sql= "INSERT INTO `tipo_domiciliarios` (`descripcion`) VALUES('Empresa');";
            $this->connection->query($sql);
            $sql= "INSERT INTO `tipo_domiciliarios` (`descripcion`) VALUES('Persona');";
            $this->connection->query($sql);
           
        }else{
            $sql = "select * from tipo_domiciliarios";      
            $existe = $this->connection->query($sql)->result();
            if(count($existe) == 0){
                $sql= "INSERT INTO `tipo_domiciliarios` (`descripcion`) VALUES('Empresa');";
                $this->connection->query($sql);
                $sql= "INSERT INTO `tipo_domiciliarios` (`descripcion`) VALUES('Persona');";
                $this->connection->query($sql);
            }
        }
        //domiciliarios
        $sql = "SHOW TABLES WHERE Tables_in_$db = 'domiciliarios'";      
        $existe = $this->connection->query($sql)->result();
        if(count($existe) == 0)
        {            
            $sql = "CREATE TABLE `domiciliarios` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `tipo` int(11) NOT NULL COMMENT '1 para Empresa 2 para persona',
                `descripcion` varchar(250) NOT NULL,
                `telefono` varchar(15) DEFAULT NULL,
                `direccion` varchar(500) DEFAULT NULL,
                `logo` TEXT DEFAULT NULL,
                `comision` DOUBLE NOT NULL DEFAULT 0,
                `activo` boolean NOT NULL DEFAULT 1,
                PRIMARY KEY (`id`),
                KEY `domiciliario` (`tipo`),
                CONSTRAINT `domiciliario` FOREIGN KEY (`tipo`) REFERENCES `tipo_domiciliarios` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
                )";
            $this->connection->query($sql);
            //agregar los 3 por defecto
           
            $sql= "INSERT INTO `domiciliarios` (tipo,descripcion,logo) VALUES('1','Rappi','rappi_logo.png');";
            $this->connection->query($sql);
            $sql= "INSERT INTO `domiciliarios` (tipo,descripcion,logo) VALUES('1','Domicilios.com','domicilios_logo.png');";
            $this->connection->query($sql);
            $sql= "INSERT INTO `domiciliarios` (tipo,descripcion,logo) VALUES('1','Uber Eats','uber_eats_logo.png');";
            $this->connection->query($sql);
            //crear carpeta de imagenes domiciliarios
            $carpeta = './uploads/'.$db.'/domiciliarios/';
            $desde   = './uploads/iconos/domiciliarios/';            
            if (!file_exists($carpeta)) {
                mkdir($carpeta, 0777, true);
                //copiar las imagenes
                
                if (copy($desde."rappi_logo.png", $carpeta."rappi_logo.png")) {                    
                }
                if (copy($desde."domicilios_logo.png", $carpeta."domicilios_logo.png")) {                    
                }
                if (copy($desde."uber_eats_logo.png", $carpeta."uber_eats_logo.png")) {                    
                }
            } 
           
        }else{
            $sql = "select * from domiciliarios";      
            $existe = $this->connection->query($sql)->result();
            if(count($existe) == 0){
                $sql= "INSERT INTO `domiciliarios` (tipo,descripcion,logo) VALUES('1','Rappi','rappi_logo.png');";
                $this->connection->query($sql);
                $sql= "INSERT INTO `domiciliarios` (tipo,descripcion,logo) VALUES('1','Domicilios.com','domicilios_logo.png');";
                $this->connection->query($sql);
                $sql= "INSERT INTO `domiciliarios` (tipo,descripcion,logo) VALUES('1','Uber Eats','uber_eats_logo.png');";
                $this->connection->query($sql);
                //crear carpeta de imagenes domiciliarios
                $carpeta = './uploads/'.$db.'/domiciliarios/';
                $desde   = './uploads/iconos/domiciliarios/';            
                if (!file_exists($carpeta)) {
                    mkdir($carpeta, 0777, true);
                    //copiar las imagenes                    
                    if (copy($desde."rappi_logo.png", $carpeta."rappi_logo.png")) {                    
                    }
                    if (copy($desde."domicilios_logo.png", $carpeta."domicilios_logo.png")) {                    
                    }
                    if (copy($desde."uber_eats_logo.png", $carpeta."uber_eats_logo.png")) {                    
                    }

                } 
            }
        }
        //domicilio
        $sql = "SHOW TABLES WHERE Tables_in_$db = 'domicilio'";      
        $existe = $this->connection->query($sql)->result();
        if(count($existe) == 0)
        { 
            $sql = "CREATE TABLE `domicilio` (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `domiciliario` INT(11) NOT NULL,
                `factura` INT(11) NOT NULL,
                `nombre` VARCHAR(250) DEFAULT NULL,
                `telefono` VARCHAR(30) DEFAULT NULL,
                `direccion` VARCHAR(500) DEFAULT NULL,
                `hora_inicio` TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
                `hora_fin` TIMESTAMP DEFAULT '0000-00-00 00:00:00',
                `estado` BOOLEAN DEFAULT 1,
                PRIMARY KEY (`id`),
                KEY `iddomiciliario` (`domiciliario`),
                CONSTRAINT `iddomiciliario` FOREIGN KEY (`domiciliario`) REFERENCES `domiciliarios` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
                KEY `idfactura` (`factura`),
                CONSTRAINT `idfactura` FOREIGN KEY (`factura`) REFERENCES `venta` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
                )";
            $this->connection->query($sql);
        }

    }

    public function desactivar($where,$data){
        $this->connection->where($where);
        $this->connection->update("domiciliarios",$data);
    }
}

?>