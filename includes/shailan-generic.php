<?php 

/* GENERIC THEME FUNCTIONS */

/** RSS Footer Text */
function shailan_postrss($content) {
	//global $post;
	if(is_feed()){
		$content = $content.' <p><strong><em>This post is originally posted on <a href="'.get_bloginfo('url').'">'.get_bloginfo('name').'</a>. <br />Visit <a href="'.get_bloginfo('url').'">'.get_bloginfo('name').'</a> for more..</em></strong></p>';
	}
	return $content;
}
add_filter('the_excerpt_rss', 'shailan_postrss');
add_filter('the_content', 'shailan_postrss');

/** The Author */
function shailan_author($authordata){
	global $authordata;
	
	$author_twitter = get_the_author_meta('twitter');
	$author_site = get_the_author_meta('url');
		
	if(!empty($author_twitter)){
		return '<a href="http://twitter.com/'.$author_twitter.'" rel="nofollow" class="twitter-link">@'.$author_twitter.'</a>';
	} elseif(!empty($author_site)) {		
		return '<a href="'.$author_site.'" rel="nofollow" class="author-link">@'.$author_site.'</a>';
	} else {
		return $authordata->display_name;
	}
} add_filter('get_the_author_link', 'shailan_author');

/** RSS Feed Thumbnails */
function shailan_rss_post_thumbnail($content) {
	global $post;
	if(is_feed()){
	if(function_exists('has_post_thumbnail') && has_post_thumbnail($post->ID)) {
		$content = '<p align="right">' . get_the_post_thumbnail($post->ID) .
		'</p>' . get_the_content();
	} }
	return $content;
}
add_filter('the_excerpt_rss', 'shailan_rss_post_thumbnail');
add_filter('the_content', 'shailan_rss_post_thumbnail');

/** Custom Editor Styles */
add_filter('mce_css', 'shailan_editor_style');
function shailan_editor_style($url) {
  if ( !empty($url) )
	$url .= ',';
  $url .= trailingslashit( get_stylesheet_directory_uri() ) . 'css/editor.css';
  return $url;
}

