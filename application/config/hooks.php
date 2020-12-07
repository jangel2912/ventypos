<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| Hooks
| -------------------------------------------------------------------------
| This file lets you define "hooks" to extend CI without hacking the core
| files.  Please see the user guide for info:
|
|	http://codeigniter.com/user_guide/general/hooks.html
|
*/
$hook['post_controller'] = array( 
    'class' => 'gestion_sesion_vendedor', // clase que controla la sesion 
    'function' => 'index', // metodo encargado de todo, dentro de la clase 
    'filename' => 'gestion_sesion_vendedor.php', // archivo a cargar 
    'filepath' => 'hooks' // carpeta donde se encuentra la clase 
);

$hook['post_controller_constructor'] = array( 
    'class' => 'gestion_sesion_vendedor', // clase que controla la sesion 
    'function' => 'cerrar_sesion_vendedor', // metodo encargado de todo, dentro de la clase 
    'filename' => 'gestion_sesion_vendedor.php', // archivo a cargar 
    'filepath' => 'hooks' // carpeta donde se encuentra la clase 
);


/* End of file hooks.php */
/* Location: ./application/config/hooks.php */