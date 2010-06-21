<?php

if(!WP_DEBUG){  define ('WP_DEBUG', true); }
@ini_set('log_errors','On');
@ini_set('display_errors','On');

if(get_option('shailan_twitter_anywhere') == 'enabled'){
	function install_twitter_anywhere(){
		$twitter_api_key = get_option('shailan_twitter_anywhere_key');
		echo "<script src=\"http://platform.twitter.com/anywhere.js?id=$twitter_api_key&v=1\" type=\"text/javascript\"></script>";
		echo "<script type=\"text/javascript\">
			twttr.anywhere(function (T) {
				T('.entry-content').hovercards();
				T('.entry-content').linkifyUsers();
			});
		</script>";
	} add_action( 'wp_head', 'install_twitter_anywhere' );
}

function install_digg(){
	echo "<script type=\"text/javascript\">
(function() {
var s = document.createElement('SCRIPT'), s1 = document.getElementsByTagName('SCRIPT')[0];
s.type = 'text/javascript';
s.async = true;
s.src = 'http://widgets.digg.com/buttons.js';
s1.parentNode.insertBefore(s, s1);
})();
</script>";
} add_action( 'wp_head', 'install_digg' );

/** Set up content width */
if ( ! isset( $content_width ) ){
	$widgetbox_page_width = get_option('widgetbox_page_width');
	$widgetbox_sidebar_width = get_option('widgetbox_sidebar_width');
	$widgetbox_padding = get_option('widgetbox_padding');

	$content_width = $widgetbox_page_width - $widgetbox_sidebar_width - $widgetbox_padding;
}

if(!defined('WB_SMARTLAYOUT')){
	define('WB_SMARTLAYOUT', true); 
};

/** Widgetbox smart content widths & paddings */
function wb_layout_styles(){

	if(WB_SMARTLAYOUT){
		$widgetbox_page_width = get_option('widgetbox_page_width');
		$widgetbox_sidebar_width = get_option('widgetbox_sidebar_width');
		$widgetbox_padding = get_option('widgetbox_padding');
		
		$content_width = $widgetbox_page_width - $widgetbox_sidebar_width - $widgetbox_padding;
		$post_width = $content_width - 2*$widgetbox_padding;
		
		$content_margin = $widgetbox_sidebar_width + $widgetbox_padding;
		$secondary_widget_width = ($widgetbox_sidebar_width>250 ? round(($widgetbox_sidebar_width - 2*$widgetbox_padding)/2) : $widgetbox_sidebar_width);
		
		$thumbnail_margin = -90 - $widgetbox_padding;
		
		$indent = "\n\t\t";

		echo "\n<!-- Start of Widgetbox Layout Styles -->";
		echo "\n\t<style type=\"text/css\" media=\"all\">";
		
		echo $indent . "div#wrapper{ width: ".$widgetbox_page_width."px; margin:".$widgetbox_padding."px auto; padding-bottom:".$widgetbox_padding."px }";
		echo $indent . "div#header, div#primary .widget, .entry-content, .entry-header{ margin-bottom: ".$widgetbox_padding."px; }";
		echo $indent . "div#container{ margin:0px -".$content_margin."px 0px 0px; }";
		echo $indent . "div#content{ margin:0px ".$content_margin."px 0px 0px; padding-left:".$widgetbox_padding."px; padding-right:".$widgetbox_padding."px }";
		echo $indent . "img.size-full{max-width: ".$post_width."px; border:none; padding:0; margin:0px auto ".$widgetbox_padding."px auto; } * html img.size-full{width: ".$post_width."px}";
		echo $indent . ".hentry{ margin-bottom:".$widgetbox_padding."px; padding-bottom:".$widgetbox_padding."px }";
		echo $indent . ".entry-thumb{ float:left; padding:0px; margin-left:".$thumbnail_margin."px; }";
		
		echo $indent . "div#primary{width: ".($widgetbox_sidebar_width)."px; margin:0px; margin-bottom:".$widgetbox_padding."px; margin-right:".$widgetbox_padding."px}";
		echo $indent . "div#secondary{width: ".($widgetbox_sidebar_width)."px; margin:0px; margin-right:".$widgetbox_padding."px}";
		//echo $indent . "div#secondary .widget{width: ".$secondary_widget_width."px; float:right; }";	

		echo $indent . "div#footer{ width: ".$widgetbox_page_width."px; margin:".$widgetbox_padding."px auto; padding-bottom:".$widgetbox_padding."px }";
		
		// HEADER IMAGE
		echo $indent . "div#header{ background-position: bottom center; background-repeat: no-repeat; background-image: url(".get_header_image()."); }";
		
		echo "\n\t</style>";
		echo "\n<!-- End of Widgetbox Layout Styles -->";
		echo "\n ";
	}
}
add_action( 'wp_head', 'wb_layout_styles' );

