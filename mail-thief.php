<?php
/*
Plugin Name: Mail Thief
*/

define( 'APPTHEMES_MAIL_THIEF_PTYPE', 'email' );

add_action( 'init', 'appthemes_mail_thief_setup' );
add_action( 'wp_mail', 'appthemes_mail_thief_block_email', 9 );
add_action( 'appthemes_blocked_email', 'appthemes_mail_thief_mail_handler' );

function appthemes_mail_thief_setup(){

	$labels = array(
		'name' => __( 'Emails', APP_TD ),
		'singular_name' => __( 'Email', APP_TD ),
		'add_new' => __( 'Add New', APP_TD ),
		'add_new_item' => __( 'Add New Email', APP_TD ),
		'edit_item' => __( 'Edit Email', APP_TD ),
		'new_item' => __( 'New Email', APP_TD ),
		'view_item' => __( 'View Email', APP_TD ),
		'search_items' => __( 'Search Emails', APP_TD ),
		'not_found' => __( 'No emails found', APP_TD ),
		'not_found_in_trash' => __( 'No emails found in Trash', APP_TD ),
		'parent_item_colon' => __( 'Parent Email:', APP_TD ),
		'menu_name' => __( 'Emails', APP_TD ),
	);

	$args = array(
		'labels' => $labels,
		'hierarchical' => false,
		'supports' => array( 'editor', 'custom-fields' ),
		'public' => false,
		'show_ui' => true,
	);

	register_post_type( APPTHEMES_MAIL_THIEF_PTYPE, $args );

}

function appthemes_mail_thief_block_email( $args ){

	global $current_user;
	get_currentuserinfo();
	
	if( ! current_user_can('administrator') )
		return $args;
	
    if( $current_user->user_email == $args['to'] ){
    
    	do_action( 'appthemes_blocked_email', $args );
    	$args['to'] = 'example@example.com';
    	
    }
	
	return $args;
}

function appthemes_mail_thief_mail_handler( $args ){

	$id = wp_insert_post( array(
		'post_type' => APPTHEMES_MAIL_THIEF_PTYPE,
		'post_title' => $args['subject'],
		'post_content' => $args['message']
	) );
	
	add_post_meta( $id, 'to_address', $args['to'] );

}