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
	}

	function plugin_activate(){
		echo 'The plugin has been activated';
	}

	function plugin_deactivate(){

	}

	function plugin_uninstall(){

	}

	//function custom_post_type(){
		//register_post_type('event', ['public' => true, 'label' => 'Events']);
	//}
}

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

add_action('init', 'ts_event_post_type');

function ts_event_post_type(){
	
	$args = array(
		'labels' => array(
			'name' => 'Events',
			'singular_name' => 'Event',
		),
		'description' => 'Events',
		'public' => true,
		'has_archive' => true,
		'menu_icon' => 'dashicons-calendar',
		'show_in_rest' => true,
		'can_export' => true,
		'publicly_queryable' => true,
		'supports' => array('title', 'editor', 'thumbnail', 'revisions', 'custom-fields', 'revisions'),
		'show_in_graphql' => true,
		'graphql_name' => 'event',
		'graphql_plural_name' => 'Events',
		'graphql_single_name' => 'Event',
		'graphql_plural_name' => 'Events',
	);
	
	register_post_type('event', $args);
}



function ts_add_post_meta_boxes(){
	add_meta_box(
		"post_metadata_events_post",
		"Event Information",
		"post_meta_box_events_post",
		"event",
		"side",
		"low"
	);
}

add_action('admin_init', 'ts_add_post_meta_boxes');

// Save Field Values
function ts_save_post_meta_boxes(){
	global $post;

	if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE){
		return;
	}
	update_post_meta($post->ID, '_event_location', $_POST['_event_location']);
	update_post_meta($post->ID, '_event_date_start', $_POST['_event_date_start']);
	update_post_meta($post->ID, '_event_date_end', $_POST['_event_date_end']);
}

add_action('save_post', 'ts_save_post_meta_boxes');

// Callback to render fields
function post_meta_box_events_post(){
	global $post;
	$custom = get_post_custom($post->ID);
	$fieldDataLocation = $custom['_event_location'][0];
	$fieldDataStart = $custom["_event_date_start"][0];
	$fieldDataEndTime = $custom["_event_date_end"][0];

	echo "<div class='eventinfo' style='display: flex; flex-direction: column;'>
		<label for='_event_location'>Location</label>
		<input type='text' style='margin-bottom: 10px;' name='_event_location' id='_event_location' value='".$fieldDataLocation."' />
		<label for='_event_date_start'>Event Start</label>
		<input required class='dateselector' style='margin-bottom: 10px;' type='datetime-local' name='_event_date_start' id='_event_date_start' value='$fieldDataStart' />
		<label for='_event_date_end_time'>Event End</label>
		<input required type='datetime-local' name='_event_date_end' id='_event_date_end' value='$fieldDataEndTime' />
	</div>
	";

	// echo "<label>Start Date</label>";
	// echo "<input type=\"date\" name=\"_event_date_start\" value=\"".$fieldDataStart."\" placeholder=\"Start Date\">";
	// echo "<label>Time</label>";
	// echo "<input type=\"time\" name=\"_event_date_start_time\" value=\"".$fieldDataStartTime."\" placeholder=\"\">";

}

add_action('graphql_register_types', function(){
	register_graphql_field('Event', 'eventLocation', [
		'type' => 'String',
		'description' => __( 'The location of the event', 'your-textdomain' ),
		'resolve' => function( $post ) {
			return get_post_meta($post->ID, '_event_location', true);
		}
	]);

	register_graphql_field( 'Event', 'eventDateStart', [
		'type' => 'String',
		'description' => __( 'The event start date', 'your-textdomain' ),
		'resolve' => function( $post ) {
			return get_post_meta($post->ID, '_event_date_start', true);
		}
	] );

	register_graphql_field( 'Event', 'eventDateEnd', [
		'type' => 'String',
		'description' => __( 'The event end time', 'your-textdomain' ),
		'resolve' => function( $post ) {
			return get_post_meta($post->ID, '_event_date_end', true);
		}
	] );
});