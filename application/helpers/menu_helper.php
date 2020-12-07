<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//==========================================================================================
//   Edwin
//==========================================================================================


//solo proposito de debug
// pr = print_r()
if ( ! function_exists('pr')){   
    function pr($obj){        
        if(is_array($obj)){
            echo("<pre>");
                print_r($obj);
            echo("</pre>");
        }elseif(is_object($obj)){
            echo("<pre>");
                var_dump($obj);
            echo("</pre>");
        }else{
            echo("<pre>");
                echo $obj;
            echo("</pre>");
        }
        exit();
    }
}
     
    
if ( ! function_exists('getOffline')){
    
    function getOffline(){        
        $ci =& get_instance();  
        $usuario = $ci->session->userdata('usuario');
        $clave = $ci->session->userdata('clave');
        $servidor = $ci->session->userdata('servidor');
        $base_dato = $ci->session->userdata('base_dato');
        
        if ( !$base_dato == ""){
        
            $dns = "mysql://$usuario:$clave@$servidor/$base_dato";
            $dbConnection = $ci->load->database($dns, true);    
            
            $ci->load->model("dashboard_model"); 
            $ci->dashboard_model->initialize($dbConnection); 
            
            $queryResult = $ci->dashboard_model->queryOfflineAjax();            
            
            return $queryResult;    
            
        }

        
        
    }
    
}

if ( ! function_exists('getComanda')){      
            
    function getComanda(){              
        $ci =& get_instance();          
        $usuario = $ci->session->userdata('usuario');       
        $clave = $ci->session->userdata('clave');       
        $servidor = $ci->session->userdata('servidor');     
        $base_dato = $ci->session->userdata('base_dato');       
                
        if ( !$base_dato == ""){        
                
            $dns = "mysql://$usuario:$clave@$servidor/$base_dato";      
            $dbConnection = $ci->load->database($dns, true);            
                    
            $ci->load->model("miempresa_model");        
            $ci->miempresa_model->initialize($dbConnection);        
                    
            $result = array(        
                "comanda" => $ci->miempresa_model->obtenerOpcion("comanda"),        
                "push" => $ci->miempresa_model->obtenerOpcion("comanda_push")       
            );      
                    
                    
            return $result;     
                    
        }       
                
                
    }       
            
}

if ( ! function_exists('getName')){

    function getName(){
        
        $ci =& get_instance();
        $ci->db->query("select * from permisos where (sistema = 'Todos' OR (sistema = '$sistema' $permisos_condition))  AND parent_id = '' and goes_menu = 't' order by peso");

        $usuario = $ci->session->userdata('usuario');
        $clave = $ci->session->userdata('clave');
        $servidor = $ci->session->userdata('servidor');
        $base_dato = $ci->session->userdata('base_dato');
        
        if ( !$base_dato == ""){
        
            $dns = "mysql://$usuario:$clave@$servidor/$base_dato";
            $dbConnection = $ci->load->database($dns, true);    

            //Si No existe atributo lo crea, v2
            $uiQuery = $dbConnection->query(" SELECT * FROM opciones WHERE nombre_opcion = 'ui_version' ");        
            if( $uiQuery->num_rows() == 0 ) { $dbConnection->query(" INSERT INTO opciones (nombre_opcion, valor_opcion) VALUES ( 'ui_version', 'v2') "); }

            //
            $uiQuery = $dbConnection->query(" SELECT * FROM opciones WHERE nombre_opcion = 'ui_version' ");
            return $uiQuery->row()->valor_opcion;
            
        }else{
            return "";
        }        
    }

}

if ( ! function_exists('getUiVersion')){

    function getUiVersion(){
        
        $ci =& get_instance();

        $isAdmin = $ci->session->userdata('is_admin');
        
        $usuario = $ci->session->userdata('usuario');
        $clave = $ci->session->userdata('clave');
        $servidor = $ci->session->userdata('servidor');
        $base_dato = $ci->session->userdata('base_dato');
        $group_licencias=array(3,4);    
        $ci->load->library('ion_auth');
        if ( !$base_dato == "" && !$ci->ion_auth->in_group($group_licencias)){
        
            $dns = "mysql://$usuario:$clave@$servidor/$base_dato";
            $dbConnection = $ci->load->database($dns, true);    

            //Si No existe atributo lo crea, v2
            $uiQuery = $dbConnection->query(" SELECT * FROM opciones WHERE nombre_opcion = 'ui_version' ");        
            if( $uiQuery->num_rows() == 0 ) { $dbConnection->query(" INSERT INTO opciones (nombre_opcion, valor_opcion) VALUES ( 'ui_version', 'v2') "); }

            //
            $uiQuery = $dbConnection->query(" SELECT * FROM opciones WHERE nombre_opcion = 'ui_version' ");
            return $uiQuery->row()->valor_opcion;
            
        }else{
            return "v2";
        }
        
    }
    
}


