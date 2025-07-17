jQuery( document ).ready( function( $ ) {
    // Remove a custom size
    $( '.ctg-delete-custom-size' ).on( 'click', function() { 
        const row = $(this).parent().parent();

        $thumbnail_slug = $(this).parent().parent().find( 'td.ctg-title-cell' ).html();
        if ( ! confirm( 'Removing ' + $thumbnail_slug + 'won\'t remove any physical files from the disk. However, it will no longer be generated in the future. Are you sure to proceed?' ) ) {
            return false;
        }
        let slug = $(this).data('slug');

        $( this ).html( 'Working...' );
        $(row).find('td').each(function() {
            $( this ).css( 'color', '#aeaeae' );
        });

        $.post( CTGRemove.ajax_url, {
            action: 'ctg_remove_custom_size',
            security: CTGRemove.nonce,
            slug
        }, function( response ) {
            if( response.data.success ) {
                console.log( response.data.message );
                $( row ).slideUp( 1200 );
            } else {
                console.log( response.message );
            }
        } );
    } );
} );