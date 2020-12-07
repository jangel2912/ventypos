<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 5.1.6 or newer
 *
 * @package		CodeIgniter
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2008 - 2011, EllisLab, Inc.
 * @license		http://codeigniter.com/user_guide/license.html
 * @link		http://codeigniter.com
 * @since		Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * CodeIgniter Application Controller Class
 *
 * This class object is the super class that every library in
 * CodeIgniter will be assigned to.
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Libraries
 * @author		ExpressionEngine Dev Team
 * @link		http://codeigniter.com/user_guide/general/controllers.html
 */

require_once APPPATH . 'libraries/convertloop/src/convertloop.php';
require_once APPPATH . 'libraries/convertloop/src/client.php';
require_once APPPATH . 'libraries/convertloop/src/HttpExecutor.php';
require_once APPPATH . 'libraries/convertloop/src/people.php';
require_once APPPATH . 'libraries/convertloop/src/EventLogs.php';

class CI_Controller {

	private static $instance;

	/**
	 * Constructor
	 */
	public function __construct()
	{
		self::$instance =& $this;
		
		// Assign all the class objects that were instantiated by the
		// bootstrap file (CodeIgniter.php) to local class variables
		// so that CI can run as one big super object.
		foreach (is_loaded() as $var => $class)
		{
			$this->$var =& load_class($class);
		}

		$this->load =& load_class('Loader', 'core');

		$this->load->initialize();
		
		log_message('debug', "Controller Class Initialized");
                
                //self::convertLog();
                
	}

	public static function &get_instance()
	{
		return self::$instance;
	}
        
        /* public function convertLog(){
            
            $estado = $this->session->userdata('bd_estado');
            if (isset($estado) && $estado > 1 && $this->input->server('REQUEST_METHOD') == 'GET'){
            
            $convertloop = new \ConvertLoop\ConvertLoop("f4c03103", "pkUU21crGXeEfVpDKZsTkVGJ", "v1"); 
            $person = array(
                "email" => $this->session->userdata('email')
            );
            $convertloop->people()->createOrUpdate($person); 
            $controller = str_replace('_','',$this->router->fetch_class()); // for controller
            $method = str_replace('_','',$this->router->fetch_method()); // for method
            $event = array(
                "name" => $controller.' '.$method,
                "person" => $person,
                "ocurred_at" => time()
            );
            $convertloop->eventLogs()->send($event);
            }

        } */ 
}
// END Controller class

/* End of file Controller.php */
/* Location: ./system/core/Controller.php */