if ( ! function_exists('getPermisos')){

    function getPermisos(){
        
        $ci =& get_instance();

        $isAdmin = $ci->session->userdata('is_admin');
        
        $usuario = $ci->session->userdata('usuario');
        $clave = $ci->session->userdata('clave');
        $servidor = $ci->session->userdata('servidor');
        $base_dato = $ci->session->userdata('base_dato');

        $dns = "mysql://$usuario:$clave@$servidor/$base_dato";
        $dbConnection = $ci->load->database($dns, true);    

        //=========================================================================

        $ci->load->model("dashboard_model",'dashboardModel'); 
        $ci->dashboardModel->initialize($dbConnection);        

        //=========================================================================        
                       
       
        $data = Array(
            "permisos" => $ci->dashboardModel->getPermisos(),
            "admin" => $isAdmin
        );
        
        return $data; 
        
    }
}

if ( ! function_exists('getNombreAlmacenCliente')){

    function getNombreAlmacenCliente(){

        $ci =& get_instance();


        $usuario = $ci->session->userdata('usuario');
        $clave = $ci->session->userdata('clave');
        $servidor = $ci->session->userdata('servidor');
        $base_dato = $ci->session->userdata('base_dato');

        $dns = "mysql://$usuario:$clave@$servidor/$base_dato";
        $dbConnection = $ci->load->database($dns, true);

        //=========================================================================

        $ci->load->model("dashboard_model",'dashboardModel'); 
        $ci->dashboardModel->initialize($dbConnection);        

        //=========================================================================

        $data = array();            
        $data['usuario'] = $ci->session->userdata('username'); 
        $data['empresa'] = $ci->dashboardModel->get_data_empresa();
        //var_dump($data['empresa']);        
        $nombre = $data['empresa']["data"]["nombre_almacen"]." - ".$data['usuario'];
        //$nombre = substr($data['empresa']["data"]["nombre_almacen"],0,7)." - ".substr($data['usuario'],0,7);
        /*$nom=explode(" ",trim($data['usuario']));       
        $alma=explode(" ",trim($data['empresa']["data"]["nombre_almacen"]));
        $nombre = $alma[0]." - ".$nom[0];*/

        echo $nombre;

    }
}

if ( ! function_exists('getNombreEmpresa')){

    function getNombreEmpresa(){

        $ci =& get_instance();


        $usuario = $ci->session->userdata('usuario');
        $clave = $ci->session->userdata('clave');
        $servidor = $ci->session->userdata('servidor');
        $base_dato = $ci->session->userdata('base_dato');

        $dns = "mysql://$usuario:$clave@$servidor/$base_dato";
        $dbConnection = $ci->load->database($dns, true);

        //=========================================================================

        $ci->load->model("dashboard_model",'dashboardModel'); 
        $ci->dashboardModel->initialize($dbConnection);        

        //=========================================================================

        $data = array();            
        $data['usuario'] = $ci->session->userdata('username'); 
        $data['empresa'] = $ci->dashboardModel->get_data_empresa();
        //var_dump($data['empresa']);
        $nombre = "<b class='mayuscula'>".$data['empresa']["data"]["nombre"]."</b>";

        echo $nombre;

    }
}

if ( ! function_exists('getLogoAlmacen')){

    function getLogoAlmacen(){

        $ci =& get_instance();

        $usuario = $ci->session->userdata('usuario');
        $clave = $ci->session->userdata('clave');
        $servidor = $ci->session->userdata('servidor');
        $base_dato = $ci->session->userdata('base_dato');

        $dns = "mysql://$usuario:$clave@$servidor/$base_dato";
        $dbConnection = $ci->load->database($dns, true);

        //=========================================================================

        $ci->load->model("dashboard_model",'dashboardModel'); 
        $ci->dashboardModel->initialize($dbConnection);        

        //=========================================================================

        $data = array();            
        $data['empresa'] = $ci->dashboardModel->get_data_empresa();

        $nombre = $data['empresa']["data"]["logotipo"];

        echo $nombre;

    }
}


