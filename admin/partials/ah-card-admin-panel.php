<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://www.andreherberth.com
 * @since      1.0.0
 *
 * @package    Ah_Card
 * @subpackage Ah_Card/admin/partials
 */

// This file should primarily consist of HTML with a little bit of PHP

$tab = (!empty($_GET['tab']))? esc_attr($_GET['tab']) : 'welcome';
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
        <?php } elseif ($tab == 'sync' ) { ?>
            <p>Syncronize Current PRO members by pressing the button below. </p>
            <?php submit_button( "Run User Sync", "primary", "ahsyncbtn" ); 
        }