<?php
/**
 * Plugin Settins Page. UI for user to create custom thumbnails
 */

 
 /** Add the submenu */
 add_action( 'admin_menu', function() {
    add_submenu_page(
        'options-general.php',
        'Custom Image Sizes',
        'Image Sizes',
        'manage_options',
        'ctg-custom-sizes',
        'ctg_render_settngs_page'
    );
 } );

 /**
  * HTML to render on Settings page
  */
 function ctg_render_settngs_page() {
    $sizes = get_option( 'ctg_custom_image_sizes' );
    
    if( file_exists( CTG_PLUGIN_PATH . 'includes/settngs-page.html' ) ) {
        $html      = file_get_contents( CTG_PLUGIN_PATH . 'includes/settings-page.html' );
        $size_list = ctg_get_size_list_table( $sizes );

        // Replace {{size-list-loop}} variable with PHP loop
        str_replace( $html, '{{size-list-loop}}', $size_list );
        echo wp_kses_post( $html, 'custom-thumbnail-generator' );
    }

    wp_enqueue_script( 
        'ctg-admin-js', 
        CTG_PLUGIN_URL . 'assets/ctg-admin.js', 
        [ 'jquery' ], '1.0', true 
    );

    wp_localize_script(
        'ctg-admin-js', 'ctg_vars', array(
            'ajax_url' => admin_url( 'admin-ajax.php' ),
            'nonce'    => wp_create_nonce( 'ctg_nonce' ),
        )
    );
 }

 /**
  * Prepare and return size list table data
  */
 function ctg_get_size_list_table( $sizes ) {
    $size_table_html = '';

    foreach( $sizes as $slug => $size ) {
        $size_table_html.= '<td>' . esc_html( $slug ) . '</td>';
        $size_table_html.= '<td>' . esc_html( $size[ 'width' ] ) . '</td>';
        $size_table_html.= '<td>' . esc_html( $size[ 'height' ] ) . '</td>';
        $size_table_html.= '<td>' . $size[ 'crop' ] ? 'Yes' : 'No' . '</td>';
        $size_table_html.= '<td><button data-slug="' . esc_html( $slug ) . '" class="button delete-size">Remove</button></td>';
    }
    
    return $size_table_html === '' ? '<td colspan="6">No custom image size found!</td>' : $size_table_html;
 }