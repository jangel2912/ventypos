<?php
class Epayco_model extends CI_Model
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
        $sql = "SHOW TABLES WHERE Tables_in_$db = 'epayco'";
        $existe = $this->connection->query($sql)->result();
        if(count($existe) == 0)
        {
            $sql = "CREATE TABLE `epayco`(  
                        `id` INT(11) NOT NULL AUTO_INCREMENT,
                        `refPayco` VARCHAR(255),
                        `idFacturaPayco` VARCHAR(255),
                        `amount` INT(20),
                        `currencyCode` VARCHAR(20),
                        `bankName` VARCHAR(50),
                        `cardnumber` VARCHAR(50),
                        `quotas` INT(3),
                        `respuesta` VARCHAR(20),
                        `fechaTransaccion` DATETIME,
                        `tipoDocumento` VARCHAR(20) COMMENT 'cliente que compra',
                        `documento` VARCHAR(20) COMMENT 'cliente que compra',
                        `nombre` VARCHAR(50) COMMENT 'cliente que compra',
                        `apellido` VARCHAR(50) COMMENT 'cliente que compra',
                        `paisCodigo` VARCHAR(20) COMMENT 'cliente que compra',
                        `ciudad` VARCHAR(20) COMMENT 'cliente que compra',
                        `direccion` VARCHAR(50) COMMENT 'cliente que compra',
                        `ip` VARCHAR(20) COMMENT 'cliente que compra',
                        `signature` VARCHAR(255),
                        `idFacturaOnline` INT(11),
                        `aleatorio` VARCHAR(25) COMMENT 'codigo para buscar transacionantes de realizar la venta',
                        `activo` TINYINT(1) DEFAULT 0 COMMENT 'estado para busqueda de aleatorio',
                        PRIMARY KEY (`id`)
                    );
                ";
            $this->connection->query($sql);
        }
    }
}