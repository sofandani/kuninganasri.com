jQuery(document).ready(function($) {jQuery('a.achor_login_front').on('click', function(e){jQuery('body').prepend('<div class="login_overlay"></div>'); jQuery('#web_wrapper').addClass('grayscaleKuasEffect'); jQuery('#login_form_containner').fadeIn(500); jQuery('div.login_overlay, #login_form_containner a.close').on('click', function(){jQuery('div.login_overlay').remove(); jQuery('#login_form_containner').hide(); jQuery('#web_wrapper').removeClass('grayscaleKuasEffect'); }); e.preventDefault(); }); jQuery('form#login_front').on('submit', function(e){jQuery('form#login_front p.status').show().text(kuas_ajax_var.kuas_ajax_msg); jQuery.ajax({type: 'POST', dataType: 'json', url: kuas_ajax_var.kuas_ajax_url, data: {'action': 'ajaxlogin', 'username': jQuery('form#login_front #username').val(), 'password': jQuery('form#login_front #password').val(), 'kuas_ssc': kuas_ajax_var.kuas_ssc }, success: function(data){jQuery('form#login_front p.status').text(data.message); if (data.loggedin == true){document.location.href = kuas_ajax_var.kuas_uri; } } }); e.preventDefault(); }); });