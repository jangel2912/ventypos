
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of db_config
 *
 * @author Locho
 */
class Db_config extends CI_Controller {
    //put your code here
    public function __construct()
    {
        parent::__construct();
        $this->load->model('backend/db_config/db_config_model', 'dbconfig');
        $this->form_validation->set_error_delimiters('<p class="text-error">', '</p>');
    }
    


    public function index(){
               /*     if (!$this->ion_auth->logged_in())
	               	{
		              	redirect('auth', 'refresh');
		            }
                    else if(!$this->ion_auth->is_admin())
                    {
                        redirect('frontend/acceso_limitado');
                    }
                    
                    $message = $this->session->flashdata('message');
                    $data['message'] = array('msg' => !empty($message) ? $message : "", 'type' => 'success');
                    */
        $data["data"] = $this->dbconfig->get_all();
        $this->layout->show('backend/db_config/index.php', array('data' => $data));
    }


    
    public function nuevo(){
        $data = array('message' => "");
        if (!$this->ion_auth->logged_in())
		{
			redirect('auth', 'refresh');
		}
                else if(!$this->ion_auth->is_admin())
                    {
                        redirect('frontend/acceso_limitado');
                    }
                    
         $this->form_validation->set_rules('usuario', "Usuario", 'required|xss_clean');           
         $this->form_validation->set_rules('clave', "Clave", 'required');  
         $this->form_validation->set_rules('re-clave', 'Re-clave', 'required|matches[clave]');
         $this->form_validation->set_rules('servidor', 'Servidor', 'required');           
        
         if ($this->form_validation->run() == true)
         {
             $username = $this->input->post('usuario');
             $clave = $this->input->post('clave');
             $servidor = $this->input->post('servidor');
            
                $conn = @mysql_connect($servidor, $username, $clave);
                if(!$conn){
                     $data['message'] = array('msg' => "Error de conexi&oacute;n a la base de datos", 'type' => 'error');
                }
                else{
                   $uid = uniqid();
                   $database_name = "sima_db_$uid";
                   $sql = "CREATE DATABASE $database_name";
                   if(mysql_query($sql, $conn)){
                       mysql_select_db($database_name, $conn);
            $sql_clientes = "CREATE TABLE IF NOT EXISTS `clientes` (
                    `id_cliente` int(11) NOT NULL AUTO_INCREMENT,
                    `id_provincia` int(11) DEFAULT NULL,
                    `nombre_comercial` varchar(100) DEFAULT NULL,
                    `razon_social` varchar(100) DEFAULT NULL,
                    `nif_cif` varchar(15) DEFAULT NULL,
                    `contacto` varchar(100) DEFAULT NULL,
                    `pagina_web` varchar(150) DEFAULT NULL,
                    `email` varchar(80) DEFAULT NULL,
                    `poblacion` varchar(80) DEFAULT NULL,
                    `direccion` text,
                    `cp` varchar(5) CHARACTER SET latin1 DEFAULT NULL,
                    `telefono` varchar(10) CHARACTER SET latin1 DEFAULT NULL,
                    `movil` varchar(10) CHARACTER SET latin1 DEFAULT NULL,
                    `fax` varchar(10) CHARACTER SET latin1 DEFAULT NULL,
                    `tipo_empresa` varchar(80) DEFAULT NULL,
                    `entidad_bancaria` varchar(100) DEFAULT NULL,
                    `numero_cuenta` varchar(50) CHARACTER SET latin1 DEFAULT NULL,
                    `observaciones` text,
                    PRIMARY KEY (`id_cliente`)
                    ) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ; 
                    ";
            $sql_servicios = " CREATE TABLE IF NOT EXISTS `servicios` (
                    `id_servicio` int(11) NOT NULL AUTO_INCREMENT,
                    `nombre` varchar(100) NOT NULL,
                    `descripcion` text NOT NULL,
                    `precio` float(10,2) NOT NULL,
                    PRIMARY KEY (`id_servicio`)
                    ) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1";
            $sql_productos = "CREATE TABLE IF NOT EXISTS `productos` (
                    `id_producto` int(11) NOT NULL AUTO_INCREMENT,
                    `nombre` varchar(100) NOT NULL,
                    `descripcion` text NOT NULL,
                    `precio` float(10,2) NOT NULL,
                    PRIMARY KEY (`id_producto`)
                    ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;";
            $sql_provincias = "CREATE TABLE IF NOT EXISTS `provincias` (
                    `id_provincia` int(11) NOT NULL AUTO_INCREMENT,
                    `nombre_provincia` varchar(100) NOT NULL,
                    PRIMARY KEY (`id_provincia`)
                    ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=53 ;";
            
            $sql_proveedores = "CREATE TABLE IF NOT EXISTS `proveedores` (
                    `id_proveedor` int(11) NOT NULL AUTO_INCREMENT,
                    `id_provincia` int(11) DEFAULT NULL,
                    `nombre_comercial` varchar(100) DEFAULT NULL,
                    `razon_social` varchar(100) DEFAULT NULL,
                    `nif_cif` varchar(15) DEFAULT NULL,
                    `contacto` varchar(100) DEFAULT NULL,
                    `pagina_web` varchar(150) DEFAULT NULL,
                    `email` varchar(80) DEFAULT NULL,
                    `poblacion` varchar(80) DEFAULT NULL,
                    `direccion` text,
                    `cp` varchar(5) CHARACTER SET latin1 DEFAULT NULL,
                    `telefono` varchar(10) CHARACTER SET latin1 DEFAULT NULL,
                    `movil` varchar(10) CHARACTER SET latin1 DEFAULT NULL,
                    `fax` varchar(10) CHARACTER SET latin1 DEFAULT NULL,
                    `tipo_empresa` varchar(80) DEFAULT NULL,
                    `entidad_bancaria` varchar(100) DEFAULT NULL,
                    `numero_cuenta` varchar(50) CHARACTER SET latin1 DEFAULT NULL,
                    `observaciones` text,
                    PRIMARY KEY (`id_proveedor`)
                    ) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
            
            $sql_proformas = "CREATE TABLE IF NOT EXISTS `proformas` (
                    `id_proforma` int(11) NOT NULL AUTO_INCREMENT,
                    `id_cliente` int(11) NOT NULL,
                    `numero` varchar(10) CHARACTER SET latin1 NOT NULL,
                    `monto` float(10,2) NOT NULL,
                    `fecha` date NOT NULL,
                    `estado` int(1) NOT NULL,
                    PRIMARY KEY (`id_proforma`)
                    ) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
            
