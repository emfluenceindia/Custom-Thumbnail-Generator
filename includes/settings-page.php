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
                        <input type="number" name="ctg-width" id="ctg-width" required />
                    </td>
                </tr>
                <tr>
                    <th>Height</th>
                    <td>
                        <input type="number" name="ctg-height" id="ctg-height" required />
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
            </table>
            <input type="submit" value="Add Image Size" class="button button-primary" id="ctg-addsise" name="ctg-addsize" />
        </form>

        <hr />

        <h2>Registered Image Sizes</h2>

        <table class="widefat ctg-list-table">
            <thead>
                <tr>
                    <th class="ctg-title-cell">Name</th>
                    <th class="ctg-text-center">Width</th>
                    <th class="ctg-text-center">Height</th>
                    <th class="ctg-text-center">Crop</th>
                    <th class="ctg-text-center">Action</th>
                </tr>
            </thead>
            <tbody id="ctg-sizes-list">
                <?php
                if( ! empty( $default_sizes ) ) {
                    foreach( $default_sizes as $slug => $size ) { 
                        if( 0 === strpos( strtolower( $slug ), 'ctg_' ) ) continue; // This is registered by the plugin. We will not consider it in default sizes.

                        // if( in_array( $slug, WP_NATIVE_THUMB_SIZES ) ) {
                        //     $slug = esc_html( $slug ) . "<br /><i class='ctg-image-size-register-by'>Registered by WordPress core</i>";
                        // } else {
                        //     $slug = esc_html( $slug ) . "<br /><i class='ctg-image-size-register-by'>Registered by " . wp_get_theme()->get( 'Name' );
                        // }
                        ?>

                        <tr>
                            <td class="ctg-title-cell"><?php echo ctg_get_size_table_title( $slug ); ?> </td>
                            <td class="ctg-text-center"><?php echo esc_html( $size[ 'width' ] ); ?>px</td>
                            <td class="ctg-text-center"><?php echo esc_html( $size[ 'height' ] ); ?>px</td>
                            <td class="ctg-text-center"><?php echo $size[ 'crop' ] ? '<span class="yes">✔</span>' : '<span class="no">✖</span>'; ?></td>
                            <td class="ctg-text-center"><button data-slug="<?php echo esc_attr( $slug ); ?>" disabled class="button ctg-delete-custom-size">Remove</button></td>
                        </tr>
                    <?php }
                }
                ?>
                <?php foreach( $sizes as $slug => $size ): ?>
                    <tr class="ctg-custom-sizes-row">
                         <td class="ctg-title-cell"><?php echo wp_kses_post( $slug . "<br /><i class='ctg-image-size-register-by'>Registered by Custom Thumbnail Generator</i>" ); ?> </td>
                         <td class="ctg-text-center"><?php echo esc_html( $size[ 'width' ] ); ?>px</td>
                         <td class="ctg-text-center"><?php echo esc_html( $size[ 'height' ] ); ?>px</td>
                         <td class="ctg-text-center"><?php echo $size[ 'crop' ] ? '<span class="yes">✔</span>' : '<span class="no">✖</span>'; ?></td>
                         <td class="ctg-text-center"><button data-slug="<?php echo esc_attr( $slug ); ?>" class="button ctg-delete-custom-size">Remove</button></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <hr />

        <button id="ctg-regenerate" class="button button-secondary">Regenerate Thumbnails</button>
    </div>

    <?php 
    /**
     * Enqueue scripts and style
     */
    wp_enqueue_script( 
        'ctg-admin-js', 
        CTG_PLUGIN_DIR . '/assets/ctg-admin.js', 
        [ 'jquery' ], '1.0', true 
    );

    wp_localize_script(
        'ctg-admin-js', 'CTGVARS', array(
            'ajax_url' => admin_url( 'admin-ajax.php' ),
            'nonce'    => wp_create_nonce( 'ctg_nonce' ),
        )
    );

    wp_enqueue_style(
        'ctg-admin.css',
        CTG_PLUGIN_DIR . '/assets/ctg-admin.css',
        null,
        '1.0'
    );
 }

 /**
  * Gets an array of default image sizes registered by WordPress core and those registered by the active theme
  */
 function ctg_default_and_theme_based_image_sizes() {
    global $_wp_additional_image_sizes;

    $sizes = array();
    $core_sizes = [ 'thumbnail', 'medium', 'medium_large', 'large' ];

    //Default sizes from WordPress settings
    foreach( $core_sizes as $default_size ) {
        $sizes[ $default_size ] = array(
            'width'  => get_option( "{$default_size}_size_w" ),
            'height' => get_option( "{$default_size}_size_h" ),
            'crop'   => get_option( "{$default_size}_crop" ),
        );
    }

    // Additional sizes registered by add_image_size()
    if( isset( $_wp_additional_image_sizes ) && is_array( $_wp_additional_image_sizes ) ) {
        foreach( $_wp_additional_image_sizes as $name => $attr ) {
            $sizes[ $name ] = array(
                'width'  => $attr[ 'width' ],
                'height' => $attr[ 'height' ],
                'crop'   => $attr[ 'crop' ],
            );
        }
    }

    return $sizes;
 }

 function ctg_get_size_table_title( $slug ) {
    if( in_array( $slug, WP_NATIVE_THUMB_SIZES ) ) {
        $slug = wp_kses_post( $slug . "<br /><i class='ctg-image-size-register-by'>Registered by WordPress core</i>" );
    } else {
        $slug = wp_kses_post( $slug . "<br /><i class='ctg-image-size-register-by'>Registered by " . wp_get_theme()->get( 'Name' ) );
    }

    return $slug;
 }