<?php
/**
 * Plugin initialization
 */

 define( 'CTG_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
 define( 'CTG_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
 define( 'CTG_PLUGIN_DIR', dirname( plugin_dir_url( __FILE__ ) ) );

 define ( 'WP_NATIVE_THUMB_SIZES', [ 'thumbnail', 'medium', 'medium_large', 'large' ] );

 register_activation_hook( __FILE__, 'ctg_activate_plugin' );
 register_deactivation_hook( __FILE__, 'ctg_deactivate_plugin' );
 register_uninstall_hook( __FILE__, 'ctg_uninstall_plugin' );

 require_once CTG_PLUGIN_PATH . 'settings-page.php';
 require_once CTG_PLUGIN_PATH . 'thumbnail-manager.php';
 require_once CTG_PLUGIN_PATH . 'ajax-handler.php';
 require_once CTG_PLUGIN_PATH . 'ctg-functions.php';

 /**
  * Plugin activation
  */
  function ctg_activate_plugin() {

  }

  /**
   * Plugin deactivation
   */
  function ctg_deactivate_plugin() {

  }

  /**
   * Plugin uninstallation
   */
  function ctg_uninstall_plugin() {
    delete_option( 'ctg_custom_image_sizes' );
  }