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
    $ctg_functions = new CTGEN_Functions(); ?>
    
    <div class="wrap">
        <?php
        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        echo $ctg_functions->ctgen_render_settings_page_form();
        $ctg_functions->ctgen_render_default_thumbnail_list_table();
        ?>
    </div>

    <?php 
 }