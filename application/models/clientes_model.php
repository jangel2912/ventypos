<?php

class Clientes_model extends CI_Model {

    var $connection;

    public function __construct() {

        parent::__construct();
    }

    public function initialize($connection) {

        $this->connection = $connection;
    }

    // Para crear la coumna cartera en la tabla cliente si no existe
    public function crearColumnaCartera() {

        // Si no existe la columna, la creamos
        if (!$this->connection->field_exists('cartera', 'clientes')) {
            $sql = " ALTER TABLE clientes ADD cartera TINYINT(1) DEFAULT 0;";
            $this->connection->query($sql);
        }
    }

    // Existe la columna cartera, si existe devuelva su valor
    public function existeCartera($id) {
        if (!$this->connection->field_exists('cartera', 'clientes')) {
            $sql = " SELECT cartera FROM clientes WHERE id_cliente = $id ";
            $cartera = $this->connection->query($sql)->row()->cartera;
            return $cartera;
        } else {
            return 0;
        }
    }

    public function setCartera($id, $opcion) {
        // Si la columna cartera no existe
        if (!$this->connection->field_exists('cartera', 'clientes')) {
            $this->crearColumnaCartera();
            $sql = " UPDATE clientes SET cartera = $opcion WHERE id_cliente = $id ";
            $this->connection->query($sql);
        } else {
            $sql = " UPDATE clientes SET cartera = $opcion WHERE id_cliente = $id ";
            $this->connection->query($sql);
        }
    }

    /* ============================================================================================== */

    //    GRUPOS
    /* ============================================================================================== */
   /**
    * [get_by_where_group description]
    *@author Dairinet avila
    *  busca todos los campos de un grupo
    * @param  $where (id o nombre del grupo por array)
    *
    * @return array|int retorna un array si tiene valores o 0 sino consigue 
    */
    public function get_by_where_group($where=0)
    {   
        $this->connection->select("*");
        $this->connection->from("grupo_clientes");

        if(!empty($where)){
            $this->connection->where($where);
        }        
        $query = $this->connection->get();
        if($query->num_rows() > 0){
            return $query->result_array();
        }
        else {
            return 0;
        }   
    }

    //Nuevo grupo de usuarios
    public function add_group() {

        $array_datos = array(
            'nombre' => $this->input->post('name_group')
        );

        $this->connection->insert("grupo_clientes", $array_datos);
    }

    /* Trae todos los grupos menos "sin grupo" */

    public function get_group() {
        $query = $this->connection->query("SELECT id, nombre  FROM grupo_clientes where id != 0 ORDER BY id ");
        return $query->result();
    }

    /* Trae todos los grupos */

    public function get_group_all() {

        $query = $this->connection->query("SELECT id, nombre  FROM grupo_clientes  ORDER BY id ");
        return $query->result();
    }

    /* Trae todos los clientes de un grupos */

    public function get_clients_group_all($id_grupo) {
        $query = $this->connection->query("SELECT * FROM clientes c where grupo_clientes_id = $id_grupo ORDER BY id_cliente DESC");
        return $query->result();
    }

    public function get_group_one($filtro) {

        $query = $this->connection->query("SELECT * FROM grupo_clientes WHERE nombre=   '" . $filtro . "'");

        return $query->result();
    }

    public function delete_group($grupo_id) {

        $query = "DELETE FROM grupo_clientes WHERE id = $grupo_id;";
        return $this->connection->query($query);
    }

    /* Actualiza el grupo de los clientes */

    public function assign_group($data) {

        $data_update = array('grupo_clientes_id' => $data['grupo']);

        if (!empty($data['clientes'])) {
            foreach ($data['clientes'] as $key => $value) {
                $this->connection->where('id_cliente', $value);
                $done = $this->connection->update("clientes", $data_update);
                if ($done != 1) {
                    return 0;
                } /* Ocurrio un error */
            }
        }

        return 1; /* Exito */
    }

    public function get_clients_group_filter($filtro) {
        $query = $this->connection->query("SELECT * FROM clientes WHERE nombre_comercial LIKE  '".$filtro."%' AND grupo_clientes_id = 1 ORDER BY nombre_comercial");
        return $query->result();
    }

    /*  public function leer_uno($cliente_id){
      $query = $this->connection->query("SELECT * FROM grupo_clientes where id = $cliente_id");
      return $query->result();
      } */

    /* ============================================================================================== */

    /* Actualiza el grupo de los clientes */

    public function assign_default_group($grupo_id) {

        $data_update = array('grupo_clientes_id' => 1);
        $this->connection->where('grupo_clientes_id', $grupo_id);
        $this->connection->update("clientes", $data_update);
    }

    public function get_clients_filter($filtro) {

        $query = $this->connection->query("SELECT * FROM clientes WHERE nombre_comercial LIKE    '" . $filtro . "%'");
        return $query->result();
    }

    public function get_total() {

        $query = $this->connection->query("SELECT count(*) cantidad FROM  clientes");

        return $query->row()->cantidad;
    }

    public function get_pais() {

        $result = array();

        $query = $this->db->query("SELECT * FROM pais");

        foreach ($query->result() as $value) {

            $result[$value->nombre_pais] = $value->nombre_pais;
        }

        return $result;
    }

    public function get_provincia($pais) {

        $result = array();
        $id_pais = '0';
        $query = $this->db->query("SELECT * FROM pais where nombre_pais = '$pais'");

        foreach ($query->result() as $value) {

            $id_pais = $value->id_pais;
        }
        $result = $this->db->query("SELECT * FROM provincia where pro_pais = '$id_pais' order by pro_nombre asc")->result();

        return $result;
    }

    public function get_all($offset) {

        $query = $this->connection->query(
                "SELECT 
                    id_cliente,nombre_comercial, razon_social, nif_cif, contacto, email, pais, provincia,grupo_clientes_id
                    FROM clientes c ORDER BY id_cliente DESC limit $offset, 8"
        );

        return $query->result();
    }

