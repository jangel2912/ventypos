<?php 

    if (!function_exists('get_image_table')) {
        /**
         * Tomamos el valor de la imagen seleccionada para las mesas 
         * @return string
         */
        function get_image_table(){

            $ci =& get_instance();
            
            $usuario = $ci->session->userdata('usuario');
            $clave = $ci->session->userdata('clave');
            $servidor = $ci->session->userdata('servidor');
            $base_dato = $ci->session->userdata('base_dato');

            $dns = "mysql://$usuario:$clave@$servidor/$base_dato";
            $connection = $ci->load->database($dns, true);    

            $ci->connection->select('*');
            $ci->connection->from('opciones');
            $ci->connection->where('nombre_opcion','table_selected');
            $ci->connection->limit('1');
            $result = $ci->connection->get();

            return $result->result()[0]->valor_opcion; 
        }
    }

    if (!function_exists('get_image_templates_shop')) {
        /**
         * Tomamos el valor de la imagen seleccionada para las mesas 
         * @return string
         */
        function get_image_templates_shop(){

            $ci =& get_instance();

            $ci->db->select('nombre,ruta_img');
            $ci->db->from('plantillas');
            $ci->db->where('active = 1');
            $ci->db->order_by('id');
            $result = $ci->db->get();

            return $result->result(); 
        }
    }

?>