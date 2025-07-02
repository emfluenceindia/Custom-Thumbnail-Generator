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

    $html      = file_get_contents( CTG_PLUGIN_PATH . 'settings-page.html' );
    // $size_list = ctg_get_size_list_table( $sizes );
    $size_list = array(
        'size_table' => ctg_get_size_list_table( $sizes )
    );

    foreach( $size_list as $key => $value ) {
        $html = str_replace( '{{' . $key . '}}', $value, $html );
    }

    echo $html;

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

    // foreach( $sizes as $slug => $size ) {
    //     $size_table_html.= '<td>' . esc_html( $slug ) . '</td>';
    //     $size_table_html.= '<td>' . esc_html( $size[ 'width' ] ) . '</td>';
    //     $size_table_html.= '<td>' . esc_html( $size[ 'height' ] ) . '</td>';
    //     $size_table_html.= '<td>' . $size[ 'crop' ] ? 'Yes' : 'No' . '</td>';
    //     $size_table_html.= '<td><button data-slug="' . esc_html( $slug ) . '" class="button delete-size">Remove</button></td>';
    // }
    
    return $size_table_html === '' ? '<td class="ctg-warning" colspan="6">No custom image size found. Add one now.</td>' : $size_table_html;
 }