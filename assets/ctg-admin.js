/**
 * Three methods are handled: Adcd, Remove amd Regenerate
 */
jQuery( document ).ready( function( $ ) {
    // Add a custom size
    $( '#ctg-form' ).on( 'submit', function( e ) {
        e.preventDefault();
        let formData = $(this).serialize();

        const responseContainer = $( '#ctg-form-response' );
        const ctgWidth          = $( '#ctg-width' )
        const ctgHeight         = $( '#ctg-height' )
        const ctgCrop           = $( '#ctg-crop' )
        
        $.post( CTGVARS.ajax_url, {
            action: 'ctg_add_custom_size',
            ...Object.fromEntries( new URLSearchParams( formData ) )
        }, function( response ) {
            console.log( response );
            if( response.success ) { 
                $( ctgWidth ).val("");
                $( ctgHeight ).val("");
                $( ctgCrop ).prop("checked", false);

                $( responseContainer ).html( '<p style="color: green">New thumbnail size added.</p>' )

                /**
                 * Call the table loading method via AJAX to reload
                 * Smooth scroll to the bottom of the table
                 */

                location.reload();
            }
            else {
                $( responseContainer ).html( '<p style="color: green">Error adding new thumbnail size.</p>' )
            }
        } ).fail( function() {
            $( responseContainer ).html( 'Unknown error! Request failed.' );
        } );
    } );

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

        $.post( CTGVARS.ajax_url, {
            action: 'ctg_remove_custom_size',
            nonce: CTGVARS.nonce,
            slug
        }, function( response ) {
            if( response.sattus === 'successs' ) {
                console.log( response.message );
                $( row ).slideUp( 1200 );
            } else {
                console.log( response.message );
            }
        } );
    } );
} );