// THEME SETUP (Pluggable)
/*add_action( 'after_setup_theme', 'widgetbox_setup' );
function widgetbox_setup(){*/

	if ( function_exists( 'add_editor_style' ) ) { add_editor_style(); }
	
	if ( function_exists( 'add_theme_support' ) ) {
		add_theme_support( 'post-thumbnails' );
		set_post_thumbnail_size( 200, 200, true );
		add_theme_support( 'nav-menus' );
		add_theme_support( 'automatic-feed-links' );
	}
	
	// Localization
	load_theme_textdomain( 'widgetbox', TEMPLATEPATH . '/languages' );
	$locale = get_locale();
	$locale_file = TEMPLATEPATH . "/languages/$locale.php";
	if ( is_readable( $locale_file ) )
		require_once( $locale_file );

	if ( function_exists( 'add_custom_background' ) ) {
		// This theme allows users to set a custom background
		add_custom_background();
	}
	
	$widgetbox_page_width = get_option('widgetbox_page_width');

	if ( function_exists( 'add_custom_image_header' ) ) {
		define( 'HEADER_TEXTCOLOR', '' );
		define( 'HEADER_IMAGE', '%s/headers/header-default.jpg' );
		define( 'HEADER_IMAGE_WIDTH', apply_filters( 'widgetbox_header_image_width', $widgetbox_page_width ) );
		define( 'HEADER_IMAGE_HEIGHT', apply_filters( 'widgetbox_header_image_height', 320 ) );
		define( 'NO_HEADER_TEXT', false );

		add_custom_image_header( '', 'widgetbox_admin_header_style' );

		if(function_exists('register_default_headers')){
		// Default custom headers packaged with the theme. %s is a placeholder for the theme template directory URI.
		register_default_headers( array(
			'blue' => array(
				'url' => '%s/headers/blue.jpg',
				'thumbnail_url' => '%s/headers/blue-thumbnail.jpg',
				/* translators: header image description */
				'description' => __( 'Blue background', 'widgetbox' )
			), 
			'slate' => array(
				'url' => '%s/headers/slate.jpg',
				'thumbnail_url' => '%s/headers/slate-thumbnail.jpg',
				/* translators: header image description */
				'description' => __( 'Slate background', 'widgetbox' )
			),
			'grass' => array(
				'url' => '%s/headers/grass.jpg',
				'thumbnail_url' => '%s/headers/grass-thumbnail.jpg',
				/* translators: header image description */
				'description' => __( 'Grass background', 'widgetbox' )
			)
			
		) );	}
	}

	// Theme options
	$layout = get_option('widgetbox_active_layout');
	
	if(strlen($layout)<=1){
		// Insert default options
		update_option('widgetbox_page_width', 980);
		update_option('widgetbox_sidebar_width', 300);
		update_option('widgetbox_padding', 15);
		update_option('widgetbox_active_layout', '2Columns-Right');
		update_option('widgetbox_active_theme', 'googled');
	}
/*
}*/

if ( ! function_exists( 'widgetbox_admin_header_style' ) ) :
	function widgetbox_admin_header_style() {
	?>
	<style type="text/css">
	#headimg {
		height: <?php echo HEADER_IMAGE_HEIGHT; ?>px;
		width: <?php echo HEADER_IMAGE_WIDTH; ?>px;
	}
	#headimg h1, #headimg #desc {
		/*display: none;*/
	}
	</style>
	<?php
	}
endif;

if ( ! function_exists( 'widgetbox_page_menu_args' ) ) :
	function widgetbox_page_menu_args($args) {
		$args['show_home'] = true; // Display home link?
		return $args;
	}
	add_filter('wp_page_menu_args', 'widgetbox_page_menu_args');
endif;

/**
 * Returns HTML with meta information for the current postódate/time and author.
 */
function widgetbox_posted_on() {
	return sprintf( __( '<span %1$s>Posted on</span> %2$s by %3$s', 'widgetbox' ),
		'class="meta-prep meta-prep-author"',
		sprintf( '<a href="%1$s" title="%2$s" rel="bookmark"><span class="entry-date">%3$s</span></a> <span class="meta-sep">',
			get_permalink(),
			esc_attr( get_the_time() ),
			get_the_date()
		),
		sprintf( '</span> <span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s">%3$s</a></span>', 
			get_author_posts_url( get_the_author_meta( 'ID' ) ),
			sprintf( esc_attr__( 'View all posts by %s', 'widgetbox' ), get_the_author() ),
			get_the_author()
		)
	);
	
}

/**
 * Returns HTML with meta information for the current postócategory, tags and permalink
 */

function widgetbox_posted_in() {
	$tag_list = get_the_tag_list( '', ', ', '' );
	if ( $tag_list ) {
		$utility_text = __( 'This entry was posted in %1$s and tagged %2$s. Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.', 'widgetbox' );
	} else {
		$utility_text = __( 'This entry was posted in %1$s. Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.', 'widgetbox' );
	}
	return sprintf(
		$utility_text,
		get_the_category_list( ', ' ),
		$tag_list,
		get_permalink(),
		the_title_attribute( 'echo=0' ),
		get_post_comments_feed_link()
	);	
}

function my_init_method() {
    wp_enqueue_script('jquery');  
	wp_enqueue_script('prototype');  
	wp_enqueue_script('scriptaculous');   //Effect.ScrollTo
	wp_enqueue_script('widgetbox', get_bloginfo('template_directory').'/js/widgetbox.js', 'jquery'); 
}    
add_action('init', 'my_init_method');

function post_link(){
	$t = get_the_title();
	$p = get_permalink();
	
	echo "<a href=\"".$p."\" class=\"post-link\">".$t."</a>"; 
}

include_once('includes/widgetbox-sidebars.php'); // SIDEBARS
include_once('includes/widgetbox-widgets.php'); // WIDGETS
include_once('includes/controlpanel.php'); // CPANEL 
include_once('includes/shailan-generic.php'); // GENERIC FUNCTIONS
include_once('includes/shortcodes.php'); // SHORTCODES

?>