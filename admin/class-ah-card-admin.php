<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.andreherberth.com
 * @since      1.0.0
 *
 * @package    Ah_Card
 * @subpackage Ah_Card/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Ah_Card
 * @subpackage Ah_Card/admin
 * @author     Andre Herberth <andre.thoresen@gmail.com>
 */
class Ah_Card_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Ah_Card_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Ah_Card_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/ah-card-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Ah_Card_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Ah_Card_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/ah-card-admin.js', array( 'jquery' ), $this->version, false );

	}
    
    public function ah_card_admin_menu(){
        add_menu_page( 'AH-Card Options', 'AH-Card', 'manage_options', 'ah-card', array(&$this, 'ah_card_admin_panel') );
    }

    public function ah_card_admin_panel(){
        include 'partials/ah-card-admin-panel.php';
    }

    public function ah_card_admin_pages($current = "welcome") {

        $tabs = array(
            'welcome'   => __("Welcome", 'ah-card-domain'), 
            'settings'  => __("Settings", 'ah-card-domain'),
            'sync' => __("Sync", "ah-card-domain")
        );
        $html =  '<h2 class="nav-tab-wrapper">';
        foreach( $tabs as $tab => $name ){
            $class = ($tab == $current) ? 'nav-tab-active' : '';
            $html .=  '<a class="nav-tab ' . $class . '" href="?page=ah-card&tab=' . $tab . '">' . $name . '</a>';
        }
        $html .= '</h2>';
        echo $html;
    }


    public function ah_card_update_settings() {
        register_setting( 'ah-card-admin-settings', 'ah-card-name' );
        register_setting( 'ah-card-admin-settings', 'ah-card-roles', array(&$this, 'ah_card_admin_roleval') );
    }
    
    /**
    *
    * ah_card_admin_roleval removes unwanted characters from the roles option for this plugins. 
    * This makes it easier to generate the array needed when hooking into role change event, and when syncing cardnumbers. 
    *
    **/
    private function ah_card_admin_roleval($rolestring) {
        
        if(empty($rolestring)) {
          $output = "no,roles,defined";             
        } else {
          $output = trim($rolestring);     
        }   
        
        return $output;
        
    }
    
    
    /*

    Below is everything related to User Sync option in control panel. 

    */

    public function ah_card_user_sync() {

        //Temporary workaround for S2Members. Will use plugin options soon.
        /*
        $args = array(
        'role__in' => array( "s2member_level1", "s2member_level2", "s2member_level3", "s2member_level4" )
        ); */
        $args = $this->ah_card_get_roles();
        
        $user_query = new WP_User_Query( $args );

        $users = $user_query->get_results();
        $ahnumsynced = 0;
        foreach($users as $user) {
            //$ah_uid = get_userdata( $user->ID );
            $this->ah_card_setpro( $user->ID );
            $ahnumsynced++;
        }
         echo "Number of cards synced: " . $ahnumsynced;
        wp_die();
    }

    public function ah_card_sync_javascript() { ?>
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
    
    private function ah_card_setpro($user_id) {
        global $wpdb;
        /*
        $table_name = $wpdb->prefix . "ahcardnum";
        //Needs to be fixed!
        $wpdb->insert( 
            $table_name, 
            array( 
                'cardid' => '', 
                'uid' => $user_id, 
                'active' => TRUE, 
            ) 
        );
        */
        $sql = "INSERT INTO {$wpdb->prefix}ahcardnum (cardid,uid,active) VALUES ('',{$user_id},TRUE) ON DUPLICATE KEY UPDATE active=TRUE; ";
        // var_dump($sql); // debug
        $sql = $wpdb->prepare($sql);
        // var_dump($sql); // debug
        $wpdb->query($sql);
        
        //Update Meta Information with the correct card number. 
        $this->ah_card_setmeta($user_id);
    }
    
    function ah_card_setmeta($user_id) { 
        global $wpdb;
        $table_name = $wpdb->prefix . "ahcardnum"; 

        $card_id = $wpdb->get_var( "SELECT cardid FROM $table_name WHERE uid = $user_id" );

        $card_number = $this->ah_card_number($card_id);

        //Making sure the card ID result is indeed correct before putting it into the meta slot. 
        if (strlen($card_number) != 6) {

        } else {
            add_user_meta( $user_id, '_ah_card_number', $card_number, true );   
        }
    }

    public function ah_card_dashboard_meta( $ah_c_user ) {
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
    
    private function ah_card_number($card_id) {
    
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
    
    public function ah_card_set_user_role($this_id, $role) {
        
        // $ah_s2roles = array("s2member_level1", "s2member_level2", "s2member_level3", "s2member_level4", "Contributor");
        $ah_rolearray = ah_card_get_roles();
        if (!in_array($role, $ah_rolearray)) {
            //TODO: Make sure dbfield Active is set to FALSE. To be used later. 
            delete_user_meta( $this_id, '_ah_card_number' );
        } else {
            $this->ah_card_setpro($this_id);
        }
    }
    
    
    
    
    private function ah_card_list_roles(){ 
    ?>
        <table class="wp-list-table widefat fixed striped users" style="width:300px;"  cellspacing="0">
            <thead>
                <tr>
                    <th id="cb" class="manage-column column-columnname" scope="col">Role Name</th>
                    <th id="columnname" class="manage-column column-columnname" scope="col">Role Slug (Use This)</th>
                </tr>
            </thead>

            <tfoot>
                <tr>
                    <th class="manage-column column-columnname" scope="col">Role Name</th>
                    <th class="manage-column column-columnname" scope="col">Role Slug (Use This)</th>
                </tr>
            </tfoot>
            <tbody>
        <?php
        
        global $wp_roles;
        $table_class = "alternate";
        foreach ( $wp_roles->roles as $key=>$value ) { ?>
              <tr class="<?php $table_class ?>">
                <td class="column-columnname"><?php echo $value['name']; ?></td>
                <td class="column-columnname"><?php echo $key; ?></td>
              </tr>
                <?php 
                    if($table_class == "") {
                      $table_class = "alternate";  
                    }else{
                        $table_class = "";
                    }
                        
                    
            }
        ?> 
    </tbody></table> <?php
        
        
        var_dump(ah_card_get_roles());
    }

    public function ah_card_get_roles() {
        
        $rolestring = get_option( 'ah-card-roles' );
        
        $rolearray = explode(",", $rolestring);
        
        return $rolearray;
        
    }
}