<?php

// Proyecto: Sistema Facturacion
// Version: 1.0
// Programador: Jorge Linares
// Framework: Codeigniter
// Clase: Productos



class Categorias_model extends CI_Model {

    var $connection;

    // Constructor

    public function __construct() {

        parent::__construct();
    }

    public function initialize($connection) {

        $this->connection = $connection;
    }
    //NUmero de registros 
    public function num_rows(){
        $consulta = $this->connection->get('categoria');
        return $consulta->num_rows();
    }

    public function pagination($limit,$offset){
        $consulta = $this->connection->get('categoria', $limit, $offset);
        if ($consulta->num_rows() > 0) 
        {
        	
        	return $consulta->result_array();
 
        }
    }

    public function getAllCategoriesNotProduct(){
       
        $sql="SELECT * FROM categoria WHERE id NOT IN (SELECT DISTINCT categoria_id FROM producto) AND nombre !='GiftCard' and nombre !='General' ";
        $result=$this->connection->query($sql)->result();
              	
        return $result; 
       
    }

    public function getAll(){
        $this->connection->select('*');
        $this->connection->from('categoria');
        $this->connection->where('activo','1');
        $consulta = $this->connection->get();
        if ($consulta->num_rows() > 0) 
        {
        	
        	return $consulta->result_array();
 
        }
    }
    // Solo la creamos si no existe la categoria
    public function crear_categoria($nombre, $imagen = "") {

        $id = 0;

        $query = $this->connection->get_where('categoria', array('nombre' => $nombre));

        // Si no existe la creamos y retornamos el id
        if ($query->num_rows == 0) {

            $row = Array(
                "codigo" => 0,
                "nombre" => $nombre,
                "imagen" => $imagen,
                'tienda' => 0,
            );

            $this->connection->insert("categoria", $row);
            $id = $this->connection->insert_id();
        } else {
            // Si ya existe retornamos el id
            $id = $query->row()->id;
        }

        return $id;
    }

    public function get_total() {

        $query = $this->connection->query("SELECT count(*) as cantidad FROM  productos s Inner Join impuestos i on s.id_impuesto = i.id_impuesto");

        return $query->row()->cantidad;
    }

    public function get_combo_data() {

        $data = array();

        $query = $this->connection->query("SELECT * FROM categoria ORDER BY id ASC");

        return $query->result();
    }

    public function getAllCategoria() {
        
                $data = array();
        
                $query = $this->connection->query("SELECT * FROM categoria ORDER BY id ASC");
        
                return $query->result();
    }

    public function get_limit($offset) {

        $data = array();

        $query = $this->connection->query("SELECT activo,codigo,id,imagen, SUBSTRING( nombre,1,10 ) as nombre ,padre FROM categoria where activo = 1 ORDER BY id ASC limit $offset , 5");

        return $query->result();
    }

    public function get_all_categories() {

        $query = $this->connection->query("SELECT activo, codigo, id, imagen, nombre, padre FROM categoria WHERE activo = 1 ORDER BY id ASC");

        return $query->result();
    }

    public function get_all($offset) {

        $query = $this->connection->query("SELECT * FROM categorias s Inner Join impuestos i on s.id_impuesto = i.id_impuesto ORDER BY id_producto DESC limit $offset, 8");

        return $query->result();
    }

