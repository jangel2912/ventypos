<?php 

    if (!function_exists('get_option')) {
        /**
         * Tomamos el valor de la imagen seleccionada para las mesas 
         * @return string
         */
        function get_option($option){

            $ci =& get_instance();
            
            $usuario = $ci->session->userdata('usuario');
            $clave = $ci->session->userdata('clave');
            $servidor = $ci->session->userdata('servidor');
            $base_dato = $ci->session->userdata('base_dato');

            $dns = "mysql://$usuario:$clave@$servidor/$base_dato";
            $connection = $ci->load->database($dns, true);    

            $connection->select('*');
            $connection->from('opciones');
            $connection->where('nombre_opcion',$option);
            $connection->limit('1');
            $result = $connection->get();

            if($result->num_rows() > 0):
                return $result->result()[0]->valor_opcion; 
            else:
                return NULL;
            endif;
            
        }
    }

    if (!function_exists('set_option')) {
        /**
         * Tomamos el valor de la imagen seleccionada para las mesas 
         * @return string
         */
        function set_option($option,$value){

            $ci =& get_instance();
            
            $usuario = $ci->session->userdata('usuario');
            $clave = $ci->session->userdata('clave');
            $servidor = $ci->session->userdata('servidor');
            $base_dato = $ci->session->userdata('base_dato');

            $dns = "mysql://$usuario:$clave@$servidor/$base_dato";
            $connection = $ci->load->database($dns, true);    

            $data = array(
                "valor_opcion" => $value
            );
            $connection->where('nombre_opcion',$option);
            $connection->update('opciones',$data);

            return $connection->affected_rows(); 
        }
    }

    if (!function_exists('valide_option')) {
        /**
         * Tomamos el valor de la imagen seleccionada para las mesas 
         * @return string
         */
        function valide_option($option,$value){

            $ci =& get_instance();
            
            $usuario = $ci->session->userdata('usuario');
            $clave = $ci->session->userdata('clave');
            $servidor = $ci->session->userdata('servidor');
            $base_dato = $ci->session->userdata('base_dato');

            $dns = "mysql://$usuario:$clave@$servidor/$base_dato";
            $connection = $ci->load->database($dns, true);    

            $data = $connection->get_where('opciones', array('nombre_opcion' => "$option"))->row_array();
            if (empty($data)) {
                $option_data = array(
                    'nombre_opcion' => $option,
                    'valor_opcion' => $value
                );
                $connection->insert("opciones",$option_data);
            }
        }
    }

    if (!function_exists('dd')) {
        /**
         * Imprimir
         * @param any $data
         * @return void
         */
        function dd($data)
        {
            print_r($data);
            die();
        }
    }

?>