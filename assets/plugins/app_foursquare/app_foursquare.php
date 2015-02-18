<?php
/*
Plugin Name: App Foursquare
Plugin URI: http://ofan.kuninganasri.com
Description: Display your venue's foursquare stats in an easy-to-use widget or with the <code>[venue id=3945]</code> shortcode.
Author: Ofan Ebob
Author URI: http://about.me/ofan
Version: 5.1.1
License: GPL2
*/

// Set Default Options

register_activation_hook(__FILE__,'foursquare_venue_activate');
register_deactivation_hook(__FILE__,'foursquare_venue_deactivate');

function foursquare_venue_activate() {
	add_option('foursquare_venue_client_id','');
	add_option('foursquare_venue_client_secret','');
	add_option('foursquare_venue_show_title','');
	add_option('foursquare_venue_stats_title','Foursquare Stats');
	add_option('foursquare_show_venue_name','');
	add_option('foursquare_show_venue_icon','');
	add_option('foursquare_show_here_now','1');
	add_option('foursquare_show_here_now_text','');
	add_option('foursquare_show_total','1');
	add_option('foursquare_show_total_text','');
	add_option('foursquare_show_mayor','1');
	add_option('foursquare_show_mayor_text','');
	add_option('foursquare_link_mayor','1');
	add_option('foursquare_show_mayor_photo','1');
	add_option('foursquare_mayor_photo_size','32');
	add_option('foursquare_venue_stats_width','');
	add_option('foursquare_venue_stats_align','');
	add_option('foursquare_venue_category','is empty');
}

function foursquare_venue_deactivate() {
	delete_option('foursquare_venue_client_id');
	delete_option('foursquare_venue_client_secret');
	delete_option('foursquare_venue_show_title');
	delete_option('foursquare_venue_stats_title');
	delete_option('foursquare_show_venue_name');
	delete_option('foursquare_show_venue_icon');
	delete_option('foursquare_show_here_now');
	delete_option('foursquare_show_here_now_text');
	delete_option('foursquare_show_total');
	delete_option('foursquare_show_total_text');
	delete_option('foursquare_show_mayor');
	delete_option('foursquare_show_mayor_text');
	delete_option('foursquare_link_mayor');
	delete_option('foursquare_show_mayor_photo');
	delete_option('foursquare_mayor_photo_size');
	delete_option('foursquare_venue_stats_width');
	delete_option('foursquare_venue_stats_align');
	delete_option('foursquare_venue_category');
}

// CLEAR THE TRANSIENT
if( isset($_GET['clear_4square_cache_venues']) ){
	delete_transient( 'foursquare_venues_'.$_GET['clear_4square_cache_venues'] );
}

if( isset($_GET['clear_4square_cache_lists']) ){
	delete_transient( 'foursquare_lists_'.$_GET['clear_4square_cache_lists'] );
}

function renderErrorVenueData($message,$id,$type){
	//return '[{"Error: '.$message.'","<a href="'.$_SERVER['REQUEST_URI'].'?clear_4square_cache_'.$type.'='.$id.'">'.__('Coba klik ini untuk memuat ulang','kuas-beta').'</a>"}]';
	return '<!-- DATA FOURSQUARE ERROR: '.$_SERVER['REQUEST_URI'].'?clear_4square_cache_'.$type.'='.$id.' -->';
}

function defineAPIFoursquareURI($id,$type){
	$validate_foursquare = date('Ymd');
	$fsq_api_key1 = array('P20APVP31JG3U0UJC4ZPWSSWW5GMP4WJ014TA5JAGWYXJBLD', 'OQIS4CBVG1TNQCRQMWOBHLOCZMCP5ZKPCF1AMXBS13EI5MEE');
	$fsq_api_key2 = array('44KWZR2C3HVDWSTGPEOEHFTNA2DHL32BKDCWUJC3HD1ZDHZF', 'LXSUSXZWIMARWLTVBZT3HVUYOA5RN0SNR1NUJF4AFRQRBWQ4');
	$fsq_api_key3 = array('A2BZQ3VFIILKB0KA1BMLV1DXS5M0E3BSNRW1FVDOXI20OELK', 'OWKK1WFBYICBUBL0VC5UF4UNTHAC0TPE0LY2LJW1C1EYN31I');
	$fsq_api_key4 = array('AXXY1AEIL1MVUIS2JKJTSJEMBLKX0IFE223EDVQPZFBR42QB', 'U5TQYKX3F1CNOVH1PT5QCKSM4WSL2H0NEKXUGRCHTOJTYHIB');
	$fsq_api_key5 = array('A4NVEI2FKX3QR5CBC24S4TIKTY1WXWJ2ZSO5VPGLMKARPM0I', 'QODM2JM2Z4BVZ5DXVI0F2U050DSNEN2B2B5LHTDTOLUD5CFX');	
	$foursquare_api_keys = array( $fsq_api_key1, $fsq_api_key2, $fsq_api_key3, $fsq_api_key4, $fsq_api_key5 );
	$random_foursquare_keys = randomArrayVar($foursquare_api_keys);
	$client_id = $random_foursquare_keys[0];
	$client_secret = $random_foursquare_keys[1];
	$request = "https://api.foursquare.com/v2/$type/$id?locale=id&client_id=$client_id&client_secret=$client_secret&v=$validate_foursquare";
	return $request;
}