    public function get_ajax_data() {

        $aColumns = array('imagen', 'codigo', 'nombre', 'padre','tienda','activo','es_menu_principal_tienda' ,'id');

        $sIndexColumn = "id";

        $sTable = "categoria";

        $sLimit = "";

        if (isset($_GET['iDisplayStart']) && $_GET['iDisplayLength'] != '-1') {

            $sLimit = "LIMIT " . intval($_GET['iDisplayStart']) . ", " .
                    intval($_GET['iDisplayLength']);
        }

        $sOrder = "";

        if (isset($_GET['iSortCol_0'])) {

            $sOrder = "ORDER BY  ";

            for ($i = 0; $i < intval($_GET['iSortingCols']); $i++) {

                if ($_GET['bSortable_' . intval($_GET['iSortCol_' . $i])] == "true") {

                    $sOrder .= "`" . $aColumns[intval($_GET['iSortCol_' . $i])] . "` " .
                            ($_GET['sSortDir_' . $i] === 'asc' ? 'asc' : 'desc') . ", ";
                }
            }



            $sOrder = substr_replace($sOrder, "", -2);

            if ($sOrder == "ORDER BY") {

                $sOrder = "";
            }
        }

        $sWhere = "";

        if (isset($_GET['sSearch']) && $_GET['sSearch'] != "") {

            $sWhere = "WHERE (";

            for ($i = 0; $i < count($aColumns); $i++) {

                if (isset($_GET['bSearchable_' . $i]) && $_GET['bSearchable_' . $i] == "true") {

                    $sWhere .= "`" . $aColumns[$i] . "` LIKE '%" . mysql_real_escape_string($_GET['sSearch']) . "%' OR ";
                }
            }

            $sWhere = substr_replace($sWhere, "", -3);

            $sWhere .= ')';
        }

        /* Individual column filtering */

        for ($i = 0; $i < count($aColumns); $i++) {

            if (isset($_GET['bSearchable_' . $i]) && $_GET['bSearchable_' . $i] == "true" && $_GET['sSearch_' . $i] != '') {

                if ($sWhere == "") {

                    $sWhere = "WHERE ";
                } else {

                    $sWhere .= " AND ";
                }

                $sWhere .= "`" . $aColumns[$i] . "` LIKE '%" . mysql_real_escape_string($_GET['sSearch_' . $i]) . "%' ";
            }
        }



        $sQuery = "

		SELECT SQL_CALC_FOUND_ROWS `" . str_replace(" , ", " ", implode("`, `", $aColumns)) . "`

		FROM   $sTable s 

		$sWhere  

		$sOrder

		$sLimit

            ";


        $rResult = $this->connection->query($sQuery);

        /* Data set length after filtering */

        $sQuery = "

                    SELECT FOUND_ROWS() as cantidad

            ";

        $rResultFilterTotal = $this->connection->query($sQuery);

        //$aResultFilterTotal = $rResultFilterTotal->result_array();

        $iFilteredTotal = $rResultFilterTotal->row()->cantidad;



        $sQuery = "

		SELECT COUNT(`" . $sIndexColumn . "`) as cantidad

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





        foreach ($rResult->result_array() as $row) {

            $data = array();

            for ($i = 0; $i < count($aColumns); $i++) {
                if($aColumns[$i] == "imagen"){

                    if($row[$aColumns[$i]] == ""){
                        $row[$aColumns[$i]] = 'default.png';
                    }
                    
                    if(file_exists("uploads/".$row[$aColumns[$i]])){
                        $data[]= base_url("uploads/".$row[$aColumns[$i]]);
                    }else if(file_exists("uploads/".$this->session->userdata('base_dato')."/categorias_productos/".$row[$aColumns[$i]]) ){
                        $data[] = base_url("uploads/".$this->session->userdata('base_dato')."/categorias_productos/".$row[$aColumns[$i]]);
                    }else{
                        $data[] = base_url("uploads1/".$this->session->userdata('base_dato')."/categorias_productos/".$row[$aColumns[$i]]);
                    }
                }else if($aColumns[$i] == "padre")
                {
                    if(!is_null($row[$aColumns[$i]]))
                    {
                        $padre = $this->connection->get_where('categoria',array('id'=>$row[$aColumns[$i]]))->row();
                        if($padre){
                            $data[] = $padre->nombre;    
                        }
                        
                    }else
                    {
                        $data[] = "Ninguna";
                    }
                }else
                {
                    $data[] = $row[$aColumns[$i]];
                }
                
            }

            $output['aaData'][] = $data;
        }

        return $output;
    }

    public function get_by_name($name) {

        $this->connection->select("id");
        $this->connection->from("categoria");
        $this->connection->where("nombre",$name);
        $this->connection->limit(1);
        $result = $this->connection->get();
        if($result->num_rows() > 0):
                return $result->result()[0]->id;
        else:
                return null;
        endif;
    }

    public function get_term($q = '') {



        $query = $this->connection->query("SELECT id_producto as id, nombre, precio, i.nombre_impuesto, i.porciento, descripcion FROM productos s Inner Join impuestos i on s.id_impuesto = i.id_impuesto WHERE nombre LIKE '%$q%' LIMIT 0,30");

        return $query->result_array();
    }

    public function get_by_id($id = 0) {

        $query = $this->connection->query("SELECT * FROM  categoria WHERE id = '" . $id . "'");



        return $query->row_array();
    }

    public function get_combo_atributos_selected_data($id) {

        $data = array();

        $query = $this->connection->query("SELECT * FROM atributos_categoria where categoria_id = $id");

        foreach ($query->result() as $value) {

            $data[] = $value->atributo_id;
        }



        return $data;
    }

    public function add($data, $atributos = null) {


        $this->connection->insert("categoria", $data);

        $id = $this->connection->insert_id();

        $atributos_categoria = array();
        if(count($atributos)){
            foreach ($atributos as $value) {    
                $atributos_categoria[] = array(
                    'categoria_id' => $id,
                    'atributo_id' => $value
                );
            }
        }
        
        return $id;

        //ATRIBUTOS CATEGORIA
        //$this->connection->insert_batch("atributos_categoria",$atributos_categoria);
    }

