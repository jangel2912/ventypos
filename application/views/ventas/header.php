<?php 
	
	/**
	 *
	 * Template header
	 *
	 **/
	
	// create an access to the template main object
	global $tpl;

?>
<?php do_action('gavernwp_doctype'); ?>
<html <?php do_action('gavernwp_html_attributes'); ?>>
<head>
	<title><?php do_action('gavernwp_title'); ?></title>
	<?php do_action('gavernwp_metatags'); ?>
	
	<link rel="profile" href="http://gmpg.org/xfn/11" />
	<link rel="shortcut icon" href="<?php get_stylesheet_directory_uri(); ?>/favicon.ico" />
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
	<?php
	wp_enqueue_style('gavern-normalize', gavern_file_uri('css/normalize.css'), false);
	wp_enqueue_style('gavern-template', gavern_file_uri('css/template.css'), array('gavern-normalize'));
	wp_enqueue_style('gavern-wp', gavern_file_uri('css/wp.css'), array('gavern-template'));
	wp_enqueue_style('gavern-stuff', gavern_file_uri('css/stuff.css'), array('gavern-wp'));
	wp_enqueue_style('gavern-wpextensions', gavern_file_uri('css/wp.extensions.css'), array('gavern-stuff'));
	wp_enqueue_style('gavern-extensions', gavern_file_uri('css/extensions.css'), array('gavern-wpextensions'));
	?>
	 <script type="text/javascript" src="/wp-content/themes/Simplicity/layouts/js/jquery-1.6.2.min.js"></script>
<script type="text/javascript" src="/wp-content/themes/Simplicity/layouts/js/validacion.js?actu=32"></script>
	<!--[if IE 9]>
	<link rel="stylesheet" href="<?php echo gavern_file_uri('css/ie9.css'); ?>" />
	<![endif]-->
	<!--[if lt IE 9]>
	<link rel="stylesheet" href="<?php echo gavern_file_uri('css/ie8.css'); ?>" />
	<![endif]-->
	
	<?php if(is_singular() && get_option('thread_comments' )) wp_enqueue_script( 'comment-reply' ); ?>
	
	<?php do_action('gavernwp_ie_scripts'); ?>
	
	<?php gk_head_shortcodes(); ?>
		  
	<?php 
	 gk_load('responsive_css'); 
	 
	 if(get_option($tpl->name . "_overridecss_state", 'Y') == 'Y') {
	   wp_enqueue_style('gavern-override', gavern_file_uri('css/override.css'), array('gavern-style'));
	 }
	?>
	
	<?php
	if(get_option($tpl->name . '_prefixfree_state', 'N') == 'Y') {
	  wp_enqueue_script('gavern-prefixfree', gavern_file_uri('js/prefixfree.js'));
	} 
	?>
	
	<?php gk_head_style_css(); ?>
	<?php gk_head_style_pages(); ?>
	
	<?php gk_thickbox_load(); ?>
	<?php wp_head(); ?>
	
	<?php do_action('gavernwp_fonts'); ?>
	<?php gk_head_config(); ?>
	<?php wp_enqueue_script("jquery"); ?>
	
	<?php
	    wp_enqueue_script('gavern-scripts', gavern_file_uri('js/gk.scripts.js'), array('jquery'), false, true);
	    wp_enqueue_script('gavern-menu', gavern_file_uri('js/gk.menu.js'), array('jquery', 'gavern-scripts'), false, true);
	?>
	
	<?php do_action('gavernwp_head'); ?>
	
	<?php 
		echo stripslashes(
			htmlspecialchars_decode(
				str_replace( '&#039;', "'", get_option($tpl->name . '_head_code', ''))
			)
		); 
	?>

<style type="text/css">
.top_links {
  width: 100%;
  min-height: 30px;
  background-color: #565656;
  font-size: 12px;
  color: #fff;
  font-weight:bold;
  }
  
.top_links .social  ul {
  padding: 0px;
  margin: 0px 0px 0px 0px;
  float: left;
}


 .sociales li { display: inline; }
 
.sociales li a {
  padding: 0px;
  margin: 3px 0px 0px 4px;
  width: 18px;
  height: 18px;
  float: left;
  text-align: center;
  line-height: 18px;
  vertical-align: middle;
  background: url(wp-content/themes/Simplicity/layouts/top-si-bg.png) no-repeat center top;
} 

