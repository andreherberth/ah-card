<?php

/**
 * Fired during plugin activation
 *
 * @link       https://www.andreherberth.com
 * @since      0.8.0
 *
 * @package    Ah_Card
 * @subpackage Ah_Card/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      0.8.0
 * @package    Ah_Card
 * @subpackage Ah_Card/includes
 * @author     Andre Herberth <andre.thoresen@gmail.com>
 */

class Ah_Card_Activator {

	/**
	 * Short Description.
	 *
	 * Long Description.
	 *
	 * @since    0.8.0
	 */
	public static function activate() {
        global $wpdb;
        $table_name = $wpdb->prefix . "ahcardnum"; 
        $charset_collate = $wpdb->get_charset_collate();
        $sql = "CREATE TABLE IF NOT EXISTS $table_name (
        cardid mediumint(9) NOT NULL AUTO_INCREMENT, 
        uid mediumint(9) NOT NULL,
        active bool,
        UNIQUE(uid),
        PRIMARY KEY(cardid)
        ) $charset_collate;";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );
	}

}