<?php

class Report_model extends CI_Model {

    // Constructor

    public function __construct() {

        parent::__construct();
    }

    public function getDbId($mail) {
        
        //Consula sql
        $sql = "SELECT
                    id as idUser,
                    email,
                    db_config_id as idDb
                FROM users
                WHERE email
                LIKE '%$mail%'";
        
        // capturamos el iddb de un email
        $queryUser = $this->db->query($sql);

        // If result is not empty
        if ($queryUser->num_rows() > 0) {

            //Capturamos el id de la db en la primera fila del query
            $idDb = $queryUser->row()->idDb;
            
            //Consulta sql
            $sql = "SELECT
                        servidor as server,
                        base_dato as db
                    FROM db_config
                    WHERE id ='$idDb'";
            
            // capturamos la informacion de la db del email
            $queryDb = $this->db->query($sql);

            //Unimos los dos objetos de los query resultantes a un solo objeto            
            $resultObjectQuey = (object) array_merge((array) $queryUser->row(), (array) $queryDb->row());

            //Retonamos sin validar ya que hay un correo asociada a una db
            return $resultObjectQuey;
            
        } else {

            return false;

        }
    }    

}

?>