<?php
/**
 * Plugin Name:       Extend Premier
 * Plugin URI:        http://themes.tradesouthwest.com/wordpress/plugins/
 * Description:       Extended functionality for coupons and plugins by Larry at codeable
 * Author:            tradesouthwestgmailcom
 * Author URI:        https://tradesouthwest.com tradesouthwest@gmail.com
 * Version:           1.0.1
 * License:           GPLv2 or later
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.html
 * Requires at least: 4.5
 * Tested up to:      5.3.1
 * Requires PHP:      5.4
 * Text Domain:       extend-premier
 * Domain Path:       /languages
*/

// exit if file is called directly
if ( ! defined( 'ABSPATH' ) ) {	exit; }
/** 
 * Constants
 * 
 * @param EXTEND_PREMIER_VER         Using bumped ver.
 * @param EXTEND_PREMIER_URL         Base path
 * @since 1.0.0 
 */
if( !defined( 'EXTEND_PREMIER_VER' )) { 
    define( 'EXTEND_PREMIER_VER', time() ); }
if( !defined( 'EXTEND_PREMIER_URL' )) { 
    define( 'EXTEND_PREMIER_URL', plugin_dir_url(__FILE__)); }

    // Start the plugin when it is loaded.
    register_activation_hook(   __FILE__, 'extend_premier_plugin_activation' );
    register_deactivation_hook( __FILE__, 'extend_premier_plugin_deactivation' );
  
/**
 * Activate/deactivate hooks
 * 
 */
function extend_premier_plugin_activation() 
{

    return false;
}
function extend_premier_plugin_deactivation() 
{
    return false;
} 

/** 
 * Admin side specific
 *
 * Enqueue admin only scripts 
 */ 
add_action( 'admin_enqueue_scripts', 'extend_premier_load_admin_scripts' );   
function extend_premier_load_admin_scripts() 
{
    /*
     * Enqueue styles */
    wp_enqueue_style( 'extend-premier-admin',  
        plugin_dir_url(__FILE__) . 'css/extend-premier-admin.css',
        array(), 
        EXTEND_PREMIER_VER, 
        false 
    );

}

/**
 * Plugin Scripts
 *
 * Register and Enqueues plugin scripts
 *
 * @since 1.0.0
 */
function extend_premier_addtosite_scripts()
{
    wp_enqueue_style( 'extend-premier-public',  
        plugin_dir_url(__FILE__) . 'css/premier-extend-public.css',
        array(), 
        EXTEND_PREMIER_VER, 
        false 
    );
    /*
    wp_enqueue_script( 'extend-premier-front', 
        plugin_dir_url( __FILE__ ) . 'js/extend-premier-form.js', 
        array( ), 
        EXTEND_PREMIER_VER, 
        true 
    ); */
}
add_action( 'wp_enqueue_scripts', 'extend_premier_addtosite_scripts' );

/**
 * Define the locale for this plugin for internationalization.
 * Set the domain and register the hook with WordPress.
 *
 * @uses slug `extend-premier`
 */
//add_action( 'plugins_loaded', 'extend_premier_load_plugin_textdomain' );
     
function extend_premier_load_plugin_textdomain() 
{

    $plugin_dir = basename( dirname(__FILE__) ) .'/languages';
                  load_plugin_textdomain( 'extend-quiickcab', false, $plugin_dir );
}

//require_once ( plugin_dir_path(__FILE__) . 'inc/extend-premier-functions.php' );
require_once ( plugin_dir_path(__FILE__) . 'inc/extend-premier-admin.php' );