    public function update($data) {

        /* $array_datos = array(

          "nombre"        => $this->input->post('nombre'),

          "codigo"        => $this->input->post('codigo'),

          "descripcion"  	=> $this->input->post('descripcion'),

          "precio"  	=> $this->input->post('precio'),

          "precio_compra" => $this->input->post('precio_compra'),

          "id_impuesto"  	=> $this->input->post('id_impuesto')

          ); */

        //Valido si la vategoria es 'Gift_Card', si lo es, actualizo su estado dependiendo de si esta activa o no,
        //ademas si es 'Gift_Card' actualizo el estado en formas de pago
        $id = $data['id'];
        $query = $this->connection->query("SELECT * FROM  categoria WHERE id = '" . $id . "'");
        if($query->row()->nombre == 'GiftCard'){
            $data_forma_pago = array(
                'activo' => $data['activo']
            );
            $this->connection->where('codigo', 'Gift_Card');
            $this->connection->update("forma_pago", $data_forma_pago);
        }
        $this->connection->where('id', $data['id']);
        $this->connection->update("categoria", $data);
    }

    public function delete($id) {
        $productos = $this->connection->get_where("producto", array("categoria_id" => $id))->result();
        $categoria = $this->connection->get_where("categoria", array("id" => $id, "codigo" => "0", "nombre" => "general"))->result();
        if (!empty($categoria)) {
            return "La categoria general no puede ser eliminada";
        }

        $padre =$this->connection->get_where("categoria",array('padre'=>$id))->result();
        if(!empty($padre)){
            return "La categoría no se puede eliminar, es padre de otras categorias";
        }

        if (empty($productos)) {
            $this->connection->where('id', $id);
            $this->connection->delete("categoria");
            return "Se ha eliminado correctamente";
        } else {
            return "No se puede eliminar la categoria ya que ha sido asociada a un producto";
        }
    }

    public function excel() {

        $this->connection->select("id_producto, nombre, descripcion, precio, nombre_impuesto, porciento");

        $this->connection->from("productos");

        $this->connection->join('impuestos', 'impuestos.id_impuesto = productos.id_impuesto');

        $query = $this->connection->get();

        return $query->result();
    }

    public function excel_exist($nombre, $precio) {



        $this->connection->where("nombre", $nombre);

        $this->connection->where("precio", $precio);

        $this->connection->from("productos");

        $this->connection->select("*");



        $query = $this->connection->get();

        if ($query->num_rows() > 0) {

            return true;
        } else {

            return false;
        }
    }

    public function excel_add($array_datos) {

        $query = "INSERT INTO `productos` (`nombre`, `descripcion`, `precio`, `id_impuesto`) VALUES ('" . $array_datos['nombre'] . "', '" . $array_datos['descripcion'] . "', " . $array_datos['precio'] . ", " . $array_datos['id_impuesto'] . ");";

        $this->connection->query($query);
    }

    public function create_or_get($categoria) {
        $nombre = strtolower($categoria);

        $q_categoria = $this->connection->select('id, nombre')
                ->from('categoria')
                ->where('TRIM(LOWER(nombre)) LIKE TRIM(LOWER("' . $nombre . '"))')
                ->get();

        if ($q_categoria->num_rows() > 0) {
            $cat = $q_categoria->row_array();
            return [
                'id' => $cat['id'],
                'descripcion' => $cat['nombre'],
                'nueva' => '0'
            ];
        } else {
            $array = [
                'nombre' => trim($categoria),
                'activo' => '1'
            ];

            $this->connection->insert('categoria', $array);
            $insert_id = $this->connection->insert_id();

            return [
                'id' => $insert_id,
                'nombre' => trim($nombre),
                'nueva' => '1'
            ];
        }
    }
    
    public function getSelect()
    {
        $categorias = $this->connection->get_where('categoria',array('activo'=>1))->result();
        $array = array("Null"=>"Ninguna");
        foreach($categorias as $c)
        {
            $array[$c->id] = $c->nombre;
        }
        return $array;
    }
    
    public function updateColumnCategoria(){
        // Esta funcion agrega el campo transaccion en donde se guardara la informacion de pagos realizados con datafono (Tarjeta)
        $sql = "SHOW COLUMNS FROM categoria LIKE 'tienda'";
        $existeCampo = $this->connection->query($sql)->result();
        if (count($existeCampo) == 0) {// Validamos si el campo no existe y los creamos
            $sql = "
                ALTER TABLE `categoria` 
                ADD COLUMN `tienda` int NULL DEFAULT 1 AFTER `activo`;
            ";
            $this->connection->query($sql);
        }
    }

    public function updateColumnMenuTienda(){
        $sql = "SHOW COLUMNS FROM categoria LIKE 'es_menu_principal_tienda'";
        $existeCampo = $this->connection->query($sql)->result();
        if (count($existeCampo) == 0) {// Validamos si el campo no existe y los creamos
            $sql = "
                ALTER TABLE `categoria` 
                ADD COLUMN `es_menu_principal_tienda` int NULL DEFAULT 0 AFTER `activo`;
            ";
            $this->connection->query($sql);
        }   
    }

    public function validateNombreyCodigo($id,$campo){
        $result = 0;
		$response = 0;
        $query =  "SELECT $campo FROM categoria WHERE $campo = '".$id."'";

        foreach($this->connection->query($query)->result() as $value) {
              
             $result = $value->$campo;           
        }
        

        if($result == 0){
            $response = 0;
        }
		else{
		   $response = 1;
		}
        return $result;
		
    }  

}

?>