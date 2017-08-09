jQuery( document ).ready( function( $ ) {
	var Premium_Link_Cloaker_Lite = {
		init: function() {
			this.check_all();
			this.copy_link();
			this.row_actions();
			this.select2();
			this.status();
		},
		check_all: function() {
			if ( $( '.plcl_link_cb' ).length > 0 ) {
				$( '.plcl_link_cb_parent' ).change( function( event ) {
					$( '.plcl_link_cb' ).prop( 'checked', $( this ).prop( 'checked' ) );
				});
			}

			if ( $( '.plcl_cat_cb' ).length > 0 ) {
				$( '.plcl_cat_cb_parent' ).change( function( event ) {
					$( '.plcl_cat_cb' ).prop( 'checked', $( this ).prop( 'checked' ) );
				});
			}
		},
		copy_link: function() {
			if ( $( '.copy-button' ).length > 0 ) {

				$( '.copy-button' ).each( function( index, el ) {
					var client = new ZeroClipboard( document.getElementById( 'copy-button-' + index ) );

					client.on( 'ready', function( readyEvent ) {
					  // alert( "ZeroClipboard SWF is ready!" );

					  client.on( 'aftercopy', function( event ) {
					    // `this` === `client`
					    // `event.target` === the element that was clicked
					    // event.target.style.display = 'none';
					    alert( PLC.text_copied + event.data['text/plain'] );
					  } );
					} );
				});
				
			}
		},
		row_actions: function() {
			if ( $( '.plcl-row-actions' ).length > 0 ) {
				$( '.plcl-row-actions' ).each( function( index, el ) {
					$( this ).closest( 'tr' ).hover( function() {
						$( this ).find( '.plcl-row-actions' ).css( {
							visibility: 'visible'
						} );
					}, function() {
						$( this ).find( '.plcl-row-actions' ).css( {
							visibility: 'hidden'
						} );
					});
				});
			}
		},
		select2: function() {
			if ( $( '.select2' ).length > 0 ) {
				$( '.select2' ).select2({
					theme: 'bootstrap',
				});
			}
		},
		status: function() {
			if ( $( '.plcl-status' ).length > 0 ) {
				$( '.plcl-status' ).show();
			}
		}
	};
	Premium_Link_Cloaker_Lite.init();
});