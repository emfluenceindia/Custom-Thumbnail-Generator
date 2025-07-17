<?php
/**
 * Plugin Settins Page. UI for user to create custom thumbnails
 */

 
 /** Add the submenu */
 add_action( 'admin_menu', function() {
    add_submenu_page(
        'options-general.php',
        'Custom Image Sizes',
        'Custom Thumbnail Generator',
        'manage_options',
        'ctg-custom-sizes',
        'ctg_render_settngs_page'
    );
 } );

 /**
  * HTML to render on Settings page
  */
 function ctg_render_settngs_page() {
    $default_sizes = ctg_default_and_theme_based_image_sizes();
    $sizes         = get_option( 'ctg_custom_image_sizes', array() ); ?>
    
    <?php
    /** Debug */
    $image_sizes = get_intermediate_image_sizes();
    $ctg_sizes = get_option( 'ctg_custom_image_sizes', array() );

    $slug_to_remove = 'ctg_75x75';

    foreach( $ctg_sizes as $slug => $size_info ) {
        if( $slug !== $slug_to_remove ) continue;

        if( isset( $ctg_sizes[ $slug_to_remove ] ) ) {
            unset( $ctg_sizes[ $slug_to_remove ] ); // remove the image size
            update_option( 'ctg_custom_image_sizes', $ctg_sizes );
        }
    }

    print_r( $ctg_sizes );
    /** Debug */
    ?>

    <div class="wrap">
        <?php
        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        echo ctg_render_settings_page_form();
        /** Debug */
        foreach( $image_sizes as $size ) {
            if( 0 !== strpos( $size, 'ctg_' ) ) continue;
            // print_r( $size );
            list( $width, $height ) = explode( 'x', $size );
            //$thumbnail_path = wp_get_upload_dir()['path'] . '/' . $size . '-' . basename(  )
        }
        /** Debug */

        ctg_render_thumbnail_list_table(); ?>
    </div>

    <?php ctg_enqueue_and_localize_scripts();
 }