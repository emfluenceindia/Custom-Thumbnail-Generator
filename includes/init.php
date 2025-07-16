<?php
/**
 * Plugin initialization
 */

 define( 'CTG_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
 define( 'CTG_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
 define( 'CTG_PLUGIN_DIR', dirname( plugin_dir_url( __FILE__ ) ) );
 define( 'CTG_TEMPLATE_DIR', plugin_dir_path( __DIR__ ) . 'templates/' );

 define( 'WP_NATIVE_THUMB_SIZES', [ 'thumbnail', 'medium', 'medium_large', 'large' ] );

 define( 'ALLOWED_TAGS', array(
  'form'     => [ 'action' => [], 'method' => [], 'id' => [], 'class' => [] ],
  'input'    => [ 'type' => [], 'name' => [], 'value' => [], 'checked' => [], 'id' => [], 'class' => [] ],
  'select'   => [ 'name' => [], 'id' => [], 'class' => [] ],
  'option'   => [ 'value' => [], 'selected' => [] ],
  'textarea' => [ 'name' => [], 'id' => [], 'class' => [] ],
  'label'    => [ 'for' => [], 'class' => [] ],
  'div'      => [ 'class' => [], 'id' => [] ],
  'span'     => [ 'class' => [] ],
  'p'        => [],
  'button'   => [ 'type' => [], 'class' => [], 'name' => [], 'value' => [] ]
 ) );

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

  /**
   * Equeue scripts and Localize for AJAX handling
   */
  add_action( 'admin_enqueue_scripts', 'ctg_enqueue_scripts' );
  
  function ctg_enqueue_scripts() {
    wp_enqueue_script( 
      'ctg-generator-scirpt', 
      plugin_dir_url( __DIR__ ) . 'assets/ctg-thumbnail-generate.js', 
      array( 'jquery' ), '1.0' 
    );

    wp_localize_script( 'ctg-generator-scirpt', 'CTGenerator', array(
      'ajax_url' => admin_url( 'admin-ajax.php' ),
      'nonce'    => wp_create_nonce( 'ctg_generator_nonce' )
    ) );
  }