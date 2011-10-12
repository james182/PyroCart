(function($){
	$(function(){
		// General -----------------------------------------------------

		// Apply sexy style to input fields with uniform
		$('select, textarea, input[type=text], input[type=file], input[type=submit]')
			.livequery(function(){
				$(this).not('.no-uniform').uniform().addClass('no-uniform');
		});

		// Folder ------------------------------------------------------

		$('.cancel.close-cbox').livequery('click', function(){
			$.colorbox.close();
		});
		$('a.product_image_upload').livequery(function(){
			$(this).colorbox({
				width:'60%',
				height:'70%',
				iframe:true

				});
		});

		$('a.iframe_form').livequery(function(){
			$(this).colorbox({
				width:'60%',
				height:'70%',
				iframe:true

				});
		});
        $('a.colorbox').livequery(function(){
			$(this).colorbox({
				width:'60%',
				scrolling	: false,
				onComplete	: function(){

					var form = $('form#ajax_edit_form');
					var $loading = $('#cboxLoadingOverlay, #cboxLoadingGraphic');


					form.submit(function(e){

						e.preventDefault();

						form.parent().fadeOut(function(){

							$loading.show();

							pyro.clear_notifications();

							$.post(form.attr('action'), form.serialize(), function(data){
								// Prepare the html notification


								// Update title
								data.title && $('#cboxLoadedContent h3:eq(0)').text(data.title);


								if (data.status == 'success')
								{

									// TODO: If self_action is edit: Create a countdown with an option to cancel before close
										setTimeout(function(){
											$.colorbox.close();
										}, 1800);

								}

								$loading.hide();

								form.parent().fadeIn(function(){

									// Show notification & resize colorbox
									pyro.add_notification(data.message, {ref: '#cboxLoadedContent', method: 'prepend'}, $.colorbox.resize);

								});

							}, 'json');

						});
					});
				},
			});
        });




	});
})(jQuery);