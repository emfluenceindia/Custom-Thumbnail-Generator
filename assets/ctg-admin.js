/**
 * Three methods are handled: Adcd, Remove amd Regenerate
 */
jQuery( document ).ready( function( $ ) {
    // Add a custom size
    $( '#ctg-form' ).on( 'submit', function( e ) {
        e.preventDefault();
        let data = $(this).serialize();
        
        // Debugging...
        console.log(data);
        //return;

        $.post( CTGVARS.ajax_url, {
            action: 'ctg_add_custom_size',
            _wpnonce: CTGVARS.nonce,
            ...Object.fromEntries( new URLSearchParams( data ) )
        }, function( response ) {
            if( response.success ) { console.log( response.data ); /*location.reload();*/ }
            else alert( response.data );
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