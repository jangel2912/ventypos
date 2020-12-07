<?php

class forma_pago_model extends CI_Model{
    
    var $connection;

    public function __construct() {

        parent::__construct();

    }

    public function initialize($connection){

        $this->connection = $connection;

    }
    
    //actualizar tabla en el caso de que no lo este
    public function actualizarTabla($pagos)
    {
        $sql = "SHOW COLUMNS FROM forma_pago LIKE 'eliminar'";
        $actualizada = $this->connection->query($sql)->result();
        
        if(count($actualizada) == 0)
        {
            //echo 1;
            $sql = "ALTER TABLE `forma_pago`   
                CHANGE `codigo` `codigo` VARCHAR(254) CHARSET latin1 COLLATE latin1_swedish_ci NULL,
                ADD COLUMN `eliminar` TINYINT(1) DEFAULT 1  NULL AFTER `activo`,
                ADD COLUMN `tipo` VARCHAR(254) NULL AFTER `eliminar`;
            ";
            $this->connection->query($sql);
            $this->connection->query("DELETE FROM forma_pago where 1");
            foreach($pagos as $key => $p)
            {
                switch ($key)
                {
                    case "tarjeta_credito" :
                    case "tarjeta_debito" :
                    case "Visa_crédito" :
                    case "Visa_débito" :
                    case "MasterCard_débito" :
                    case "MasterCard Crédito" :
                    case "American_Express" :
                    case "Tarjeta_Codensa":
                    case "Maestro_Debito":
                    case "Tarjeta_Codensa":
                    case "Diners_Club":
                        $tipo = "Datafono";
                        break;
                    default:
                        $tipo = "";
                }
                
                $data = array(
                    "codigo"=>$key,
                    "nombre"=>$p,
                    "activo"=>1,
                    "eliminar"=>0,
                    "tipo"=>$tipo
                );
                $this->connection->insert("forma_pago",$data);
            }
        }
    }
    
    public function get_ajax_data()
    {
        $is_admin = $this->session->userdata('is_admin');
        $username = $this->session->userdata('username');
        $db_config_id = $this->session->userdata('db_config_id');
                    
                 
        $aColumns = array('nombre', 'tipo', 'activo', 'id');

        $sIndexColumn = "id";

        $sTable = "forma_pago";

        $sLimit = "";

        if ( isset( $_GET['iDisplayStart'] ) && $_GET['iDisplayLength'] != '-1' ){
            $sLimit = "LIMIT ".intval( $_GET['iDisplayStart'] ).", ".
            intval( $_GET['iDisplayLength'] );
        }

        $sOrder = "";

        if ( isset( $_GET['iSortCol_0'] ) )
        {
            $sOrder = "ORDER BY  ";
            for ( $i=0 ; $i<intval( $_GET['iSortingCols'] ) ; $i++ )
            {
                if ( $_GET[ 'bSortable_'.intval($_GET['iSortCol_'.$i]) ] == "true" )
                {
                    $sOrder .= $aColumns[ intval( $_GET['iSortCol_'.$i] ) ].' '.($_GET['sSortDir_'.$i]==='asc' ? 'asc' : 'desc') .", ";
                }
            }
            $sOrder = substr_replace( $sOrder, "", -2 );
            if ( $sOrder == "ORDER BY" )
            {
                $sOrder = "";
            }
        }
        $sWhere = "where nombre <> 'Bancos' ";
        if ( isset($_GET['sSearch']) && $_GET['sSearch'] != "" )
        {
            $sWhere .= "AND (";
            for ( $i=0 ; $i<count($aColumns) ; $i++ )
            {
                if ( isset($_GET['bSearchable_'.$i]) && $_GET['bSearchable_'.$i] == "true" )
                {
                    $sWhere .= $aColumns[$i]." LIKE '%".mysql_real_escape_string( $_GET['sSearch'] )."%' OR ";
                }
            }

            $sWhere = substr_replace( $sWhere, "", -3 );
            $sWhere .= ')';
        }
        
        $sQuery = "
            SELECT nombre, tipo, activo, id 
            FROM $sTable $sWhere $sOrder $sLimit
        ";

        // echo $sQuery;
        $rResult =  $this->connection->query($sQuery);

        /* Data set length after filtering */
        $sQuery = "
            SELECT FOUND_ROWS() as cantidad
            ";

        $rResultFilterTotal = $this->connection->query($sQuery);
        $iFilteredTotal = $rResultFilterTotal->row()->cantidad;
        
        $sQuery = "
        SELECT COUNT(`".$sIndexColumn."`) as cantidad
        FROM   $sTable
            ";

        $rResultTotal = $this->connection->query($sQuery);
        $iTotal = $rResultTotal->row()->cantidad; 

        $output = array(
            "sEcho" => intval($_GET['sEcho']),
            "iTotalRecords" => $iTotal,
            "iTotalDisplayRecords" => $iFilteredTotal,
            "aaData" => array()
        );
        foreach($rResult->result_array() as $row)
        {
            $data = array();

            for($i = 0; $i<count($aColumns) ; $i++){
                $data[] = $row[ $aColumns[$i] ];
            }

            $output['aaData'][] = $data;
        }

        return $output;
    }
    
    public function get($id)
    {
        $data = $this->connection->get_where("forma_pago",array("id"=>$id));
        return $data->row();
    }
    
    public function insertar($data)
    {
        $this->connection->set($data);
        $this->connection->insert('forma_pago');
    }
    
    public function modificar($data,$id)
    {
        $this->connection->where('id', $id);
        $this->connection->update('forma_pago', $data);
    }
    
    public function eliminar($id)
    {
        $this->connection->where('id', $id);
        $this->connection->delete("forma_pago");	
    }
    
    public function getActiva()
    {
        $this->connection->select("*");
        $this->connection->from("forma_pago");
        $this->connection->where("activo", 1);
        $this->connection->where("codigo !=","Bancos");
        $result = $this->connection->get();

        return $result->result();
    }

    /**
     * @method  getAvaible()
     *  Función para traer las formas de pago disponibles
     *  para plan separe y créditos
     * @author [José Fernnado]
     * @return array
     */
    public function getAvaible(){
        
        $this->connection->select("*");
        $this->connection->from("forma_pago");
        $this->connection->where("activo", 1);
        $this->connection->where("codigo !=","Gift_Card");
        $this->connection->where("codigo !=","nota_credito");
        $this->connection->where("codigo !=","Puntos");
        $this->connection->where("codigo !=","Credito");
        $this->connection->where("codigo !=","Bancos");
        $this->connection->order_by("id","Asc");
        $result = $this->connection->get();
        
        return $result->result();
    }
}