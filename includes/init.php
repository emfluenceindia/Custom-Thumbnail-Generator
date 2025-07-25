<?php
/**
 * Plugin initialization
 */

 define( 'CTG_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
 define( 'CTG_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
 define( 'CTG_PLUGIN_DIR', dirname( plugin_dir_url( __FILE__ ) ) );
 define( 'CTG_TEMPLATE_DIR', plugin_dir_path( __DIR__ ) . 'templates/' );

 define( 'WP_NATIVE_THUMB_SIZES', [ 'thumbnail', 'medium', 'medium_large', 'large' ] );

 define( 'CTG_LOGO', plugin_dir_url( __DIR__ ) . 'assets/images/icon-64x64.png' );

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
 require_once CTG_PLUGIN_PATH . 'class-ctg-functions.php';
 
 require_once CTG_PLUGIN_PATH . 'class-ajax-handlers.php';
 new CTG_Ajax_Handlers();


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
      // plugin_dir_url( __DIR__ ) . 'assets/js/ctg-media-actions.min.js',
      plugin_dir_url( __DIR__ ) . 'assets/raw/ctg-media-actions.js',
      array( 'jquery' ), '1.0', true
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
        plugin_dir_url( __DIR__ ) .  'assets/css/ctg-admin.min.css',
        null,
        '1.0'
    );

    /** Enqueue FontSAwesome */
    wp_enqueue_style(
      'ctg-fa-css',
      'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css'
    );
 }

 /**
  * Register custom image sizes in WordPress
  */
 add_action( 'init', 'ctg_register_custom_sizes' );

 function ctg_register_custom_sizes() {
   $sizes = get_option( 'ctg_custom_image_sizes', array() );

   foreach( $sizes as $slug => $args ) {
       add_image_size( $slug, $args[ 'width' ], $args[ 'height' ], $args[ 'crop' ] );
   }
 }

 
 /**
  * Make custom image sizes available in post
  */
 add_filter( 'image_size_names_choose', 'ctg_add_custom_sizes_to_media_dropdown' );

 function ctg_add_custom_sizes_to_media_dropdown( $sizes ) {
   $custom_sizes = get_option( 'ctg_custom_image_sizes', array() );
   foreach( $custom_sizes as $slug => $args ) {
       $sizes[ $slug ] = $slug;
   }

   return $sizes;
 }