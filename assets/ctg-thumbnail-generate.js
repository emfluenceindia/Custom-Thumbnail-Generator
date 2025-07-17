jQuery( document ).ready( function($) {
    $( "#ctg-regenerate" ).on( 'click', function() {
        $.post(CTGenerator.ajax_url, {
            action: 'ctg_get_attachments',
            security: CTGenerator.nonce
        }, function( response ) {
            if( response.success ) {
                let ids = response.data.ids;
                let total = ids.length;
                let count = 0;

                function processNext() {
                    if( count >= total ) return;

                    $.post(CTGenerator.ajax_url, {
                      action: 'ctg_regenerate_single'  ,
                      security: CTGenerator.nonce,
                      attachment_id: ids[count]
                    }, function( response ) {
                        count++;
                        let percent = Math.round( (count / total) * 100 );
                        $( '#ctg-progress' ).css( 'width', percent + '%' );
                        $( '#ctg-generator-status' ).text( `Processed ${count} of ${total}` );

                        if( count < total ) {
                            processNext();
                        } else {
                            $( '#ctg-generator-status' ).text( 'All thumbnails regerated' );
                        }
                    });
                }

                processNext();
            }
        } );
    } );
} );