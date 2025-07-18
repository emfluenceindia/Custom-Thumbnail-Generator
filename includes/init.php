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

 require_once CTG_PLUGIN_PATH . 'settings-page.php';
 require_once CTG_PLUGIN_PATH . 'thumbnail-manager.php';
 require_once CTG_PLUGIN_PATH . 'ajax-handler.php';
 require_once CTG_PLUGIN_PATH . 'ctg-functions.php';

  /**
   * Equeue scripts and Localize for AJAX handling
   */
  add_action( 'admin_enqueue_scripts', 'ctg_enqueue_scripts' );
  
  function ctg_enqueue_scripts() {
    /**
     * Enqueue media action scripts
     */
    wp_enqueue_script(
      'ctg-media-actions-script',
      plugin_dir_url( __DIR__ ) . 'assets/ctg-media-actions.js',
      array( 'jquery' ), '1.0'
    );

    /**
     * Localize media action script
     */
    wp_localize_script( 'ctg-media-actions-script', 'CTGMediaAction', array(
      'ajax_url' => admin_url( 'admin-ajax.php' ),
      'nonce'    => wp_create_nonce( 'ctg_media_actions' )
    ) );

    /** Enqueue admin settings page CSS */
    wp_enqueue_style(
        'ctg-admin.css',
        plugin_dir_url( __DIR__ ) .  'assets/ctg-admin.css',
        null,
        '1.0'
    );
  }