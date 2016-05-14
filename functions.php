<?php

function ah_card_profile_shortcode() {
    // Utilizing template files and allowing edits in themes/child themes. 
    
    if ( current_user_can('s2member_level1') || current_user_can('s2member_level2') || current_user_can('s2member_level3') || current_user_can('s2member_level4')) {
        
        if ( $overridden_template = locate_template( 'ah-card-pro-custom.php' ) ) {
            load_template( $overridden_template );
        } 
        else {
            load_template( dirname( __FILE__ ) . '/templates/ah-card-pro.php' );
        }
        
    }
    else {
        
        if ( $overridden_template = locate_template( 'ah-card-sub-custom.php' ) ) {
            load_template( $overridden_template );
        } 
        else {
            load_template( dirname( __FILE__ ) . '/templates/ah-card-sub.php' );
        }
        
    }
}

function ah_card_number($card_id) {
    
        $ah_sl = strlen($card_id);
        
        switch ($ah_sl) {
            case 0:
                return "Error: UserID unknown";
                break;
            case 1:
                $output = "50000";
                $output .= $card_id;
                break;
            case 2:
                $output = "5000";
                $output .= $card_id;
                break;
            case 3:
                $output = "500";
                $output .= $card_id;
                break;
            case 4:
                $output = "50";
                $output .= $card_id;
                break;
            case 5:
                $output = "5";
                $output .= $card_id;
                break;
            case 6:
                $output = $card_id;
                break;
            default: $output = "An error occured. Number could not be generated";
        }
        
        return $output;
}
    
function ah_card_setpro($user_id) {
     /* To be used as hook or syncing the plugin. */
     //   add_user_meta( $user_id, '_ah_card_number', ah_card_number($user_id), true );
    global $wpdb;
	
	$table_name = $wpdb->prefix . "ahcardnum";
	
	$wpdb->insert( 
		$table_name, 
		array( 
			'cardid' => '', 
			'uid' => $user_id, 
			'active' => TRUE, 
		) 
	);
    
    ah_card_setmeta($user_id);
}

function ah_card_setmeta($user_id) {
    global $wpdb;
    $table_name = $wpdb->prefix . "ahcardnum"; 
    
    $card_id = $wpdb->get_var( "SELECT cardid FROM $table_name WHERE uid = $user_id" );
    
    $card_number = ah_card_number($card_id);
    
    //Making sure the card ID result is indeed correct before putting it into the meta slot. 
    if (strlen($card_number) != 6) {
          
    } else {
        add_user_meta( $user_id, '_ah_card_number', $card_number, true );   
    }
}

function ah_card_dashboard_meta( $ah_c_user ) {
        ?>
        <h3>GreenKardPro Card</h3>
        <table class="form-table">
            <tr>
                <th><label>Your GreenKardPro Number</label></th>
                <td><input type="text" value="<?php echo get_user_meta( $ah_c_user->ID, '_ah_card_number', true ); ?>" class="regular-text" readonly=readonly /></td>
            </tr>
        </table>
        <?php
}

function ah_card_activate(){
  /* http://codex.wordpress.org/Function_Reference/register_activation_hook - Adding on activate function */ 
    
/*
    TODO: Generate tables if not already done. Also generate plugin option for version number. 
    Should include if plugin_option not set, do full install. If set, do nothing. 
    
    Can in the future contain per version upgrades.
*/
  // https://codex.wordpress.org/Creating_Tables_with_Plugins
    global $wpdb;
    $table_name = $wpdb->prefix . "ahcardnum"; 
    
    $sql = "CREATE TABLE $table_name (
    cardid mediumint(9) NOT NULL AUTO_INCREMENT,
    uid mediumint(9) NOT NULL,
    active bool,
    UNIQUE KEY cardid (cardid)) $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );
}
    
function ah_card_set_user_role($this_id, $role) {
    
    $ah_s2roles = array("s2member_level1", "s2member_level2", "s2member_level3", "s2member_level4", "Contributor");

    if (!in_array($role, $ah_s2roles)) {
        //TODO: Add code that removes 
        delete_user_meta( $this_id, '_ah_card_number' );
        
        
        
    } else {
        ah_card_setpro($this_id);
    }

}

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
    <?php }
}

function ah_card_admin_pages($current = "welcome") {
    
    $tabs = array(
        'welcome'   => __("Welcome", 'ah-card-domain'), 
        'settings'  => __("Settings", 'ah-card-domain')
    );
    $html =  '<h2 class="nav-tab-wrapper">';
    foreach( $tabs as $tab => $name ){
        $class = ($tab == $current) ? 'nav-tab-active' : '';
        $html .=  '<a class="nav-tab ' . $class . '" href="?page=ah-card&tab=' . $tab . '">' . $name . '</a>';
    }
    $html .= '</h2>';
    echo $html;
}

if( !function_exists("ah_card_update_settings") ) {
function ah_card_update_settings() {
    register_setting( 'ah-card-admin-settings', 'ah-card-name' );
    register_setting( 'ah-card-admin-settings', 'ah-card-roles' );
}
}