function getVenueData($id,$type,$timetransientreset=5){
    $transient_name = 'foursquare_'.$type.'_'.$id;
    if(get_transient( $transient_name )){
    	$data = get_transient( $transient_name );
    }
    else {
		$request = defineAPIFoursquareURI($id,$type);
		$response  = kuas_custom_parse_curl($request);
		if( $response == null ) {
			$data = renderErrorVenueData('Request Null',$id,$type);
		} 
		else {
			$data = json_decode($response);
			// Default set time is 1 half hours = 5 * 24 * 60 * 60 is same 5 days living
			// set_transient( $transient_name, $data, apply_filters( 'foursquare_cache', 90 * DAY_IN_SECONDS) );
		}
    }
    return $data;
}

function renderVenueInfo($venue) {
	$validate_foursquare = date('Ymd');
	$venueID = $venue->response->venue->id;
	$venueName = $venue->response->venue->name;
	$venueCategories = $venue->response->venue->categories;
	$venueURL = $venue->response->venue->shortUrl;
	
	$stats_title = get_option('foursquare_venue_stats_title');
	
	$rendered_html .= '<h3 data-date="'.$validate_foursquare.'" data-vid="'.$venueID.'">';
	if(get_option('foursquare_show_venue_name')==1) {
		if(get_option('foursquare_show_venue_icon')==1 && count($venueCategories) > 0) {
			$categoriesName = $venue->response->venue->categories[0]->name;
			$venueIconPreFix = $venue->response->venue->categories[0]->icon->prefix;
			$venueIconSuffix = $venue->response->venue->categories[0]->icon->suffix;
			$venueIcon = $venueIconPreFix.'32'.$venueIconSuffix;
			$rendered_html .= '<img src="' . $venueIcon . '" style="border: 0; margin: 0;" align="absmiddle" alt="'.$categoriesName.'" /> ';
		}
		else {
			$rendered_html .= '<img src="https://foursquare.com/img/categories_v2/none_32.png" style="border: 0; margin: 0;" align="absmiddle" alt="'.$categoriesName.'" /> ';
		}
		if(get_option('foursquare_venue_show_title')==1) {
			$rendered_html .= $stats_title.': ';
		}

		if(count($venueCategories) > 0){
			$categoriesName = $venue->response->venue->categories[0]->name;
			$rendered_html .= '<a href="' . $venueURL . '" title="'.$venueName.' (jenis '.$categoriesName.' di 4square)" target="_blank">';
		} else {
			$rendered_html .= '<a href="' . $venueURL . '" title="'.$venueName.'" target="_blank">';			
		}

		$rendered_html .= $venueName;
		$rendered_html .= '</a>';
	}
	$rendered_html .= '</h3>';
	return $rendered_html;
}

function renderVenueStats($venue,$id) {
	if ($venue->meta->code==200){
		$venueName = $venue->response->venue->name;
		$rating = $venue->response->venue->rating;
		$mayorVenue = $venue->response->venue->mayor;
		$hereNow = $venue->response->venue->hereNow;		
		$PeopleStats = $venue->response->venue->stats;
		$GeoLocation = $venue->response->venue->location;
		$venueURL = $venue->response->venue->shortUrl;
		$venueAttributes = $venue->response->venue->attributes->groups;

		$mayor_text = get_option('foursquare_show_mayor_text');
		if($mayor_text=='') {
			$mayor_text = 'Mayor:';
		}

		$rendered_html .= '<div class="foursquare_venue_content">';

			$rendered_html .= '<div class="stats_foursquare_venue">';
			
			//RenderPeopleStats
			$rendered_html .= renderPeopleStats($PeopleStats,$hereNow);

			$showRating = $rating? $rating : '';
			//RenderStatsGraphical
			$rendered_html .= renderStatsGraphical($showRating,$mayorVenue);
			
			//RenderVenueButtonCheckin
			$rendered_html .= renderVenueButtonCheckin($venueURL,$venue->response->venue->id,'black');
			$rendered_html .= '</div>';

		//RenderVenueMaps
		if(wp_is_mobile()){
		$maps_size = '500x170';
		}
		else {
		$maps_size = '285x155';	
		}
		$rendered_html .= renderVenueMaps(array('VenueLat'=>$GeoLocation->lat,'VenueLng'=>$GeoLocation->lng,'VenueAddress'=>$GeoLocation->address),$venueName,array('size'=>$maps_size,'limit_title'=>20),false);
		if(wp_is_mobile()==false){
		$rendered_html .= '<div class="clear"></div>';
		}

		//RenderAttributes
		$rendered_html .= renderAttributes($venueAttributes,$venueName,false);
		$rendered_html .= '</div>';
		return $rendered_html;
	}
	else{
		return renderErrorVenueData($venue->meta->errorDetail,$id,'venues');
	}
}

