<?php
/*
Plugin Name: Mail Thief
*/

function appthemes_mail_thief( $args ){

	global $current_user;
	get_currentuserinfo();
	
	if( ! current_user_can('administrator') )
		return $args;
	
    if( $current_user->user_email == $args['to'] )
    	$args['to'] = 'example@example.com';
	
	return $args;
}
add_action( 'wp_mail', 'appthemes_mail_thief', 9 );