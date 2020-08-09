( function( $ ) {

	$( '#registration-form-btn' ).on('click', function( event ) {
		event.preventDefault();
		let _this   = $(this);
		let form    = _this.closest('form');
		let email   = form.find('#registration-form-email').val();
		let message = form.find('.message');

		if( email != '' ) {
			message.remove();

			$.ajax( {
				type: "POST",
				url: window.wp_data.ajax_url,
				data : {
					action : 'test_register_users_action_callback',
					email  : email
				},
				/*beforeSend: function() { preloader.show(); },*/
				success: function( data ) {
					if(  data == 'success' ) {
						form.append('<span class="message success">success</span>');
					} else {
						form.append('<span class="message error">' + data + '</span>');
					}
					form.find('.message').slideDown(400, function() {
						setTimeout(function() {
							form.find('.message').slideUp(400);
						}, 1000);
					});
					// preloader.hide();
				}
			});
		}
	});

}( jQuery ) );