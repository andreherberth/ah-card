<?php
require_once 'functions.php';
// Creating the correct database tables. 
register_activation_hook( __FILE__, 'ah_card_activate' );

// Then we initializing adding card number on new PRO memberships.


add_action( 'user_register', 'ah_card_setnum', 10, 1 );
// Displaying in wordpress built in profile editor
add_action( 'show_user_profile', 'ah_card_dashboard_meta' );
add_action( 'edit_user_profile', 'ah_card_dashboard_meta' );

// Adding frontside Profile Shortcode to be used on custom profile page. 
add_shortcode ('ah-profile', 'ah_card_profile_shortcode');

//TODO: Admin panel for admin users. Will Contain function to sync all users. Especially good if we are changing the number scheme. 