function renderPeopleStats($PeopleStats,$hereNow){
	$hereNow = $hereNow->count;
	$totalUser = $PeopleStats->usersCount;
	$totalCheckins = $PeopleStats->checkinsCount;

	$here_now_text = get_option('foursquare_show_here_now_text');
		if($here_now_text=='') { $here_now_text = 'People here now:'; }

	$total_text = get_option('foursquare_show_total_text');
		if($total_text=='') { $total_text = 'Total check-ins:'; }

	$output = '<ul id="foursquare_stats" class="foursquare_data_root">';
	if(get_option('foursquare_show_here_now')==1){
		if($hereNow > 0) { $output .= '<li>' .thousandsCurrencyFormat($hereNow,'in').' &quot;org '.$here_now_text.'</li>'; }
	}
	if($totalUser > 0) { $output .= '<li>'.thousandsCurrencyFormat($totalUser,'in') .' &quot;org pernah kesini</li>'; }
	if(get_option('foursquare_show_total')==1) {
		$output .= '<li>'.thousandsCurrencyFormat($totalCheckins,'in').' &quot;org '.$total_text.' disini</li>';
	}		
	$output .= '</ul>';	
	return $output;
}

function renderStatsGraphical($showRating='',$mayorVenue=0){
	if(wp_is_mobile()==false){
	$output = '<div class="clear"></div>';
	}
	$output .= '<ul id="foursquare_stats_graphic" class="foursquare_data_root stats_foursquare_graphic">';
	if($showRating!=''){
		if($showRating > 6){$color_class='rate_green';}elseif($showRating == 10){$color_class='rate_orange';}else{$color_class='rate_white';}
		$output .= '<li><span class="rating_box '.$color_class.'"><small>Rating</small><br><big>'.$showRating.'</big></span></li>';
	}
	if(get_option('foursquare_show_mayor')==1 && $mayorVenue->count > 0) {
		//Get detail fetch data mayor if this venue have recent mayor
		$mayor = $mayorVenue->user->firstName . ' ' . $mayorVenue->user->lastName;
		$mayorURL = 'http://foursquare.com/user/' . $mayorVenue->user->id;
		$mayorPicPrefix = $mayorVenue->user->photo->prefix;
		$mayorPicSuffix = $mayorVenue->user->photo->suffix;		
		//Render Mayor data to HTML
		$mayorPic = $mayorPicPrefix.get_option('foursquare_mayor_photo_size').'x'.get_option('foursquare_mayor_photo_size').$mayorPicSuffix;
		$output .= '<li id="foursquare_mayor" class="foursquare_data_root mayor_venue">';
		if(get_option('foursquare_link_mayor')==1) {
			$output .= $mayor_text . ' ';
			$output .= '<a href="' . $mayorURL . '" class="normalTip" title="' . $mayor . '" target="_blank">'.$mayor.'</a>';
		}
		if(get_option('foursquare_show_mayor_photo')==1 && get_option('foursquare_link_mayor')==1) {
			$output .= '<br />';
		}
		if(get_option('foursquare_show_mayor_photo')==1) {
			$output .= '<a href="' . $mayorURL . '" class="normalTip" title="Mayor ' .$venueName. ' di 4Square: ' . $mayor . '" target="_blank">';
			$output .= '<img src="https://ss0.4sqi.net/img/venuepage/icon-mayor.png" height="' . get_option('foursquare_mayor_photo_size') . '" width="' . get_option('foursquare_mayor_photo_size') . '" style="border:0;margin:0;" align="absmiddle" /></a>';
		}
		$output .= '</li>';
	}
	$output .= '</ul>';
	return $output;
}

