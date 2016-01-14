<?php
 /**
 * @package    message-trigger
 * @subpackage message-trigger/admin
 */
	add_action( 'load-post.php', 'mt_init' );
	add_action( 'load-post-new.php', 'mt_init' );
	add_action( 'admin_enqueue_scripts', 'stylesheet_for_admin' , 99);

    function stylesheet_for_admin() {
		if( isset($_GET['page']) && 'message_trigger' == $_GET['page'] ){
			wp_enqueue_style( 'message-trigger-admin', plugins_url('admin.css', __FILE__) );
    	}
    }

	function mt_init() {
		new MT_MessageTrigger();
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
		add_settings_section('mt_section_gen',__('General'), null,'message_trigger');
		add_settings_field('mt_active',__('Activate'),'mt_activate','message_trigger','mt_section_gen');

		add_settings_section('mt_section_id',__('Message section'),'mt_callback_section','message_trigger');
		add_settings_field('mt_head',__('Enter Header Page Message'),'mt_head_cb','message_trigger','mt_section_id');
		add_settings_field('mt_foot',__('Enter Footer Page Message'),'mt_foot_cb','message_trigger','mt_section_id');
	
	}//end mt_admin_init

	function mt_callback_section(){
		 //echo "dfgdgfdgfd";
	}

	function mt_activate(){
		$mt_options = get_option('mt_plugin_options');
		$mt_active = $mt_options['mt_active'];
		$checked = '';
		if('on' == $mt_active){
			$checked = 'checked';
		}
		?>
		<div class="onoffswitch">
			<input type="checkbox" name="mt_plugin_options[mt_active]" class="onoffswitch-checkbox" id="mt_onoffswitch" <?php echo $checked; ?>>
			<label class="onoffswitch-label" for="mt_onoffswitch">
				<span class="onoffswitch-inner"></span>
				<span class="onoffswitch-switch"></span>
			</label>
		</div>
		<?php
	}
	
	function mt_head_cb(){
		$mt_options = get_option('mt_plugin_options');
		$mt_option_value = $mt_options['mt_head_message'];
		echo "<input id='mt_head_message' name='mt_plugin_options[mt_head_message]' type='text' value='$mt_option_value' class='regular-text'/>";
		echo '<p>#id : message-trigger-header</p>';
	}

	function mt_foot_cb(){
		$mt_options = get_option('mt_plugin_options');
		$mt_option_value = $mt_options['mt_foot_message'];
		echo "<input id='mt_foot_message' name='mt_plugin_options[mt_foot_message]' type='text' value='$mt_option_value' class='regular-text'/>";
		echo '<p>#id : message-trigger-footer</p>';
	}

	function mt_validate_options($mt_input){
		$mt_valid = array();
		$mt_valid['mt_active']		 = sanitize_text_field($mt_input['mt_active']);
		$mt_valid['mt_head_message'] = sanitize_text_field($mt_input['mt_head_message']);
		$mt_valid['mt_foot_message'] = sanitize_text_field($mt_input['mt_foot_message']);
		return $mt_valid;
	}

?>