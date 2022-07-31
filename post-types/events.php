<?php

// Events.php
// Custom Post Type Declarations for Events.

class EventPosts{
    public function __construct(){
        add_action('init', array($this, 'register_event_post_type'));
        
        
    }

    function register_event_post_type(){
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
        );

        register_post_type('event', $args);
    }

}


add_action('admin_init', 'register_event_post_meta_boxes');

function register_event_post_meta_boxes(){
    add_meta_box(
        "post_metadata_events_post",
        "Event Information",
        "post_meta_box_events_post",
        "event",
        "side",
        "low"
    );
}

add_action('save_post', array($this, 'register_save_post_meta_boxes'));

function register_save_post_meta_boxes(){
    global $post;
    
    if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE){
        return;
    }

    update_post_meta($post->ID, '_event_location', $_POST['_event_location']);
    update_post_meta($post->ID, '_event_date_start', $_POST['_event_date_start']);
    update_post_meta($post->ID, '_event_date_end', $_POST['_event_date_end']);

    update_post_meta($post->ID, '_project_url', $_POST['_project_url']);

}

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

