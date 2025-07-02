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

    return $sizes;
 }

 /**
  * Thumbnail regeneration method called via AJAX
  */
 function ctg_regenerate_thumbnails() {
    check_ajax_referer( 'ctg_nonce' );

    $attachments = get_posts( array(
        'post_type'      => 'attachment',
        'post_mime_type' => 'image',
        'post_status'    => 'inherit',
        'posts_per_page' => -1
    ) );

    foreach( $attachments as $attachment ) {
        $file = get_attached_file( $attachment->ID );
        $meta = wp_generate_attachment_metadata( $attachment->ID, $file );
        wp_update_attachment_metadata( $attachment->ID, $meta );
    }

    wp_send_json_success( 'Regeneration Complete!' );
 }