jQuery( document ).ready( function( $ ) {
    /**
     * Add new thumbnail size
     */
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

    /**
     * Remove an exsting thumbnail size
     */
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

        $.post( CTGMediaAction.ajax_url, {
            action: 'ctg_remove_custom_size',
            security: CTGMediaAction.nonce,
            slug
        }, function( response ) {
            if( response.data.success ) {
                $( row ).slideUp( 1200 );
            } else {
                alert( response.message );
            }
        } );
    } );

    /**
     * Regenerate thumbnails
     */
    $( "#ctg-regenerate" ).on( 'click', function() {
        $.post(CTGMediaAction.ajax_url, {
            action: 'ctg_get_attachments',
            security: CTGMediaAction.nonce
        }, function( response ) {
            if( response.success ) {
                let ids = response.data.ids;
                let total = ids.length;
                let count = 0;

                function processNext() {
                    if( count >= total ) return;

                    $.post(CTGMediaAction.ajax_url, {
                      action: 'ctg_regenerate_single'  ,
                      security: CTGMediaAction.nonce,
                      attachment_id: ids[count]
                    }, function( response ) {
                        count++;
                        let percent = Math.round( (count / total) * 100 );
                        $( '#ctg-progress' ).css( 'width', percent + '%' );
                        if( percent >= 50 ) {
                            $( '#ctg-percentage-increment' ).css( 'color', '#ffffff' );
                        }
                        $( '#ctg-percentage-increment' ).html( percent + '%' );
                        $( '#ctg-generator-status' ).text( `Processed ${count} of ${total}` );

                        if( count < total ) {
                            processNext();
                        } else {
                            $( '#ctg-generator-status' ).text( 'Thumbnails generated.' );
                        }
                    });
                }

                processNext();
            }
        } );
    } );
} );