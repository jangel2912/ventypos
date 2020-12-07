<?php 
class Tamanos_productos_model extends CI_Model {

    var $connection;

    public function __construct() {

        parent::__construct();

    }

    public function initialize($connection) {

        $this->connection = $connection;

    }

    public function agregar_tamano($data){
        $this->connection->insert('tamanos_productos',$data);
        return $this->connection->insert_id();
    }

    public function insertar_categorias_tamanos($data){
        $this->connection->insert('tamanos_productos_posee_categoria',$data);
    }

    public function get_un_tamano($where){
        $this->connection->where($where);
        $query = $this->connection->get('tamanos_productos');
        return $query->result();
    }

    public function get_categoria_tamanos($where){
        $this->connection->where($where);
        $this->connection->select('tamanos_productos_posee_categoria.categoria_id,tamanos_productos.idtamanos_productos,tamanos_productos.descripcion_tamano,tamanos_productos.nombre_tamano');
        $this->connection->from('tamanos_productos_posee_categoria');
        $this->connection->join('tamanos_productos','tamanos_productos_posee_categoria.idtamanos_productos=tamanos_productos.idtamanos_productos');
        $query= $this->connection->get();
        return $query->result();
    }

    public function actualizar_tamano($where,$data){
        $this->connection->where($where);
        return $this->connection->update('tamanos_productos',$data);
    }

    public function eliminar_categorias_tamanos($where){
        $this->connection->where($where);
        return $this->connection->delete('tamanos_productos_posee_categoria');
    }

    public function crear_tablas_tamanos(){
    	$instruccion_sql = "CREATE TABLE IF NOT EXISTS `tamanos_productos` (
							  `idtamanos_productos` INT NOT NULL AUTO_INCREMENT COMMENT 'Identificador unico de cada registro',
							  `creado_por` INT NULL COMMENT 'el id del usuario que creo el registro',
							  `fecha_creacion` DATETIME NULL COMMENT 'la fecha en que se crea el registro',
							  `modificado_por` INT NULL COMMENT 'id del ultimo usuario que modifico el registro',
							  `fecha_modificacion` DATETIME NULL COMMENT 'la ultima fecha de modificacion del registro',
							  `nombre_tamano` VARCHAR(45) NULL COMMENT 'Nombre que se mostrara al usuario en el sistema',
							  `descripcion_tamano` VARCHAR(1000) NULL COMMENT 'una descripcion adicional por ejemplo para especificar en que se usa esta tamano',
							  PRIMARY KEY (`idtamanos_productos`))
							ENGINE = INNODB";
		$this->connection->query($instruccion_sql);

		$instruccion_sql = "CREATE TABLE IF NOT EXISTS `tamanos_productos_posee_categoria` (
                          `idtamanos_productos` INT NOT NULL,
                          `categoria_id` INT(11) NOT NULL,
                          PRIMARY KEY (`idtamanos_productos`, `categoria_id`),
                          INDEX `fk_tamanos_productos_has_categoria_categoria1_idx` (`categoria_id` ASC),
                          INDEX `fk_tamanos_productos_has_categoria_tamanos_productos1_idx` (`idtamanos_productos` ASC),
                          CONSTRAINT `fk_tamanos_productos_has_categoria_tamanos_productos1`
                            FOREIGN KEY (`idtamanos_productos`)
                            REFERENCES `tamanos_productos` (`idtamanos_productos`)
                            ON DELETE NO ACTION
                            ON UPDATE NO ACTION,
                          CONSTRAINT `fk_tamanos_productos_has_categoria_categoria1`
                            FOREIGN KEY (`categoria_id`)
                            REFERENCES `categoria` (`id`)
                            ON DELETE NO ACTION
                            ON UPDATE NO ACTION)
                        ENGINE = InnoDB";
		$this->connection->query($instruccion_sql);					
    }

    public function get_ajax_data() {

        $aColumns = array('tamanos_productos.idtamanos_productos', 'nombre_tamano', 'descripcion_tamano');

        $sIndexColumn = "idtamanos_productos";

        $sTable = "tamanos_productos";

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

                    $sOrder .=  $aColumns[intval($_GET['iSortCol_' . $i])] ." ".
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

                    $sWhere .=  $aColumns[$i] . " LIKE '%" . mysql_real_escape_string($_GET['sSearch']) . "%' OR ";
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

                $sWhere .= $aColumns[$i] . " LIKE '%" . mysql_real_escape_string($_GET['sSearch_' . $i]) . "%' ";
            }
        }



        $sQuery = "

        SELECT SQL_CALC_FOUND_ROWS " . str_replace(" , ", " ", implode(",", $aColumns)) . ", GROUP_CONCAT(categoria.nombre SEPARATOR ', ') nombre_categoria

        FROM   $sTable  
        left JOIN tamanos_productos_posee_categoria on (tamanos_productos.idtamanos_productos=tamanos_productos_posee_categoria.idtamanos_productos)
        left join categoria on (tamanos_productos_posee_categoria.categoria_id=categoria.id)
        $sWhere   
		GROUP BY tamanos_productos.idtamanos_productos 
        $sOrder

        $sLimit

            ";

       // echo $sQuery;    

        $rResult = $this->connection->query($sQuery);

        /* Data set length after filtering */

        $sQuery = "SELECT FOUND_ROWS() as cantidad ";

        $rResultFilterTotal = $this->connection->query($sQuery);

        //$aResultFilterTotal = $rResultFilterTotal->result_array();

        $iFilteredTotal = $rResultFilterTotal->row()->cantidad;

        $sQuery = "SELECT COUNT(" . $sIndexColumn . ") as cantidad FROM   $sTable as s";     

        $rResultTotal = $this->connection->query($sQuery);

        $iTotal = $rResultTotal->row()->cantidad;

        $output = array(
            "sEcho" => intval($_GET['sEcho']),
            "iTotalRecords" => $iTotal,
            "iTotalDisplayRecords" => $iFilteredTotal,
            "aaData" => array()
        );

        foreach ($rResult->result() as $row) {

            $data = array(
                        $row->idtamanos_productos,
                        $row->nombre_tamano,
                        $row->descripcion_tamano,
                      	$row->nombre_categoria,
                      	$row->idtamanos_productos,
                    );

            $output['aaData'][] = $data;
        }

        return $output;
    }

}

?>