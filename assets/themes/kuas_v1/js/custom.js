var hashtagRegex = /(#\w+)/g;
var urlRegex = /\(?(?:(http|https|ftp):\/\/)?(?:((?:[^\W\s]|\.|-|[:]{1})+)@{1})?((?:www.)?(?:[^\W\s]|\.|-)+[\.][^\W\s]{2,4}|localhost(?=\/)|\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3})(?::(\d*))?([\/]?[^\s\?]*[\/]{1})*(?:\/?([^\s\n\?\[\]\{\}\#]*(?:(?=\.)){1}|[^\s\n\?\[\]\{\}\.\#]*)?([\.]{1}[^\s\?\#]*)?)?(?:\?{1}([^\s\n\#\[\]]*))?([\#][^\s\n]*)?\)?/gi;

jQuery(document).ready(function(){

/* NPROGRESS */
	jQuery('body').on('click','a',function(e){
		if(urlRegex.test(jQuery(this).attr('href')) && hashtagRegex.test(jQuery(this).attr('href'))==false){
		    NProgress.start();
		    NProgress.inc();
		    setTimeout(function(){
		    	NProgress.done();
		    }, 1000);
		}
	});

	jQuery('form#searchform').on('submit',function(){
	    NProgress.start();
	    NProgress.inc();
	    setTimeout(function(){
	    	NProgress.done();
	    }, 1000);
	});

/* MAIN MENU */
	if(kuas_ajax_var.kuas_is_mobile==1){
		jQuery('.menu_navigation_site ul.ul-top').mobileMenu({
		    defaultText: 'Menuju Ke Halaman...', className: 'sub-menu', subMenuDash: '&ndash;'
		});
		jQuery('.menu_navigation_site ul.ul-main').mobileMenu({
		    defaultText: 'Menuju Ke Halaman...',
		});

		jQuery('select.first-ul-menu').live('change',function(){
			jQuery('option:selected',this).each(function() {
			    NProgress.start();
			    NProgress.inc();
			    setTimeout(function(){
			    	NProgress.done();
			    }, 1000);
			});
		});
	}
	else {
	    function init_nav(){
	    	jQuery(".menu_navigation_site ul.first-ul-menu").superfish({delay : 140, animation : {opacity:'show',height:'show'}, speed : 'normal', autoArrows	: false, dropShadows	: true });
	    }
	    init_nav();		
	}

/* CAROUSEL */
	jQuery(".carousel-autotrue").jCarouselLite({btnNext: ".next", btnPrev: ".prev", visible: 3, scroll: 3, pause: true, auto: true, speed: 1000, timeout:10000, easing: "easeOutQuad"});
	jQuery(".carousel-autofalse").jCarouselLite({btnNext: ".next", btnPrev: ".prev", visible: 3, scroll: 3, auto: false, speed: 500 });
	jQuery(".feat-carousel").each(function(){var data_limit = jQuery(this).attr('data-limit'); jQuery(this).jCarouselLite({vertical: true, visible: data_limit, scroll: 1, pause: true, auto: 1, speed: 1000, timeout:5500, easing: "easeOutExpo"}); });
	jQuery(".widget_views .most_viewed").jCarouselLite({autoCSS: true, vertical: true, visible: 5, scroll: 2, pause: true, auto: 2, speed: 2000, easing: "easeOutQuint"});
	jQuery(".widget_views .most_viewed_category").jCarouselLite({autoCSS: true, vertical: true, visible: 5, pause: true, auto: 1, speed: 1000, timeout: 5500, easing: "easeOutQuint"});
	jQuery(".gallery-pages-carousel").jCarouselLite({autoCSS: true, visible: 1, scroll: 1, pause: false, auto: true, speed: 1000, timeout:10000, easing: "easeOutElastic"});

/* AUTOSIZE */
	jQuery('textarea').autosize();
	jQuery('#comment textarea').Kuas_Watermark({inner_txt:'Apa yang akan anda katakan?'}); 
	jQuery('input.searchfield').Kuas_Watermark();

/* AJAX LOAD */
    jQuery('#wrapper_post_list').on('click', '.pagination a', function(e){
        e.preventDefault();
        var link = jQuery(this).attr('href');
        jQuery('#wrapper_post_list').animate({opacity:'0.5'},500, function(){
            jQuery(this).load(link + ' #wrapper_post_list', function() {
                jQuery(this).animate({opacity:'1'},500);
                kuasBackToTop('#wrapper_post_list',50,10,500);
            });
        });
    });

});

jQuery(function() {

/* VENUE INFO MODAL WINDOWS */
if(kuas_ajax_var.kuas_is_mobile==0){
	jQuery('a.achor-marker-map.by_foursquare, a.checkin_button_foursquare').live('click',function(e){
		e.preventDefault();
		if(jQuery('body').hasClass('home')){
		jQuery(parent_venue).gmap('closeInfoWindow');
		}
		jQuery('body').prepend('<div id="overlay_venue_info"></div>');
		jQuery('#web_wrapper').addClass('grayscaleKuasEffect');
		var validate = kuas_ajax_var.kuas_ajax_date, locale = kuas_ajax_var.kuas_locale.split('_'), venueid = jQuery(this).attr('id');
		var api_request = 'https://api.foursquare.com/v2/venues/'+venueid+'?v='+validate+'&locale='+locale[0]+'&client_id='+clid+'&client_secret='+clsc;
		jQuery.getJSON(api_request).done(function(detail_venue){
			var vres = detail_venue.response.venue;
			var vdrs = vres.location.address? '<br /><span class="venue_info_address">'+vres.location.address+'</span>' : '';
			buildHTML = '<a href="javascript:void(0)" class="venue_info_close">x</a><div class="venue_info_container">';
			buildHTML += '<div class="content_venue_info">';
			if(vres.photos.groups.length != 0){
				var vphoto = vres.photos.groups[0].items;
				if(vres.photos.groups[0].items.length > 1){
					buildHTML += '<h2 class="title_venue_info vp_min_type" style="background-image:url('+vphoto[0].prefix+'800x200/blur20'+vphoto[0].suffix+');">'+vres.name+vdrs+'</h2>';
					buildHTML += '<div class="content_venue_info_photo">';
					for(i=0;i<vphoto.length;i++){
					buildHTML += '<img src="'+vphoto[i].prefix+'150x150'+vphoto[i].suffix+'">';
					}
					buildHTML += '</div>';
				}
				else {
					buildHTML += '<h2 class="title_venue_info vp_big_type" style="background-image:url('+vphoto[0].prefix+'800x400/blur5'+vphoto[0].suffix+');">'+vres.name+vdrs+'</h2>';
				}
			}
			else {
				buildHTML += '<h2 class="title_venue_info vp_big_type" style="background-image:url(http://maps.googleapis.com/staticmap?center='+vres.location.lat+','+vres.location.lng+'&zoom=15&size=800x400&sensor=false&markers='+vres.location.lat+','+vres.location.lng+'&key=AIzaSyDqTk9v4VHI8ztJffbjsRnOlhY29lJzrgA);">';
				buildHTML += vres.name+vdrs+'</h2>';
			}
			buildHTML += '</div>'; // .content_venue_info
			buildHTML += '<div class="meta_venue_info">';
			if(vres.tips.groups.length > 0){
				buildHTML += '<div class="venue_info_tips"><h3>Tips '+vres.name+'</h3><ul>';
				var vtips = vres.tips.groups[0].items;
				for(i=0;i<vtips.length;i++){
				buildHTML += '<li>'+vtips[i].text+'</li>';
				}
				buildHTML += '</ul></div>';
			}
			var vrate = vres.rating? vres.rating : 0;
			if(vrate!=0){
			buildHTML += '<div class="venue_info_rating"><small>Rating:</small><br /><big>'+vrate+'</big></div>';
			}

			buildHTML += '<div class="venue_info_stats">'+vres.stats.checkinsCount+'+ orang Check-in di:<br />'+vres.name+'</div>';

			buildHTML += '</div>';
			buildHTML += '<a class="venue_source" href="'+vres.canonicalUrl+'" target="_blank"></a></div>';
			jQuery('#overlay_venue_info').html(buildHTML).css({'background-image':'none','background-color':'whitesmoke'});
		});
	});
	
	jQuery('#overlay_venue_info .venue_info_close').live('click',function(){
		jQuery(this).parent('#overlay_venue_info').remove();
		jQuery('#web_wrapper').removeClass('grayscaleKuasEffect');
	});

	jQuery(document).keyup(function(e) {
		if (e.keyCode == 27) {
			jQuery('#overlay_venue_info').remove();
			jQuery('#web_wrapper').removeClass('grayscaleKuasEffect');
		}
	});	
}

/* SHARE LAZY */
	(function(){
		var s=document.createElement('script'); 
		s.type='text/javascript'; 
		s.async=true; 
		s.src='http://connect.facebook.net/id_ID/all.js?ver=MU#xfbml=1&appId=427637250695040'; 
		var x=document.getElementsByTagName('script')[0]; 
		x.parentNode.insertBefore(s,x);
	})(); 
	jQuery('#social-count-post[data-post^="post"]').bind("mouseenter",function(){
		var id=jQuery(this).attr("data-post").slice(5); 
		var permalink=jQuery(this).attr("data-permalink"); 
		var title=jQuery(this).attr("data-title"); 
		jQuery('#sharing-'+ id).css('background','none'); 
		var fb_str='<fb:like href="'+ permalink+'" layout="button_count" width="100" height="20" colorscheme="light" layout="button_count" action="like" show_faces="false" send="false"></fb:like>'; 
		jQuery('#fb-kuas-share-'+ id).removeClass('facebook').css({'width':'auto','height':'20px'}).html(fb_str); 
		FB.XFBML.parse(document.getElementById('fb-kuas-share-'+ id)); 
		var twitter_str='<span style="float:left;width:100px;margin-right:5px;"><iframe allowtransparency="true" frameborder="0" scrolling="no" src="http://platform.twitter.com/widgets/tweet_button.html?url='+ permalink+'&amp;text='+ title+'&amp;via=OfanEbob" style="width:130px; height:50px;" allowTransparency="true" frameborder="0"></iframe></span>'; 
		jQuery('#tweet-kuas-share-'+ id).css('width','110px').removeClass('twitter').html(twitter_str); 
		jQuery('#gplus-kuas-share-'+ id).parent().removeClass('gplus'); 
		if(typeof(gapi)!='object')
			jQuery.getScript('http://apis.google.com/js/plusone.js',function(){
				gapi.plusone.render('gplus-kuas-share-'+ id,{"href":permalink,"size":'medium'});
			});
			else{gapi.plusone.render('gplus-kuas-share-'+ id,{"href":permalink,"size":'medium'});
		}
		jQuery(this).unbind('mouseenter mouseleave');
	});

/* SMILEY */
    function show_fontcode_cheat(){jQuery(this).one("click", hide_fontcode_cheat); jQuery('div.font_emot_glossarium').fadeIn(); } 
    function hide_fontcode_cheat(){jQuery(this).one("click", show_fontcode_cheat); jQuery('div.font_emot_glossarium').fadeOut(); } 
    jQuery("a.show_smiley_code_cheat").one("click", show_fontcode_cheat);

/* TOOLTIP */
	if(kuas_ajax_var.kuas_is_mobile==0){
		jQuery('.normalTip').Kuas_ToolTip({toolTipClass: 'kuas_Tooltip bgColorBaseKuas_y3 borderColorBaseKuas headingColorBaseKuas'});	
		jQuery('.fixedTip').Kuas_ToolTip({ fixed: true, toolTipClass: 'kuas_Tooltip bgColorBaseKuas_y3 borderColorBaseKuas headingColorBaseKuas'});	
		//jQuery('.callBackTip').Kuas_ToolTip({ clickIt: true, onShow: function(){alert('I fired OnShow')}, onHide: function(){alert('I fired OnHide')}});
		jQuery('.clickTip').Kuas_ToolTip({ clickIt: true, tipContent: jQuery(this).attr('title') });
	}

});

jQuery(document).ready(function($){

/* CITIZEN JURNAL - AJAX FORM */
	jQuery("#FrontPostImage").change(function(){ kuasReadImageUpload(this,'#KuasImagePreview');});
	var options = { 
	target: '.message_frontpost', 
	beforeSubmit: KuasShowRequestPost, success: KuasShowResponsePost, 
	dataType:'json', url: kuas_ajax_var.kuas_ajax_url
	}; 
	jQuery('#FrontPressForm').ajaxForm(options); 

});

/* CITIZEN JURNAL - AJAX FORM */
function KuasShowRequestPost(formData, jqForm, options){
	kuasBackToTop('#web_wrapper',10,10,500);
    NProgress.start();
    NProgress.inc();
	jQuery(this).find('input[type="submit"]').attr("disabled", "disabled");
}
function KuasShowResponsePost(responseText, statusText, xhr, $form){
	if(responseText.code==2){type_code = 'attention_message';}
	else if(responseText.code==1){
		type_code = 'success_message';
		$form.resetForm();
		kuasResetImageRead($form,'#KuasImagePreview');
	}
	else{type_code = 'error_message';}
	jQuery('.message_frontpost').html('<div id="'+type_code+'">'+responseText.msg+'</div>');
	$form.find('input[type="submit"]').removeAttr("disabled");
	NProgress.done();
}
function kuasReadImageUpload(input,elm) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            jQuery(elm).css({'background-image':'url('+e.target.result+')'}).addClass('valid_img');
        }
        reader.readAsDataURL(input.files[0]);
    }
}
function kuasResetImageRead(t,elm){
	t.find(elm).css({'background-image':'url('+kuas_ajax_var.kuas_domain+'/assets/images/photo-placeholder.jpg)'}).removeClass('valid_img');
}

/* MODULE FUNCTION SLIDING TOP */
function kuasBackToTop(element,minus,plus,speed){
	if(kuas_ajax_var.kuas_is_mobile==0){
		if(!minus){minus = 10;}
		if(!plus){plus = 10;}
		if(!speed){speed = 500;}
		if(jQuery(element).offset()){
			if(jQuery(element).offset().top < jQuery(window).scrollTop()){
			jQuery('html,body').animate({scrollTop:jQuery(element).offset().top - minus}, speed);
		}
		else if(jQuery(element).offset().top + jQuery(element).height() > jQuery(window).scrollTop() + (window.innerHeight || document.documentElement.clientHeight)){
			jQuery('html,body').animate({scrollTop:jQuery(element).offset().top - (window.innerHeight || document.documentElement.clientHeight) + jQuery(element).height() + plus}, speed);}
		}
	}
}

/* MODULE FUNCTION BIG FIRST WORD */
function ucwords(str) {
  return (str + '').replace(/^([a-z\u00E0-\u00FC])|\s+([a-z\u00E0-\u00FC])/g, function ($1) {
    return $1.toUpperCase();
  });
}

/* MODULE FUNCTION MAP LOADING INDICATOR */
function maps_loading(t,c){
	if(!c){ c = search_venue_text; }
	switch (t) {
		case 'start':
		jQuery(loader_maps).text(c).prependTo('#containner_top');
		break;
		case 'stop':
		jQuery('span.maps_loading').remove(); 
		break;
		default:
		jQuery(loader_maps).text(c).prependTo('#containner_top');
	}
}

/* MODULE FUNCTION PROTOTYPE ARRAY COUNT */
Array.prototype.count = function() {
	return this.length;
};

/* MODULE FUNCTION PROTOTYPE MATCH ARRAY */
String.prototype.isMatch = function(s){
   return this.match(s)!==null
}

/* MODULE FUNCTION ARRAY RANDOM */
function getRandomArr(w) {
    var index = Math.floor( Math.random() * w.length );
    return w[index];
}

/* MODULE FUNCTION LIMIT TEXT */
function limitTitleWords(t,l){
	var searchSpace = t.match(/ /g); 
	var limitTitleWords = Array();
	if(searchSpace){
		if(searchSpace.length > l){
			var split_title = t.split(' ');
			for(i=0;i<l;i++){
			limitTitleWords.push(split_title[i]);
			}
		}
		else {
			limitTitleWords.push(t);
		}
	}
	else {
		limitTitleWords.push(t);
	}
	return ucwords(limitTitleWords.join(' '));
}

/* MODULE FUNCTION EXTEND JQUERY TOGGLE CLICK */
(function($) {
    $.fn.clickToggle = function(func1, func2) {
        var funcs = [func1, func2]; this.data('toggleclicked', 0); this.click(function() {var data = $(this).data(); var tc = data.toggleclicked; $.proxy(funcs[tc], this)(); data.toggleclicked = (tc + 1) % 2; }); return this;
    };

	$.fn.shuffle = function() {
	return this.each(function(){
	  var items = $(this).children(); return (items.length) ? $(this).html($.shuffle(items)) : this; });
	}

	$.shuffle = function(arr) {
	for(var j, x, i = arr.length; i; j = parseInt(Math.random() * i), x = arr[--i], arr[i] = arr[j], arr[j] = x ); return arr;
	}
})(jQuery);