.sociales li a:hover {
	background: url(wp-content/themes/Simplicity/layouts/top-si-bg-hover.png) no-repeat center top;
} 

.top_links .contact_info {
  padding: 0px;
  margin: 0px 0px 0px 0px;
  float: right;
}
.top_links .contact_info ul {
  padding: 0px;
  margin: 0px;
  float: left;
}
.top_links .contact_info li {
  padding: 0px;
  margin: 0px 0px 0px 10px;
  float: left;
}
.top_links .contact_info li.icons {
  padding: 0px 5px;
  margin-top: 1px;
  float: left;
  background-color: #188512;
  border-radius: 2px;
  font-weight: normal;
}
.top_links .contact_info li.icons img {
  float: left;
  padding: 0px 5px 0px 0px;
}
.top_links .contact_info li a {
	padding: 0px;
	margin: 0px;
	float: left;
	color: #fff;
}
.top_links .contact_info li a:hover {
	color: #33c92b;
}
</style>
	
</head>
<body <?php do_action('gavernwp_body_attributes'); ?>>



<?php   if($_SERVER["REQUEST_URI"] == '/'){       ?> 

<div class="top_links"><center>
<table style="height: 32px;  width: 1300px;" >
<tr>
<td  width="6%" >&nbsp;</td>
<td  width="8%">
Redes Sociales: 
</td>
<td  height="25px">
<div class="floatleft socicons" >
<div class="social">
                <ul class="sociales"> 
                        <li><a href="https://www.facebook.com/vendtycom" target="_blank"><img src="wp-content/themes/Simplicity/layouts/top-si1.png" alt=""></a></li>
                        <li><a href="http://www.twitter.com/vendtyapps" target="_blank"><img src="wp-content/themes/Simplicity/layouts/top-si2.png" alt=""></a></li>
                        <li><a href="https://www.youtube.com/user/VendtyApps" target="_blank"><img src="wp-content/themes/Simplicity/layouts/top-si6.png" alt=""></a></li>
                    </ul>
                </div>
</td>
<td  height="25px" align="right" >

<div class="contact_info">
                	<ul>
                    	<li>Contactanos:</li>
                        <li class="icons"><img src="wp-content/themes/Simplicity/layouts/top-phone-icon.png" alt="">3194751398 - 3016991</li>
                        <li class="icons"><img src="wp-content/themes/Simplicity/layouts/top-mail-icon.png" alt=""><a href="mailto:info@vendty.com">info@vendty.com</a></li>
                        <li><a href="http://www.vendty.com/invoice/">Entrar</a>&nbsp;|</li>
                        <li><a href="#">Recursos</a>&nbsp;|&nbsp;</li>
                        <li class="icons"><a href="#"><img src="wp-content/themes/Simplicity/layouts/top-chat-icon.png" alt="">Chat en linea</a></li>
                    </ul>
                </div>&nbsp;&nbsp;
</td>
<td  width="6%" >&nbsp;</td>
</tr>
</table>
</center>
</div>

<?php  } ?>

	<header id="gk-head">


			<div class="gk-page" id="gk-header-nav">
				<?php if(get_option($tpl->name . "_branding_logo_type", 'css') != 'none') : ?>
				<h1>
					<a href="<?php echo home_url(); ?>" class="<?php echo get_option($tpl->name . "_branding_logo_type", 'css'); ?>Logo"><?php gk_blog_logo(); ?></a>
				</h1>
				<?php endif; ?>
				
				<?php if(gk_show_menu('mainmenu')) : ?>
					<?php gavern_menu('mainmenu', 'gk-main-menu', array('walker' => new GKMenuWalker())); ?>
					<?php gavern_menu('mainmenu', 'main-menu-mobile', array('walker' => new GKMenuWalkerMobile(), 'items_wrap' => '<select onchange="window.location.href=this.value;"><option value="#">'.__('Select a page', GKTPLNAME).'</option>%3$s</select>', 'container' => 'div')); ?>
				<?php endif; ?>
			</div>
		</div>
		
		<?php if(gk_is_active_sidebar('header')) : ?>
		<div id="gk-header-mod" class="gk-page">
			<?php gk_dynamic_sidebar('header'); ?>
		</div>
		<?php endif; ?>
	</header>
