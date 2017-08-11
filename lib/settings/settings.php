<?php

class TyxoSettings {

	function __construct() {


	    #
        # Check for Logout
        #

        if(!empty(TYXO_API_KEY) && !empty($_GET['logout']) && !empty($_GET['s'] && $_GET['s'] == sha1(TYXO_API_KEY)) ) {

            delete_option('api_key');
            delete_option('profile_big_id');

            delete_option('uptime_big_id');
            delete_option('tracker_big_id');
            delete_option('statuspage_big_id');

            echo '<div class="txwrap">
			
                <div class="panel">
                    <div class="panel-body" style="text-align: center">
                        <img src="'.TYXO_PLUGIN_LIB_HTTP.'static/ic/tyxo_logo_bgwhite.png" height="44">
                        <h2>Log Out Completed</h2>
                    </div>
                </div>
			
		    </div>';
            exit;

        }



        #
        # Check for Save Changes
        #

        if(!empty(TYXO_API_KEY) && !empty($_POST['save'])) {

            $api_response = TyxoClass::apiCall('auth/settings_save.do', $_POST);

        }


	    #
        # Check Authentication
        #

        $api_response   = null;
	    if(!empty(TYXO_API_KEY)) {

            $api_response = TyxoClass::apiCall('auth/sync_settings.do', array());

            # Update user settings
            if(is_array($api_response)) {
                update_option('tyxo_user_settings', wp_json_encode($api_response));
            }

        }

	    if(!empty($api_response['is_authenticated'])) {
            require_once TYXO_PLUGIN_LIB_DIR.'settings/settings_auth.php';
            new TyxoPage($api_response);
        } else {
            require_once TYXO_PLUGIN_LIB_DIR.'settings/settings_none_auth.php';
        }

    return true;
	}


}

new TyxoSettings();
?>