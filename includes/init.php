<?php
/**
 * Plugin initialization
 */

 define( 'CTGEN_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
 define( 'CTGEN_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
 define( 'CTGEN_TEMPLATE_DIR', plugin_dir_path( __DIR__ ) . 'templates/' );
 define( 'CTGEN_PLUGIN_DIR', plugin_dir_url( __DIR__ ) );

 define( 'WP_NATIVE_THUMB_SIZES', [ 'thumbnail', 'medium', 'medium_large', 'large' ] );

 define( 'CTGEN_LOGO', CTGEN_PLUGIN_DIR . 'assets/images/icon-80x80.png' );

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

 require_once CTGEN_PLUGIN_PATH . 'settings-page.php';
 require_once CTGEN_PLUGIN_PATH . 'class-ctgen-functions.php';
 
 require_once CTGEN_PLUGIN_PATH . 'class-ajax-handlers.php';
 new CTGEN_Ajax_Handlers();

 /**
  * Equeue scripts and Localize for AJAX handling
  */
 add_action( 'admin_enqueue_scripts', 'ctgen_enqueue_scripts' );
  
 function ctgen_enqueue_scripts() {
    /**
     * Enqueue media action scripts
     */
    wp_enqueue_script(
      'ctgen-media-actions-script',
      CTGEN_PLUGIN_DIR . 'assets/js/ctgen-media-actions.min.js',
      array( 'jquery' ), '1.0', true
    );

    /**
     * Localize media action script
     */
    wp_localize_script( 'ctgen-media-actions-script', 'CTGENMediaAction', array(
      'ajax_url' => admin_url( 'admin-ajax.php' ),
      'nonce'    => wp_create_nonce( 'ctgen_media_actions' )
    ) );

    /** Enqueue admin settings page CSS */
    wp_enqueue_style(
        'ctg-admin.css',
        CTGEN_PLUGIN_DIR .  'assets/css/ctgen-admin.min.css',
        null,
        '1.0'
    );

    /** Enqueue FontSAwesome */
    wp_enqueue_style(
      'ctg-fa-css',
      CTGEN_PLUGIN_DIR . 'assets/css/font-awesome.min.css',
      null, '4.7.0', 'all'
    );
 }

 /**
  * Register custom image sizes in WordPress
  */
 add_action( 'init', 'ctgen_register_custom_sizes' );

 function ctgen_register_custom_sizes() {
   $sizes = get_option( 'ctgen_custom_image_sizes', array() );

   foreach( $sizes as $slug => $args ) {
       add_image_size( $slug, $args[ 'width' ], $args[ 'height' ], $args[ 'crop' ] );
   }
 }

 
 /**
  * Make custom image sizes available in post
  */
 add_filter( 'image_size_names_choose', 'ctgen_add_custom_sizes_to_media_dropdown' );

 function ctgen_add_custom_sizes_to_media_dropdown( $sizes ) {
   $custom_sizes = get_option( 'ctgen_custom_image_sizes', array() );
   foreach( $custom_sizes as $slug => $args ) {
       $sizes[ $slug ] = $slug;
   }

   return $sizes;
 }