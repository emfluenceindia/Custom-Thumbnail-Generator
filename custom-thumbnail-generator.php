<?php
/**
 * Plugin Name: Custom Thumbnail Generator
 * Description: Create theme-independent custom thumbnail sizes with Regenerate feature.
 * Version: 1.0.0
 * Author: Subrata Sarkar
 * Author URI: https://profiles.wordpress.org/subrataemfluence
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: custom-thumbnail-generator
 * Method Prefix: ctg_
 */

 if( ! defined( 'ABSPATH' ) ) exit;

 require_once plugin_dir_path( __FILE__ ) . 'includes/init.php';

 /**
 * Add a Settings link (plugin action link) under the plugin name on main plugin page
 * 
 * @param  array $links An array of plugin action links
 * @return array An updated array of plugin action links
 */
function ctg_add_plugin_settings_links( $links ) {
    // Build the URL
    $settings_url = admin_url( 'options-general.php?page=ctg-custom-sizes' );

    // Create the HTML for the link
    $settings_link = '<a href="' . esc_url( $settings_url ) . '">' . __( 'Settings', 'custom-thumbnail-generator' ) . '</a>';

    // Add the link to the beginning of the links array
    array_unshift( $links, $settings_link );

    // Return the modified $links array
    return $links;
}

add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'ctg_add_plugin_settings_links' );