/**
 * Three methods are handled: Adcd, Remove amd Regenerate
 */
jQuery( document ).ready( function( $ ) {
    // Add a custom size
    $( '#ctg-form' ).on( 'submit', function( e ) {
        e.preventDefault();
        let formData = $(this).serialize();
        const responseContainer = $( '#ctg-form-response' );
        
        // Debugging...
        console.log(formData);
        // return;

        $.post( CTGVARS.ajax_url, {
            action: 'ctg_add_custom_size',
            // _wpnonce: CTGVARS.nonce,
            ...Object.fromEntries( new URLSearchParams( formData ) )
        }, function( response ) {
            console.log( response );
            return;
            if( response.success ) { 
                console.log( response.data ); /*location.reload();*/ 
                $( responseContainer ).html( '<p style="color: green">New thumbnail size added.</p>' )
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
        alert('dfdfdffsd');
        let slug = $(this).data('slug');
        $.post( CTGVARS.ajax_url, {
            action: 'ctg_remove_custom_size',
            nonce: CTGVARS.nonce,
            slug
        }, function() {
            location.reload();
        } );
    } );

    // Regenerate thumbnails
    $( '#ctg-regenerate' ).on( 'click', function( e ) {
        if( confirm( 'Are you sure you want to regenerate all thumbnails?' ) ) {
            $.post( CTGVARS.ajax_url, {
                action: 'ctg_regenerate_thumbnails',
                nonce: CTGVARS.nonce
            }, function( response ) {
                alert( response.data );
            } );
        }
    } );
} );