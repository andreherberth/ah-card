<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.andreherberth.com
 * @since      0.8.1
 *
 * @package    Ah_Card
 * @subpackage Ah_Card/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Ah_Card
 * @subpackage Ah_Card/public
 * @author     Andre Herberth <andre.thoresen@gmail.com>
 */
class Ah_Card_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    0.8.1
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    0.8.1
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    0.8.1
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    0.8.1
	 */
	public function enqueue_styles() {

		/**
		 * Load Public faceing CSS and allow override from current theme or Childtheme. 
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Ah_Card_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Ah_Card_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
        
        if(file_exists ( get_stylesheet_directory() . ah-card-public.css )) {
            wp_enqueue_style( $this->plugin_name, get_stylesheet_directory_uri() . '/ah-card-public.css', array(), $this->version, 'all' );
        } else {
            wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/ah-card-public.css', array(), $this->version, 'all' );
            
        }
        
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    0.8.1
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

		// wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/ah-card-public.js', array( 'jquery' ), $this->version, false );

	}
    
    /**
	 * This function output content to a display card shortcode. 
     *
     * Allows
	 *
	 * @since    0.8.1
	 */  
    public function ah_card_profile_shortcode() {
        // Utilizing template files and allowing edits in themes/child themes. 

        if ( current_user_can('s2member_level1') || current_user_can('s2member_level2') || current_user_can('s2member_level3') || current_user_can('s2member_level4')) {

            if ( $overridden_template = locate_template( 'ah-card-pro-custom.php' ) ) {
                load_template( $overridden_template );
            } 
            else {
                load_template( plugin_dir_path( __FILE__ ) . 'partials/ah-card-pro.php' );
            }

        }
        else {

            if ( $overridden_template = locate_template( 'ah-card-sub-custom.php' ) ) {
                load_template( $overridden_template );
            } 
            else {
                load_template( plugin_dir_path( __FILE__ ) . 'partials/ah-card-sub.php' );
            }

        }
    }
    
    public function register_shortcodes() {
        add_shortcode( 'ah-profile', array( $this, 'ah_card_profile_shortcode') );
    }

}