            $sql_proformas_detalles = "CREATE TABLE IF NOT EXISTS `proformas_detalles` (
                    `id_proforma_detalle` int(11) NOT NULL AUTO_INCREMENT,
                    `id_proforma` int(11) NOT NULL,
                    `descripcion` text NOT NULL,
                    `precio` float(10,2) NOT NULL,
                    `cantidad` int(11) NOT NULL,
                    PRIMARY KEY (`id_proforma_detalle`)
                    ) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ; ";
            
            $sql_presupuestos = "CREATE TABLE IF NOT EXISTS `presupuestos` (
                    `id_presupuesto` int(11) NOT NULL AUTO_INCREMENT,
                    `id_cliente` int(11) NOT NULL,
                    `numero` varchar(10) CHARACTER SET latin1 NOT NULL,
                    `monto` float(10,2) NOT NULL,
                    `fecha` date NOT NULL,
                    PRIMARY KEY (`id_presupuesto`)
                    ) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
            
            $sql_presupuestos_detalles = "CREATE TABLE IF NOT EXISTS `presupuestos_detalles` (
                    `id_presupuesto_detalle` int(11) NOT NULL AUTO_INCREMENT,
                    `id_presupuesto` int(11) NOT NULL,
                    `descripcion` text NOT NULL,
                    `precio` float(10,2) NOT NULL,
                    `cantidad` int(11) NOT NULL,
                    PRIMARY KEY (`id_presupuesto_detalle`)
                    ) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
            $sql_facturas = "CREATE TABLE IF NOT EXISTS `facturas` (
                    `id_factura` int(11) NOT NULL AUTO_INCREMENT,
                    `id_cliente` int(11) NOT NULL,
                    `numero` varchar(10) CHARACTER SET latin1 NOT NULL,
                    `monto` float(10,2) NOT NULL,
                    `fecha` date NOT NULL,
                    `estado` int(1) NOT NULL,
                    PRIMARY KEY (`id_factura`)
                    ) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
            $sql_facturas_detalles = "CREATE TABLE IF NOT EXISTS `facturas_detalles` (
                    `id_factura_detalle` int(11) NOT NULL AUTO_INCREMENT,
                    `id_factura` int(11) NOT NULL,
                    `descripcion` text NOT NULL,
                    `precio` float(10,2) NOT NULL,
                    `cantidad` int(11) NOT NULL,
                    PRIMARY KEY (`id_factura_detalle`)
                    ) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
            
                       mysql_query($sql_clientes, $conn);
                       mysql_query($sql_servicios, $conn);
                       mysql_query($sql_productos, $conn);
                       mysql_query($sql_provincias, $conn);
                       mysql_query($sql_proveedores, $conn);
                       mysql_query($sql_proformas, $conn);
                       mysql_query($sql_proformas_detalles, $conn);
                       mysql_query($sql_facturas, $conn);
                       mysql_query($sql_facturas_detalles, $conn);
                       mysql_query($sql_presupuestos, $conn);
                       mysql_query($sql_presupuestos_detalles, $conn);
                       
                       @mysql_close($conn);
                       
                       
                       $this->dbconfig->add(array('servidor' =>$servidor, 'base_dato' => $database_name, 'usuario' => $username, 'clave' => $clave, 'fecha' => date("Y-m-d")));
                       
                       $data['message'] = array('msg' => "Base de datos creada correctamente", 'type' => 'success');
                   }
                   else{
                      $data['message'] = array('msg' => "Error al crear la base de datos", 'type' => 'error');
                   }

                }
            }
         
            //$dbNew = $this->load->database($dsn, TRUE);
            //$dsn = "mysql://$username:$clave@$servidor/$base_datos";
            //$dbNew->close();
         
        $this->layout->show('backend/db_config/nuevo.php', array('data' => $data));
    }
    
    public function eliminar($id){
        
        if (!$this->ion_auth->logged_in())
		{
			redirect('auth', 'refresh');
		}
                else if(!$this->ion_auth->is_admin())
                    {
                        redirect('frontend/acceso_limitado');
                    }
        $this->dbconfig->delete($id);
        $this->session->set_flashdata('message', 'Base de datos eliminada correctamente');
        
        redirect("backend/db_config/index");
    }
}
?>