    public function get_ajax_data($start,$limit,$search=null, $orderby=null) {

        //   $aColumns = array('nombre_comercial', 'nif_cif', 'telefono', 'movil', 'email', 'id_cliente');
        
        if($search != null)
                $search = "and nombre_comercial like '%$search%' or nif_cif like '%$search%'";
            else
                $search = '';  

        $sql = "SELECT SQL_CALC_FOUND_ROWS nombre_comercial, nif_cif, telefono, movil, email, id_cliente, nombre,IF(onlineTienda = 1,'si','no') as tienda from clientes LEFT JOIN grupo_clientes ON  grupo_clientes_id = grupo_clientes.id WHERE id_cliente > 0 $search $orderby LIMIT $start, $limit";
      
        $data = array();
        $rResult = $this->connection->query($sql);
        /* Data set length after filtering */
        $sQuery = "SELECT FOUND_ROWS() as cantidad";
        $rResultFilterTotal = $this->connection->query($sQuery);
        // $aResultFilterTotal = $rResultFilterTotal->result_array();
        $iFilteredTotal = $rResultFilterTotal->row()->cantidad;
        $sQuery = " SELECT COUNT(id_cliente) as cantidad FROM  clientes";
        $rResultTotal = $this->connection->query($sQuery);
        $iTotal = $rResultTotal->row()->cantidad;
        $output = array(
            "sEcho" => intval($_GET['sEcho']) ,
            "iTotalRecords" => $iTotal,
            "iTotalDisplayRecords" => $iFilteredTotal,
            "aaData" => array()
        );
        foreach ($rResult->result() as $value) {
            $data[] = array(
                $value->nombre_comercial,
                $value->nif_cif,
                $value->telefono,
                $value->movil,
                $value->email,
                $value->nombre,
                $value->tienda,
                $value->id_cliente
            );
        }
        $output['aaData'] = $data;
        return $output;
        /*return array(
            'aaData' => $data
        );*/

       
    }

