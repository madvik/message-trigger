<?php
/*
Plugin Name: Message Trigger
Plugin URI: http://wordpress.org/plugins/message-trigger
Description: Using this plugin we can easily add notifications/messages at the top / bottom / after content section of the post.It uses marquee ,so it this works perfect in HTML5 themes
Author: Bravokeyl
Author URI: http://cloudspier.com
Version: 1.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.

*/

	if( ! defined( 'MT_VERSION' ) ) {
	  define( 'MT_VERSION', 1.0 );
	} // end if

	function mt_init() {
	    new MT_MessageTrigger();
	}

	if ( is_admin() ) {
	    add_action( 'load-post.php', 'mt_init' );
	    add_action( 'load-post-new.php', 'mt_init' );
	}

	class MT_MessageTrigger {

		public function __construct() {

			add_action( 'add_meta_boxes', array( $this, 'mt_add_meta_box' ) );
			add_action( 'save_post', array( $this, 'mt_save_meta' ) );
		}

		public function mt_add_meta_box( $mt_post_type ) {

	            $mt_post_types = array('post');    
	            if ( in_array( $mt_post_type, $mt_post_types )) {
				add_meta_box(
				'message-trigger-options' ,
				__( 'Message Trigger', 'mt' ) ,
				array( $this, 'mt_meta_box_display' ) ,
				$mt_post_type ,
				'advanced' ,
				'high'
			);
	            }
		}

		public function mt_save_meta( $post_id ) {
		
			if ( ! isset( $_POST['mt_meta_box_inner_nonce'] ) )
				return $post_id;

			$mt_nonce = $_POST['mt_meta_box_inner_nonce'];

			if ( ! wp_verify_nonce( $mt_nonce, 'mt_meta_box_inner' ) )
				return $post_id;

			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
				return $post_id;
            
            if ( ! current_user_can( 'edit_post', $post_id ) )
					return $post_id;

			$mt_message_value = sanitize_text_field( $_POST['mt_message'] );

			update_post_meta( $post_id, '_mt_message_key', $mt_message_value );
		}

		public function mt_meta_box_display( $post ) {
		
			wp_nonce_field( 'mt_meta_box_inner', 'mt_meta_box_inner_nonce' );

			$mt_value = get_post_meta( $post->ID, '_mt_message_key', true );

			echo '<input type="text" id="mt_message" name="mt_message"';
	                echo ' value="' . esc_attr( $mt_value ) . '" class="widefat" />';
		}
	}

	add_filter('the_content','mt_add_message');

	function mt_add_message($content){

		$output = $content;
		$output .= "<b><marquee>".get_post_meta(get_the_ID(),'_mt_message_key',true)."</marquee></b>";
		return $output;
	}


	add_action('admin_menu','mt_plugin_page');

	function mt_plugin_page(){

		add_options_page(__('Message Trigger'),__('Message Trigger'),'manage_options','message_trigger','mt_options_page');
	}

	function mt_options_page(){?>
		<div class="wrap" >
			<h2><?php _e('Message Trigger'); ?></h2 >
			<form action="options.php" method="post" >
				<?php settings_fields('mt_plugin_options');
					  do_settings_sections('message_trigger');

				 ?>
				 <input name="submit" type="submit" value="Save Changes" class="button-primary"/>
			</form>  
		</div>
	<?php } // mt_options_page

	add_action('admin_init','mt_admin_init');

	function mt_admin_init(){

		register_setting('mt_plugin_options','mt_plugin_options','mt_validate_options');
		add_settings_section('mt_section_id',__('Header Message section'),'mt_callback_section','message_trigger');
		add_settings_section('mt_section_foot',__('Footer Message section'),'mt_callback_section','message_trigger');
		add_settings_field('mt_head',__('Enter Header Message'),'mt_head_cb','message_trigger','mt_section_id');
		add_settings_field('mt_foot',__('Enter Footer Message'),'mt_foot_cb','message_trigger','mt_section_foot');
	
	}//end mt_admin_init

	function mt_callback_section(){
		//echo "";
	}

	function mt_head_cb(){

		$mt_options = get_option('mt_plugin_options');
		$mt_option_value = $mt_options['mt_head_message'];
		echo "<input id='mt_head_message' name='mt_plugin_options[mt_head_message]' type='text' value='$mt_option_value' class='regular-text'/>";
	}

	function mt_foot_cb(){

		$mt_options = get_option('mt_plugin_options');
		$mt_option_value = $mt_options['mt_foot_message'];
		echo "<input id='mt_foot_message' name='mt_plugin_options[mt_foot_message]' type='text' value='$mt_option_value' class='regular-text'/>";
	}

	function mt_validate_options($mt_input){

		$mt_valid = array();
		$mt_valid['mt_head_message'] = sanitize_text_field($mt_input['mt_head_message']);
		$mt_valid['mt_foot_message'] = sanitize_text_field($mt_input['mt_foot_message']);
		return $mt_valid;
	}

	add_action('wp_head','mt_header');
	add_action('wp_footer','mt_footer');

	function mt_header(){

        $mt_options = get_option('mt_plugin_options');
		$mt_option_value = $mt_options['mt_head_message'];

		echo "<h3><marquee>".$mt_option_value."</marquee></h3>";

	}

	function mt_footer(){

        $mt_options = get_option('mt_plugin_options');
		$mt_option_value = $mt_options['mt_foot_message'];

		echo "<h3><marquee>".$mt_option_value."</marquee></h3>";
		
	}
