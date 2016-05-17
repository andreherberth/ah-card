<?php
require_once 'functions.php';

// Starting the Memberchange hooks. 
add_action( 'set_user_role', 'ah_card_set_user_role', 10, 2 );
add_action( 'add_user_role', 'ah_card_set_user_role', 10, 2 );
add_action( 'remove_user_role', 'ah_card_set_user_role', 10, 2 );

// Displaying card number in WP's built in profile editor.
add_action( 'show_user_profile', 'ah_card_dashboard_meta' );
add_action( 'edit_user_profile', 'ah_card_dashboard_meta' );

// Adding frontside Profile Shortcode to be used on custom profile page. 
add_shortcode ('ah-profile', 'ah_card_profile_shortcode');

//Admin panel for admin users. Will Contain function to sync all users. Especially good if we are changing the number scheme. 
add_action('admin_menu', 'ah_card_admin_menu');
add_action( 'admin_init', 'ah_card_update_settings' );
//TODO: Add sync button. Secondly, add field to choose roles that should get the cards. 
add_action( 'admin_footer', 'ah_card_sync_javascript' );
add_action( 'wp_ajax_ah_card_user_sync', 'ah_card_user_sync' );