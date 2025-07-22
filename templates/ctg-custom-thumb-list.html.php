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
</div>