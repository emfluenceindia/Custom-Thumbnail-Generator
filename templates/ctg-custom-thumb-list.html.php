<div id="ctg-thumb-list">
    <h3>Custom Thumbnails</h3>
    <hr />
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
        <?php $sizes = get_option( 'ctg_custom_image_sizes', array() ); ?>
        <tbody class="ctg-sizes-list">
            <tr>
                <?php foreach( $sizes as $slug => $size ): ?>
                    <?php $crop = ( ! empty( esc_html( $size[ 'crop' ] ) ) ) ? 1 : 0; ?>
                    <tr class="ctg-1m-0-false">
                        <td class="ctg-title-cell"><?php echo wp_kses_post( $slug ); ?> </td>
                        <td class="ctg-text-center"><?php echo esc_html( $size[ 'width' ] ); ?>px</td>
                        <td class="ctg-text-center"><?php echo esc_html( $size[ 'height' ] ); ?>px</td>
                        <td class="ctg-text-center"><?php echo $size[ 'crop' ] ? '<span class="yes">✔</span>' : '<span class="no">✖</span>'; ?></td>
                        <td class="ctg-text-center">
                            <button title="Regenerate"
                                data-width="<?php echo esc_attr( $size[ 'width' ] ); ?>"
                                data-height="<?php echo esc_attr( $size[ 'height' ] ) ?>"
                                data-crop="<?php echo esc_attr( $crop ) ?>"    
                                data-slug="<?php echo esc_attr( $slug ); ?>" 
                                class="button ctg-regen-thumbs">
                                    <i class="fa fa-recycle" aria-hidden="true"></i>
                            </button>
                            <button title="Remove" 
                                data-width="<?php echo esc_attr( $size[ 'width' ] ); ?>"
                                data-height="<?php echo esc_attr( $size[ 'height' ] ) ?>"
                                data-crop="<?php echo esc_attr( $crop ) ?>"
                                data-slug="<?php echo esc_attr( $slug ); ?>" class="button ctg-delete-custom-size">
                                    <i class="fa fa-trash" aria-hidden="true"></i>
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tr>
        </tbody>
    </table>
</div>