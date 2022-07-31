<?php

// Projects.php
// Custom Post Type Declarations for Projects.

class ProjectsPosts{
    public function __construct(){
        add_action('init', array($this, 'register_project_post_type'));
    }

    function register_project_post_type(){
        $args = array(
            'labels' => array(
                'name' => 'Projects',
                'singular_name' => 'Project',
            ),
            'description' => 'Projects',
            'public' => true,
            'has_archive' => true,
            'menu_icon' => 'dashicons-calendar',
            'show_in_rest' => true,
            'can_export' => true,
            'publicly_queryable' => true,
            'supports' => array('title', 'editor', 'thumbnail', 'revisions', 'custom-fields', 'revisions'),
            'show_in_graphql' => true,
            'graphql_name' => 'event',
            'graphql_plural_name' => 'Projects',
            'graphql_single_name' => 'Project',
        );

        register_post_type('project', $args);
    }
}



add_action('admin_init', 'register_project_post_meta_boxes');

function register_project_post_meta_boxes(){
    // If the post type is not a project, return.
    if(get_post_type() !== 'project'){
        return;
    }else{
        add_meta_box(
            "project_post_metadata_events_post",
            "Project Information",
            "project_post_meta_box_events_post",
            "event",
            "side",
            "low"
        );
    }
}

// add_action('save_post', array($this, 'project_register_save_post_meta_boxes'));

// function project_register_save_post_meta_boxes(){
//     global $post;
    
//     if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE){
//         return;
//     }

// }

function project_post_meta_box_events_post(){
    global $post;
    $custom = get_post_custom($post->ID);
	$projectURL = $custom['_project_url'][0];

	echo "<div class='projectinfo' style='display: flex; flex-direction: column;'>
		<label for='_project_url'>Project URL</label>
		<input type='text' style='margin-bottom: 10px;' name='_project_url' id='_project_url' value='".$projectURL."' />
	</div>
	";

    // echo "<label>Start Date</label>";
    // echo "<input type=\"date\" name=\"_event_date_start\" value=\"".$fieldDataStart."\" placeholder=\"Start Date\">";
    // echo "<label>Time</label>";
    // echo "<input type=\"time\" name=\"_event_date_start_time\" value=\"".$fieldDataStartTime."\" placeholder=\"\">";

}

