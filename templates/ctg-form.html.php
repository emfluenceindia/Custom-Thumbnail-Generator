<!-- Form the add nre thumbnail size -->
<h1>Create Custom Thumbnails</h1>
<hr />
<p>
    <strong>Custom Thumbnail Generator</strong> ensures that all custom thumbnail sizes 
    defined through the plugin are registered and seamlessly integrated 
    into the environment as long as the plugin remains active. 
    They remain fully accessible and functional throughout the application, even when 
    switching themes, providing a consistent image handling.
</p>

<table class="ctg-form-table">
    <tr>
        <td class="ctg-form-cell">
            <fieldset class="ctg-form-container">
                <h3>Add New Thumbnail Size</h3>
                <hr />
                <form id="ctg-form" method="post">
                    <table class="ctg-form-table">
                        <tr>
                            <th>Width&nbsp;</th>
                            <td>
                                <input type="number" name="ctg-width" id="ctg-width" required /> px
                            </td>
                        </tr>
                        <tr>
                            <th>Height&nbsp;</th>
                            <td>
                                <input type="number" name="ctg-height" id="ctg-height" required /> px
                            </td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>
                                <input type="checkbox" name="ctg-crop" id="ctg-crop" value="1" />
                                &nbsp;<label for="ctg-crop">Allow crop</label>
                            </td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>
                                <input type="submit" value="Add New Thumbnail" class="button button-primary" id="ctg-addsise" name="ctg-addsize" />
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <?php
                                // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                                echo wp_nonce_field( 'ctg_form_action', 'ctg_form_nonce' );
                                ?>
                            </td>
                        </tr>
                    </table>

                    <div id="ctg-form-response"><!-- AJAX response appears here --></div>
                </form>
            </fieldset>
        </td>
        <td class="ctg-thumbs-cell">
            <h2>Custom Thumbnails</h2>
            <table class="widefat ctg-list-table ctg-custom-size-list">
                <thead>
                    <tr>
                        <th class="ctg-title-cell">Name</th>
                        <th class="ctg-text-center">Width</th>
                        <th class="ctg-text-center">Height</th>
                        <th class="ctg-text-center">Crop</th>
                        <th class="ctg-text-center">Action</th>
                    </tr>
                </thead>
                <?php 
                $sizes = get_option( 'ctg_custom_image_sizes', array() );
                ?>
                <tbody class="ctg-sizes-list">
                    <tr>
                        <?php foreach( $sizes as $slug => $size ): ?>
                            <tr class="ctg-custom-sizes-row">
                                <td class="ctg-title-cell"><?php echo wp_kses_post( $slug ); ?> </td>
                                <td class="ctg-text-center"><?php echo esc_html( $size[ 'width' ] ); ?>px</td>
                                <td class="ctg-text-center"><?php echo esc_html( $size[ 'height' ] ); ?>px</td>
                                <td class="ctg-text-center"><?php echo $size[ 'crop' ] ? '<span class="yes">✔</span>' : '<span class="no">✖</span>'; ?></td>
                                <td class="ctg-text-center"><button data-slug="<?php echo esc_attr( $slug ); ?>" class="button ctg-delete-custom-size">Remove</button></td>
                            </tr>
                        <?php endforeach; ?>
                    </tr>
                </tbody>
            </table>
            <hr />
        </td>
    </tr>
</table>

