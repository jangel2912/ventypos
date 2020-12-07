<?php

class New_count_model extends CI_Model {

    var $connection;

    // Constructor

    public function __construct() {

        parent::__construct();
    }

    public function initialize($connection) {

        $this->connection = $connection;
    }

    public function getUsuarioEstado() {

        // 3 usuario nuevo
        // 2 usuario prueba
        // 1 usuario pago

        $idUser = $this->session->userdata('user_id');

        $sql = "
            SELECT  db_config.estado,db_config.fecha
            FROM users
            INNER JOIN db_config ON users.db_config_id = db_config.id
            WHERE users.id = $idUser
        ";

        $query = $this->db->query($sql);
        $estado = $query->row()->estado;
        $fecha = $query->row()->fecha;

        if ($estado == "4") {
            $idUser = $this->session->userdata('user_id');
            $this->setUsuarioEstado($idUser, "3");
        }

        $data = array(
            "estado" => $estado,
            "fecha" => $fecha
        );

        return $data;
    }

    public function setUsuarioEstado($idUser, $estado) {

        // 3 usuario nuevo
        // 2 usuario prueba
        // 1 usuario pago


        $sql = "
            SELECT db_config_id FROM users WHERE id = $idUser
        ";
        $idDb = $this->db->query($sql)->row()->db_config_id;

        $sql = "
            UPDATE db_config
            SET estado = $estado
            WHERE id = $idDb
        ";

        $this->db->query($sql);
    }

    public function setNewUserData($data) {


        $nombre = $data["nombre"];
        $nit = $data["nit"];
        $factura = $data["factura"];
        $logo = $data["logo"];


        $sql = "
            UPDATE opciones
            SET valor_opcion = '$nombre'
            WHERE nombre_opcion = 'nombre_empresa';
        ";
        $this->connection->query($sql);

        $sql = "
            UPDATE opciones
            SET valor_opcion = '$logo'
            WHERE nombre_opcion = 'logotipo_empresa';
        ";
        $this->connection->query($sql);

        $sql = "
            UPDATE opciones
            SET valor_opcion = '$nit'
            WHERE nombre_opcion = 'nit';
        ";
        $this->connection->query($sql);

        $sql = "
            UPDATE opciones
            SET valor_opcion = '$factura'
            WHERE nombre_opcion = 'plantilla_empresa';
        ";
        $this->connection->query($sql);


        $idUser = $this->session->userdata('user_id');
        $this->setUsuarioEstado($idUser, "2");
    }

    public function setDataBase($database) {
        // Se carga la informacion de categorias y productos
        
        switch ($database) {
            case 1:// Moda
                $sql1 = "INSERT INTO categoria (SELECT * FROM  vendty2_db_5457_fashi2016.categoria)";
                $sql2 = "INSERT INTO producto  (SELECT * FROM  vendty2_db_5457_fashi2016.producto)";
                $sql3 = "INSERT INTO stock_actual  (SELECT * FROM  vendty2_db_5457_fashi2016.stock_actual)";
                break;
            case 2:// Comidas
                $sql1 = "INSERT INTO categoria (SELECT * FROM  vendty2_db_5459_burge2016.categoria)";
                $sql2 = "INSERT INTO producto  (SELECT * FROM  vendty2_db_5459_burge2016.producto)";
                $sql3 = "INSERT INTO stock_actual  (SELECT * FROM  vendty2_db_5459_burge2016.stock_actual)";
                break;
            case 3:// Mini Mercados
                $sql1 = "INSERT INTO categoria (SELECT * FROM  vendty2_db_5460_marke2016.categoria)";
                $sql2 = "INSERT INTO producto  (SELECT * FROM  vendty2_db_5460_marke2016.producto)";
                $sql3 = "INSERT INTO stock_actual  (SELECT * FROM  vendty2_db_5460_marke2016.stock_actual)";
                break;
            case 4: // Droguerias
                $sql1 = "INSERT INTO categoria (SELECT * FROM  vendty2_db_5460_marke2016.categoria)";
                $sql2 = "INSERT INTO producto  (SELECT * FROM  vendty2_db_5460_marke2016.producto)";
                $sql3 = "INSERT INTO stock_actual  (SELECT * FROM  vendty2_db_5460_marke2016.stock_actual)";
                break;
            case 5: // Retail General
                $sql1 = "INSERT INTO categoria (SELECT * FROM  vendty2_db_5461_techn2016.categoria)";
                $sql2 = "INSERT INTO producto  (SELECT * FROM  vendty2_db_5461_techn2016.producto)";
                $sql3 = "INSERT INTO stock_actual  (SELECT * FROM  vendty2_db_5461_techn2016.stock_actual)";
                break;
            default:
                // No hace nada
                break;
        }

//              var_dump($sql1,$sql2);
        
        
        // Limpiamos la tabla categorias para realizar el insert -> se elimina en cascada producto y stock _actual
        if(isset($sql1) && isset($sql2) && isset($sql3) ){
            $this->connection->empty_table('categoria');
            $this->connection->query($sql1);
            $this->connection->query($sql2);
            $this->connection->query($sql3);
        }
        
    }

    public function setUserData($id, $nombre, $telefono) {

        $sql = "
            UPDATE users
            SET phone = $telefono, first_name = '$nombre'
            WHERE id = $id;
        ";

        $this->db->query($sql);
    }

    public function getUserData() {

        $sql = "
            SELECT * FROM users WHERE id = 1445
        ";

        return $this->db->query($sql)->result();
    }

}

?>