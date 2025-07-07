<?php
/**
 * Handles AJAX requests
 */

 add_action( 'wp_ajax_ctg_add_custom_size', 'ctg_add_custom_size' );
 add_action( 'wp_ajax_ctg_remove_custom_size', 'ctg_remove_custom_size' );
 add_action( 'wp_ajax_ctg_regenerate_thumbnails', 'ctg_regenerate_thumbnails' );

 function ctg_add_custom_size() {
    check_ajax_referer( 'ctg_nonce' );

    $width  = absint( sanitize_text_field( $_POST[ 'ctg-width' ] ) );
    $height = absint( sanitize_text_field( $_POST[ 'ctg-height' ] ) );
    $crop   = sanitize_text_field( isset( $_POST[ 'ctg-crop' ] ) );
    $slug   = 'ctg_' . $width . 'x' . $height;

    // $width  = absint( sanitize_text_field( $_REQUEST[ 'ctg-width' ] ) );
    // $height = absint( sanitize_text_field( $_REQUEST[ 'ctg-height' ] ) );
    // $crop   = sanitize_text_field( isset( $_REQUEST[ 'ctg-crop' ] ) );
    // $slug   = 'ctg_' . $width . 'x' . $height;

    $sizes = get_option( 'ctg_custom_image_sizes', array() );;

    $str_message = "";

    if( isset( $sizes[ $slug ] ) ) {
        $str_message = "The requested thumbnail size ($width px x $height px) already exists!";
        wp_send_json_error( $str_message );
        die();
    }

    $sizes[ $slug ] = compact( 'width', 'height', 'crop' );
    update_option( 'ctg_custom_image_sizes', $sizes );

    $str_message = "The requested thumbnail size ($width px x $height px) has been generated";
    wp_send_json_success( $str_message );
 }

 function ctg_remove_custom_size() {
    check_ajax_referer( 'ctg_nonce' );
    $slug = sanitize_text_field( $_POST['slug'] );

    $sizes = get_option( 'ctg_custom_image_sizes', array() );

    if( isset( $sizes[ $slug ] ) ) {
        unset( $sizes[ $slug ] );
        update_option( 'ctg_custom_image_sizes', $sizes );
    }

    wp_send_json_success();
 }