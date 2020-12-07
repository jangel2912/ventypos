<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Images extends CI_Controller
{
    var $dbConnection;

    public function __construct()
    {

        parent::__construct();

        $usuario = $this->session->userdata('usuario');
        $clave = $this->session->userdata('clave');
        $servidor = $this->session->userdata('servidor');
        $base_dato = $this->session->userdata('base_dato');

        $dns = "mysql://$usuario:$clave@$servidor/$base_dato";
        $this->dbConnection = $this->load->database($dns, true);

        $this->load->model("productos_model", 'productos');
        $this->productos->initialize($this->dbConnection);
    }

    public function load_user_images() {

        try {
            $this->load->helper('directory');

            $search = $this->input->post('search', "");
            $base_dato = $this->session->userdata('base_dato');
            $carpeta = 'uploads/'.$base_dato.'/imagenes_productos';
            if (!file_exists($carpeta)) {
                mkdir($carpeta, 0777, true);
            }

            $map = directory_map($carpeta, 1);
            $response = array();
            $valid_extensions = array('jpg', 'jpeg', 'png', 'gif', 'svg', 'webp', 'JPG', 'JPEG', 'PNG', 'GIF', 'SVG', 'WEBP');
            foreach($map as $key => $image) {
                
                if(!is_array($image) && ((!empty($search) && strpos(strtoupper($image), strtoupper($search)) !== false) || empty($search))) {

                    $image_path = $carpeta . "/" .  $image;
                    $path_parts = pathinfo($image_path);

                    if(isset($path_parts['extension']) && in_array($path_parts['extension'], $valid_extensions)) {
                        $response[] = [
                            'image_name' => $image,
                            'image_path' => $image_path,
                            'image_url' => base_url($image_path)
                        ];
                    }
                }
            }

            $this->output->set_content_type('application/json')->set_output(json_encode($response));
        } catch (Exception $e) {
            $this->output->set_content_type('application/json')->set_output(json_encode($e->getMessage()));
        }
    }

    public function refactor_user_image_folder(){
        $this->load->helper('directory');
        $base_dato = $this->session->userdata('base_dato');
        $carpeta = 'uploads/'.$base_dato.'/imagenes_productos';
        if (!file_exists($carpeta)) {
            mkdir($carpeta, 0777, true);
        }

        $map = directory_map($carpeta, 1);
        $valid_extensions = array('jpg', 'jpeg', 'png', 'gif', 'svg', 'webp', 'JPG', 'JPEG', 'PNG', 'GIF', 'SVG', 'WEBP');
        
        foreach($map as $image) {
            if(!is_array($image)) {

                $image_path = $carpeta . "/" .  $image;
                $path_parts = pathinfo($image_path);

                if(isset($path_parts['extension']) && in_array($path_parts['extension'], $valid_extensions)) {
                    $clear_image_names = $this->_clean($image);
                    rename($carpeta . "/" . $image , $carpeta . "/" . $clear_image_names);
                }
            }
        }

        echo "Image refactor successfully";
    }

    function _clean($string) {
        $string = str_replace(' ', '_', $string); // Replaces all spaces with hyphens.
        return preg_replace('/[^A-Za-z0-9-_\.]/', '', $string); // Removes special chars.
    }

    public function plain_image_content() {
        $this->load->helper('directory');
        $base_dato = $this->session->userdata('base_dato');
        $carpeta = 'uploads/'.$base_dato.'/imagenes_productos';
        if (!file_exists($carpeta)) {
            mkdir($carpeta, 0777, true);
        }

        $map = directory_map($carpeta, 1);

        echo "<pre>";
            print_r($map);
        echo "</pre>";
        die;
    }

    public function update_product_image() {
        $product_id = $this->input->post("product_id");
        $image = $this->input->post("image");

        $this->dbConnection->where('id', $product_id);
        $this->dbConnection->update('producto', array(
            'imagen' => $image
        ));
        
        $get_referencia = $this->productos->get_by_id($product_id);
        if(isset($get_referencia['referencia_id']) && is_numeric($get_referencia['referencia_id'])) { 
            $this->dbConnection->where('id', $get_referencia['referencia_id']);
            $this->dbConnection->update('producto_referencia', array(
                'imagen' => $image
            ));
        }

        $this->session->set_flashdata('message', "Imágen actualizada correctamente.");
        $this->output->set_content_type('application/json')->set_output(json_encode(array('success' => true)));
    }

    public function get_user_images_zip() {
        $this->load->helper('url');
        // Load zip library
        $this->load->library('zip');
        $this->load->helper('directory');
        
        $base_dato = $this->session->userdata('base_dato');
        $carpeta = 'uploads/'.$base_dato.'/imagenes_productos';
        if (!file_exists($carpeta)) {
            mkdir($carpeta, 0777, true);
        }

        $map = directory_map($carpeta, 1);
        $valid_extensions = array('jpg', 'jpeg', 'png', 'gif', 'svg', 'webp', 'JPG', 'JPEG', 'PNG', 'GIF', 'SVG', 'WEBP');
        foreach($map as $key => $image) {
                
            if(!is_array($image)) {

                $image_path = $carpeta . "/" .  $image;
                $path_parts = pathinfo($image_path);

                if(in_array($path_parts['extension'], $valid_extensions)) {
                    $this->zip->read_file($image_path);
                }
            }
        }

        // Download
        $filename = "backup.zip";
        $this->zip->download($filename);
    }
}