<?php
/** SHAILAN THEME FRAMEWORK 
 Author		: Matt Say
 Author URL	: http://shailan.com
 Version	: 1.0
 Contact	: metinsaylan (at) gmail (dot) com
*/
global $stf;

class Shailan_Framework{
	var $themename, $shortname, $options;

	/** Constructor */
	function Shailan_Framework(){
		// Load shortcodes, widgets, template tags
		require_once("shailan-loader.php");
		
		// TODO : Set default options set here
		$this->options = array();
	}
	
	/** Setup theme */
	function setupTheme($args){
		$defaults = array(
			"name" => "Shailan Theme Framework",
			"shortname" => "stf",
			"domain" => "",
			"editor_style" => false,
			"nav_menus" => false,
			"custom_background" => true,
			"post_thumbnails" => true,
			"automatic_feed_links" => true,
			"thumbnail_size" => "200x200",
			"custom_image_sizes" => "",
			"localization_directory" => TEMPLATEPATH
		);
		
		$setup_options = wp_parse_args( $args, $defaults );
		extract( $setup_options, EXTR_SKIP );
		
		$this->name = $name;
		$this->shortname = $shortname;
		
		if ( function_exists( 'add_editor_style' ) && $editor_style ) { add_editor_style(); }
		if ( function_exists( 'add_theme_support' )) {	
			if($post_thumbnails){
				add_theme_support( 'post-thumbnails' );
				$size = explode("x", $thumbnail_size);
				set_post_thumbnail_size( $size[0], $size[1], true ); 
				
				if(is_array($custom_image_sizes)){
					foreach($custom_image_sizes as $tag=>$size){
						$size = explode( "x" , $size );
						add_image_size( $tag, $size[0], $size[1] );
					}
				}
			}
			if( $nav_menus ){ add_theme_support( 'nav-menus' ); }
			if( $automatic_feed_links ){ add_theme_support( 'automatic-feed-links' ); }
		}
		
		/*if( $domain )*/
		load_theme_textdomain( $domain, $localization_directory );
		$locale = get_locale();
		$locale_file = $localization_directory . "/$locale.php";
		if ( is_readable( $locale_file ) )
			require_once( $locale_file );

		if ( function_exists( 'add_custom_background' ) && $custom_background ) { add_custom_background(); }
		
		add_action('admin_init', array(&$this, 'theme_admin_init'));
		add_action('admin_menu', array(&$this, 'theme_admin_header'));
	
	}
	
	function register_theme_options($options){
		$this->options = $options;
	}
	
	function extend_options($options){
		$this->options = array_merge((array)$options, (array)$this->options);
	}
	
	function theme_admin_init(){
		$file_dir=get_bloginfo('template_directory');
		wp_enqueue_style("functions", $file_dir . "/framework/options.css", false, "1.0", "all");
	}
	
	function theme_admin_header(){
		if ( @$_GET['page'] == "theme-options" ) {
			if ( @$_REQUEST['action'] && 'save' == $_REQUEST['action'] ) {
				foreach ($options as $value) {
				update_option( $value['id'], $_REQUEST[ $value['id'] ] ); }
		 
			foreach ($options as $value) {
				if( isset( $_REQUEST[ $value['id'] ] ) ) 
					{ update_option( $value['id'], $_REQUEST[ $value['id'] ]  ); }
				else 
					{ delete_option( $value['id'] ); } }
				header("Location: admin.php?page=theme-options&saved=true");
				die;
			} else if( @$_REQUEST['action'] && 'reset' == $_REQUEST['action'] ) {
				foreach ($options as $value) {
					delete_option( $value['id'] ); 
				}
				
				header("Location: admin.php?page=theme-options&reset=true");
				die;
			}
		}
		//add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position );
		//add_menu_page($this->name, $this->name, 'administrator', basename(__FILE__), array(&$this, 'theme_admin_page'));
		//add_submenu_page( $parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function);
		add_submenu_page('themes.php', $this->name . " Options", "Theme Options", "administrator", "theme-options", array(&$this, 'theme_admin_page'));	
		
	}
	
	function theme_admin_page(){
	
		if ( @$_REQUEST['saved'] ) echo '<div id="message" class="updated fade"><p><strong>'.$themename.' settings saved.</strong></p></div>';
		if ( @$_REQUEST['reset'] ) echo '<div id="message" class="updated fade"><p><strong>'.$themename.' settings reset.</strong></p></div>';
		
		// Render theme options page
		include_once("stf-page-options.php");
	
	}

};

$stf = new Shailan_Framework();

// Theme template tags
function get_theme_name(){
	global $stf;
	return $stf->name;
}