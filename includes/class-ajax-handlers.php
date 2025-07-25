<?php

class CTG_Ajax_Handlers {

    public function __construct() {
        /** Action hooks */
        add_action( 'wp_ajax_ctg_add_custom_size', array( $this, 'ctg_ajax_add_custom_size' ) );
        add_action( 'wp_ajax_ctg_remove_custom_size', array( $this, 'ctg_ajax_remove_custom_size' ) );

        add_action( 'wp_ajax_ctg_get_attachments', array( $this, 'ctg_get_all_attachments' ) );
        add_action( 'wp_ajax_ctg_regenerate_single', array( $this, 'ctg_regenerate_single_attachment_thumbnail' ) );

        add_action( 'wp_ajax_ctg_reload_thumb_list', array( $this, 'ctg_ajax_load_thumb_list' ) );
    }

    /** Add new thumbnail size */
    function ctg_ajax_add_custom_size() {
        check_ajax_referer( 'ctg_media_actions', 'security' );

        if( ! current_user_can( 'manage_options' ) ) {
            wp_die( 'You are not authorized to perform this action!' );
        }

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

    /** Load custom thumbnail list table */
    function ctg_ajax_load_thumb_list() {
        check_ajax_referer( 'ctg_media_actions', 'security' );

        if( ! current_user_can( 'manage_options' ) ) {
            wp_die( 'You are not authorized to perform this action!' );
        }

        $html_template = CTG_TEMPLATE_DIR . 'ctg-custom-thumb-list.html.php';
        ob_start();
        include( $html_template );
        $template = ob_get_clean();

        wp_send_json_success(
            array(
                'success'   => true,
                'data_list' => $template
            )
        );
    }

    /** Get all attachments */
    function ctg_get_all_attachments() {
        check_ajax_referer( 'ctg_media_actions', 'security' );

        if( ! current_user_can( 'manage_options' ) ) {
            wp_die( 'You are not authorized to perform this action!' );
        }

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

    /** Generate thumbnails for a single attachment by attachment ID */
    function ctg_regenerate_single_attachment_thumbnail() {
        check_ajax_referer( 'ctg_media_actions', 'security' );

        if( ! current_user_can( 'manage_options' ) ) {
            wp_die( 'You are not authorized to perform this action!' );
        }

        if( ! isset( $_POST['attachment_id'] ) || empty( $_POST['attachment_id'] ) ) {
            wp_send_json_error( array( 'message' => 'Invalid argument' ) );
        }

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

    /** Remove a custom thumbnail size without removing any existing thumbnail files */
    function ctg_ajax_remove_custom_size() {
        check_ajax_referer( 'ctg_media_actions', 'security' );

        if( ! current_user_can( 'manage_options' ) ) {
            wp_die( 'You are not authorized to perform this action!' );
        }

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
}