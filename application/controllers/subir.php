<?php

if (!defined('BASEPATH'))
    exit('No ingrese directamente es este script');

/**
 * Clase se encarga de hacer subidas al servidor de imagenes y archivos
 *
 * @package         App
 * @subpackage      Controller
 * @category        Controller
 * @author          Juan Carlos Castañeda Trujillo <juancarlos@vendty.com>
 * @license         GPL
 * @version         1.0
 */
class Subir extends MY_Controller {

    /**
     * __construct()
     * 
     * Constructor de la clase
     * 
     * @author Juan Carlos Castañeda Trujillo <juancarlos@vendty>
     * @access public
     * @param none
     * @return none
     */
    public function __construct() {
        
    }
    
    /**
     * __destruct()
     * 
     * Constructor de la clase
     * 
     * @author Juan Carlos Castañeda Trujillo <juancarlos@vendty>
     * @access public
     * @param none
     * @return none
     */
    public function __destruct() {
        
    }

    /**
     * index()
     * 
     * Función inicial de la clase
     * 
     * @author Juan Carlos Castañeda Trujillo <juancarlos@vendty>
     * @access public
     * @param none
     * @return none
     */
    public function index() {
        
    }

    /**
     * subirImagen()
     * 
     * Esta función se encarga se subir las imagenes al servidor
     * 
     * @author Juan Carlos Castañeda Trujillo <juancarlos@vendty>
     * @access public
     * @access public
     * @return String
     */
    public function subirImagen() {

        //creo la variable direcotrio
        $valorDirectorio = "cargas/" . $this->input->post('directorioImagen', TRUE);
        //valido si el directorio existe
        if (!file_exists($valorDirectorio)) {
            mkdir($valorDirectorio, 0755);
        }

        //defino la configuración del video
        $config['upload_path'] = $valorDirectorio;
        $config['allowed_types'] = 'gif|jpg|png|jpeg|JPG';
        $config['max_size'] = 0;
        $config['max_width'] = 0;
        $config['max_height'] = 0;

        //cargo la libreria
        $this->load->library('upload', $config);
        //verifico la carga.
        if (!$this->upload->do_upload($this->input->post('controlImagen', TRUE))) :
            echo $error = $this->upload->display_errors();
        else:
            $data = $this->upload->data();
            echo $data['file_name'];
        endif;
    }

    /**
     * @name        Subir Archivo
     * @abstract    Esta función se encarga se subir las archivos al servidor
     * @return      String
     */
    public function subirArchivo() {

        //defino la configuración del video
        $config['upload_path'] = "cargas/" . $this->input->post('directorioArchivo', TRUE);
        $config['allowed_types'] = 0;
        $config['max_size'] = 0;
        $config['max_width'] = 0;
        $config['max_height'] = 0;
        //cargo la libreria
        $this->load->library('upload', $config);
        //verifico la carga.
        if (!$this->upload->do_upload($this->input->post('controlArchivo', TRUE))) :
            echo json_encode($this->upload->display_errors(), JSON_FORCE_OBJECT);
        else:
            //creo la data upload
            $dataUpload = $this->upload->data();
            //creo la data retorno
            $dataRetorno = array(
                'nombre' => $dataUpload['file_name'],
                'peso' => $dataUpload['file_size'],
                'tipo' => $dataUpload['file_ext'],
            );
            echo json_encode($dataRetorno);
        endif;
    }

}
