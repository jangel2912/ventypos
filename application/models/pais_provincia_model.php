<?php

// Proyecto: Sistema Facturacion

// Version: 1.0

// Programador: Jorge Linares

// Framework: Codeigniter

// Clase: Provincias



class Pais_provincia_model extends CI_Model

{

	public function __construct()

            {

                parent::__construct();

                

            }

        

        public function get_pais(){

            $xml = simplexml_load_file("public/continents-countries-statesprovinces.xml");

            /*echo "<pre>";

                print_r($xml);

            echo "</pre>";

            die;*/

            $country = array();

            

            foreach ($xml->Children->SimpleGeoName as $value) {

                foreach ($value->Children->SimpleGeoName as $row){

                    $country[] = $row->Name;

                }

            }

            

            rsort($country);

            

            return $country;

        }

        

        public function get_provincia_from_pais($country){

            $xml = simplexml_load_file("public/continents-countries-statesprovinces.xml");

            $state = array();

            

            foreach ($xml->Children->SimpleGeoName as $value) {

                foreach ($value->Children->SimpleGeoName as $row){

                    if($row->Name == $country){

                        foreach ($row->Children->SimpleGeoName as $provincias) {

                            $state[] = $provincias->Name;

                        }

                        return $state;

                    } 

                }

            }

            return $state;

        }

}

?>