//==========================================================================================
//   Fin Edwin
//==========================================================================================


if ( ! function_exists('get_top_menu'))

{

	function get_top_menu()

	{

            

            $top_menu = "";

            $ci =& get_instance();

            //$ci->db->cache_on();

            //$menu = $ci->db->query("select * from menu order by peso");

            $usuario = $ci->session->userdata('usuario');

            $clave = $ci->session->userdata('clave');

            $servidor = $ci->session->userdata('servidor');

            $base_dato = $ci->session->userdata('base_dato');



            $dns = "mysql://$usuario:$clave@$servidor/$base_dato";

            $dbConnection = $ci->load->database($dns, true);

            

            $ci->load->model("miempresa_model",'miempresa');

            $ci->miempresa->initialize($dbConnection);

            $sistema = $ci->miempresa->get_sistema_empresa();

          

            

            $rol = $ci->session->userdata('rol_id');

            

            $rol_permisos = $dbConnection->query("select id_permiso from permiso_rol where id_rol = '$rol'");

            $permisos_condition = "";

            $count = 0;

            foreach ($rol_permisos->result() as $value) {

                if($count++ > 0){

                    $permisos_condition .= ",{$value->id_permiso}";

                }

                else{

                    $permisos_condition .=  "AND id_permiso in({$value->id_permiso}";

                }

            }

            if(!empty($permisos_condition)){

                $permisos_condition .= ")";

            }

            

            

            $menu = $ci->db->query("select * from permisos where (sistema = 'Todos' OR (sistema = '$sistema' $permisos_condition))  AND parent_id = '' and goes_menu = 't' order by peso");

            if($ci->session->userdata('is_admin') == 't'){

                $menu = $ci->db->query("select * from permisos where (sistema = 'Todos' OR sistema = '$sistema')  AND parent_id = '' and goes_menu = 't' order by peso");

            }

            

           

           $i=1;

            foreach($menu->result() as $row){ 

                $top_menu .= "<li>";

                $top_menu .= "<a href='". ($row->has_child == 't' ? "#" : site_url($row->url)) ."' class='button ".$row->color."'>";

                if($row->has_child == 't'){

                    $top_menu .= "<div class='arrow'></div>";

                }

                $top_menu .= "<div class='icon'>";

                $top_menu .= "<span class='".$row->icono."'></span>";

                $top_menu .= "</div>";

                $top_menu .= "<div class='name'>".($row->has_child == 't' ? custom_lang($row->modulo, $row->modulo) : $row->nombre_permiso)."</div>";

                $top_menu .= "</a>";

                        if($row->has_child == 't'){

                            $top_menu .= "<ul class='sub'>";

                                $top_menu .= "<li><a href='".site_url($row->url)."'>".strtoupper(custom_lang($row->nombre_permiso, $row->nombre_permiso))."</a></li>";

                            //$top_menu .= "</ul>";

                        }

                        

                        $submenuQuery = "select * from permisos where parent_id = ".$row->id_permiso." AND (sistema = '$sistema' $permisos_condition) order by peso";

                        if($ci->session->userdata('is_admin') == 't'){

                            $submenuQuery = "select * from permisos where parent_id = ".$row->id_permiso." AND (sistema = '$sistema') order by peso";

                        }

                        

                        $submenu = $ci->db->query($submenuQuery);

                        if($submenu->num_rows() > 0)

                            { 

                                if($row->has_child == 'f'){

                                    $top_menu .= "<ul class='sub'>";

                                }

                                

                                foreach($submenu->result() as $links)

                                {

                                    $top_menu .= "<li><a href='".site_url($links->url)."'>".strtoupper(custom_lang($links->nombre_permiso, $links->nombre_permiso))."</a></li>";

                                }

                                

                                if($row->has_child == 'f'){

                                     $top_menu .= "</ul>";

                                }

                               

                           }

                           if($row->has_child == 't'){

                                $top_menu .= "</ul>";

                           }
						   

                $top_menu .= "</li>";


          $i++;   

            }

		  
		    if($i <= '5'){
			
                $top_menu .= "<li>";

                $top_menu .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";

                $top_menu .= "<li>";			
			
			}

            if($ci->session->userdata('is_admin') == 't'){

                $top_menu .= "<li>";

                $top_menu .= "<a href='".site_url('frontend/configuracion')."' class='button orange'>";

                $top_menu .= "<div class='icon'>";

                $top_menu .= "<span class='ico-cogs'></span>";

                $top_menu .= "</div>";

                $top_menu .= "<div class='name'>".custom_lang("Configuracion", "Configuracion")."</div>";

                $top_menu .= "</a>";
/*
                $top_menu .= "<li>";

                $top_menu .= "<a href='".site_url('frontend/supcripcion')."' class='button blue'>";

                $top_menu .= "<div class='icon'>";

                $top_menu .= "<span class='ico-money'></span>";

                $top_menu .= "</div>";

                $top_menu .= "<div class='name'>".custom_lang("Configuracion", "Suscripci&oacute;n")."</div>";

                $top_menu .= "</a>";
*/
            }

            

            //$ci->db->cache_off();

            echo $top_menu;

	}

}



