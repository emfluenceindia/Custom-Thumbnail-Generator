<?php
/**
 * Enqueue scripts and CSS
 */
 function ctg_enqueue_and_localize_scripts() {
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
  * Render registered thumbnail sizes table
  */
 function ctg_render_thumbnail_list_table() {
    $default_sizes = ctg_default_and_theme_based_image_sizes(); ?>
    <h2>Default Thumbnails</h2>

    <table class="widefat ctg-list-table">
        <thead>
            <tr>
                <th class="ctg-title-cell">Name</th>
                <th class="ctg-text-center">Width</th>
                <th class="ctg-text-center">Height</th>
                <th class="ctg-text-center">Crop</th>
            </tr>
        </thead>
        <tbody id="ctg-sizes-list">
            <?php
            if( ! empty( $default_sizes ) ) {
                foreach( $default_sizes as $slug => $size ) { 
                    if( 0 === strpos( strtolower( $slug ), 'ctg_' ) ) continue; // This is registered by the plugin. We will not consider it in default sizes. ?>

                    <tr>
                        <td class="ctg-title-cell"><?php echo wp_kses_post( ctg_get_size_table_title( $slug ) ); ?> </td>
                        <td class="ctg-text-center"><?php echo esc_html( $size[ 'width' ] ); ?>px</td>
                        <td class="ctg-text-center"><?php echo esc_html( $size[ 'height' ] ); ?>px</td>
                        <td class="ctg-text-center"><?php echo $size[ 'crop' ] ? '<span class="yes">✔</span>' : '<span class="no">✖</span>'; ?></td>
                    </tr>
                <?php }
            }
            ?>
        </tbody>
    </table>

    <?php
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

/**
 * Prints thumbnail size registrar's name under slug
 */
 function ctg_get_size_table_title( $slug ) {
    if( in_array( $slug, WP_NATIVE_THUMB_SIZES ) ) {
        $slug = wp_kses_post( $slug . "<br /><i class='ctg-image-size-register-by'>Registered by WordPress core</i>" );
    } else {
        $slug = wp_kses_post( $slug . "<br /><i class='ctg-image-size-register-by'>Registered by " . wp_get_theme()->get( 'Name' ) );
    }

    return $slug;
 }

 /**
  * Render Form
  */
  function ctg_render_settings_page_form() {
    $html_template = CTG_TEMPLATE_DIR . 'ctg-form.html.php';
    
    if( ! file_exists( $html_template ) ) {
        echo '<p class="ctg-common-error">Error! Template file missing.</p>';
        return;
    }

    ob_start();
    include( $html_template );
    // $form_content = file_get_contents( $html_template );
    $form_content = ob_get_clean();
    return $form_content;
  }

  /**
   * Render list of custom thumbnails registered by Custom Thumbnail Generator plugin
   */
  function ctg_render_plugin_generated_thmbnail_list() {
    
  }