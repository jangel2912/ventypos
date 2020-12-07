<?php

class Opciones_model extends CI_Model {

    var $connection;

    // Constructor
    public function __construct() {
        parent::__construct();
    }

    // inicializacion
    public function initialize($connection) {
        $this->connection = $connection;
    }

    public function getOpcion($opcion = false) {
        if ($opcion) {
            $valor= get_option($opcion);
            /*$data = $this->connection->get_where('opciones', array('nombre_opcion' => "$opcion"))->row_array();
            if (empty($data)) {
                $insert = array(
                    'nombre_opcion' => "$opcion",
                    'valor_opcion' => ''
                );
                $this->setNew($insert);
                return "";*/
            }

            //return $data['valor_opcion'];
            return $valor;
        
    }

    public function setNew($data = false) {
        if ($data) {
            $this->connection->insert('opciones', $data);
        }
    }

    public function getDataMoneda() {
        $sql ="
           select 
              (select if(o.valor_opcion is null or o.valor_opcion ='', '0', o.valor_opcion ) from opciones o where nombre_opcion='decimales_moneda' limit 1)  decimales,
              (select if(o.valor_opcion is null or o.valor_opcion ='', 'COP', o.valor_opcion ) from opciones o where nombre_opcion='tipo_moneda' limit 1)  tipo_moneda,
              (select if(o.valor_opcion is null or o.valor_opcion ='', ' ', o.valor_opcion ) from opciones o where nombre_opcion='tipo_separador_miles' limit 1)  tipo_separador_miles,
              (select if(o.valor_opcion is null or o.valor_opcion ='', '.', o.valor_opcion )from opciones o where nombre_opcion='tipo_separador_decimales' limit 1)  tipo_separador_decimales,
              (select if(o.valor_opcion is null or o.valor_opcion ='', '$', o.valor_opcion )from opciones o where nombre_opcion='simbolo' limit 1)  simbolo,
              (select if(o.valor_opcion is null or o.valor_opcion ='', '0', o.valor_opcion )from opciones o where nombre_opcion='redondear_precios' limit 1)  redondear
             from opciones
            limit 1";
        $rest = $this->dbConnection->query($sql)->row();
        $rest1 = $this->dbConnection->query($sql)->row_array();
        
        if($rest1['decimales'] == null)
        {
            $sql1 = "INSERT INTO opciones (`nombre_opcion`,`valor_opcion`)VALUES ('decimales_moneda','0')";
            $this->dbConnection->query($sql1);
            $rest = $this->dbConnection->query($sql)->row();
        }
        if($rest1['tipo_moneda'] == null)
        {
            $sql1 = "INSERT INTO opciones (`nombre_opcion`,`valor_opcion`)VALUES ('tipo_moneda','COP')";
            $this->dbConnection->query($sql1);
            $rest = $this->dbConnection->query($sql)->row();
        }
        if($rest1['tipo_separador_miles'] == null)
        {
            $sql1 = "INSERT INTO opciones (`nombre_opcion`,`valor_opcion`)VALUES ('tipo_separador_miles',' ')";
            $this->dbConnection->query($sql1);
            $rest = $this->dbConnection->query($sql)->row();
        }
        if($rest1['tipo_separador_decimales'] == null)
        {
            $sql1 = "INSERT INTO opciones (`nombre_opcion`,`valor_opcion`)VALUES ('tipo_separador_decimales','.')";
            $this->dbConnection->query($sql1);
            $rest = $this->dbConnection->query($sql)->row();
        }
        if($rest1['simbolo'] == null)
        {
            $sql1 = "INSERT INTO opciones (`nombre_opcion`,`valor_opcion`)VALUES ('simbolo','$')";
            $this->dbConnection->query($sql1);
            $rest = $this->dbConnection->query($sql)->row();
        }
        if($rest1['redondear'] == null)
        {
            $sql1 = "INSERT INTO opciones (`nombre_opcion`,`valor_opcion`)VALUES ('redondear_precios','0')";
            $this->dbConnection->query($sql1);
            $rest = $this->dbConnection->query($sql)->row();
        }

        return $rest;
    }