/** Custom Admin Logo */
function shailan_custom_logo() {
	echo '<style type="text/css">
		#header-logo { background-image: url('.get_bloginfo('template_directory').'/images/custom-logo.gif) !important; }
	</style>'; }
//add_action('admin_head', 'shailan_custom_logo');

/** Custom Admin Footer */
function shailan_admin_footer() {
	echo 'Fueled by <a href="http://www.wordpress.org" target="_blank">WordPress</a> | Designed by <a href="http://shailan.com" target="_blank">Shailan</a> (Follow: <a href="http://twitter.com/mattsay">@mattsay</a> | <a href="http://feeds.feedburner.com/shailan">RSS</a>)';
} // add_filter('admin_footer_text', 'shailan_admin_footer');

/** Custom Default Avatar */
function shailan_avatar ($avatar_defaults) {
$myavatar = get_bloginfo('template_directory') . '/images/gravatar.gif';
$avatar_defaults[$myavatar] = "Custom Avatar";
return $avatar_defaults;
} //add_filter( 'avatar_defaults', 'shailan_avatar' );

/** Custom Profile Fields for authors */
function shailan_contactmethods( $contactmethods ) {
	unset($contactmethods['aim']);
	unset($contactmethods['jabber']);
	unset($contactmethods['yim']);
	// Add Twitter
	$contactmethods['twitter'] = 'Twitter';
	//add Facebook
	$contactmethods['facebook'] = 'Facebook';
return $contactmethods;
}
add_filter('user_contactmethods','shailan_contactmethods',10,1);

/** Google Analytics Support */
function shailan_google_analytics(){	
	echo stripslashes(get_option('shailan_analytics_code'));
}; add_action('wp_head', 'shailan_google_analytics');

/** Feed redirects */
function shailan_feed_link($output, $feed) {
	$feed_url = get_option('shailan_feedburner');
	if(empty($feed_url)){$feed_url = "http://feeds.feedburner.com/shailan";}
	
	$feed_array = array('rss' => $feed_url, 'rss2' => $feed_url, 'atom' => $feed_url, 'rdf' => $feed_url, 'comments_rss2' => '');
	$feed_array[$feed] = $feed_url; $output = $feed_array[$feed];
 
	return $output;
}

function other_feed_links($link) {
	$feed_url = get_option('shailan_feedburner');
	if(empty($feed_url)){$feed_url = "http://feeds.feedburner.com/shailan";}
	$link = $feed_url;
	return $link;
}
add_filter('feed_link','shailan_feed_link', 1, 2);
add_filter('category_feed_link', 'other_feed_links');
add_filter('author_feed_link', 'other_feed_links');
add_filter('tag_feed_link','other_feed_links');
add_filter('search_feed_link','other_feed_links');

/** Custom Favicon Support */
function shailan_favicon() { 
	$favicon = get_option('shailan_favicon');
	echo "<link rel=\"shortcut icon\" href=\"".$favicon."\" />";
} add_action('wp_head', 'shailan_favicon');

/** Excerpt Length Settings */
function shailan_excerpt_length($length) {
	$excerpt_length = get_option('shailan_excerpt_length');
	if(empty($excerpt_length)){ $excerpt_length = 25; }
	return $excerpt_length;
}
add_filter('excerpt_length', 'shailan_excerpt_length');
 
/** Excerpt More text */
function shailan_excerpt_more( $more ) {
	$more_text = get_option('shailan_more');
	if(empty($more_text)){ $more_text = ' &hellip; <a href="'. get_permalink() . '">' . __('Continue reading <span class="meta-nav">&rarr;</span>') . '</a>'; }
	return $more_text;
}
add_filter( 'excerpt_more', 'shailan_excerpt_more' );

/** Threaded comments script adder */
function enable_threaded_comments(){
	if (!is_admin()) {
	if (is_singular() AND comments_open() AND (get_option('thread_comments') == 1))
		wp_enqueue_script('comment-reply');
	}
}
add_action('get_header', 'enable_threaded_comments');

/** Remove wordpress version */
function shailan_remove_version() { return ''; } add_filter('the_generator', 'shailan_remove_version');

/** Allow shortcodes in widgets */
add_filter('widget_text', 'do_shortcode');

/** Feedburner subscriber count */
function shailan_feedburner_count($feedburner_id, $display = false){
	$whaturl="http://api.feedburner.com/awareness/1.0/GetFeedData?uri=" . $feedburner_id;
	//Initialize the Curl session
	$ch = curl_init();
	//Set curl to return the data instead of printing it to the browser.
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	//Set the URL
	curl_setopt($ch, CURLOPT_URL, $whaturl);
	//Execute the fetch
	$data = curl_exec($ch);
	//Close the connection
	curl_close($ch);
	$xml = new SimpleXMLElement($data);
	$fb = $xml->feed->entry['circulation'];
	
	if($display){ echo $fb; } else { return $fb; }
	
}	

/** Twitter follower count */
function shailan_twitter_followers($twitter_id){
	$opt_key = 'shailan_twitter_followers_' . $twitter_id;
	$twitter = maybe_unserialize(get_option( $opt_key ));
	$last_check = $twitter['lastcheck'];
	$now = time();
	$ago = $now - 3600;
	
	if($last_check < $ago){
		$xml=file_get_contents('http://twitter.com/users/show.xml?screen_name=' . $twitter_id);
		if (preg_match('/followers_count>(.*)</',$xml,$match)!=0) {
			$twitter['count'] = $match[1];
		};

		$twitter['lastcheck'] = time();
	
	$twitter = serialize($twitter);	
	update_option( $opt_key, $twitter);
	}
	return $twitter['count'];
}

/** Get author link */
function shailan_get_the_author_link( $meta = null , $domain = '', $title = '' ) {
	if ( $meta && get_the_author_meta($meta) ) {
		return '<a href="' . $domain . get_the_author_meta($meta) . '" title="' . $title . '" rel="external">' . get_the_author() . '</a>';
	} elseif(get_the_author_meta('url')) {
		return '<a href="' . get_the_author_meta('url') . '" title="' . esc_attr( sprintf(__("Visit %s&#8217;s website"), get_the_author()) ) . '" rel="external">' . get_the_author() . '</a>';
	} else {
		return get_the_author();
	}
}

?>