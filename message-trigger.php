<?php
/**
 * @since             1.1
 * @package           message-trigger
 *
 * @wordpress-plugin
 * Plugin Name: Message Trigger
 * Plugin URI: http://wordpress.org/plugins/message-trigger
 * Description: Using this plugin we can easily add notifications/messages at the top / bottom / after content section of the post.It uses marquee ,so it this works perfect in HTML5 themes
 * Author: Bravokeyl, madvic
 * Author URI: http://cloudspier.com
 * Version: 1.1
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 */

	// If this file is called directly, abort.
	if ( ! defined( 'WPINC' ) ) {
		die;
	}

	if( !defined( 'MT_VERSION' ) ) {
		define( 'MT_VERSION', 1.1 );
	} // end if

	if ( is_admin() ) {
		include plugin_dir_path( __FILE__ ) .'/admin/class-MT_MessageTrigger.php';
		include plugin_dir_path( __FILE__ ) .'/admin/message-trigger-admin.php';
	}
	else{
		include plugin_dir_path( __FILE__ ) .'/public/message-trigger-public.php';
	}

?>