    public function get_termg_cartera($q = '') {

        $query = $this->connection->query("
                    SELECT (select id from lista_precios where grupo_cliente_id = c.grupo_clientes_id limit 1) as lista, c.grupo_clientes_id, c.id_cliente as id, c.cartera AS cartera, CONCAT(c.nombre_comercial,' (', ifNull(c.nif_cif, ''),')') as value, CONCAT(ifNull(c.nif_cif, ''), ', ', ifNull(direccion, ''), ', ', ifNull(poblacion, ''), ', ', ifNull(pais, ''), ',', ifNull(provincia, ''),', ',IF(onlineTienda = 1,'si','no') as onlineTienda,ifNull(cp, '')) as descripcion,  
                    FROM clientes c 
                    WHERE CONCAT(nombre_comercial,' ',ifNull(c.nif_cif, ''),' ',ifNull(c.poblacion, '')) LIKE '%$q%' LIMIT 0, 30
            ");

        return $query->result_array();
    }

    public function get_termg($q = '', $almacen="") {
        $and="";
        if(!empty($almacen)){
            $and=" AND (almacen_id=$almacen OR almacen_id=0)";
        }

        $query = $this->connection->query("
                    SELECT (select id from lista_precios where grupo_cliente_id = c.grupo_clientes_id $and limit 1) as lista, c.grupo_clientes_id, c.id_cliente as id, CONCAT(c.nombre_comercial,' (', ifNull(c.nif_cif, ''),')') as value, CONCAT(ifNull(c.nif_cif, ''), ', ', ifNull(direccion, ''), ', ', IFNULL(telefono, ''), ', ',IFNULL(movil, ''), ', ', ifNull(poblacion, ''), ', ', ifNull(pais, ''), ',', ifNull(provincia, ''),', ',ifNull(cp, '')) as descripcion 
                    FROM clientes c 
                    WHERE CONCAT(nombre_comercial,' ',ifNull(c.nif_cif, ''),' ',ifNull(c.poblacion, '')) LIKE '%$q%'  OR telefono LIKE '%$q%' OR movil LIKE '%$q%' ORDER BY c.nombre_comercial ASC LIMIT 0, 30
            ");
        return $query->result_array();
    }

    public function get_by_id($id = 0) {

        $query = $this->connection->query("SELECT * FROM  clientes

										WHERE id_cliente = '" . $id . "'");



        return $query->row_array();
    }

    public function add_csv($data, $usuario) {

        $grupo_clientes_id = trim($data['grupo_clientes_id']);
        $id_uni = 0;
        $array_datos = array();
        /*
          $categoria = $this->connection->query("SELECT id FROM producto ORDER BY id DESC LIMIT 1 ")->result();
          foreach ($categoria as $dat) {
          $id_prod = $dat->id;
          }
         */
        $unidades = $this->connection->query("SELECT id FROM grupo_clientes where nombre = '" . $grupo_clientes_id . "' ")->result();
        foreach ($unidades as $dat) {
            $id_uni = $dat->id;
        }

        $array_datos = array(
            "nombre_comercial" => $data['nombre_comercial'],
            "razon_social" => $data['razon_social'],
            "nif_cif" => $data['nif_cif'],
            "direccion" => $data['direccion'],
            "telefono" => $data['telefono'],
            "grupo_clientes_id" => $id_uni,
            "pais" => $data['pais'],
            "provincia" => $data['provincia'],
            "email" => $data['email']
        );

        $this->connection->insert("clientes", $array_datos);
    }

    public function add() {
        
        $grupo = 1;
        if(isset($_POST['grupo']) && $_POST['grupo'] != "")
        {
            $grupo = $this->input->post('grupo');
        }
        $array_datos = array(
            "id_cliente" => '',
            "pais" => $this->input->post('pais'),
            "provincia" => $this->input->post('provincia'),
            "nombre_comercial" => $this->input->post('nombre_comercial'),
            "razon_social" => $this->input->post('razon_social'),
            "tipo_identificacion" => $this->input->post('tipo_identificacion'),
            "nif_cif" => $this->input->post('nif_cif'),
            "contacto" => $this->input->post('contacto'),
            "pagina_web" => $this->input->post('pagina_web'),
            "email" => $this->input->post('email'),
            "poblacion" => $this->input->post('poblacion'),
            "direccion" => $this->input->post('direccion'),
            "cp" => $this->input->post('cp'),
            "telefono" => $this->input->post('telefono'),
            "movil" => $this->input->post('movil'),
            "fax" => $this->input->post('fax'),
            "tipo_empresa" => $this->input->post('tipo_empresa'),
            "entidad_bancaria" => $this->input->post('entidad_bancaria'),
            "numero_cuenta" => $this->input->post('numero_cuenta'),
            "observaciones" => $this->input->post('observaciones'),
            "fecha_nacimiento" => $this->input->post('fecha_nacimiento'),
            "genero" => $this->input->post('genero'),
            "grupo_clientes_id" => $grupo,
        );



        $this->connection->insert("clientes", $array_datos);

        $array_datos['id_cliente'] = $this->connection->insert_id();

        return $array_datos;
    }

    public function get_tipo_identificacion() {

        $this->db->select('valor_opcion, mostrar_opcion');

        $query = $this->db->get_where('opciones', array('nombre_opcion' => 'tipo_identificacion'));

        $result = array();

        foreach ($query->result() as $value) {

            $result[$value->valor_opcion] = $value->mostrar_opcion;
        }

        return $result;
    }

    public function add_light($nombre_comercial, $tipo_identificacion, $nif_cif, $email, $telefono, $direccion, $pais, $ciudad, $celular, $plan, $plan_punto, $cod, $grupo) {

        $array_datos = array(
            "nombre_comercial" => $nombre_comercial,
            "razon_social" => $nombre_comercial,
            "tipo_identificacion" => $tipo_identificacion,
            "nif_cif" => $nif_cif,
            "email" => $email,
            "telefono" => $telefono,
            "direccion" => $direccion,
            "pais" => $pais,
            "provincia" => $ciudad,
            "movil" => $celular,
            "grupo_clientes_id" => $grupo
        );

        $this->connection->insert("clientes", $array_datos);
        $id = $this->connection->insert_id();

        if ($plan == 'true') {

            $array_datos = array(
                "id_cliente" => $id,
                "plan_id" => $plan_punto,
                "codinterna" => $cod
            );

            $this->connection->insert("cliente_plan_punto", $array_datos);
        }

        return $id;
    }

    public function update() {
        
        $grupo = 1;
        if(isset($_POST['grupo']) && $_POST['grupo'] != "")
        {
            $grupo = $this->input->post('grupo');
        }
        $array_datos = array(
            "pais" => $this->input->post('pais'),
            "provincia" => $this->input->post('provincia'),
            "nombre_comercial" => $this->input->post('nombre_comercial'),
            "razon_social" => $this->input->post('razon_social'),
            "tipo_identificacion" => $this->input->post('tipo_identificacion'),
            "nif_cif" => $this->input->post('nif_cif'),
            "contacto" => $this->input->post('contacto'),
            "pagina_web" => $this->input->post('pagina_web'),
            "email" => $this->input->post('email'),
            "poblacion" => $this->input->post('poblacion'),
            "direccion" => $this->input->post('direccion'),
            "cp" => $this->input->post('cp'),
            "telefono" => $this->input->post('telefono'),
            "movil" => $this->input->post('movil'),
            "fax" => $this->input->post('fax'),
            "tipo_empresa" => $this->input->post('tipo_empresa'),
            "entidad_bancaria" => $this->input->post('entidad_bancaria'),
            "numero_cuenta" => $this->input->post('numero_cuenta'),
            "observaciones" => $this->input->post('observaciones'),
            "fecha_nacimiento" => $this->input->post('fecha_nacimiento'),
            "genero" => $this->input->post('genero'),
            "grupo_clientes_id"=>$grupo,
        );



        $this->connection->where('id_cliente', $this->input->post('id'));

        $this->connection->update("clientes", $array_datos);
    }

     public function update_ajax($where,$data) {
              
        $this->connection->where($where);

        $this->connection->update("clientes", $data);
    }

    public function delete($id) {
        //consultar si hay ventas asociadas
        $cliente_result = $this->connection->get_where('venta', array('cliente_id' => $id))->result();

        if (count($cliente_result) != 0) {
            return 'facturas asociadas';
        }

        //consultar si hay plan separes asociados
        $plan = $this->connection->get_where('plan_separe_factura', array("cliente_id" => $id, "activo" => 1, "estado" => 0))->result();

        if (count($plan) != 0) {
            return 'plan separes asociados';
        }

        $this->connection->where('id_cliente', $id);
        $this->connection->delete("clientes");
        return 'eliminado';
    }

    public function excel() {

        $this->connection->select("*");
        $this->connection->from("clientes");
        $query = $this->connection->get();
        $data_clientes = array();

        foreach($query->result_array() as $cliente){
            $this->connection->select("*");
            $this->connection->from("grupo_clientes");
            $this->connection->where("id",$cliente["grupo_clientes_id"]);
            $result = $this->connection->get();
            if($result->num_rows() > 0){
                $row = $result->result_array();
                $grupo = $row[0]["nombre"];
            }else{
                $grupo = "sin grupo";
            }
            $cliente["grupo"] = $grupo;
            $data_clientes[] = array(
                $cliente
            );
        }


        return $data_clientes;
    }

    public function excel_exist($nombre_comercial, $email, $nif_cif) {



        $this->connection->where("nombre_comercial", $nombre_comercial);

        $this->connection->where("nif_cif", $nif_cif);

        $this->connection->where("email", $email);

        $this->connection->from("clientes");

        $this->connection->select("*");

        $flag = false;

        $query = $this->connection->get();

        if ($query->num_rows() > 0) {

            $flag = true;
        }

        $query->free_result();

        return $flag;
    }

    public function excel_exist_get_id($nombre_comercial, $email, $nif_cif) {

        $this->connection->where("nombre_comercial", $nombre_comercial);

        $this->connection->where("nif_cif", $nif_cif);

        $this->connection->where("email", $email);

        $this->connection->from("clientes");

        $this->connection->select("*");

        $query = $this->connection->get();

        if ($query->num_rows() > 0) {

            return $query->row()->id_cliente;
        } else {



            $array_datos = array(
                "nombre_comercial" => $nombre_comercial,
                "email" => $email,
                "nif_cif" => $nif_cif
            );



            $this->connection->insert("clientes", $array_datos);

            return $this->connection->insert_id();
        }
    }

    public function excel_add($array_datos) {

        $this->connection->insert("clientes", $array_datos);
    }

    public function nif_check($str) {

        $result = $this->connection->from('clientes')
                ->select('count(id_cliente) as cantidad, id_cliente')
                ->where('nif_cif', $str)
                ->get()
                ->row();


        if ($result->cantidad > 0 && $result->id_cliente != $this->input->post('id')) {

            return true;
        }

        return false;
    }

    public function getGrupoClientes() {
        $this->connection->from('grupo_clientes');
        $this->connection->order_by("nombre", "asc");
        $result = $this->connection->get();
        return $result->result();
    }

    public function importExcelNewImportar($dataExcel) {

        $sheetData = $dataExcel;

        // PRIMERO HACEMOS TRIM A CADA CELDA PARA EVITAR MUCHOS ERRORES !!!!
        foreach ($sheetData as $i => $row) {
            foreach ($row as $letra => $val) {
                $sheetData[$i][$letra] = trim($val);
            }
        }

        foreach ($sheetData as $i => $row) {
            if ($row['g'] != "") {
                $sql = "select id from grupo_clientes where nombre = '" . $row['g'] . "'";
                $grupo = $this->connection->query($sql)->row();
            } else {
                $grupo = array('id' => 1);
            }
            $data = array(
                "nombre_comercial" => $row['a'],
                "pais" => $row['b'],
                "provincia" => $row['c'],
                "razon_social" => $row['d'],
                "tipo_identificacion" => $row['e'],
                "nif_cif" => $row['f'],
                "grupo_clientes_id" => $grupo['id'],
                "contacto" => $row['h'],
                "pagina_web" => $row['i'],
                "email" => $row['j'],
                "poblacion" => $row['k'],
                "direccion" => $row['l'],
                "cp" => $row['m'],
                "telefono" => $row['n'],
                "movil" => $row['o'],
                "fax" => $row['p'],
                "tipo_empresa" => $row['q'],
                "entidad_bancaria" => $row['r'],
                "numero_cuenta" => $row['d'],
                "observaciones" => $row['t'],
            );
            $this->connection->insert('clientes', $data);
        }
        /*
          try{

          $this->connection->trans_begin();

          $idUser = $this->session->userdata('user_id');

          //$errorFix = '{"categorias":[{"k":"shop & more","v":"Camisa"},{"k":"pantalones","v":"Zapatos"},{"k":"Gorras","v":"Categoría 4"},{"k":"Categoría 1","v":"Categoría 5"}],"impuestos":[{"k":"2","v":"IMPOCONSUMO"},{"k":"iva 20","v":"Sin Impuesto"},{"k":"20","v":"Iva 5"}],"unidades":[{"k":"kilos","v":"kilogramo"},{"k":"Litros","v":"gramo"}],"proveedores":[{"k":"claro@claro.com.co","v":"giseth salazar"},{"k":"asda","v":"CLARO"},{"k":"b","v":"Johana "}]}';
          $errorFix = json_decode( $errorFix, true);


          // validamos si fue generado dinamicamente y si existe, returnamos el key en el array, de lo contrario false
          $existMin = $this->textInArray("mínimo",$sheetData[1]);
          $existMax = $this->textInArray("máximo",$sheetData[1]);
          $existUbi = $this->textInArray("ubicación",$sheetData[1]);
          $existUnid = $this->textInArray("unidad",$sheetData[1]);
          $existFecha = $this->textInArray("fecha",$sheetData[1]);
          $existAct = $this->textInArray("activo",$sheetData[1]);
          $existTie = $this->textInArray("tienda",$sheetData[1]);
          $existTienExis = $this->textInArray("existencia",$sheetData[1]);
          $existProv = $this->textInArray("proveedor",$sheetData[1]);



          //pr($errorFix);
          //pr($sheetData);


          // SOBREESCRIBIMOS CATEGORIAS, IMPUESTOS, UNIDADES Y PROVEEDORES ERRONEOS y CREAMOS ARRAY PARA FOREING KEYS
          foreach ( $sheetData as $i => $val) {


          // Si no somos la primer línea y si no hay informacion en categoria, nombre producto, precio venta e impuesto, no se crea el producto
          if( $i != 1 ){


          if (array_key_exists('categorias', $errorFix)){
          foreach( $errorFix["categorias"] as $fixObj ){
          if( trim( $val['A'] ) == trim( $fixObj["k"] ) ){
          $sheetData[$i]["A"] = trim( $fixObj["v"] );
          }
          }
          }

          if (array_key_exists('impuestos', $errorFix)){
          foreach( $errorFix["impuestos"] as $fixObj ){
          $tmpImp = str_replace("%", "", $val['F'] );
          if( trim( $tmpImp ) == trim( $fixObj["k"] ) ){
          $sheetData[$i]["F"] = trim( $fixObj["v"] );
          }
          }
          }

          if (array_key_exists('unidades', $errorFix)){
          foreach( $errorFix["unidades"] as $fixObj ){
          if( trim( $val[$existUnid] ) == trim( $fixObj["k"] ) ){
          $sheetData[$i][$existUnid] = trim( $fixObj["v"] );
          }
          }
          }

          if (array_key_exists('proveedores', $errorFix)){
          foreach( $errorFix["proveedores"] as $fixObj ){
          if( trim( $val[$existProv] ) == trim( $fixObj["k"] ) ){
          $sheetData[$i][$existProv] = trim( $fixObj["v"] );
          }
          }
          }

          }

          }


          //----------------------------------------------------------
          // Consultamos Almacenes
          //----------------------------------------------------------
          $almacenesLetras = [];
          $almacenesMapLetras = [];
          foreach($sheetData[1] as $key => $val ){
          if( strpos( strtolower($val), "cantidad" ) !== false ){
          $nombreAlmacen =

          $val = str_replace(")", "", $val);
          $val = explode('(', $val)[1];
          $val = strtolower(trim($val));

          $almacenesLetras[] = $key;

          $query = $this->connection->query(" SELECT id FROM almacen WHERE nombre = '$val' ");

          $id = 1;

          // Si existe el almacen
          if($query->num_rows() > 0){
          $id = $query->row()->id;
          }


          // info completa
          $tmpAlm = array(
          "n" => $val,
          "id" => $id
          );
          $almacenesMapLetras[$key] = $tmpAlm;

          }
          }



          //----------------------------------------------------------
          // Consultamos tablas foreing con los respectivos datos
          //----------------------------------------------------------

          $categoriasMap = Array();
          $query = $this->connection->query(" SELECT TRIM(nombre) AS 'k', id AS 'v' FROM categoria WHERE nombre <> 'GiftCard' ");
          $categorias = $query->result_array();
          foreach($categorias as $val){
          $categoriasMap[strtolower($val["k"])] = $val["v"];
          }

          $impuestosMap = Array();
          $impuestosPorcenMap = Array();
          $query = $this->connection->query(" SELECT TRIM( REPLACE(nombre_impuesto, '%', '') ) AS 'k', TRIM(porciento) AS 'kn', id_impuesto AS 'v' FROM impuesto ");
          $impuesto = $query->result_array();
          foreach($impuesto as $val){
          $impuestosMap[strtolower($val["k"])] = $val["v"];
          $impuestosPorcenMap[$val["kn"]] = $val["v"];
          }

          $unidadesMap = Array();
          $query = $this->connection->query(" SELECT trim(nombre) AS 'k', id AS 'v' FROM unidades ");
          $unidades = $query->result_array();
          foreach($unidades as $val){
          $unidadesMap[strtolower($val["k"])] = $val["v"];
          }

          $proveedoresMap = Array();
          $query = $this->connection->query(" SELECT TRIM(nombre_comercial) AS 'k', id_proveedor AS 'v' FROM proveedores ");
          $proveedor = $query->result_array();
          foreach($proveedor as $val){
          $proveedoresMap[strtolower($val["k"])] = $val["v"];
          }


          //----------------------------------------------------
          // Array para mostrar reporte de resultados
          //----------------------------------------------------
          $productosImportadosHeader = [
          "s", // estado del producto en la importacion, si es importado o no
          "Excel",
          "Categoría",
          "Código",
          "Nommbre",
          "PC",
          "PV",
          "Imp"
          ];

          // Si los campos fueron generados dinamicamente en el excel los añadimos al reporte
          if( $existMin != false ) $productosImportadosHeader[] = 'S. Mín';
          if( $existMax != false ) $productosImportadosHeader[] = 'S. Máx';
          if( $existUbi != false ) $productosImportadosHeader[] = 'Ubi.';
          if( $existUnid != false ) $productosImportadosHeader[] = 'Uni.';
          if( $existFecha != false ) $productosImportadosHeader[] = 'FV';
          if( $existAct != false ) $productosImportadosHeader[] = 'Act.';
          if( $existTie != false ) $productosImportadosHeader[] = 'Tie.';
          if( $existTienExis != false ) $productosImportadosHeader[] = 'TE';
          if( $existProv != false ) $productosImportadosHeader[] = 'Prov.';


          //añadimos los almacenes al encabezado
          foreach( $almacenesLetras as $valLetraAlm ){
          $nombreAlmacen = $almacenesMapLetras[ $valLetraAlm ]["n"];
          $productosImportadosHeader[] = ucwords($nombreAlmacen);
          }


          //añadimos el header ( títulos ) a la lista de importados
          $masterReport[] = $productosImportadosHeader;

          //--------------------------
          //FIN array Reportes
          //--------------------------

          // AGREGAMOS PRODUCTOS
          foreach ( $sheetData as $i => $val) {

          // Si no somos la primer línea y si no hay informacion en categoria, nombre producto, precio venta e impuesto, no se crea el producto
          if( $i != 1 && trim($val['A']) != "" && trim($val['C']) != "" && trim($val['E']) != "" && trim($val['F']) != "" ){

          // CAMPOS OBLIGATORIOS

          // CODIGO
          $codigo;
          if( trim($val['B']) == "" ){
          $codigo = substr( strtoupper(md5(microtime())) ,0,15);
          }else{
          $codigo = trim($val['B']);
          }

          // PRECIO COMPRA
          $precioC;
          if( trim($val['D']) == "" ){
          $precioC = 0;
          }else{
          $precioC = $this->toNum( trim($val['D']) );
          }

          // PRECIO VENTA
          $precioV;
          if( trim($val['E']) == "" ){
          $precioV = 0;
          }else{
          $precioV = $this->toNum( trim($val['E']) );
          }


          // CAMPOS DINAMICOS

          // activo
          $activo = 1;
          if( $existAct != false ){
          if( strtolower(trim($val[ $existAct ])) == "no" || strtolower(trim($val[ $existAct ])) == "0"  ) $activo = 0;
          if( strtolower(trim($val[ $existAct ])) == "si" || strtolower(trim($val[ $existAct ])) == "1" || strtolower(trim($val[ $existAct ])) == "" ) $activo = 1;
          }else{
          $activo = 1;
          }

          // tienda
          $tienda = 1;
          if( $existTie != false ){
          if( strtolower(trim($val[ $existTie ])) == "no" || strtolower(trim($val[ $existTie ])) == "0" || strtolower(trim($val[ $existTie ])) == "" ) $tienda = 0;
          if( strtolower(trim($val[ $existTie ])) == "si" || strtolower(trim($val[ $existTie ])) == "1") $tienda = 1;
          }else{
          $tienda = 1;
          }

          // tienda existencias
          $tiendaExis = 0;
          if($existTienExis != false ) {
          if( strtolower(trim($val[ $existTienExis ])) == "no" || strtolower(trim($val[ $existTienExis ])) == "0" || strtolower(trim($val[ $existTienExis ])) == "" ) $tiendaExis = 0;
          if( strtolower(trim($val[ $existTienExis ])) == "si" || strtolower(trim($val[ $existTienExis ])) == "1" ) $tiendaExis = 1;
          }else{
          $tiendaExis = 0;
          }

          // stock minimo
          $min = 0;
          if($existMin != false ) {
          if( trim($val[ $existMin ]) == "") $min = 0;
          else $min = trim($val[ $existMin ]);
          }else{
          $min = 0;
          }

          // stock maximo
          $max = 1;
          if($existMax != false ) {
          if( trim($val[ $existMax ]) == "") $max = 1;
          else $max = trim($val[ $existMax ]);
          }else{
          $max = 1;
          }

          // fehca vencimiento
          $fechaV = $existFecha != false ?  trim($val[ $existFecha ]) : '';

          // ubicacion
          $ubicacion = $existUbi != false ?  trim($val[ $existUbi ]) : '';


          // FOREING TABLES
          $id_proveedor = $existProv != false ?  $proveedoresMap[ strtolower( trim($val[ $existProv ])) ] : 0;
          $id_unidad = $existUnid != false ?  $unidadesMap[ strtolower(trim($val[ $existUnid ])) ] : 1;
          $id_categoria = $categoriasMap[ strtolower(trim( $val['A'] )) ];

          // IMPUESTO
          $id_impuesto = 0;
          $tmpImp = str_replace("%", "", $val['F'] );

          if( !is_numeric( trim( $tmpImp ) ) ){ $id_impuesto = $impuestosMap[ strtolower(trim( $tmpImp )) ]; }
          if( is_numeric( trim( $tmpImp ) ) ){ $id_impuesto = $impuestosPorcenMap[ trim( $tmpImp ) ]; }

          //-----------------------------------------------------------------

          //----------------------------------------------------------------
          // Solo para el reporte de productos importados correctamente
          //----------------------------------------------------------------

          $reportProveedor = $existProv != false ? trim($val[ $existProv ]) : "";
          $reportUnidad = $existUnid != false ? trim($val[ $existUnid ]) : "";

          $reportActivo = $activo == 1 ? "Si" : "No";
          $reportTienda = $tienda == 1 ? "Si" : "No";
          $reportTiendaE = $tiendaExis == 1 ? "Si" : "No";



          $tempProductosImportados = [
          1, // Significa que el producto será importado
          $i, // fila en el excel
          trim( $val['A'] ), // Categoria
          $codigo,
          trim( $val['C'] ), // Nombre
          $precioC,
          $precioV,
          trim( $val['F'] ) // Impuesto
          ];

          // Si los campos fueron generados dinamicamente en el excel los añadimos al reporte
          if( $existMin != false ) $tempProductosImportados[] = $min;
          if( $existMax != false ) $tempProductosImportados[] = $max;
          if( $existUbi != false ) $tempProductosImportados[] = $ubicacion;
          if( $existUnid != false ) $tempProductosImportados[] = $reportUnidad;
          if( $existFecha != false ) $tempProductosImportados[] = $fechaV;
          if( $existAct != false ) $tempProductosImportados[] = $reportActivo;
          if( $existTie != false ) $tempProductosImportados[] = $reportTienda;
          if( $existTienExis != false ) $tempProductosImportados[] = $reportTiendaE;
          if( $existProv != false ) $tempProductosImportados[] = $reportProveedor;

          //----------------------------------------------------------------
          // Fin productos importados correctamente
          //----------------------------------------------------------------

          $data = array(
          'imagen' => "product-dummy.png",
          "nombre" => trim( $val['C'] ),
          "codigo" => $codigo,
          "precio_venta" => $precioV,
          "precio_compra" => $precioC,
          "categoria_id" => $id_categoria,
          "impuesto" => $id_impuesto,
          "id_proveedor" => $id_proveedor,
          "unidad_id" => $id_unidad,
          'activo' => $activo,
          'tienda' => $tienda,
          'muestraexist' => $tiendaExis,
          'fecha_vencimiento' => $fechaV,
          'ubicacion' => $ubicacion,
          'material' => 0,
          'stock_minimo' => $min,
          'stock_maximo' => $max
          );


          if( $tipoAccion == "guardar"){

          // AGREGAMOS EL PRODUCTO
          $this->connection->insert("producto", $data);
          $id_producto = $this->connection->insert_id();

          }else{
          $id_producto = 0;
          }


          // ACTUALIZAMOS STOCK ACTUAL Y DIARIO
          foreach( $almacenesLetras as $valLetraAlm ){

          $idProd = $id_producto;

          $idAlmacen = $almacenesMapLetras[ $valLetraAlm ]["id"];
          $nombreAlmacen = $almacenesMapLetras[ $valLetraAlm ]["n"];
          $cantidad = trim( $val[ $valLetraAlm ] );


          // Si no se especifica la cantidad
          if( $cantidad == "") $cantidad = "0";


          //------------------------------------------------------------------------
          // Añadimos unidaes al reporte final de objetos importados correctamente
          //------------------------------------------------------------------------
          $tempProductosImportados[] = $cantidad;
          //------------------------
          // Fin reporte
          //------------------------


          $data_stock_actual = array(
          'almacen_id' => $idAlmacen,
          'producto_id' => $idProd,
          'unidades' => $cantidad
          );

          $data_stock_diario =  array(
          'producto_id' => $idProd,
          'almacen_id' => $idAlmacen,
          'fecha' => date('Y-m-d'),
          'unidad' => $cantidad,
          'precio' => $precioV,
          'usuario' => $idUser,
          'razon' => 'E'
          );

          if( $tipoAccion == "guardar"){

          $this->connection->insert('stock_actual', $data_stock_actual);
          $this->connection->insert('stock_diario', $data_stock_diario);

          }

          }



          // agregamos el objeto importado a la lista
          $masterReport[] = $tempProductosImportados;


          }else{ // ERRORES EN EL EXCEL

          // Si no estamos en la primer fila que es el encabezado
          if( $i != 1){

          // Si todas la celdas estan en blanco no lo añadimos al reporte de no importados
          if( trim($val['A']) == "" && trim($val['B']) == "" && trim($val['C']) == "" && trim($val['D']) == "" && trim($val['E']) == "" && trim($val['F']) == "" ){ }
          else{

          // creamos la lista de los productos que no serán importados

          $a = trim($val['A']) == "" ? "?" : trim($val['A']);
          $c = trim($val['C']) == "" ? "?" : trim($val['C']);
          $e = trim($val['E']) == "" ? "?" : $this->toNum(trim($val['E']));
          $f = trim($val['F']) == "" ? "?" : trim($val['F']);

          $tmpNoImportado = [
          0, // Significa que el producto NO será importado
          $i,
          $a,
          trim($val['B']),
          $c,
          $this->toNum( trim($val['D']) ),
          $e,
          $f
          ];

          // Si los campos fueron generados dinamicamente en el excel los añadimos al reporte
          if( $existMin != false ) $tmpNoImportado[] = "";
          if( $existMax != false ) $tmpNoImportado[] =  "";
          if( $existUbi != false ) $tmpNoImportado[] =  "";
          if( $existUnid != false ) $tmpNoImportado[] =  "";
          if( $existFecha != false ) $tmpNoImportado[] =  "";
          if( $existAct != false ) $tmpNoImportado[] =  "";
          if( $existTie != false ) $tmpNoImportado[] =  "";
          if( $existTienExis != false ) $tmpNoImportado[] =  "";
          if( $existProv != false ) $tmpNoImportado[] =  "";


          // Añadimos las celdas vacias de los almacenes
          foreach( $almacenesLetras as $valLetraAlm ){
          $tmpNoImportado[] = "";
          }


          // agregamos el objeto no importado a la lista
          $masterReport[] = $tmpNoImportado;

          }
          }
          }
          }

          if ($this->connection->trans_status() === FALSE){
          $this->connection->trans_rollback();
          } else {
          $this->connection->trans_commit();
          }


          // Retornamos reporte
          return $masterReport;


          } catch (Exception $e) {
          // $this->connection->trans_rollback();
          print_r($e);
          die;
          } */
    }

    public function importExcelNewValidar($dataExcel) {

        $sheetData = $dataExcel;

        // PRIMERO HACEMOS TRIM A CADA CELDA PARA EVITAR MUCHOS ERRORES !!!!
        foreach ($sheetData as $i => $row) {
            foreach ($row as $letra => $val) {
                $sheetData[$i][$letra] = trim($val);
            }
        }

        //$existTienExis = $this->textInArray("existencia",$sheetData[1]);
        //Campos a validar
        $grupo = array();
        $nombre = array();
        $identificacion = array();
        $camposFaltantes = array();
        // En esta lista se guardarán las filas de las columnas ( Activo, Tienda, ExistenciaTienda ) que no tengan si o no en sus celdas

        foreach ($sheetData as $i => $val) {
            // Si no somos la primer línea y si no hay informacion en categoria, nombre producto, precio venta e impuesto, no se crea el producto
            if ($i != 1 && trim($val['A']) != "" && trim($val['F']) != "") {
                if ($val['G'] != "") {
                    $grupo[] = trim($val['G']);
                }
                $nombre[] = trim($val['A']);
                $identificacion[] = trim($val['F']);
            } else {
                if (trim($val['A']) == "" && trim($val['F']) == "") {
                    $camposFaltantes[] = "Falta el Nombre comercial y el Numero de identifcación en la fila " . ($i);
                } else if (trim($val['A']) == "" && trim($val['F']) != "") {
                    $camposFaltantes[] = "Falta el Nombre comercial en la fila " . ($i);
                } else if (trim($val['A']) != "" && trim($val['F']) == "") {
                    $camposFaltantes[] = "Falta el Numero de identifcación en la fila " . ($i);
                }
            }
        }
        //echo 1;
        //var_dump($sheetData);
        //echo 1;
        // eliminamos elementos duplicados
        $grupo = array_unique($grupo);
        // ====================================================================================================
        // Consultamos si existen en DB las categorias, impuestos, unidades y proveedores, posteriormente
        // ====================================================================================================


        $masterResult = Array();


        // En estos array almacenaremos los datos que existen en db
        $nombreRes = [];
        $identificacionRes = [];
        // En estos array almacenaremos los datos que no existen en db
        $grupoRes = [];
        // En estos array almacenaremos los datos que estan repetidos en el excel
        $nombreRep = [];
        $identificacionRep = [];

        // Nombre
        //validar repetidos en el excel
        $res = array_diff($nombre, array_diff(array_unique($nombre), array_diff_assoc($nombre, array_unique($nombre))));
        foreach (array_unique($res) as $v) {
            array_push($nombreRep, "Duplicado $v en la fila: " . implode(', ', array_keys($res, $v)));
        }
        $nombre = array_unique($nombre);
        // Validar repetidos en base de datos
        foreach ($nombre as $val) {

            $val = trim($val);
            $query = $this->connection->query("SELECT id_cliente FROM clientes WHERE nombre_comercial = '$val' ");
            //echo "SELECT id FROM clientes WHERE nombre_comercial = '$val'";
            //var_dump($query);
            if ($query->num_rows() != 0)
                $nombreRes[] = $val;
        }

        // Identificacion
        //validar repetidos en el excel
        $res = array_diff($identificacion, array_diff(array_unique($identificacion), array_diff_assoc($identificacion, array_unique($identificacion))));
        foreach (array_unique($res) as $v) {
            array_push($identificacionRep, "Duplicado $v en la fila: " . implode(', ', array_keys($res, $v)));
        }
        $identificacion = array_unique($identificacion);
        //validar repetidos en db
        foreach ($identificacion as $val) {
            $val = trim($val);
            $query = $this->connection->query("SELECT id_cliente FROM clientes WHERE nif_cif = '$val' ");
            if ($query->num_rows() != 0)
                $identificacionRes[] = $val;
        }


        // Grupo
        foreach ($grupo as $val) {
            $val = trim($val);
            $query = $this->connection->query("SELECT id FROM grupo_clientes WHERE nombre = '$val' ");
            if ($query->num_rows() == 0)
                $grupoRes[] = $val;
        }

        // CODIGOS REPETIDOS EN EXCEL
        /* $codigosExcelRes = [];
          // Contamos los valores duplicados
          $contCodigos = array_count_values($codigosExcel);
          foreach( $contCodigos as $key => $val ){
          if( $val != 1 ){
          $codigosExcelRes[] = $key;
          }
          }
          /*
          // CODIGOS REPETIDOS EN DB
          $codigosDBRes = [];
          foreach( $codigosDB as $item ){

          $i = $item["i"];
          $val = trim( $item["val"] );

          $query = $this->connection->query(" SELECT codigo, nombre FROM producto WHERE codigo = '$val' LIMIT 1 ");
          if( $query->num_rows() != 0) {
          $tmpCod = array();
          $tmpCod["c"] = $val;
          $tmpCod["i"] = $i;
          $tmpCod["ex"] = $listaCodigosNombre2[$val];
          $tmpCod["db"] = $query->row()->nombre;
          $codigosDBRes[] = $tmpCod;

          }
          } */

        // ======================================================================================
        // Añadimos los resultados al arrayMaster y si añadimos los datos reales
        // ======================================================================================

        $listaErrores = [];
        $realData = [];

        //var_dump($camposFaltantes);
        //var_dump($identificacionRep);
        // Campos obligatorios
        // Campos Faltantes
        if (count($camposFaltantes) > 1) {
            $masterResult["faltantes"] = $camposFaltantes;
            $listaErrores[] = "faltantes";
        }
        //nombre
        if (count($nombreRes) > 0) {
            $masterResult["nombre"] = $nombreRes;
            $listaErrores[] = "nombre";
        }
        if (count($nombreRep) > 0) {
            $listaErrores[] = "nombreRep";
            $masterResult["nombreRep"] = $nombreRep;
        }
        //identificacion
        if (count($identificacionRes) > 0) {
            $masterResult["identificacion"] = $identificacionRes;
            $listaErrores[] = "identificacion";
        }
        if (count($identificacionRep) > 0) {
            $listaErrores[] = "identificacionRep";
            $masterResult["identificacionRep"] = $identificacionRep;
        }
        //grupo
        if (count($grupoRes) > 0) {
            $masterResult["grupo"] = $grupoRes;
            $listaErrores[] = "grupo";
            $query = $this->connection->query(" SELECT id AS 'k', nombre AS 'v' FROM grupo_clientes");
            $realData["grupo"] = $query->result_array();
        }

        // Codigos duplicados en excel
        /* $codigoExcelFinal = [];
          if( count( $codigosExcelRes ) > 0){

          // generamos los productos repetidos con su nombre
          foreach($codigosExcelRes as $val){
          foreach($listaCodigosNombre as $valFinal){
          if( $val == $valFinal["k"]){
          $tmpCod = array();
          $tmpCod["i"] = $valFinal["i"];
          $tmpCod["c"] = $valFinal["k"];
          $tmpCod["ex"] = $valFinal["v"];
          $codigoExcelFinal[]= $tmpCod;
          }
          }
          $codigoExcelFinal[]= array("c"=>"-","ex"=>"-");
          }

          $listaErrores[] = "codigosExcel";
          $masterResult["codigosExcel"] = $codigoExcelFinal;
          } */


        // codigos duplicados en DB
        /* if( count( $codigosDBRes ) > 0){ 
          $masterResult["codigosDB"] = $codigosDBRes;
          $listaErrores[] = "codigosDB";
          } */


        //==============================================================
        // Compilamos resultado
        //==============================================================

        $resultado = array();

        $resultado["errores"] = $listaErrores;
        $resultado["objErrores"] = $masterResult;
        $resultado["realData"] = $realData;

        return $resultado;
    }

    public function agregarGrupo($nombre) {
        $id = $this->connection->insert('grupo_clientes', array(
            "nombre" => $nombre
        ));

        return $id;
    }

    public function importarExcel($sheetData) {
        set_time_limit(180);
        // PRIMERO HACEMOS TRIM A CADA CELDA PARA EVITAR MUCHOS ERRORES !!!!
        $fila = array("");
        $usar = false;
        foreach ($sheetData as $i => $row) {
            foreach ($row as $letra => $val) {
                $sheetData[$i][$letra] = trim($val);
                if (count(trim($sheetData[$i][$letra])) != 0) {
                    $usar = true;
                }
                $fila[] = $usar;
            }
        }

        $errores = array();
        $camposFaltantes = array();
        $data = array();

        foreach ($sheetData as $i => $val) {
            if ($fila[$i]) {

                // Si no somos la primer línea y si no hay informacion en categoria, nombre producto, precio venta e impuesto, no se crea el producto
                if ($i != 1 && trim($val['A']) != "" && trim($val['F']) != "") {
                    if (trim($val['G']) != "") {
                        $grupo = $this->connection->get_where("grupo_clientes", array("nombre" => trim($val['G'])))->row();
                        //var_dump($grupo);
                        if (!isset($grupo->id)) {
                            $errores[] = "El grupo '" . $val['G'] . "' no ha sido creado, corrija la fila $i o agregue el grupo de cliente";
                        }
                    }
                    //el nombre comercial ya existe
                   /* $nombre = $this->connection->get_where("clientes", array("nombre_comercial" => trim($val['A'])))->row();
                    if (count($nombre) != 0) {
                        $errores[] = "El Nombre Comercial '" . trim($val['A']) . "' ya existe en la base de datos corrija la fila $i";
                    }*/
                    //el numero de identificacion ya existe
                    $nif_cif = $this->connection->get_where("clientes", array("nif_cif" => trim($val['F'])))->row();
                    if (count($nif_cif) > 0) {
                        $errores[] = "El No de identificacion '" . trim($val['F']) . "' ya existe en la base de datos corrija la fila $i";
                    }

                    $data[] = array(
                        "nombre_comercial" => trim($val['A']),
                        "pais" => trim($val['B']),
                        "provincia" => trim($val['C']),
                        "razon_social" => trim($val['D']),
                        "tipo_identificacion" => trim($val['E']),
                        "nif_cif" => trim($val['F']),
                        "grupo_clientes_id" => trim($val['G']),
                        "contacto" => trim($val['H']),
                        "pagina_web" => trim($val['I']),
                        "email" => trim($val['J']),
                        "poblacion" => trim($val['K']),
                        "direccion" => trim($val['L']),
                        "cp" => trim($val['M']),
                        "telefono" => trim($val['N']),
                        "movil" => trim($val['O']),
                        "fax" => trim($val['P']),
                        "tipo_empresa" => trim($val['Q']),
                        "entidad_bancaria" => trim($val['R']),
                        "numero_cuenta" => trim($val['S']),
                        "observaciones" => trim($val['T']),
                    );
                } else {
                    if (trim($val['A']) == "" && trim($val['F']) == "") {
                        $camposFaltantes[] = "Falta el Nombre comercial y el Numero de identifcación en la fila " . ($i);
                    } else if (trim($val['A']) == "" && trim($val['F']) != "") {
                        $camposFaltantes[] = "Falta el Nombre comercial en la fila " . ($i);
                    } else if (trim($val['A']) != "" && trim($val['F']) == "") {
                        $camposFaltantes[] = "Falta el Numero de identifcación en la fila " . ($i);
                    }
                }
            }
        }

        if (empty($camposFaltantes) && empty($errores)) {
            $cuantos = 0;
            $sin_grupo = $this->connection->get_where("grupo_clientes", array("nombre" => $val['G']))->row();
            //var_dump($data);
            if (count($sin_grupo) == 0) {
                $this->connection->insert("grupo_clientes", array("id" => 1, "nombre" => "sin_grupo"));
            }
            foreach ($data as $i => $val) {
                if ($i != 1) {
                    if ($val['grupo_clientes_id'] != "") {
                        $grupo = $this->connection->get_where("grupo_clientes", array("nombre" => $val['grupo_clientes_id']))->row();
                        if (count($grupo) != 0) {
                            $val['grupo_clientes_id'] = $grupo->id;
                        }
                    } else {
                        $val['grupo_clientes_id'] = 1;
                    }

                    /* $data = array(
                      "nombre_comercial" => $val['A'],
                      "pais" => $val['B'],
                      "provincia" => $val['C'],
                      "razon_social" => $val['D'],
                      "tipo_identificacion" => $val['E'],
                      "nif_cif" => $val['F'],
                      "grupo_clientes_id" => $grupo->id,
                      "contacto" => $val['H'],
                      "pagina_web" => $val['I'],
                      "email" => $val['J'],
                      "poblacion" => $val['K'],
                      "direccion" => $val['L'],
                      "cp" => $val['M'],
                      "telefono" => $val['N'],
                      "movil" => $val['O'],
                      "fax" => $val['P'],
                      "tipo_empresa" => $val['Q'],
                      "entidad_bancaria" => $val['R'],
                      "numero_cuenta" => $val['S'],
                      "observaciones" => $val['T'],
                      ); */

                    if ($this->connection->insert("clientes", $val)) {
                        $cuantos++;
                    }
                }
            }
         
            return array("resp" => 1, "cuantos" => $cuantos);
        } else {
            return array("resp" => 0, "errores" => array("camposFaltantes" => $camposFaltantes, "errores" => $errores));
        }
    }

    public function actualizarTabla()
    {
        $sql = "SHOW COLUMNS FROM clientes LIKE 'onlineTienda'";
        $actualizada = $this->connection->query($sql)->result();
        
        if(count($actualizada) == 0)
        {
            $sql ="ALTER TABLE `clientes` 
            ADD COLUMN `onlineTienda` TINYINT(1) DEFAULT 0  NULL AFTER `grupo_clientes_id`,
            ADD COLUMN `password` VARCHAR(100) NULL AFTER `onlineTienda`;";
            
            $this->connection->query($sql);
        }
        // Fecha_nacimiento y genero
        $sql = "SHOW COLUMNS FROM clientes LIKE 'fecha_nacimiento'";
        $actualizada2 = $this->connection->query($sql)->result();
        
        if(count($actualizada2) == 0)
        {
            $sql ="ALTER TABLE `clientes` 
            ADD COLUMN `fecha_nacimiento` VARCHAR(100) NULL AFTER `password`,
            ADD COLUMN `genero` VARCHAR(100) NULL AFTER `fecha_nacimiento`;";
            $this->connection->query($sql);
        }
    }
}

?>