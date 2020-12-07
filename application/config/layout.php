<?php
/*
  |--------------------------------------------------------------------------
  | Layout: Templates.
  |--------------------------------------------------------------------------
  |
  | This array contains a list of templates that are available to the layout
  | library. The primary array key must be the name used to identify the
  | template, the value contains an array of CodeIgniter named views that
  | will be rendered in sequence.
  |
  | Use '-YIELD-' to load the primary view that is passed to the layout
  | library at run time.
  |
  | For example :
  |
  |	'my_template'	=>	array(
  |		'header_template',
  |		'-YIELD-',
  |		'footer_template'
  |	)
 */

// VERSON 2
$layout['templatesV2'] = array(
    'new_layout' => array(
        'layouts/new_layout',
        '-YIELD-',
        'layouts/newFooter',
    ),
    'member' => array(
        'layouts/v2HeaderMember',
        '-YIELD-',
        'layouts/v2Footer',
    ),
    'memberquickservice' => array(
        'layouts/HeaderMemberQuickService',
        '-YIELD-',
        'layouts/FooterMemberQuickService',
    ),
	'member2' => array(
        'layouts/v2HeaderMember2',
        '-YIELD-',
        'layouts/v2Footer',
    ),
	'dashboard' => array(
        'layouts/v2HeaderDashboard',
        '-YIELD-',
        'layouts/v2FooterDashboard',
    ),
	'ventas' => array(
        'layouts/v2HeaderVentas',
        '-YIELD-',
        'layouts/v2FooterVentas',
    ),
	'ventasOffline' => array(
        'layouts/v2HeaderVentasOffline',
        '-YIELD-',
        'layouts/v2FooterVentasOffline',
    ),
    'main'	=> array(
        'layouts/header',
        '-YIELD-',
        'layouts/footer',
    ),
    'excel' => array(
        'layouts/headerExcel',
        '-YIELD-',
        'layouts/footerLogin',
    ),
    'login' => array(
        'layouts/headerLogin',
        '-YIELD-',
        'layouts/footerLogin',
    ),
    'forgot' => array(
        'layouts/headerForgot',
        '-YIELD-',
        'layouts/footerLogin',
    ),
    'ajax'=> array(
        '-YIELD-',
    ),
    'administracion_vendty' => array(
        'layouts/header_administracion',
        '-YIELD-',
        'layouts/footer_administracion',
    ),
    'distribuidores_vendty' => array(
        'layouts/header_distribuidores',
        '-YIELD-',
        'layouts/footer_distribuidores',
    ),
);

// VERSON 1
$layout['templates'] = array(
	'main'	=> array(
        'layouts/header',
        '-YIELD-',
        'layouts/footer',
    ),
    'dashboard' => array(
        'layouts/headerMember',
        '-YIELD-',
        'layouts/footer',
    ),
    'member' => array(
        'layouts/headerMember',
        '-YIELD-',
        'layouts/footer',
    ),
    'ventas' => array(
        'layouts/headerVentas',
        '-YIELD-',
        'layouts/footerVentas',
    ),
    'excel' => array(
        'layouts/headerExcel',
        '-YIELD-',
        'layouts/footerLogin',
    ),
    'login' => array(
        'layouts/headerLogin',
        '-YIELD-',
        'layouts/footerLogin',
    ),
    'forgot' => array(
        'layouts/headerForgot',
        '-YIELD-',
    ),
    'ajax'	=> array(
		'-YIELD-',
	)
);

/*
  |--------------------------------------------------------------------------
  | Layout: CSS Prefix.
  |--------------------------------------------------------------------------
  |
  | This prefix will be prepended to your requested CSS files, and can be
  | used to specify a global location for your CSS files.
  |
 */

$layout['css_prefix'] = '';

/*
  |--------------------------------------------------------------------------
  | Layout: JS Prefix.
  |--------------------------------------------------------------------------
  |
  | This prefix will be prepended to your requested JS files, and can be
  | used to specify a global location for your JS files.
  |
 */

$layout['js_prefix'] = '';

/*
  |--------------------------------------------------------------------------
  | Layout: Default Values
  |--------------------------------------------------------------------------
  |
  | These default values will be available to all views loaded using the
  | layout library. You may use the bind() method to overwrite them at
  | run time.
  |
 */

$layout['default_values'] = array(
    // format 'key' => 'value',
);
