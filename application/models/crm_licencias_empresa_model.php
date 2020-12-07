<?php

// Proyecto: Sistema Facturacion
// Version: 1.0
// Programador: Leonardo Molina
// Framework: Codeigniter
// Clase: crm

class Crm_licencias_empresa_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }


    public function add($data) {
        $this->db->insert("crm_licencias_empresa", $data);  
        return $this->db->insert_id();
    }

    public function add_pago($data) {
        $this->db->insert("crm_pagos_licencias", $data); 
        return $this->db->insert_id();
    }

    public function validar_licencia($id_cliente,$id_almacen){
        $data = array(
            'idempresas_clientes' => $id_cliente,
            'id_almacen' => $id_almacen
        );
        $this->db->select("*");
        $this->db->from("crm_licencias_empresa");
        $this->db->where($data);
        $result = $this->db->get();

        if($result->num_rows() > 0){
            return $result->result_array()[0];
        }else{
            return NULL;
        }
    }

    public function update($data){       
        $this->db->where('idlicencias_empresa', $data['idlicencias_empresa']);
        return $this->db->update('crm_licencias_empresa', $data); 
    }

    public function update_by_almacen($data){       
        $this->db->where('idlicencias_empresa', $data['idempresas_clientes']);
        $this->db->where('id_almacen', $data['id_almacen']);
        return $this->db->update('crm_licencias_empresa', $data); 
    }

    public function delete($id,$por=0){       
        if(!empty($por)){
            $this->db->where($por);
        }else{
            $this->db->where('idlicencias_empresa', $id);
        }
        
        return $this->db->delete('crm_licencias_empresa'); 
    }

    public function get_all($plan=true) { 
        if($plan){
            $this->db->where("id_plan !=", 1); 
        }  
            
        $query = $this->db->get('v_crm_licencias');
        return $query->result();        
    }   

    public function get_ajax_data_licencias($plan=true) { 
        if($plan){
            //$this->db->where("id_plan !=", 1); 
        }  
      
        $aColumns = array(            
            'id_licencia',
            'nombre_empresa',
            'id_almacen',
            'nombre_plan',
            'valor_plan',
            'fecha_inicio_licencia',
            'fecha_vencimiento',            
            'estado_licencia',            
            'id_licencia',
            'id_db_config'                
        );

        $sIndexColumn = "id_licencia";
        $sTable = "v_crm_licencias v";
        $sLimit = "";
        if (isset($_GET['iDisplayStart']) && $_GET['iDisplayLength'] != '-1') {
            $sLimit = "LIMIT " . intval($_GET['iDisplayStart']) . ", " . intval($_GET['iDisplayLength']);
        }

        $sOrder = "";
        if (isset($_GET['iSortCol_0'])) {
            $sOrder = "ORDER BY  ";
            for ($i = 0; $i < intval($_GET['iSortingCols']); $i++) {
                if ($_GET['bSortable_' . intval($_GET['iSortCol_' . $i]) ] == "true") {
                    $sOrder.= $aColumns[intval($_GET['iSortCol_' . $i]) ] . ' ' . ($_GET['sSortDir_' . $i] === 'asc' ? 'asc' : 'desc') . ", ";
                }
            }

            $sOrder = substr_replace($sOrder, "", -2);
            if ($sOrder == "ORDER BY") {
                $sOrder = "";
            }
        }
        $sWhere="";
        $groupby=" GROUP BY $sIndexColumn ";
        
        if (isset($_GET['sSearch']) && $_GET['sSearch'] != "") {
            $sWhere.= " WHERE (";
            for ($i = 0; $i < count($aColumns); $i++) {
                if (isset($_GET['bSearchable_' . $i]) && $_GET['bSearchable_' . $i] == "true") {
                    $sWhere.= $aColumns[$i] . " LIKE '%" . mysql_real_escape_string($_GET['sSearch']) . "%' OR ";
                }
            }

            $sWhere = substr_replace($sWhere, "", -3);
            $sWhere.= ')';
        }

        $sQuery = "
            SELECT SQL_CALC_FOUND_ROWS " . str_replace(" , ", " ", implode(", ", $aColumns)) . "
            FROM   $sTable            
            $sWhere
            $groupby 
            $sOrder
            $sLimit";
            
        $rResult = $this->db->query($sQuery);
        /* Data set length after filtering */
        $sQuery = "SELECT FOUND_ROWS() as cantidad";       
        $rResultFilterTotal = $this->db->query($sQuery);
        $iFilteredTotal = $rResultFilterTotal->row()->cantidad;

        $sQuery = "SELECT COUNT(`" . $sIndexColumn . "`) as cantidad FROM $sTable";        
        $rResultTotal = $this->db->query($sQuery);
        $iTotal = $rResultTotal->row()->cantidad;

        $output = array(
            "sEcho" => intval($_GET['sEcho']) ,
            "iTotalRecords" => $iTotal,
            "iTotalDisplayRecords" => $iFilteredTotal,
            "aaData" => array()
        );
        
        foreach($rResult->result_array() as $row) {   
            //busco la bd de la licencia
            
            $this->db->select("base_dato, usuario, clave, servidor");
            $this->db->from("db_config");
            $this->db->where("id", $row["id_db_config"]);
            $this->db->limit("1");
            $sql_bd = $this->db->get();
            $bd = "";
            $almacen = "";
            if ($sql_bd->num_rows() > 0) {
                $result = $sql_bd->result_array();

                $usuarioh = $result[0]['usuario'];
                $claveh = $result[0]['clave'];
                $servidorh = $result[0]['servidor'];
                $base_datosh = $result[0]['base_dato'];                
               
                $dnsbd = "mysql://$usuarioh:$claveh@$servidorh";     
                $this->dbConnection = $this->load->database($dnsbd, true);
                //verificar si existe la bd 
                $existeDB = $this->db->query("SHOW DATABASES WHERE `database` = '".$base_datosh."'");        
                if($existeDB->num_rows() > 0)
                {
                     //busco conectarme a la bd del cliente  
                    $dns = "mysql://$usuarioh:$claveh@$servidorh/$base_datosh";
                    $this->dbConnection = $this->load->database($dns, true);              
                               
                    //busco el almacen de la licencia
                    $this->dbConnection->select("nombre");
                    $this->dbConnection->from("almacen");
                    $this->dbConnection->where("id", $row["id_almacen"]);
                    $this->dbConnection->limit("1");
                    $sql_alma = $this->dbConnection->get();                
                    if ($sql_alma->num_rows() > 0) {
                        $result = $sql_alma->result_array();                   
                        $almacen = $result[0]["nombre"];
                    }
                }else{
                     $almacen="No existe BD";
                }
            }

            $data = array();
            for ($i = 0; $i < count($aColumns); $i++) {
                switch ($i) {
                    case '2':
                        $data[] = $almacen;
                        break;

                    case '7':
                        $estado=($row[$aColumns[$i]]==1)? 'Activa' : 'Suspendida';
                        $data[] = $estado;
                        break;

                    default:
                        $data[] = $row[$aColumns[$i]]; 
                        break;
                }            
            }
            $output['aaData'][] = $data;
        }
        
        return $output;
      
    }    

    public function get_all_id($where) {   
        $this->db->where($where);     
        $query = $this->db->get('v_crm_licencias');
        return $query->result();        
    }
   
    public function get_by_id($id = 0) {
        $this->db->where("idempresas_clientes", $id);
        $query = $this->db->get('crm_empresas_clientes');
        return $query->result();        
    }

    public function excel_exist($id_proveedor, $id_impuesto, $fecha, $cantidad=null){           

        $this->connection->where("id_proveedor", $id_proveedor);

        $this->connection->where("id_impuesto", $id_impuesto);

        $this->connection->where("fecha", $fecha);

        $this->connection->where("cantidad", $cantidad);

        $this->connection->from("proformas");

        $this->connection->select("*");

        $flag = false; 

        $query = $this->connection->get();

         if($query->num_rows() > 0){

             $flag = true;

         }

         $query->free_result();

         return $flag;

     }

}

?>