    public function formatoMonedaMostrar($numero) {
        $dataDecimales = $this->getDataMoneda();       //var_dump($dataDecimales);
        $numero = number_format($numero, $dataDecimales->decimales, $dataDecimales->tipo_separador_decimales, $dataDecimales->tipo_separador_miles);
         return $numero;
    }
    
    public function setValue($precio)
    {
        $moneda = $this->getDataMoneda(); 
        if($moneda->tipo_separador_miles != $moneda->tipo_separador_decimales)
        {
            $precio = number_format($precio,$moneda->decimales,'.','');
        }else
        {
            $arrayPrecio = explode($moneda->tipo_separador_miles,$precio);
            $precioC = "";

            $ultimo =  $arrayPrecio[count($arrayPrecio)-1];
            $ultimo = explode("",$ultimo);

            if((count($ultimo)+1) == $moneda->decimales)
            {
                foreach($arrayPrecio as $key=>$p)
                {
                    if($key == (count($arrayPrecio)-1))
                    {
                        $precioC .= ".".$p;
                    }else
                    {
                        $precioC .= $p;
                    }
                }
                $precio = (float)$precioC;
            }else
            {
                $precio = number_format($precio,$moneda->decimales,'.','');
            }
        }
        return $precio;
    }
    
    public function redondear($num)
    {
        $moneda = $this->getDataMoneda(); 
        if($moneda->redondear && $moneda->decimales == 0)
        {
            $num = (int)$num;
            $numero = (string)$num;
            $decena = substr($numero, -2);
            $num = round($num / 100) * 100;
            if((int)$decena <= 50 && (int)$decena >= 1)
            {
                $num = substr($numero,0,- 2).'50';
                $num = (int)$num;
            }
        }
        
        return $num;
    }
    
    //limpiar data excel
    public function limpiarCampo($precio)
    {
        $moneda = $this->getDataMoneda(); 
        if($moneda->tipo_separador_miles != $moneda->tipo_separador_decimales)
        {
            $precio = number_format($precio,$moneda->decimales,',','');
        }else
        {
            $arrayPrecio = explode($moneda->tipo_separador_miles,$precio);
            $precioC = "";

            $ultimo =  $arrayPrecio[count($arrayPrecio)-1];
            $ultimo = explode("",$ultimo);

            if((count($ultimo)+1) == $moneda->decimales)
            {
                foreach($arrayPrecio as $key=>$p)
                {
                    if($key == (count($arrayPrecio)-1))
                    {
                        $precioC .= ".".$p;
                    }else
                    {
                        $precioC .= $p;
                    }
                }
                $precio = (float)$precioC;
            }else
            {
                $precio = number_format($precio,$moneda->decimales,',','');
            }
        }
        return $precio;
    }
    
    public function getNombre($nombre=false)
    {
        $data = array();
        if($nombre)
        {
            $data = $this->connection->get_where('opciones', array('nombre_opcion' => "$nombre"))->row_array();
        }
        
        return $data;
    }
    
    public function deleteName($nombre=false)
    {
        if($nombre)
        {
            $query = "DELETE FROM `opciones` WHERE `nombre_opcion` = '$nombre';";
            $this->connection->query($query);
        }
    }
    
    public function editForName($nombre=false,$valor = "")
    {
        if($nombre && $valor)
        {
            $this->connection->where("nombre_opcion",$nombre)->update("opciones",array("valor_opcion"=>$valor));
        }
    }

    public function update($option,$value){
        $this->getOpcion($option);
        $data = array(
            "valor_opcion" => $value
        );
        $this->connection->where("nombre_opcion",$option);
        $this->connection->update("opciones",$data);
        
    }
}
