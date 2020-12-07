<?php
class Nota_credito_model extends CI_Model
{
    var $connection;
    // Constructor
    public function __construct()
    {
        parent::__construct();
    }

    public function initialize($connection)
    {
        $this->connection = $connection;
    }

      // MARK: OBTENER NOTAS DE CREDITOS 
      public function show($id = '', $where = [])
      {
          $this->connection->where($where);
  
          if (is_numeric($id)) {
  
              $this->connection->where('notacredito.id', $id);
  
              $venta = $this->connection->get('notacredito')->row();
  
  
  
              return $venta;
  
          }
  
          $this->connection->order_by('consecutivo', 'DESC');
  
          return $this->connection->get('notacredito')->result_array();
      }
  
      public function nuevo($data) {
  
          $dato['fecha'] = date('Y-m-d H:i:s');
          $dato['consecutivo'] = $data['consecutivo'];
          $dato['usuario_id'] = $this->session->userdata('user_id');
          $dato['tipoNota'] = 'NC';
          $dato['valor'] = $data['valor'];
          $dato['factura_id'] = $data['venta_id'];
          $dato['cliente_id'] = $data['cliente_id'];
          $dato['estado'] = 1;
          $dato['nota'] = $data['nota'];
          $dato['electronic_invoice'] = 1;
  
  
          $this->connection->insert('notacredito', $dato);
          $insert_id = $this->connection->insert_id();
  
          return $insert_id;
  
      }
    
    public function existeNotaCredito($db)
    {
        $sql = "SHOW TABLES WHERE Tables_in_$db = 'notacredito'";
        $existe = $this->connection->query($sql)->result();
        if(count($existe) == 0)
        {
            $sql = "CREATE TABLE `notacredito` (
                        `id` int(11) NOT NULL AUTO_INCREMENT,
                        `consecutivo` varchar(20) DEFAULT NULL,
                        `usuario_id` int(11) DEFAULT NULL,
                        `tipoNota` varchar(2) DEFAULT NULL,
                        `valor` float DEFAULT NULL,
                        `fecha` datetime DEFAULT NULL,
                        `devolucion_id` int(11) DEFAULT NULL,
                        `factura_id` int(11) DEFAULT NULL,
                        `notaForeign_id` INT(11) DEFAULT NULL,
                        `movimiento_id` int(11) DEFAULT NULL,
                        `cliente_id` int(11) DEFAULT NULL,
                        `estado` INT(1) DEFAULT NULL,
                        PRIMARY KEY (`id`)
            )";
            $this->connection->query($sql);
        }
    }
    
    public function add($data)
    {
        return $this->connection->insert("notacredito",$data);
    }
    
    public function estadoNotaCredito($codigo = "")
    {
        $nota = $this->connection->get_where("notacredito",array("consecutivo"=>$codigo));
        //var_dump($nota->row()->estado);
        if(count($nota->result()) == 0)
        {
            return array("estado"=>"empty","nombre"=>"","valor"=>"");
        }else if($nota->row()->estado != 1)
        {
            return array("estado" => "cancelado", "nombre" => "", "valor" => "");
        }else if($nota->row()->estado == 1)
        {
            $ci = &get_instance();
            $ci->load->model("opciones_model");
            $decimales_moneda=get_option('decimales_moneda');
            $factura = $this->connection->get_where("venta",array('id'=>$nota->row()->factura_id));
            return array("estado" => "pagado", "nombre" => $factura->row()->factura, "valor" => strval(number_format(floatval($nota->row()->valor), intval($decimales_moneda), ".", "")));
        }
    }
    
    //Nota Credito con las que se pago una factura
    public function cancelarNotaCredito( $listaNotaCredito ){        
        
        foreach ($listaNotaCredito as $val){
            $notaCredito = $this->connection->get_where("notacredito",array("consecutivo"=>$val))->row();
            
            $arragloDebito = array(
                "consecutivo"=>"--",
                "usuario_id"=>$this->session->userdata('user_id'),
                "tipoNota" => "ND",
                "valor" => (int)$notaCredito->valor,
                "fecha" => date("Y-m-d H:i:s"),
                "devolucion_id" => $notaCredito->devolucion_id,
                "factura_id" => -1,
                "movimiento_id" => -1,
                "cliente_id" => -1,
                "estado" => 1,
                "notaForeign_id" => $notaCredito->id,
            );
            
            $this->connection->insert("notacredito",$arragloDebito);
            $id = $this->connection->insert_id();
            
            $data = array(
                'estado' => 0,
                "notaForeign_id" => $id,
            );
            $this->connection->where('consecutivo', $val);
            $this->connection->update('notacredito', $data);
            
        }
    }
    
    public function get_nota_credito_devolucionId($id)
    {
        return $this->connection->get_where("notacredito",array("devolucion_id"=>$id,"tipoNota"=>"NC"))->row();
    }

    public function crearCamposElectronicInvoice() {
        $sql = "SHOW COLUMNS FROM notacredito LIKE 'electronic_invoice'";
        $existeCampo = $this->connection->query($sql)->result();
        if(count($existeCampo) == 0){
            $sql = "ALTER TABLE notacredito ADD COLUMN electronic_invoice INT(2) NULL";
            $this->connection->query($sql);
            $sql = "ALTER TABLE notacredito ADD COLUMN nota TEXT";
            $this->connection->query($sql);
            $sql = "ALTER TABLE notacredito ADD COLUMN id_transaccion varchar(100)";
            $this->connection->query($sql);
        }
    }
}
    