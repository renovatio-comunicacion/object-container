<?php
/**
 * Object Container
 *
 * Conteiner for Post, Page...
 *
 * @package   Object_Container
 * @author    joaquin <joaquin@renovatio-comunicacion.com>
 * @license   GPL-2.0+
 * @link      http://www.renovatio-comunicacion.com
 * @copyright 2014 joaquin
 *
 * @wordpress-plugin
 * Plugin Name:       Object Container
 * Plugin URI:        http://www.renovatio-comunicacion.com
 * Description:       Conteiner for Post, Page...
 * Version:           0.0.1
 * Author:            joaquin
 * Author URI:        http://www.renovatio-comunicacion.com
 * Text Domain:       object-container
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
 * GitHub Plugin URI: 
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/*----------------------------------------------------------------------------*
 * Public-Facing Functionality
 *----------------------------------------------------------------------------*/

require_once( plugin_dir_path( __FILE__ ) . 'public/class-object-container.php' );

/*
 * Register hooks that are fired when the plugin is activated or deactivated.
 * When the plugin is deleted, the uninstall.php file is loaded.
 */
register_activation_hook( __FILE__, array( 'Object_Container', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'Object_Container', 'deactivate' ) );

add_action( 'plugins_loaded', array( 'Object_Container', 'get_instance' ) );

/*----------------------------------------------------------------------------*
 * Dashboard and Administrative Functionality
 *----------------------------------------------------------------------------*/

/*
 * @TODO:
 *
 * If you want to include Ajax within the dashboard, change the following
 * conditional to:
 *
 * if ( is_admin() ) {
 *   ...
 * }
 *
 * The code below is intended to to give the lightest footprint possible.
 */
if ( is_admin() ) 
{   
    require_once( plugin_dir_path( __FILE__ ) . 'admin/class-object-container-admin.php' );
    add_action( 'plugins_loaded', array( 'Object_Container_Admin', 'get_instance' ) );
    
    if ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX )
    {
        // Especific to not ajax request
    }
}

$includedWidgets = array('renovatio-widget-page', 'renovatio-widget-post', 'renovatio-widget-free', 'renovatio-widget-calendar', 'renovatio-widget-social');
foreach( $includedWidgets as $iw )
{
    include_once(__DIR__."/widgets/".$iw."/plugin.php");
}
