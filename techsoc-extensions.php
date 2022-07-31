<?php
/**
 * Plugin Name: Techsoc Gatsby Extensions
 * Plugin URI: https://bruneltech.net
 * Description: Gatsby.js Extensions for Techsoc Website.
 * Version: 1.1.1
 * Author: James
 * Author URI: https://pyxlwuff.dev
 */

// Create a plugin page

class TechsocExtensions{
	function __construct(){
		//add_action('init', array($this, 'custom_post_type'));
		require_once(plugin_dir_path(__FILE__) . 'post-types/events.php');
		require_once(plugin_dir_path(__FILE__) . 'post-types/projects.php');
		$events = new EventPosts();
		$projects = new ProjectsPosts();

	}

	function plugin_activate(){
		echo 'The plugin has been activated';
		
		// call the functions from post-types folder

	}

	function plugin_deactivate(){

	}

	function plugin_uninstall(){

	}
	//function custom_post_type(){
		//register_post_type('event', ['public' => true, 'label' => 'Events']);
	//}
}

//////////////
//
// Plugin Checks
//
// These functions check to see if WPGatsby or WPGraphQL are not installed. You'll get an undismissable error on your admin panel
// until they are installed, and activated.
//
//////////////

function ts_check_gatsby(){
	if(!class_exists('WPGatsby')){
		echo '<div class="notice notice-error">
			<p>The <strong>WPGatsby</strong> plugin is either not activated or missing. You will experience errors until this plugin is installed.</p>
		</div>';

	}
}

function ts_check_graphql(){
	if(!class_exists('WPGraphQL')){
		echo '<div class="notice notice-error">
			<p>The <strong>WP GraphQL</strong> plugin is either not activated or missing. <strong>Your Gatsby frontend will not be able to fetch content</strong> from your server, and will error out.</p>
		</div>';
		
	}
}

add_action('admin_notices', 'ts_check_gatsby');
add_action('admin_notices', 'ts_check_graphql');



if(class_exists('TechsocExtensions')){
	$techsoc_extensions = new TechsocExtensions();
}


// Activation
register_activation_hook( __FILE__, array( $techsoc_extensions, 'plugin_activate' ) );


// This GraphQL action grabs the website logo.
add_action( 'graphql_register_types', function() {

	register_graphql_field( 'RootQuery', 'siteLogo', [
		'type' => 'MediaItem',
		'description' => __( 'The logo set in the customizer', 'your-textdomain' ),
		'resolve' => function() {

			$logo_id = get_theme_mod( 'custom_logo' );

			if ( ! isset( $logo_id ) || ! absint( $logo_id ) ) {
				return null;
			}

			$media_object = get_post( $logo_id );
			return new \WPGraphQL\Model\Post( $media_object );

		}
	]  );

} );