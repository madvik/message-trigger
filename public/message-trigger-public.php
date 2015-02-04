<?php
/**
 * The front-end fonctionnality
 * @package    message-trigger
 * @subpackage message-trigger/public
 */
 
	add_filter('the_content','mt_add_message');

	function mt_add_message($content){
		$output = $content;
		$output .= '<div id="message-trigger-post">'.get_post_meta(get_the_ID(),'_mt_message_key',true).'</div>';
		return $output;
	}


	add_action('wp_head','mt_header');
	add_action('wp_footer','mt_footer');

	function mt_header(){
		$mt_options = get_option('mt_plugin_options');
		$mt_option_value = $mt_options['mt_head_message'];
		echo '<div id="message-trigger-header">'.$mt_option_value.'</div>';
	}

	function mt_footer(){
		$mt_options = get_option('mt_plugin_options');
		$mt_option_value = $mt_options['mt_foot_message'];
		echo '<div id="message-trigger-footer">'.$mt_option_value.'</div>';
		
	}

?>