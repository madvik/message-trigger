<?php
/**
 * The MT_MessageTrigger class.
 *
 * @since      1.0
 * @package    message-trigger
 * @subpackage message-trigger/admin
 * @author     Bravokeyl, madvic
 */
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
			echo '<p>#id : message-trigger-post</p>';
		}

	} // end class
?>