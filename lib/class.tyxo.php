<?php

class TyxoClass {

    public static function init() {

        if(!empty(TYXO_TRACKER_BIG_ID)) {
            add_action('wp_print_footer_scripts', array('TyxoClass', 'show_tracker_script_and_data'));
        }

    }

    public static function show_tracker_script_and_data() {

        # Include exec. time
        if(!empty($_SERVER["REQUEST_TIME_FLOAT"])) {
            $exec_time = round(microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"], 3);
            echo '<div style="display: none" id="tyxo-data" data-exec-time="'.$exec_time.'"></div>';
        }

        echo '<script>
(function(i,s,o,g,r,a,m){i[\'TyxoObject\']=r;i[r]=i[r]||function(){
(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
})(window,document,\'script\',\'//s.tyxo.com/c.js\',\'tx\');
tx(\'create\', \'TX-'.TYXO_TRACKER_BIG_ID.'\');
tx(\'pageview\');
</script>';

    }

	/**
	 * Attached to activate_{ plugin_basename( __FILES__ ) } by register_activation_hook()
	 * @static
	 */
	public static function plugin_activation() {

        //add_option('plugin_do_activation_redirect', true);

	}

	/**
	 * Removes all connection options
	 * @static
	 */
	public static function plugin_deactivation( ) {

	}

    public static function generateMenu() {

        $pages          = array();
        $menu_position  = '26.1000';
        $pages[] = add_menu_page(
            'Tyxo Monitoring',
            'Tyxo Monitoring',
            'administrator',
            TYXO_MENU_PREFIX.'visit',
            array('TyxoClass', 'loadOverview'),
            'dashicons-chart-area',
            $menu_position
        );

        $pages[] = add_submenu_page(
            TYXO_MENU_PREFIX.'visit',
            'Uptime',
            'Uptime',
            'administrator',
            TYXO_MENU_PREFIX.'uptime-overview',
            array('TyxoClass', 'loadUptimeOverview')
        );

        $pages[] = add_submenu_page(
            TYXO_MENU_PREFIX.'visit',
            'Status Page',
            'Status Page',
            'administrator',
            TYXO_MENU_PREFIX.'statuspage',
            array('TyxoClass', 'loadStatusPage')
        );

        $pages[] = add_submenu_page(
            TYXO_MENU_PREFIX.'visit',
            'Live Tracker',
            'Live Tracker',
            'administrator',
            TYXO_MENU_PREFIX.'last100',
            array('TyxoClass', 'loadLast100')
        );

        $pages[] = add_submenu_page(
            TYXO_MENU_PREFIX.'visit',
            'Settings',
            'Settings',
            'administrator',
            TYXO_MENU_PREFIX.'settings',
            array('TyxoClass', 'loadSettings')
        );

        /*
        $pages[] = add_options_page(
            'Tyxo Monitoring Settings',
            'Tyxo Monitoring',
            'administrator',
            TYXO_MENU_PREFIX.'settings',
            array('TyxoClass', 'loadSettings')
        );
        */

        foreach($pages as $page) {
            add_action( 'admin_print_scripts-' . $page, array('TyxoClass', 'registerPluginScripts' ));
            add_action( 'admin_print_styles-' . $page, array('TyxoClass', 'registerPluginStyles'));
        }


        //add_pages_page('My Plugin Pages', 'My Plugin', 'read', 'tyxo-some-page', array('TyxoClass', 'loadSomePage'));

    }


    public static function loadOverview() {

    require_once TYXO_PLUGIN_LIB_DIR.'pages/overview.php';
    }

    public static function loadLast100() {

    require_once TYXO_PLUGIN_LIB_DIR.'pages/tracker/last100.php';
    }


    public static function loadSettings() {

    require_once TYXO_PLUGIN_LIB_DIR.'settings/settings.php';
    }

    public static function loadUptimeOverview() {

    require_once TYXO_PLUGIN_LIB_DIR.'pages/uptime/overview.php';
    }

    public static function loadStatusPage() {

        require_once TYXO_PLUGIN_LIB_DIR.'pages/statuspage/overview.php';
    }

	public static function registerPluginStyles() {

		wp_register_style('tyxo-font-lato', 'https://fonts.googleapis.com/css?family=Lato:400,300,700,900');
		wp_enqueue_style('tyxo-font-lato');

        wp_register_style('tyxo-base-css', TYXO_PLUGIN_LIB_HTTP.'static/css/style.css');
        wp_enqueue_style('tyxo-base-css');

        wp_register_style('tyxo-font-awesome', TYXO_PLUGIN_LIB_HTTP.'static/css/font-awesome.min.css');
        wp_enqueue_style('tyxo-font-awesome');

        wp_register_style('tyxo-flags', TYXO_PLUGIN_LIB_HTTP.'static/flags/flags.min.css');
        wp_enqueue_style('tyxo-flags');

	}

	public static function registerPluginScripts() {

        wp_enqueue_script('jquery');

		wp_register_script('tyxo-base-js', TYXO_PLUGIN_LIB_HTTP.'static/js/tyxo.js');
		wp_enqueue_script('tyxo-base-js');

		wp_register_script('tyxo-common-js', TYXO_PLUGIN_LIB_HTTP.'static/js/common.js');
		wp_enqueue_script('tyxo-common-js');

        wp_register_script('tyxo-chart-js', TYXO_PLUGIN_LIB_HTTP.'static/js/chart.min.js');
        wp_enqueue_script('tyxo-chart-js');


	}

	public static function apiCall($endpoint, $post_array=array()) {

		$wp_version     = (!empty($GLOBALS['wp_version']))?$GLOBALS['wp_version']:null;

		$args = array(
			'body' => is_array($post_array)?$post_array:array(),
			'timeout' => '4',
			'redirection' => '3',
			'httpversion' => '1.1',
			'blocking' => true,
			'headers' => array('tyxo-plugin-version' => TYXO_VERSION, 'wp-version' => $wp_version, 'api-key' => TYXO_API_KEY, 'profile-id' => TYXO_PROFILE_ID),
			'cookies' => array()
		);

		$response       = json_decode(wp_json_encode(wp_remote_post(TYXO_API_BASE.$endpoint, $args)), true);
		$api_response   = (!empty($response['body']))?json_decode($response['body'], true):array();

	return !empty($api_response['data'])?$api_response['data']:array();
	}

}
