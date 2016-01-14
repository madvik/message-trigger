<?php
/**
 * The front-end fonctionnality
 * @package    message-trigger
 * @subpackage message-trigger/public
 */

$mt_options = get_option('mt_plugin_options');
$mt_active = $mt_options['mt_active'];

if( 'on' == $mt_active ){

	add_filter('the_content','mt_add_message');

	function mt_add_message($content){
		$output = $content;
		$mt_post_option_value = get_post_meta(get_the_ID(),'_mt_message_key',true);
		if( !empty( $mt_post_option_value ) ){
		$output .= '<div id="message-trigger-post" class="notification info canhide">'.do_shortcode( $mt_post_option_value ) .'</div>';
		}
		return $output;
	}


	add_action('wp_head','mt_header');
	add_action('wp_footer','mt_footer');

	function mt_header(){
		$mt_options = get_option('mt_plugin_options');
		$mt_option_value = $mt_options['mt_head_message'];
		if( !empty($mt_option_value ) ){
			echo '<div id="message-trigger-header" class="notification info canhide">'.do_shortcode($mt_option_value).'</div>';
		}
	}

	function mt_footer(){
		$mt_options = get_option('mt_plugin_options');
		$mt_option_value = $mt_options['mt_foot_message'];
		if( !empty($mt_option_value ) ){
			echo '<div id="message-trigger-footer" class="notification info canhide">'.do_shortcode($mt_option_value).'</div>';
		}
	}

} //end condition

?>