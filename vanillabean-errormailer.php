<?php
/*
Plugin Name: Vanilla Bean - Error Mailer
Plugin URI: https://wordpress.org/plugins/vanilla-bean-errormailer/
Description: Email errors unobtrusively from production environment.   --Vanilla Beans for Wordpress by Velvary Pty Ltd
Version: 3.11
Author: Velvary <info@velvary.com.au>
Author URI: https://www.velary.com.au
License: GPLv2
*/
namespace VanillaBeans\ErrorMailer;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( !defined( 'VBEANERRORMAILER_PLUGIN_DIR' ) ) {
	define( 'VBEANERRORMAILER_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
}
if ( !defined( 'VBEANERRORMAILER_PLUGIN_URL' ) ) {
	define( 'VBEANERRORMAILER_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
}
if ( !defined( 'VBEANERRORMAILER_PLUGIN_FILE' ) ) {
	define( 'VBEANERRORMAILER_PLUGIN_FILE', __FILE__ );
}
if ( !defined( 'VBEANERRORMAILER_PLUGIN_VERSION' ) ) {
	define( 'VBEANERRORMAILER_PLUGIN_VERSION', '3.11' );
}
global $vbean_errormailer_db_version;
$vbean_errormailer_db_version = '0.9';



$includes = array(
	'functions.php',
	'vbean-exceptions.php',
        'install.php'
);

$frontend_includes = array();


$adminincludes= array(
    'settings.php'
);

            // Load common includes
            foreach ( $includes as $include ) {
                    require_once( dirname( __FILE__ ) . '/inc/'. $include );
            }
            // Load admin only
	if(is_admin()){		//load admin part
            foreach ( $adminincludes as $admininclude ) {
                require_once( dirname( __FILE__ ) . '/inc/admin/'. $admininclude );
            }
	}else{		//load front part
            foreach ( $frontend_includes as $include ) {
                    require_once( dirname( __FILE__ ) . '/inc/'. $include );
            }
	}

        
add_action('admin_menu', 'VanillaBeans\ErrorMailer\vbean_errormailer_create_menu');


// activation
register_activation_hook(__FILE__, '\VanillaBeans\ErrorMailer\vbean_installerrormailer');


register_deactivation_hook(__FILE__, '\VanillaBeans\ErrorMailer\vbean_errormailer_deactivate');


if(!function_exists('vbean_errormailer_create_menu')){
function vbean_errormailer_create_menu() {

        if ( empty ( $GLOBALS['admin_page_hooks']['vanillabeans-settings'] ) ){
                //create new top-level menu
        	add_menu_page('Vanilla Beans', 'Vanilla Beans', 'administrator', 'vanillabeans-settings', 'VanillaBeans\LiveSettings', VBEANERRORMAILER_PLUGIN_URL.'vicon.png', 4);
            
        }
        add_submenu_page('vanillabeans-settings', 'Error Mailer', 'Error Mailer', 'administrator', __FILE__,'VanillaBeans\ErrorMailer\SettingsPage');
    

	//call register settings function
	add_action( 'admin_init', 'VanillaBeans\ErrorMailer\RegisterSettings' );
}
}       
        

//if(!function_exists('\VanillaBeans\ErrorMailer\vbean_errortest')){
//    function vbean_errortest(){
//         trigger_error("Cannot divide by zero", E_WARNING);
//    }
//}
//add_action('wp_loaded','\VanillaBeans\ErrorMailer\vbean_errortest');