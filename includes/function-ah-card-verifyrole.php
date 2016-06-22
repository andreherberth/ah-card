<?php

if (!function_exists('ah_card_role_verify')) {
    
    function ah_card_role_verify( $user_id ) {

                $rolestring = get_option( 'ah-card-roles' );
                $rolearray = explode(",", $rolestring);
                $user = get_userdata( $user_id );

                if (!( $user_id == 0 )) {

                    if ( empty( array_intersect($rolearray, $user->roles ) ) ) {
                    return FALSE;
                    } else {
                       return TRUE;
                    } 
                } else {
                    return FALSE;
                }
            }
}