if ( ! function_exists('get_sidebar_menu'))

{

    function get_sidebar_menu()

    {

        $sidebar_menu = "<ul class='navigation'>";

            $ci =& get_instance();

            //$ci->db->cache_on();

            $usuario = $ci->session->userdata('usuario');

            $clave = $ci->session->userdata('clave');

            $servidor = $ci->session->userdata('servidor');

            $base_dato = $ci->session->userdata('base_dato');



            $dns = "mysql://$usuario:$clave@$servidor/$base_dato";

            $dbConnection = $ci->load->database($dns, true);

            

            $ci->load->model("miempresa_model",'miempresa');

            $ci->miempresa->initialize($dbConnection);

            $sistema = $ci->miempresa->get_sistema_empresa();

          

            

            $rol = $ci->session->userdata('rol_id');

            $rol_permisos = $dbConnection->query("select id_permiso from permiso_rol where id_rol = '$rol'");

            $permisos_condition = "";

            $count = 0;

            foreach ($rol_permisos->result() as $value) {

                if($count++ > 0){

                    $permisos_condition .= ",{$value->id_permiso}";

                }

                else{

                    $permisos_condition .=  "AND id_permiso in({$value->id_permiso}";

                }

            }

            if(!empty($permisos_condition)){

                $permisos_condition .= ")";

            }

            //$menu = $ci->db->query("select * from permisos where (sistema = 'Todos' OR (sistema = '$sistema' $permisos_condition))  AND parent_id = '' and goes_menu = 't' order by peso");

           $menu = $ci->db->query("select * from permisos where (sistema = 'Todos' OR (sistema = '$sistema' $permisos_condition))  AND parent_id = '' and goes_menu = 't' order by peso");

            if($ci->session->userdata('is_admin') == 't'){

                $menu = $ci->db->query("select * from permisos where (sistema = 'Todos' OR sistema = '$sistema')  AND parent_id = '' and goes_menu = 't' order by peso");

            }

            

            foreach($menu->result() as $row){ 

                $sidebar_menu .= "<li>";

                $sidebar_menu .= "<a href='". ($row->has_child == 't' ? "#" : site_url($row->url)) ."' class='bl".$row->color."'>".($row->has_child == 't' ? custom_lang($row->modulo, $row->modulo) : $row->nombre_permiso)."</a>";

                        if($row->has_child=='t'){

                            $sidebar_menu .= "<div class='open'></div>";

                            $sidebar_menu .= "<ul>";

                                $sidebar_menu .= "<li><a href='".site_url($row->url)."'>".strtoupper(custom_lang($row->nombre_permiso, $row->nombre_permiso))."</a></li>";

                        }

                        

                        $submenuQuery = "select * from permisos where parent_id = ".$row->id_permiso." AND (sistema = '$sistema' $permisos_condition) order by peso";

                        if($ci->session->userdata('is_admin') == 't'){

                            $submenuQuery = "select * from permisos where parent_id = ".$row->id_permiso." AND (sistema = '$sistema') order by peso";

                        }

                        $submenu = $ci->db->query($submenuQuery);

                        if($submenu->num_rows() > 0){

                                if($row->has_child == 'f'){

                                    $sidebar_menu .= "<div class='open'></div>";

                                    $sidebar_menu .= "<ul>";

                                }

                                

                                foreach($submenu->result() as $links)

                                {

                                    $sidebar_menu .= "<li><a href='".site_url($links->url)."'>".strtoupper(custom_lang($links->nombre_permiso, $links->nombre_permiso))."</a></li>";

                                }

                                

                                if($row->has_child == 'f'){

                                    $sidebar_menu .= "</ul>";

                                }

                                

                         }

                           

                         if($row->has_child=='t'){

                            $sidebar_menu .= "</ul>";

                        }

                $sidebar_menu .= "</li>";

            }

            if($ci->session->userdata('is_admin') == 't'){

                $sidebar_menu .= "<li>";

                $sidebar_menu .= "<a href='". site_url('frontend/configuracion') ."' class='blyellow'>Configuracion</a>";

                $sidebar_menu .= "</li>";

            }

            $sidebar_menu .= "</ul>";

            //$ci->db->cache_off();

            echo $sidebar_menu;

    }

    

}



