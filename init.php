<?php
require_once 'functions.php';
// Creating the correct database tables. 
register_activation_hook( __FILE__, 'ah_card_activate' );

// Then we initializing adding card number on new PRO memberships.


add_action( 'set_user_role', 'ah_card_set_user_role', 10, 3 );
add_action( 'add_user_role', 'ah_card_set_user_role', 10, 3 );
add_action( 'remove_user_role', 'ah_card_set_user_role', 10, 3 );

// Displaying in wordpress built in profile editor
add_action( 'show_user_profile', 'ah_card_dashboard_meta' );
add_action( 'edit_user_profile', 'ah_card_dashboard_meta' );

// Adding frontside Profile Shortcode to be used on custom profile page. 
add_shortcode ('ah-profile', 'ah_card_profile_shortcode');

//TODO: Admin panel for admin users. Will Contain function to sync all users. Especially good if we are changing the number scheme. 




