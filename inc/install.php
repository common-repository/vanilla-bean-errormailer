<?php

namespace VanillaBeans\ErrorMailer;

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

// <editor-fold defaultstate="collapsed" desc="Install Functions"> 


if (!function_exists('\VanillaBeans\ErrorMailer\vbean_installerrormailer')) {

    function vbean_installerrormailer() {


        global $wpdb;
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        global $vbean_errormailer_db_version;
        $charset_collate = $wpdb->get_charset_collate();

        // <editor-fold defaultstate="collapsed" desc="Schema Definitions"> 

        // 
        // <editor-fold defaultstate="collapsed" desc="Subscription">
        $tablename = $wpdb->prefix . "errormailer_log";
        $sql = "CREATE TABLE $tablename (
                        iderror BIGINT(20) NOT NULL auto_increment,
                        siteurl VARCHAR(1000) NULL,
                        errornumber VARCHAR(10) NULL,
                        errortype VARCHAR(10) NULL,
                        errorline VARCHAR(10) NULL,
                        errorpage VARCHAR(500) NULL,
                        stacktrace LONGTEXT NULL,
                        firsttriggered INT(11),
                        lasttriggered INT(11),
                        dailycount BIGINT DEFAULT 0,
                        totalcount BIGINT DEFAULT 0,
                        PRIMARY KEY  (iderror) ,
                        UNIQUE KEY iderror_UNIQUE (iderror)           
                ) $charset_collate;";
        dbDelta($sql);
        //                echo $wpdb->last_error;  
        //                die();

        // </editor-fold>     
        
        // </editor-fold>


        update_option('vbean_errormailer_db_version', $vbean_errormailer_db_version);
    }

}






// </editor-fold>
// <editor-fold defaultstate="collapsed" desc="Deactivate and uninstall Functions"> 

if (!function_exists('\VanillaBeans\ErrorMailer\vbean_errormailer_deactivate')) {

    function vbean_errormailer_deactivate() {
        if(get_site_url() == 'https://sandbox.wordpress'){ // full uninstall if we are in dev
            vbean_errormailer_uninstall();
        }
        restore_error_handler();
    }

}

if (!function_exists('\VanillaBeans\ErrorMailer\vbean_errormailer_uninstall')) {

    function vbean_errormailer_uninstall() {
        vbean_errormailer_deleteoptions();
        vbean_errormailer_removeallfiles();
        vbean_errormailer_removetables();
    
    }

}

if (!function_exists('\VanillaBeans\ErrorMailer\vbean_errormailer_removetables')) {

    function vbean_errormailer_removetables() {
        global $wpdb;
        $tables = ['errormailer_log']; // list tables here without prefix
        foreach($tables as $table){
            $tablename = $wpdb->prefix . $table;
            $sql = "DROP TABLE IF EXISTS $tablename; "; $wpdb->query($sql);
        }
    }

}

if (!function_exists('\VanillaBeans\ErrorMailer\vbean_errormailer_removedata')) {

    function vbean_errormailer_removedata() {
        
    }

}


if (!function_exists('\VanillaBeans\ErrorMailer\vbean_errormailer_removeallfiles')) {
    function vbean_errormailer_removeallfiles() {
    }
}

if (!function_exists('\VanillaBeans\ErrorMailer\delete_directory')) {

    function delete_directory($dirname) {
        if (is_dir($dirname))
            $dir_handle = opendir($dirname);
        if (!$dir_handle)
            return false;
        while ($file = readdir($dir_handle)) {
            if ($file != "." && $file != "..") {
                if (!is_dir($dirname . "/" . $file))
                    unlink($dirname . "/" . $file);
                else
                    delete_directory($dirname . '/' . $file);
            }
        }
        closedir($dir_handle);
        rmdir($dirname);
        return true;
    }

}


if (!function_exists('\VanillaBeans\ErrorMailer\vbean_errormailer_deleteoptions')) {

    function vbean_errormailer_deleteoptions() {
        $options = ['vbean_errormailer_recipients', 
         'vbean_errormailer_exemptions',
        'vbean_errormailer_subject',
        'vbean_errormailer_fatals',
         'vbean_errormailer_warnings',
         'vbean_errormailer_notices',
         'vbean_errormailer_parse',
         'vbean_errormailer_excludetypes',
         'vbean_errormailer_useslack',
         'vbean_errormailer_slackchannel',
         'vbean_errormailer_slackfrom',
         'vbean_errormailer_slackicon',
            'vbean_errormailer_lasterror',
            'vbean_errormailer_lasterrorcount',
            'vbean_errormailer_lasterrortime'];
        foreach($options as $option){
            delete_option($option);
        }
    }

}





// </editor-fold>
// <editor-fold defaultstate="collapsed" desc="Active plugin installation checks"> 

if (!function_exists('\VanillaBeans\DocketPocket\vbean_errormailer_update_db_check')) {

    function vbean_errormailer_update_db_check() {
        global $vbean_errormailer_db_version;
        if (get_site_option('vbean_errormailer_db_version') != $vbean_errormailer_db_version) {
            vbean_installerrormailer();
        }
    }

}
add_action('plugins_loaded', '\VanillaBeans\ErrorMailer\vbean_errormailer_update_db_check');


        // </editor-fold>

