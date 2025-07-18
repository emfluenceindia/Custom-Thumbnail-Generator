<?php
/**
 * Thumbnail Management
 */

 add_action( 'init', 'ctg_register_custom_sizes' );

 function ctg_register_custom_sizes() {
    $sizes = get_option( 'ctg_custom_image_sizes', array() );

    foreach( $sizes as $slug => $args ) {
        add_image_size( $slug, $args[ 'width' ], $args[ 'height' ], $args[ 'crop' ] );
    }
 }

 add_filter( 'image_size_names_choose', 'ctg_add_to_media_dropdown' );
 
 function ctg_add_to_media_dropdown( $sizes ) {
    $custom_sizes = get_option( 'ctg_custom_image_sizes', array() );
    foreach( $custom_sizes as $slug => $args ) {
        $sizes[ $slug ] = $slug;
    }
 }