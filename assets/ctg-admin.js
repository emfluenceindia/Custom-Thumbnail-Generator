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
} );