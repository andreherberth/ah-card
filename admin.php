<?php

function ah_card_admin_menu(){
        add_menu_page( 'AH-Card Options', 'AH-Card', 'manage_options', 'ah-card', 'ah_card_admin_panel' );
}

function ah_card_admin_panel(){
    $tab = (!empty($_GET['tab']))? esc_attr($_GET['tab']) : 'first';
    ah_card_admin_pages($tab);

    if($tab == 'welcome' ) { ?>
	<style>#ahwrap{background-color:#ffffff; padding: 20px 0 20px 20px; box-sizing: border-box; margin-top:0px;}</style>
	<div id="ahwrap">
        <h1>Welcome</h1>
        <p>Welcome to the AH-Card Plugin. - This plugin generates unique card numbers for members when their role changes.</p>
	</div>    
<?php
    }
    elseif($tab == 'settings' ) {
    ?> 
        <style>#ahwrap{background-color:#ffffff; padding: 20px 0 20px 20px; box-sizing: border-box; margin-top:0px;}</style>
	<div id="ahwrap">  
	<h1>Card Settings</h1>
          <form method="post" action="options.php">
            <?php settings_fields( 'ah-card-admin-settings' ); ?>
            <?php do_settings_sections( 'ah-card-admin-settings' ); ?>
            <table class="form-table">
              <tr valign="top">
              <th scope="row">Card Name</th>
              <td><input type="text" name="ah-card-name" value="<?php echo get_option( 'ah-card-name' ); ?>"/></td>
              </tr>
                <tr valign="top">
                <th scope="row">Generate for the following roles sepearted by comma</th>
              <td><input type="text" name="ah-card-roles" value="<?php echo get_option( 'ah-card-roles' ); ?>"/></td>
                </tr>
            </table>
            <?php submit_button(); ?>
          </form>
	</div>
    <?php } elseif {
        <p>Syncronize Current PRO members by pressing the button below. </p>
        <?php submit_button( "Run User Sync", "primary", "ahsyncbtn" ) ?>
    }
}

function ah_card_admin_pages($current = "welcome") {
    
    $tabs = array(
        'welcome'   => __("Welcome", 'ah-card-domain'), 
        'settings'  => __("Settings", 'ah-card-domain'),
        'Sync' => __("Sync", "ah-card-domain");
    );
    $html =  '<h2 class="nav-tab-wrapper">';
    foreach( $tabs as $tab => $name ){
        $class = ($tab == $current) ? 'nav-tab-active' : '';
        $html .=  '<a class="nav-tab ' . $class . '" href="?page=ah-card&tab=' . $tab . '">' . $name . '</a>';
    }
    $html .= '</h2>';
    echo $html;
}

if( !function_exists( "ah_card_update_settings" ) ) {
function ah_card_update_settings() {
    register_setting( 'ah-card-admin-settings', 'ah-card-name' );
    register_setting( 'ah-card-admin-settings', 'ah-card-roles' );
}
}




/*

Below is everything related to User Sync option in control panel. 

*/

function ah_card_user_sync() {
    
    //Temporary workaround for S2Members. Will use plugin options soon.
    $args = array(
	'role__in' => array( "s2member_level1", "s2member_level2", "s2member_level3", "s2member_level4" )
    );
    $user_query = new WP_User_Query( $args );
    
    $users = $user_query->get_results();
    $ahnumsynced = 0;
    foreach($users as $user) {
        
        $ah_uid = get_userdata( $user->ID );
        ah_card_setpro( $ah_uid );
        $ahnumsynced++
    }
     echo "Number of users synced: " . $ahnumsynced;
}

function ah_card_sync_javascript() { ?>
	<script type="text/javascript" >
	jQuery("#ahsyncbtn").click(function($) {
		var data = {
			'action': 'ah_card_user_sync',
		};

		// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
		jQuery.post(ajaxurl, data, function(response) {
			alert('Got this from the server: ' + response);
		});
	});
	</script> <?php
}