if ( ! function_exists('get_language_menu'))

{

    function get_language_menu()

    {

        $ci =& get_instance();

        $ci->db->cache_on();

        

        $ci->db->select('valor_opcion, mostrar_opcion');    

        $query = $ci->db->get_where('opciones' ,array('nombre_opcion' => 'idioma'));

        $idiomas = array();

        foreach ($query->result() as $value) {

            $idiomas[$value->valor_opcion] = $value->mostrar_opcion;

        }

         $ci->db->cache_off();

         $idioma_actual = $ci->session->userdata('idioma');

         

         echo form_dropdown("idioma", $idiomas, $idioma_actual, "id='change_languaje'");

    }

    

}

if(!function_exists('existeVentasOnline'))
{
    function existeVentasOnline()
    {
        $ci =& get_instance();  
        $usuario = $ci->session->userdata('usuario');
        $clave = $ci->session->userdata('clave');
        $servidor = $ci->session->userdata('servidor');
        $base_dato = $ci->session->userdata('base_dato');

        $dns = "mysql://$usuario:$clave@$servidor/$base_dato";
        $dbConnection = $ci->load->database($dns, true);
        
        $sql = "SHOW TABLES WHERE Tables_in_$base_dato = 'online_venta'";
        $result= $dbConnection->query($sql)->result();
        
        if(count($result) == 0)
        {
            return false;
        }
        return true;
    }
}

if(!function_exists('acceso_informe')){
    function acceso_informe($nombre_informe){
	    /*
        $ci = &get_instance();
        $data=array(
            'fecha_acceso' => date("y-m-d h:i:s"),
            'nombre_informe' => $nombre_informe,
            'usuario_consulta' => $ci->session->userdata('email'),
            'base_datos' => $ci->session->userdata('base_dato'),
            );

        $ci->db->insert('accesos_informes',$data);
	*/
    }
}


if ( ! function_exists('getOptions')){

    function getOptions(){
        
        $ci =& get_instance();

        $isAdmin = $ci->session->userdata('is_admin');
        
        $usuario = $ci->session->userdata('usuario');
        $clave = $ci->session->userdata('clave');
        $servidor = $ci->session->userdata('servidor');
        $base_dato = $ci->session->userdata('base_dato');

        $dns = "mysql://$usuario:$clave@$servidor/$base_dato";
        $dbConnection = $ci->load->database($dns, true);    

        //=========================================================================
        $ci->load->model("miempresa_model", 'mi_empresa');
        $ci->mi_empresa->initialize($dbConnection);
        //=========================================================================        
        $data = Array(
            "opciones" => $ci->mi_empresa->get_data_empresa()
        );
        
        return $ci->mi_empresa->get_data_empresa()['data']; 
    }
}


if ( ! function_exists('getGeneralOptions')){

    function getGeneralOptions($name_option){
        
        $ci =& get_instance();

        $isAdmin = $ci->session->userdata('is_admin');
        
        $usuario = $ci->session->userdata('usuario');
        $clave = $ci->session->userdata('clave');
        $servidor = $ci->session->userdata('servidor');
        $base_dato = $ci->session->userdata('base_dato');

        $dns = "mysql://$usuario:$clave@$servidor/$base_dato";
        $dbConnection = $ci->load->database($dns, true);    

        //=========================================================================
        $ci->dbConnection->select('*');
        $ci->dbConnection->from('opciones');
        $ci->dbConnection->where('nombre_opcion',$name_option);
        $ci->dbConnection->limit('1');
        $result = $ci->dbConnection->get();

        return $result->result()[0]; 
    }
}

?>