function renderVenueMaps($dataVenue,$venueName,$dataAttr=array('size'=>'250x170','limit_title'=>20),$print=true){
	//Set condition jika $dataVenue adalah array
	if(is_array($dataVenue)){
		$GeoLocationLat = $dataVenue['VenueLat'];
		$GeoLocationLng = $dataVenue['VenueLng'];
		$addressVenue = $dataVenue['VenueAddress'];
	}
	//Set condition else jika $dataVenue adalah JSON parse
	else{
		$dataVenue=str_replace("https://foursquare.com/venue/", "", $dataVenue);
		$dataVenue=str_replace("http://foursquare.com/venue/", "", $dataVenue);
		$venue = getVenueData($dataVenue,'venues',5);
		$GeoLocationLat = $venue->response->venue->location->lat;
		$GeoLocationLng = $venue->response->venue->location->lng;
		$addressVenue = $venue->response->venue->location->address;
	}
	//Set Render HTML containner
	$output = '<div class="map_foursquare_venue">';
	//Set condition jika $addressVenue bukan type boolean true/false atau $dataVenue adalah strings
	if(is_bool($addressVenue) == false OR is_string($addressVenue)){
		$output .= '<big><a href="https://maps.google.com/maps?daddr='.$GeoLocationLat.','.$GeoLocationLng.'" target="_blank">';
		//Jika terdapat $addressVenue di JSON data, maka yang akan diambil untuk judul adalah $addresssVenue
		if($addressVenue!=''){
			$output .= kuas_beta_snippet_text($addressVenue,$dataAttr['limit_title']);
		} 
		//Jika $addressVenue tidak ada di JSON parse makan $venueName dari variable function diambil untuk judul
		else {
			$output .= 'Peta '.kuas_beta_snippet_text($venueName,$dataAttr['limit_title']);
		}
		//Selesai untuk judul
		$output .= '</big>';
	}
	$output .= '<a href="https://maps.google.com/maps?daddr='.$GeoLocationLat.','.$GeoLocationLng.'" target="_blank" class="normalTip" title="Klik peta untuk melihat '.$addressVenue.' di Google Map">';
	if(wp_is_mobile()){
	$output .= '<span class="venue_foursquare_maps" style="background-image:url(https://maps.googleapis.com/maps/api/staticmap?center='.$GeoLocationLat.','.$GeoLocationLng.'&zoom=15&size='.$dataAttr['size'].'&markers='.$GeoLocationLat.','.$GeoLocationLng.'&sensor=false)" />';
	}
	else {
	$output .= '<img src="https://maps.googleapis.com/maps/api/staticmap?center='.$GeoLocationLat.','.$GeoLocationLng.'&zoom=15&size='.$dataAttr['size'].'&markers='.$GeoLocationLat.','.$GeoLocationLng.'&sensor=false" />';
	}
	$output .= '</a></div>';

	if($print==false){return $output;}else{echo $output;}
}

function renderVenueTips($venueTips=0,$VenueName='',$limit=0,$print=false){
	$venueTipsCount = $venueTips->count;
	if( count($venueTips->groups[0]) > 0 && $venueTipsCount > 0 ) {
		$output = '<ul id="foursquare_tips" class="foursquare_data_root">';
		$TipsItems = $venueTips->groups[0]->items;		
		$loop = 0;
		foreach ($TipsItems as $k => $items) {
			$output .= '<li>'.$items->text.'</li>';
			$loop++;
			if(is_numeric($limit) && $loop == $limit) break;
		}
		$output .= '</ul>';
		if($print==false){return $output;}else{echo $output;}
	}
}

function renderVenueSpecials($venueSpecials=0,$VenueName='',$limit=0,$print=false){
	if( count($venueSpecials) > 0) {
		$SpecialsItmes = $venueSpecials->items;
		$output = '';
		$loop = 0;
		foreach ($SpecialsItmes as $k => $items) {
			if(is_bool($VenueName)==true){
			$output .= '<big>'.$items->title.'</big>';
			$output .= '<img src="https://ss0.4sqi.net/img/specials/'.$items->icon.'.png" width="48" height="48" style="padding:5% 0;" align="absmiddle" />';
			$output .= '</a>';
			}
			elseif (is_string($VenueName)=='text') {
			$output .= '<a title="'.$items->finePrint.'" class="normalTip">';
			$output .= '<span><img src="https://ss0.4sqi.net/img/specials/'.$items->icon.'.png" width="48" height="48" style="padding:5% 0;" align="absmiddle" /></span>';
			$output .= '<span>'.$items->message.'</span>';
			$output .= '</a>';
			}
			else{
			$output .= '<a title="'.$items->message.' | '.$items->finePrint.'" class="normalTip">';
			$output .= '<img src="https://ss0.4sqi.net/img/specials/'.$items->icon.'.png" width="48" height="48" style="padding:5% 0;" align="absmiddle" />';
			$output .= '</a>';
			}
			$loop++;
			if(is_numeric($limit) && $loop == $limit) break;
		}
		if($print==false){return $output;}else{echo $output;}
	}
}

function renderAttributes($venueAttributes=0,$VenueName='',$print=false){
	if( count($venueAttributes) > 0) {
		if(is_bool($VenueName)==false && is_string($VenueName) && $VenueName!=''){
		$output = '<h4 class="bgElement" style="background-repeat:no-repeat;background-position: -20px -1200px;padding:5px 20px;margin:0 0 10px 0;">Fasilitas '.$VenueName.'</h4>';
		}
		$output .= '<table><tbody>';
		foreach ($venueAttributes as $k => $groups) {
			$attributesType = $groups->type;
			$attributesName = $groups->name;
			$attributesCount = $groups->count;
			$array_items = array();
			$output .= '<tr><td class="'.$attributesType.'" width="150">'.ucwords(__($attributesName,'kuas-beta')).'</td>';
			if( count($attributesCount) > 0 ){
				//Define loop attributes->items
				$attributesItems = $groups->items;
				foreach ($attributesItems  as $n => $item) {
					$item->displayName = array_push($array_items, $item->displayValue);
				}
				$array_items = join(', ',$array_items);
				$output .=  '<td>'.$array_items.'</td>';
			}
			$output .= '</tr>';
		}
		$output .= '</tbody></table>';
	}
	if($print==false){return $output;}else{echo $output;}
}

