<?php

class Fixdb_model extends CI_Model
{

    public $connection;
    public $dbConnection;

    // Constructor

    public function __construct()
    {

        parent::__construct();
    }

    public function initialize($connection)
    {

        $this->connection = $connection;
    }

    public function fix()
    {

        $horaInicio = date("H:i:s");

        $sql = " SELECT id,servidor,base_dato AS db, TRIM(usuario) AS 'user', TRIM(clave) AS pass FROM db_config WHERE estado IN (1,2) ";
        $query = $this->db->query($sql)->result();
        //$query = $this->connection->query($sql)->result_array();

        echo "<style> td{padding:0px 10px;} </style>";
        echo "<table>";

        echo "<tr><td></td><td></td></tr><tr><td>Database </td><td> Estado </td> </tr><tr><td>----------------------------------</td><td>----------------------------------</td></tr>";
        $n = 0;

        foreach ($query as $i => $row) {

            $usuario = $row->user;
            $clave = $row->pass;
            $servidor = $row->servidor;
            $base_dato = $row->db;

            // Si hay un nombre de base de datos
            if ($base_dato != "") {

                // para saber si la base de datos existe
                $this->load->dbutil();

                // Si la base de datos existe
                if ($this->dbutil->database_exists($base_dato)) {

                    $dns = "mysql://$usuario:$clave@$servidor/$base_dato";
                    $dbConnection = $this->load->database($dns, true);

                    try {

                        $dbConnection->trans_begin();

                        $n = $n + 1;

                        //==============================
                        //   TABLA ventas_pago
                        //==============================
                        //Si la tabla existe
                        $tabla = "ventas_pago";
                        if ($dbConnection->table_exists($tabla)) {
                            $sql = "
                                    UPDATE ventas_pago SET
                                    forma_pago = REPLACE(REPLACE(forma_pago,'á','a'),'Á','A'),
                                    forma_pago = REPLACE(REPLACE(forma_pago,'é','e'),'É','E'),
                                    forma_pago = REPLACE(REPLACE(forma_pago,'í','i'),'Í','I'),
                                    forma_pago = REPLACE(REPLACE(forma_pago,'ó','o'),'Ó','O'),
                                    forma_pago = REPLACE(REPLACE(forma_pago,'ú','u'),'Ú','U');
                                ";
                            $dbConnection->query($sql);

                        } else {
                            echo "<tr><td>$base_dato </td><td> Tabla [$tabla] No existe!!</td> </tr>";
                        }

                        //==============================
                        //   TABLA pago_orden_compra
                        //==============================
                        //Si la tabla existe
                        $tabla = "pago_orden_compra";
                        if ($dbConnection->table_exists($tabla)) {

                            $sql = "
                                    UPDATE pago_orden_compra SET
                                    tipo = REPLACE(REPLACE(tipo,'á','a'),'Á','A'),
                                    tipo = REPLACE(REPLACE(tipo,'é','e'),'É','E'),
                                    tipo = REPLACE(REPLACE(tipo,'í','i'),'Í','I'),
                                    tipo = REPLACE(REPLACE(tipo,'ó','o'),'Ó','O'),
                                    tipo = REPLACE(REPLACE(tipo,'ú','u'),'Ú','U');
                                ";
                            $dbConnection->query($sql);

                        } else {
                            echo "<tr><td>$base_dato </td><td> Tabla [$tabla] No existe!!</td> </tr>";
                        }

                        //==============================
                        //   TABLA plan_separe_pagos
                        //==============================
                        //Si la tabla existe
                        $tabla = "plan_separe_pagos";
                        if ($dbConnection->table_exists($tabla)) {

                            $sql = "
                                    UPDATE plan_separe_pagos SET
                                    forma_pago = REPLACE(REPLACE(forma_pago,'á','a'),'Á','A'),
                                    forma_pago = REPLACE(REPLACE(forma_pago,'é','e'),'É','E'),
                                    forma_pago = REPLACE(REPLACE(forma_pago,'í','i'),'Í','I'),
                                    forma_pago = REPLACE(REPLACE(forma_pago,'ó','o'),'Ó','O'),
                                    forma_pago = REPLACE(REPLACE(forma_pago,'ú','u'),'Ú','U');
                                ";
                            $dbConnection->query($sql);

                        } else {
                            echo "<tr><td>$base_dato </td><td> Tabla [$tabla] No existe!!</td> </tr>";
                        }

                        //==============================
                        //   TABLA pago
                        //==============================
                        //Si la tabla existe
                        $tabla = "pago";
                        if ($dbConnection->table_exists($tabla)) {

                            $sql = "
                                    UPDATE pago SET
                                    tipo = REPLACE(REPLACE(tipo,'á','a'),'Á','A'),
                                    tipo = REPLACE(REPLACE(tipo,'é','e'),'É','E'),
                                    tipo = REPLACE(REPLACE(tipo,'í','i'),'Í','I'),
                                    tipo = REPLACE(REPLACE(tipo,'ó','o'),'Ó','O'),
                                    tipo = REPLACE(REPLACE(tipo,'ú','u'),'Ú','U');
                                ";
                            $dbConnection->query($sql);

                        } else {
                            echo "<tr><td>$base_dato </td><td> Tabla [$tabla] No existe!!</td> </tr>";
                        }

                        //==============================
                        //   TABLA movimientos_cierre_caja
                        //==============================
                        //Si la tabla existe
                        $tabla = "movimientos_cierre_caja";
                        if ($dbConnection->table_exists($tabla)) {

                            $sql = "
                                    UPDATE movimientos_cierre_caja SET
                                    forma_pago = REPLACE(REPLACE(forma_pago,'á','a'),'Á','A'),
                                    forma_pago = REPLACE(REPLACE(forma_pago,'é','e'),'É','E'),
                                    forma_pago = REPLACE(REPLACE(forma_pago,'í','i'),'Í','I'),
                                    forma_pago = REPLACE(REPLACE(forma_pago,'ó','o'),'Ó','O'),
                                    forma_pago = REPLACE(REPLACE(forma_pago,'ú','u'),'Ú','U');
                                ";
                            $dbConnection->query($sql);

                        } else {
                            echo "<tr><td>$base_dato </td><td> Tabla [$tabla] No existe!!</td> </tr>";
                        }

                        echo "<tr><td>$base_dato</td><td> ok</td> </tr>";

                        if ($dbConnection->trans_status() === false) {
                            $dbConnection->trans_rollback();
                        } else {
                            $dbConnection->trans_commit();
                        }

                    } catch (Exception $e) {
                        // $this->connection->trans_rollback();
                        print_r($e);
                        die;
                    }

                } else {
                    echo "<tr><td>$base_dato</td><td> DB no existe!!</td> </tr>";
                }

            }

        }

        $horaFin = date("H:i:s");

        echo "<tr><td>----------------------------------</td><td>----------------------------------</td></tr><tr><td>Hora Inicio </td><td> $horaInicio </td> </tr><tr><td></td><td></td></tr>";
        echo "<tr><td>----------------------------------</td><td>----------------------------------</td></tr><tr><td>Hora Fin </td><td> $horaFin </td> </tr><tr><td></td><td></td></tr>";
        echo "<tr><td>----------------------------------</td><td>----------------------------------</td></tr><tr><td>Total </td><td> $n </td> </tr><tr><td></td><td></td></tr>";

        echo "</table>";

    }

}
