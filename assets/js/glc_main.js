
function glc_show_loader() {
    var loader_div = jQuery('#order_review');
	loader_div.addClass('loader-parent');
	if (loader_div.find('.custom-loader')[0]) {
		jQuery('.custom-loader').show();
	}else{
		var loader_html = '<div class="custom-loader"><div class="dot-pulse"></div></div>';
		loader_div.append(loader_html);
	}
}

function glc_hide_loader() {
	jQuery('.custom-loader').hide();
}

function glc_ajax_call() {
	glc_show_loader();
	jQuery.ajax({
		type:'POST',
		data:{action:'glc_get_landing_cost'},
		url: ajax_object.ajaxurl,
		dataType: "json",
		success: function(response) {
			var html_data = '';
			
			if (response.code == 1) {
 				html_data = response.entity.landed_cost;
			}else{
				html_data = response.message;
			}

			glc_hide_loader();
			jQuery('.landing-cost-amount').html(html_data);
		}
    });
}

jQuery(document).ready(function () {
	glc_ajax_call();
});