function renderVenueButtonCheckin($venueURL,$venueID,$type='blue'){
	//Available color button is: blue, white, black
	if(wp_is_mobile()==false){
	$output = '<div class="clear"></div>';
	}
	$output .= '<a href="'.$venueURL.'" target="_blank" class="checkin_button_foursquare" id="'.$venueID.'">';
	$output .= '<img src="https://playfoursquare.s3.amazonaws.com/press/logo/checkinon-'.$type.'@2x.png" />';
	$output .= '</a>';
	return $output;
}

class Foursquare_Venue extends WP_Widget {

	function Foursquare_Venue() {
		$widget_ops = array('classname' => 'foursquare_venue_widget', 'description' => 'Foursquare Venue');
		$this->WP_Widget('foursquare_venue', 'Foursquare Venue', $widget_ops);
	}

	function widget($args, $instance) {
		extract($args);
		
		$id=str_replace("https://foursquare.com/venue/", "", $instance['venue_id']);
		$id=str_replace("http://foursquare.com/venue/", "", $id);
		$venue = getVenueData($id,'venues',5);

		echo $before_widget;
		$title = strip_tags($instance['title']);
		echo $before_title . $title . $after_title;

			$rating = $venue->response->venue->rating;
			$mayorVenue = $venue->response->venue->mayor;
			$hereNow = $venue->response->venue->hereNow;		
			$PeopleStats = $venue->response->venue->stats;

			//RenderPeopleStats
			$widget_html .= renderPeopleStats($PeopleStats,$hereNow);
			$showRating = $rating? $rating : '';
			//RenderStatsGraphical
			$widget_html .= renderStatsGraphical($showRating,$mayorVenue);

		echo $widget_html;

		echo $after_widget;
	}

	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['venue_id'] = trim(strip_tags($new_instance['venue_id']));

		return $instance;
	}

	function form($instance) {
		$instance = wp_parse_args((array)$instance, array('title' => 'Foursquare', 'venue_id' => 3945));
		$title = strip_tags($instance['title']);
		$venue_id = strip_tags($instance['venue_id']);
		?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>">Title: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo attribute_escape($title); ?>" /></label></p>
		<p><label for="<?php echo $this->get_field_id('venue_id'); ?>">Venue ID: <input class="widefat" id="<?php echo $this->get_field_id('venue_id'); ?>" name="<?php echo $this->get_field_name('venue_id'); ?>" type="text" value="<?php echo attribute_escape($venue_id); ?>" /></label></p>
		<?php
	}
}

add_action('widgets_init', 'RegisterFoursquareVenueWidget');

function RegisterFoursquareVenueWidget() {
	register_widget('Foursquare_Venue');
}

// Admin Page

add_action('admin_menu', 'foursquare_venue_menu');

function foursquare_venue_menu() {
  add_options_page('Foursquare Venue Options', 'Foursquare Venue', 'manage_options', 'foursquare-venue-options', 'foursquare_venue_options');
}

