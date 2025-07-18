<?php
/**
 * Handles AJAX requests
 */

 add_action( 'wp_ajax_ctg_add_custom_size', 'ctg_ajax_add_custom_size' );
 add_action( 'wp_ajax_ctg_remove_custom_size', 'ctg_ajax_remove_custom_size' );

 add_action( 'wp_ajax_ctg_get_attachments', 'ctg_get_all_attachments' );
 add_action( 'wp_ajax_ctg_regenerate_single', 'ctg_regenerate_single_attachment_thumbnail' );
//  add_action( 'wp_ajax_ctg_regenerate_thumbnails', 'ctg_regenerate_thumbnails' );

 function ctg_ajax_add_custom_size() {
    // check_ajax_referer( 'ctg_form_action', 'ctg_form_nonce' );
    check_ajax_referer( 'ctg_media_actions', 'security' );

    $fld_validation_message = __( 'Not all field values are supplied!', 'custom-thumbnail-generator' );

    if( ! isset( $_POST[ 'ctg-width' ] ) || ! isset( $_POST[ 'ctg-height' ] ) ) {
        wp_send_json_error( $fld_validation_message );
    }

    $width  = absint( sanitize_text_field( wp_unslash( $_POST[ 'ctg-width' ] ) ) );
    $height = absint( sanitize_text_field( wp_unslash( $_POST[ 'ctg-height' ] ) ) );
    $crop   = sanitize_text_field( isset( $_POST[ 'ctg-crop' ] ) );
    $slug   = 'ctg_' . $width . 'x' . $height;

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

 function ctg_get_all_attachments() {
    // check_ajax_referer( 'ctg_generator_nonce', 'security' );
    check_ajax_referer( 'ctg_media_actions', 'security' );

    $query_args = array(
        'post_type'      => 'attachment',
        'post_status'    => 'inherit',
        'posts_per_page' => -1,
        'orderby'        => 'DATE',
        'order'          => 'ASC',
        'fields'         => 'ids',
    );

    $query = new WP_Query( $query_args );

    wp_send_json_success( array( 'ids' => $query->posts ) );
 }

 function ctg_regenerate_single_attachment_thumbnail() {
    // check_ajax_referer( 'ctg_generator_nonce', 'security' );
    check_ajax_referer( 'ctg_media_actions', 'security' );

    $id = (int)$_POST['attachment_id'];
    if( ! $id ) {
        wp_send_json_error( array( 'message' => 'Invalid ID' ) );
    }

    require_once ABSPATH . 'wp-admin/includes/image.php';
    $metadata = wp_generate_attachment_metadata( $id, get_attached_file( $id ) );

    if( $metadata ) {
        wp_update_attachment_metadata( $id, $metadata );
        wp_send_json_success( array( 'message' => 'Success' ) );
    } else {
        wp_send_json_error( array( 'message' => 'Failed' ) );
    }
 }

 function ctg_ajax_remove_custom_size() {
    // check_ajax_referer( 'ctg_removal_nonce', 'security' );
    check_ajax_referer( 'ctg_media_actions', 'security' );

    if( ! isset( $_POST['slug'] ) || empty( $_POST['slug'] ) ) {
        wp_send_json_error( 
            array(
                'status'  => 'failed', 
                'message' => 'There has been a critical error! Plase trye again later.'
            ) 
        );
    }

    $slug = sanitize_text_field( wp_unslash( $_POST['slug'] ) );

    $sizes = get_option( 'ctg_custom_image_sizes', array() );

    if( isset( $sizes[ $slug ] ) ) {
        unset( $sizes[ $slug ] );
        update_option( 'ctg_custom_image_sizes', $sizes );
    }

    wp_send_json_success(
        array(
            'success'    => true,
            'size_slug'  => $slug,
            'message'    => 'Thumbnail ' . $slug . ' removed',
        )
    );
 }