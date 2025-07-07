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
    $default_sizes = ctg_default_and_theme_based_image_sizes();
    $sizes         = get_option( 'ctg_custom_image_sizes', array() ); ?>

    <div class="wrap">
        <h1>Create and register theme-independent Custom Image Sizes</h1>
        <hr />
        <form id="ctg-form" method="post">
            <table class="form-table">
                <tr>
                    <th>Width</th>
                    <td>
                        <input type="number" name="ctg-width" id="ctg-width" required /> px
                    </td>
                </tr>
                <tr>
                    <th>Height</th>
                    <td>
                        <input type="number" name="ctg-height" id="ctg-height" required /> px
                    </td>
                </tr>
                <tr>
                    <th>
                        <label for="ctg-crop">Allow crop</label>
                    </th>
                    <td>
                        <input type="checkbox" name="ctg-crop" id="ctg-crop" value="1" />
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <?php wp_nonce_field( 'ctg_form_action', 'ctg_form_nonce' ); ?>
                    </td>
                </tr>
            </table>
            <input type="submit" value="Add Image Size" class="button button-primary" id="ctg-addsise" name="ctg-addsize" />
            <div id="ctg-form-response"><!-- AJAX response appears here --></div>
        </form>

        <?php ctg_render_thumbnail_list_table(); ?>

        <hr />

        <button id="ctg-regenerate" class="button button-secondary">Regenerate Thumbnails</button>
    </div>

    <?php ctg_enqueue_and_localize_scripts();
 }