function foursquare_venue_options() {

  if (!current_user_can('manage_options'))  {
    wp_die( __('You do not have sufficient permissions to access this page.') );
  }

	?>

	<div class="wrap">

		<div id="icon-plugins" class="icon32"></div><h2>Foursquare Venue</h2>
		
		<h3>Getting Started</h3>
		
		<p>Before using this plugin, you'll have to set up a free Foursquare API key. Visit <a href="https://foursquare.com/oauth/">foursquare.com/oauth</a>, click "Register a new consumer," then enter the name of your site, your site's address, and for "Callback URL" just enter your site's address again. You'll be given two keys, "Client ID" and "Client Secret," which need to be copied and pasted into the matching fields on this page.</p>
		<p>After saving your API key you'll be able to start using the widget or shortcode. To use the widget simply add it to your sidebar and set the venue's ID. To use the shortcode just insert <code>[venue id=23647]</code> (replacing the '23647' with the desired venue's ID) into any post or page, and the plugin do the rest.</p>
		<p>The venue's ID can be found by taking the number from the end of the venue's page on the Foursquare website. For example, if the venue's URL is <code>https://foursquare.com/venue/<span style="color:green;">1278862</span></code>, the id is <code>1278862</code>.</p>
		
		<div id="icon-options-general" class="icon32"></div><h2>Options</h2>

		<form method="post" action="options.php">
		<?php wp_nonce_field('update-options'); ?>

		<h3>API Key</h3>
		
		<table class="form-table">
		
		<tr valign="top">
		<th scope="row">Client ID</th> 
		<td><fieldset><legend class="screen-reader-text"><span>Client ID</span></legend> 
		<input name="foursquare_venue_client_id" type="text" id="foursquare_venue_client_id" value="<?php echo get_option('foursquare_venue_client_id'); ?>" />
		</fieldset></td> 
		</tr>
		
		<tr valign="top">
		<th scope="row">Client Secret</th> 
		<td><fieldset><legend class="screen-reader-text"><span>Client Secret</span></legend> 
		<input name="foursquare_venue_client_secret" type="text" id="foursquare_venue_client_secret" value="<?php echo get_option('foursquare_venue_client_secret'); ?>" />
		</fieldset></td> 
		</tr>
		
		</table>

		<h3>Check-ins</h3>
		
		<table class="form-table">
		
		<tr valign="top"> 
		<th scope="row">Show Current Check-ins</th> 
		<td><fieldset><legend class="screen-reader-text"><span>Show the number of people currently checked-in</span></legend> 
		<label for="foursquare_show_here_now"><input name="foursquare_show_here_now" type="checkbox" id="foursquare_show_here_now" value="1" <?php if(get_option('foursquare_show_here_now')==1) echo "checked='checked'"; ?>/> Show the number of people currently checked-in</label> 
		</fieldset></td> 
		</tr>
		
		<tr valign="top">
		<th scope="row">Current Check-ins Text</th> 
		<td><fieldset><legend class="screen-reader-text"><span>Customize the text for the current number of check-ins</span></legend> 
		<label for="foursquare_show_here_now_text"><input name="foursquare_show_here_now_text" type="text" id="foursquare_show_here_now_text" value="<?php if(($here_now_text=get_option('foursquare_show_here_now_text'))!='') echo $here_now_text; else echo 'People here now:'; ?>" /> Customize the text for the current number of check-ins</label> 
		</fieldset></td> 
		</tr>

		<tr valign="top"> 
		<th scope="row">Show Total Check-ins</th> 
		<td><fieldset><legend class="screen-reader-text"><span>Show the total number of check-ins</span></legend> 
		<label for="foursquare_show_total"><input name="foursquare_show_total" type="checkbox" id="foursquare_show_total" value="1" <?php if(get_option('foursquare_show_total')==1) echo "checked='checked'"; ?>/> Show the total number of check-ins</label> 
		</fieldset></td> 
		</tr>
		
		<tr valign="top">
		<th scope="row">Total Check-ins Text</th> 
		<td><fieldset><legend class="screen-reader-text"><span>Customize the text for the total number of check-ins</span></legend> 
		<label for="foursquare_show_total_text"><input name="foursquare_show_total_text" type="text" id="foursquare_show_total_text" value="<?php if(($total_text=get_option('foursquare_show_total_text'))!='') echo $total_text; else echo 'Total check-ins:'; ?>" /> Customize the text for the total number of check-ins</label> 
		</fieldset></td> 
		</tr>
		
		</table>
		
		<h3>Mayor</h3>
		
		<table class="form-table">
		
		<tr valign="top"> 
		<th scope="row">Show Mayor</th> 
		<td><fieldset><legend class="screen-reader-text"><span>Show the current mayor</span></legend> 
		<label for="foursquare_show_mayor"><input name="foursquare_show_mayor" type="checkbox" id="foursquare_show_mayor" value="1" <?php if(get_option('foursquare_show_mayor')==1) echo "checked='checked'"; ?>/> Show the current mayor</label> 
		</fieldset></td> 
		</tr>
		
		<tr valign="top">
		<th scope="row">Mayor Text</th> 
		<td><fieldset><legend class="screen-reader-text"><span>Customize the text for the mayor</span></legend> 
		<label for="foursquare_show_mayor_text"><input name="foursquare_show_mayor_text" type="text" id="foursquare_show_mayor_text" value="<?php if(($mayor_text=get_option('foursquare_show_mayor_text'))!='') echo $mayor_text; else echo 'Mayor:'; ?>" /> Customize the text for the mayor</label> 
		</fieldset></td> 
		</tr>
		
		<tr valign="top"> 
		<th scope="row">Link to Mayor</th> 
		<td><fieldset><legend class="screen-reader-text"><span>Link to the current mayor</span></legend> 
		<label for="foursquare_link_mayor"><input name="foursquare_link_mayor" type="checkbox" id="foursquare_link_mayor" value="1" <?php if(get_option('foursquare_link_mayor')==1) echo "checked='checked'"; ?>/> Link to the current mayor</label> 
		</fieldset></td> 
		</tr>
		
		<tr valign="top">
		<th scope="row">Show Mayor's Photo</th> 
		<td><fieldset><legend class="screen-reader-text"><span>Show a photo of the current mayor next to their name</span></legend> 
		<label for="foursquare_show_mayor_photo"><input name="foursquare_show_mayor_photo" type="checkbox" id="foursquare_show_mayor_photo" value="1" <?php if(get_option('foursquare_show_mayor_photo')==1) echo "checked='checked'"; ?>/> Show a photo of the current mayor next to their name</label> 
		</fieldset></td> 
		</tr>

		<tr valign="top">
		<th scope="row">Mayor Photo Size</th> 
		<td><fieldset><legend class="screen-reader-text"><span>Choose what size to display the mayor's photo</span></legend> 
		<label for="foursquare_mayor_photo_size"><input name="foursquare_mayor_photo_size" type="text" id="foursquare_mayor_photo_size" value="<?php echo get_option('foursquare_mayor_photo_size'); ?>" size="3" /> (ex: 32 will create a 32x32 photo)</label> 
		</fieldset></td> 
		</tr>
		
		</table>
		
		<h3>Style</h3>
		
		<p>Note: These settings do not affect the Foursquare Venue widget, only the shortcode. Advanced users can style their Foursquare stats using CSS. Ex: <code>.venue-stats { width: 300px; float: right; }</code></p>
		
		<table class="form-table">
		
		<tr valign="top"> 
		<th scope="row">Show Shortcode Title</th> 
		<td><fieldset><legend class="screen-reader-text"><span>Show the current mayor</span></legend> 
		<label for="foursquare_venue_show_title"><input name="foursquare_venue_show_title" type="checkbox" id="foursquare_venue_show_title" value="1" <?php if(get_option('foursquare_venue_show_title')==1) echo "checked='checked'"; ?>/> Show a title above the Foursquare stats</label> 
		</fieldset></td> 
		</tr>
		
		<tr valign="top">
		<th scope="row">Shortcode Title Text</th> 
		<td><fieldset><legend class="screen-reader-text"><span>Customize the text for the mayor</span></legend> 
		<label for="foursquare_venue_stats_title"><input name="foursquare_venue_stats_title" type="text" id="foursquare_venue_stats_title" value="<?php if(($mayor_text=get_option('foursquare_venue_stats_title'))!='') echo $mayor_text; else echo 'Mayor:'; ?>" /> Customize the title above the stats</label> 
		</fieldset></td> 
		</tr>
		
		<tr valign="top"> 
		<th scope="row">Show Venue Name</th> 
		<td><fieldset><legend class="screen-reader-text"><span>Show the venue name</span></legend> 
		<label for="foursquare_show_venue_name"><input name="foursquare_show_venue_name" type="checkbox" id="foursquare_show_venue_name" value="1" <?php if(get_option('foursquare_show_venue_name')==1) echo "checked='checked'"; ?>/> Show name and link to the venue</label> 
		</fieldset></td> 
		</tr>
		
		<tr valign="top"> 
		<th scope="row">Show Venue Icon</th> 
		<td><fieldset><legend class="screen-reader-text"><span>Show venue icon</span></legend> 
		<label for="foursquare_show_venue_icon"><input name="foursquare_show_venue_icon" type="checkbox" id="foursquare_show_venue_icon" value="1" <?php if(get_option('foursquare_show_venue_icon')==1) echo "checked='checked'"; ?>/> Show an icon for the venue's category</label> 
		</fieldset></td> 
		</tr>
		
		<tr valign="top">
		<th scope="row">Width</th> 
		<td><fieldset>
		<label for="foursquare_venue_stats_width"><input name="foursquare_venue_stats_width" type="text" id="foursquare_venue_stats_width" value="<?php echo get_option('foursquare_venue_stats_width'); ?>" size="3" />px</label> 
		</fieldset></td> 
		</tr>
		
		<tr valign="top">
		<th scope="row">Align</th> 
		<td><fieldset>
		<label for="foursquare_venue_stats_alignleft"><input type="radio" name="foursquare_venue_stats_align" id="foursquare_venue_stats_alignleft" value="left" <?php if(get_option('foursquare_venue_stats_align')=='left') echo 'checked="checked" '; ?>/> Left</label> <label for="foursquare_venue_stats_alignright"><input type="radio" name="foursquare_venue_stats_align" id="foursquare_venue_stats_alignright" value="right" <?php if(get_option('foursquare_venue_stats_align')=='right') echo 'checked="checked" '; ?>/> Right</label> <label for="foursquare_venue_stats_alignnone"><input type="radio" name="foursquare_venue_stats_align" id="foursquare_venue_stats_alignnone" value="" <?php if(get_option('foursquare_venue_stats_align')=='') echo 'checked="checked" '; ?>/> None</label> 
		</fieldset></td> 
		</tr>
		
		</table>
		
		<input type="hidden" name="action" value="update" />
		<input type="hidden" name="page_options" value="foursquare_venue_client_id,foursquare_venue_client_secret,foursquare_venue_show_title,foursquare_venue_stats_title,foursquare_show_venue_name,foursquare_show_venue_icon,foursquare_show_here_now,foursquare_show_here_now_text,foursquare_show_total,foursquare_show_total_text,foursquare_show_mayor,foursquare_show_mayor_text,foursquare_link_mayor,foursquare_show_mayor_photo,foursquare_mayor_photo_size,foursquare_venue_stats_width,foursquare_venue_stats_align" />
		
		<p class="submit">
		<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
		</p>
		
		</form>

		<form method="post" action="options.php">
		<?php wp_nonce_field('update-options'); ?>

		<h3>Category Venue</h3>
		
		<table class="form-table">	
		<tr valign="top">
		<th scope="row">Get Venue Categories</th> 
		<td><fieldset>
		<textarea rows="10" cols="150" name="foursquare_venue_category" type="text" id="foursquare_venue_category"><?php echo get_option('foursquare_venue_category'); ?></textarea>
		</fieldset></td> 
		</tr>
		</table>
		<p class="submit">
		<input type="submit" class="button-primary" value="<?php _e('Get Category Foursquare') ?>" />
		</p>
		</form>

	</div>

	<?php
}

if(!function_exists('getCategoriesVenue')){
	function getCategoriesVenue(){
		$venue_category_opt = json_decode(get_option('foursquare_venue_category'));
		$venue_cat_opt = $venue_category_opt->categories;
		$results = array();
		foreach($venue_cat_opt as $vCat) {
			$results[] = '{"id":"'.$vCat->id.'","name":"'.$vCat->name.'","icon":"'.$vCat->icon->prefix.'"}';
		}
		return join(',',$results);
	}
}

class Meta_Box_Foursquare_Venue{
	function __construct() {
		$this->Meta_Box_Foursquare_Venue();
	}
	
	function Meta_Box_Foursquare_Venue() {
		add_action( 'init', array( &$this, 'init_Foursquare_Venue' ) );
	}

	function init_Foursquare_Venue() {
		add_shortcode('venue', array( &$this, 'shortcode_foursquare_venue') );	
		add_action( 'admin_init', array( &$this, 'admin_init_setting_Foursquare_Venue') );
		add_action( 'save_post', array( &$this, 'meta_save_setting_Foursquare_Venue') );
	}

	function admin_init_setting_Foursquare_Venue(){
		add_meta_box("add_setting_Foursquare_Venue", "Venue ID (untuk tulisan yang harus menampilkan peta)", array( &$this, "add_setting_Foursquare_Venue"), "post", "normal", "high");
		add_meta_box("add_setting_Foursquare_Venue", "Venue ID (untuk tulisan yang harus menampilkan peta)", array( &$this, "add_setting_Foursquare_Venue"), "page", "normal", "high");
	}

	function add_setting_Foursquare_Venue() {
		global $post;
		//Add an nonce field so we can check for it later.
		wp_nonce_field( 'add_setting_Foursquare_Venue', 'add_setting_Foursquare_Venue_nonce' );
		//Use get_post_meta to retrieve an existing value from the database.
		$value = get_post_meta( $post->ID, 'Venue_ID', true );

		$title = 'Ketik di kotak cari Im looking for, kemudian cari venue yg dimaksud (sesuai judul & paling populer), klik judulnya dan copy paste kode dibelakang nama venue. Contoh: https://foursquare.com/v/kuningan/COPY_PASTE_KODE_INI';
		$output = '<div id="Foursquare_Venue" class="Foursquare_Venue">';
		$output .= '<label for="Venue_ID">'.__('Foursquare Venue ID:').'</label>';
		$output .= '<input type="text" id="Venue_ID" class="Venue_ID" size="50" name="Venue_ID" value="'.$value.'" />';
		$output .= '<hr size="1" color="eeeeee" /><a href="https://foursquare.com/explore?mode=url&near=Kuningan%2C%20Indonesia" target="_blank" title="'.$title.'">Cari: '.$post->title.' di Foursquare &rarr;</a></div>';
		echo $output;
	}

	function meta_save_setting_Foursquare_Venue(){
		global $post;
		if ( !isset( $_POST['add_setting_Foursquare_Venue_nonce'] ) ) return $post_id;

		/* Verify that the nonce is valid. */
		if ( !wp_verify_nonce( $_POST['add_setting_Foursquare_Venue_nonce'], 'add_setting_Foursquare_Venue' ) ) return $post_id;

		/* If this is an autosave, our form has not been submitted, so we don't want to do anything. */
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return $post_id;

		/* Check the user's permissions. */
		if ( 'page' == $_POST['post_type'] ) {
			if ( !current_user_can( 'edit_page', $post_id ) ) return $post_id;
		} 
		else {
			if ( !current_user_can( 'edit_post', $post_id ) ) return $post_id;
		}

		/* OK, its safe for us to save the data now. */
		/* Sanitize user input */
		$VenueData = sanitize_text_field( $_POST['Venue_ID'] );
		update_post_meta($post->ID, "Venue_ID",$VenueData);	  
	}

	/* Shortcode */
	function shortcode_foursquare_venue($atts) {
		extract(shortcode_atts(array( 'id' => '', ), $atts));

			$venue = getVenueData($id,'venues',5);
			
			if ($venue->meta->code==200) {

				$widget_html = '<div class="venue-stats';
				if(get_option('foursquare_venue_stats_align')=='left') $widget_html .= ' alignleft';
				if(get_option('foursquare_venue_stats_align')=='right') $widget_html .= ' alignright';
				$widget_html .= '"';
				if(get_option('foursquare_venue_stats_width')!='') $widget_html .= ' style="width:calc(100% - 2px);min-width:' . get_option('foursquare_venue_stats_width') . ';"';
				$widget_html .= '>';

				// Display Venue's Info
				$widget_html .= renderVenueInfo($venue);

				// Display Venue's Statistics
				$widget_html .= renderVenueStats($venue,$id);

				$widget_html .= '</div>';
				
				return $widget_html;
			
			} 
			else {
				return renderErrorVenueData($venue->meta->errorDetail,$id,'venues');
			}
	}
}
$kuas_Meta_Box_Foursquare_Venue = new Meta_Box_Foursquare_Venue();

?>