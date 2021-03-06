<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes() ?>>
<head profile="http://gmpg.org/xfn/11">
	<title><?php wp_title( '@', true, 'right' ); echo esc_html( get_bloginfo('name'), 1 ) ?></title>
	<meta http-equiv="content-type" content="<?php bloginfo('html_type') ?>; charset=<?php bloginfo('charset') ?>" />
	
	<?php stf_css_960gs(); ?>	
	<?php stf_css_common(); ?>
	
	<link rel="stylesheet" type="text/css" href="<?php bloginfo('stylesheet_url') ?>" />
	<!-- WP_HEAD -->
	<?php wp_head(); ?>
	<!-- /WP_HEAD -->
	<link rel="alternate" type="application/rss+xml" href="<?php bloginfo('rss2_url') ?>" title="<?php printf( __( '%s latest posts', 'widgetbox' ), esc_html( get_bloginfo('name'), 1 ) ) ?>" />
	<link rel="alternate" type="application/rss+xml" href="<?php bloginfo('comments_rss2_url') ?>" title="<?php printf( __( '%s latest comments', 'widgetbox' ), esc_html( get_bloginfo('name'), 1 ) ) ?>" />
	
	<link rel="pingback" href="<?php bloginfo('pingback_url') ?>" />
</head>

<body <?php body_class(); ?>>
<!-- <div id="main-title"><h1><?php wp_title( "", true ); ?></h1></div> -->
<?php do_action('template_body_top'); ?>

<div id="wrapper">

<div id="header_wrap">
<div id="header" class="clearfix">
	<span class="gradient"></span>
	<div id="branding" >
		<?php $logo_url = stf_get_setting('stf_logo_url'); if( !empty($logo_url) ){ ?>
		<div id="logo">
			<a href="<?php echo home_url( '/' ); ?>" rel="home <?php if(!is_front_page() || !is_home()){ echo 'nofollow';} ?>">
				<img src="<?php echo $logo_url; ?>" width="50" height="50" class="logo" alt="<?php bloginfo('description'); ?>" title="<?php bloginfo('name'); ?>" />
			</a>
		</div>
		<?php } // End logo ?>
			
		<div id="site-id" role="banner">
			<?php $heading_tag = ( is_home() || is_front_page() ) ? 'h1' : 'div'; ?>
			<<?php echo $heading_tag; ?> id="site-title">
				<span><a href="<?php echo home_url( '/' ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home <?php if(!is_front_page() || !is_home()){ echo 'nofollow';} ?>"><?php bloginfo( 'name' ); ?></a></span>
			</<?php echo $heading_tag; ?>>
			<div id="site-description"><?php bloginfo( 'description' ); ?></div>
		</div><!-- #branding -->
	</div>
	
	<div id="top-menu">
		<?php wp_nav_menu( array( 'theme_location' => 'top-navigation', 'fallback_cb' => false, 'menu_id'=> 'menu-top', 'container_class' => 'top-navigation', 'depth' => '0' ) ); ?>
	</div>
	
	<div id="header-widgets" class="grid_7">
		<?php stf_widgets('header'); ?>
	</div>
	<div class="clear"></div>
	<div id="header-menu">
		<?php wp_nav_menu( array( 'theme_location' => 'header-bottom', 'menu_id' => 'menu-main', 'container_class' => 'header-navigation clearfix' ) ); ?>
	</div>
	<div class="clear"></div>
</div>
</div>