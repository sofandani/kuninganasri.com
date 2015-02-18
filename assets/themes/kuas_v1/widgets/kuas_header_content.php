<?php
/**
 * Header Widget Display Foursquare Recomended Venue & Twitrend
 * name: Header Suggest Widget
 *
 * @file      kuas_header_suggest.php
 * @package   kuas-beta
 * @author    Kuningan_Asri.
 * @link 	  http://kuas.com
 */

//add_action('wp_enqueue_scripts', 'kuas_header_maps_script');
if(wp_is_mobile()==false){
	add_action('wp_enqueue_scripts', 'kuas_header_content_script');
	add_action('inner_header_bottom', 'kuas_foursquare_varvar_script');
	add_action('wp_print_styles', 'kuas_header_content_style');
	add_action('wp_ajax_post_venue', 'kuas_post_by_venue_ajax');
	add_action('wp_ajax_nopriv_post_venue', 'kuas_post_by_venue_ajax');
}

add_shortcode('kuas_gmaps', 'shortcode_kuas_gmaps');

/**
 *
 * KUAS POST BY ID VENUE FOURSQUARE
 *
 * @since KuAs 1.0
 *
 */
function kuas_post_by_venue_id($post_type='',$cat='',$orderby='',$limit='',$order=''){
	//meta_value_num
	$post_type = $post_type? $post_type : 'post';
	$cat = $cat? $cat : '38,49';
	$orderby = $orderby? $orderby : 'meta_value_num';
	$limit = $limit? $limit : '100';
	$order = $order? $order : 'ASC';

	$post_venue_param = array('post_type'=>$post_type,'cat'=>$cat,'meta_query'=>array(array('key'=>'Venue_ID','value'=>'','compare'=>'!=')),'orderby'=>$orderby,'posts_per_page'=>$limit,'order'=>$order);
	query_posts( $post_venue_param );
	if( have_posts() ) {
		$result = array();
		while( have_posts() ){ 
			the_post();
			$venue_id = get_post_meta( get_the_ID(), 'Venue_ID', 'single');
			if($venue_id){
				//$foursquare_venue = get_transient('foursquare_venues_'.$venue_id);
				$foursquare_venue = getVenueData($venue_id,'venues',5);
				$data_name = $foursquare_venue->response->venue->name;
				$data_url = $foursquare_venue->response->venue->shortUrl;
				$GeoLocation = $foursquare_venue->response->venue->location;
				$data_total_user = $foursquare_venue->response->venue->stats->usersCount;
				$data_total_checkins = $foursquare_venue->response->venue->stats->checkinsCount;
				if(count($foursquare_venue->response->venue->categories) > 0){
				$data_icon_prefix = $foursquare_venue->response->venue->categories[0]->icon->prefix;
				$data_icon_suffix = $foursquare_venue->response->venue->categories[0]->icon->suffix;
				$data_icon_fix = $data_icon_prefix.'bg_32'.$data_icon_suffix;
				$data_category_id = $foursquare_venue->response->venue->categories[0]->id;
				$data_category_name = $foursquare_venue->response->venue->categories[0]->shortName;
				}
				else {
				$data_icon_fix = site_url('assets/images').'/none_bg_32.png';
				$data_category_id = '';
				$data_category_name = 'uncategories';
				}
				$data_venue = array('venue_id'=>$venue_id,
									'venue_url'=>$data_url,
									'venue_name'=>$data_name,
									'venue_checkin'=>$data_total_checkins,
									'venue_users'=>$data_total_user,
									'venue_address'=>$GeoLocation->address,
									'venue_city'=>$GeoLocation->city,
									'venue_state'=>$GeoLocation->state,
									'venue_country'=>$GeoLocation->country,
									'venue_lat'=>$GeoLocation->lat,
									'venue_lng'=>$GeoLocation->lng,
									'venue_category_id'=>$data_category_id,
									'venue_category_name'=>$data_category_name,
									'venue_icon'=>$data_icon_fix);
			}
			else {
				$data_venue = null;
			}
			$post_url = wp_get_shortlink(get_the_ID(),'post');
			$social_count_meta_post = kuas_render_social_share_count($post_url,'total',get_the_ID(),0,0);
			$views_post = get_post_meta( get_the_ID(), 'views', 'single');
			$views_post = $views_post ? intval($views_post) : 0;
			$venue_post_image_url = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'thumbnail');
			$result[] = array(	'id'=>get_the_ID(),
								'link'=>$post_url,
								'title'=>get_the_title(get_the_ID()),
								//'excerpt'=>kuas_beta_slice_title_limit(get_the_excerpt(),20),
								'thumbnail'=>$venue_post_image_url[0],
								'score'=>(intval($social_count_meta_post)+$views_post),
								'venue'=>$data_venue
							);	    
		}
		$return = array('code'=>200,'post_venue'=>$result);
	}
	else {
		$return = array('code'=>500);
	}
	wp_reset_query();
	return $return;
}

/**
 *
 * KUAS POST BY VENUE ID FOURSQUARE (mobile mode)
 *
 * @since KuAs 1.0
 *
 */
function kuas_post_by_venue_id_mobile($limit=4,$print=1){
	//meta_value_num
	$limit = $limit? $limit : 4;

	$post_venue_param = array('post_type'=>'post','cat'=>'37,38,49','meta_query'=>array(array('key'=>'Venue_ID','value'=>'','compare'=>'!=')),'orderby'=>'rand','posts_per_page'=>$limit,'order'=>'DESC');
	query_posts( $post_venue_param );
	if( have_posts() ) {
		$output = '<div id="venue_post_featured">';
		while( have_posts() ){ 
			the_post();
			$venue_id = get_post_meta( get_the_ID(), 'Venue_ID', 'single');
			if($venue_id){
				$foursquare_venue = getVenueData($venue_id,'venues',5);
				$data_icon_prefix = $foursquare_venue->response->venue->categories[0]->icon->prefix;
				$data_icon_suffix = $foursquare_venue->response->venue->categories[0]->icon->suffix;
				$GeoLocation = $foursquare_venue->response->venue->location;

			}    
			$output .= '<div id="venue_post_'.get_the_ID().'" class="venue_post_content"><a href="'.get_permalink(get_the_ID()).'" class="venue_post display-inline-block">';
			$output .= '<i class="'.$cat_slug.' iconicballs iconic_feat" style="background-image:url('.$data_icon_prefix.'32'.$data_icon_suffix.')"></i><h3 class="headingColorBaseKuas bgColorBaseKuas_y1">'.kuas_beta_slice_title_limit(get_the_title(get_the_ID()),4).'...</h3>';
			$output .= '<span class="thumb_venue_post">'.get_the_post_thumbnail( get_the_ID(), 'feat-thumb', array('title' => get_the_title(get_the_ID()) )).'</span>';
			$output .= '<span class="maps_venue_post" style="background-image:url(https://maps.googleapis.com/maps/api/staticmap?center='.$GeoLocation->lat.','.$GeoLocation->lng.'&zoom=15&size=350x350&markers='.$GeoLocation->lat.','.$GeoLocation->lng.'&sensor=false)" /></a></span>';
			$output .= '</a></div>';
		}
		$output .= '</div>';
	}
	wp_reset_query();

	$output .= '<a href="'.site_url('?s=kuningan&saluran=kulinerkuningan,wisatakuningan').'" class="read_more">'.__('Cari tempat lainnya','kuas-beta').'</a>';

	if($print==true){
	echo $output;
	} 
	else {
	return $output;
	}
}

/**
 *
 * KUAS SHORTCODE MAPS STATICS
 *
 * @since KuAs 1.0
 *
 */
function shortcode_kuas_gmaps($atts) {
	$merged = ( shortcode_atts( array( 'lat'=>'-7.013734', 'lng'=>'108.57007', 'width'=>'200', 'height'=>'200', 'zoom'=>'15' ), $atts ) );
	$output = '<img class="gmaps_shortcode" width="'.$merged['width'].'" height="'.$merged['height'].'" src="https://maps.googleapis.com/maps/api/staticmap?center='.$merged['lat'].','.$merged['lng'].'&zoom='.$merged['zoom'].'&size='.$merged['width'].'x'.$merged['height'].'&markers='.$merged['lat'].','.$merged['lng'].'&sensor=false" />';
	return $output;
}

/**
 *
 * KUAS AJAX CALL POST BY VENUE ID FOURSQUARE (define variable Javascript Maps searching Front Page)
 *
 * @since KuAs 1.0
 *
 */
function kuas_post_by_venue_ajax(){
	/*@header('Access-Control-Allow-Headers', 'Content-Type');
	@header('Cache-Control: no-cache, must-revalidate');
	@header('Expires: Mon, 1 Jul 2020 05:00:00 GMT');
	@header('Content-type: application/json');*/
	$post_type = $_REQUEST['type']? $_REQUEST['type'] : '';
	$cat = $_REQUEST['cat']? $_REQUEST['cat'] : '';
	$orderby = $_REQUEST['ordby']? $_REQUEST['ordby'] : '';
	$limit = $_REQUEST['limit']? $_REQUEST['limit'] : '';
	$order = $_REQUEST['order']? $_REQUEST['order'] : '';
	echo json_encode(kuas_post_by_venue_id($post_type,$cat,$orderby,$limit,$order));
	die();
}

/**
 *
 * KUAS DEFINE MAPS JAVASCRIPT POST BY VENUE ID
 *
 * @since KuAs 1.0
 *
 */
if(!function_exists('kuas_header_maps_script')) {
    function kuas_header_maps_script() {
    	if(is_home()){
    		wp_enqueue_script('maps', get_template_directory_uri() . '/js/jquery.ui.maps.js', array());
    		wp_enqueue_script('marker', get_template_directory_uri() . '/js/marker.with.label.js', array());
    		//wp_enqueue_script('marker', get_template_directory_uri() . '/js/gmap3.min.js', array());
    	}
    }
}

/**
 *
 * KUAS DEFINE JAVASCRIPT POST BY VENUE ID
 *
 * @since KuAs 1.0
 *
 */
if(!function_exists('kuas_header_content_script')) {
    function kuas_header_content_script() {
    	if(is_home()){
    		wp_enqueue_script('kuas_foursquare_list', get_template_directory_uri() . '/js/kuas_foursquare_list.min.js', array('jquery'), '1.0', true);
			//wp_localize_script('kuas_foursquare_list', 'kuas_foursquare', array(getCategoriesVenue()));
    	}
    }
}

/**
 *
 * KUAS DEFINE GLOBAL VARIABLE JAVASCRIPT (venue)
 *
 * @since KuAs 1.0
 *
 */
if(!function_exists('kuas_foursquare_varvar_script')){
	function kuas_foursquare_varvar_script(){
		$o = "<script type=\"text/javascript\">\r\n/* <![CDATA[ */\r\n";

		/* Define foursquare API client & secret */
		$fsq_api_key1 = array('P20APVP31JG3U0UJC4ZPWSSWW5GMP4WJ014TA5JAGWYXJBLD', 'OQIS4CBVG1TNQCRQMWOBHLOCZMCP5ZKPCF1AMXBS13EI5MEE');
		$fsq_api_key2 = array('44KWZR2C3HVDWSTGPEOEHFTNA2DHL32BKDCWUJC3HD1ZDHZF', 'LXSUSXZWIMARWLTVBZT3HVUYOA5RN0SNR1NUJF4AFRQRBWQ4');
		$foursquare_api_keys = array( $fsq_api_key1, $fsq_api_key2 );
		$random_foursquare_keys = randomArrayVar($foursquare_api_keys);
		$o .= 'var clid = "'.$random_foursquare_keys[0].'";';
		$o .= 'var clsc = "'.$random_foursquare_keys[1].'";';

		$maps_fsq_api_key1 = array('AXXY1AEIL1MVUIS2JKJTSJEMBLKX0IFE223EDVQPZFBR42QB', 'U5TQYKX3F1CNOVH1PT5QCKSM4WSL2H0NEKXUGRCHTOJTYHIB');
		$maps_fsq_api_key2 = array('A4NVEI2FKX3QR5CBC24S4TIKTY1WXWJ2ZSO5VPGLMKARPM0I', 'QODM2JM2Z4BVZ5DXVI0F2U050DSNEN2B2B5LHTDTOLUD5CFX');
		$maps_foursquare_api_keys = array( $maps_fsq_api_key1, $maps_fsq_api_key2 );
		$maps_random_foursquare_keys = randomArrayVar($maps_foursquare_api_keys);
		$o .= 'var maps_clid = "'.$maps_random_foursquare_keys[0].'";';
		$o .= 'var maps_clsc = "'.$maps_random_foursquare_keys[1].'";';
		
		if(is_home()){
		$o .= "var loader_maps = '<span class=\"maps_loading\"></span>';";
		$o .= "var parent_venue = 'div#map_venue';";
		$o .= "var searching_text = '".__('Mencari', 'kuas-beta')."';";
		$o .= "var search_venue_text = '".__('mencari tempat', 'kuas-beta')."';";
		$o .= "var search_text = '".__('Cari?', 'kuas-beta')."';";
		$o .= "var searching_nearby = '".__('Cari tempat sekitaran', 'kuas-beta')."';";
		$o .= "var select_category_text = '".__('Pilih Kategori', 'kuas-beta')."';";
		$o .= "var select_location_text = '".__('Pilih Lokasi', 'kuas-beta')."';";
		$o .= "var nearby_text = '".__('Sekitaran', 'kuas-beta')."';";
		$o .= "var zooming_text = '".__('Perbesar', 'kuas-beta')."';";
		$o .= "var zoomout_text = '".__('Perkecil', 'kuas-beta')."';";
		$o .= "var failed_load_text = '".__('gagal loading', 'kuas-beta')."';";
		$o .= "var recomended_text = '".__('Rekomendasi', 'kuas-beta')."';";
		$o .= "var people_here_text = '".__('orang pernah kesini', 'kuas-beta')."';";
		$o .= "var view_information_text = '".__('Lihat Informasinya', 'kuas-beta')."';";
		$o .= "var people_obj_text = '".__('orang', 'kuas-beta')."';";
		$o .= "var container_venue = 'div#containner_map';";
		$o .= "var KNG_longlat = '-7.013734,108.57007';";
		$o .= "var KNG_sw = '-7.020568,108.392216';";
		$o .= "var KNG_ne = '-6.968345,108.661281';";

		/* Define coordinate poin */
		$data_kordinat = array(	'var'=>array('KNG_linggarjatiLL'=>array('ll'=>'-6.88378,108.482001','nm'=>'Linggarjati'),
											 'KNG_sangkanhuripLL'=>array('ll'=>'-6.886235,108.505906', 'nm'=>'Pandawuan & Sangkanhurip'),
											 'KNG_jalaksanaLL'=>array('ll'=>'-6.90406,108.49655', 'nm'=>'Jalaksana & Maniskidul'),
											 'KNG_cirendangLL'=>array('ll'=>'-6.958258,108.488874', 'nm'=>'Cirendang & Cigintung'),
									 		 'KNG_cijohoLL'=>array('ll'=>'-6.968417,108.488324','nm'=>'Bunderan Cijoho'),
											 'KNG_pusatLL'=>array('ll'=>'-6.975758, 108.485695','nm'=>'Kuningan Kota'),
									 		 'KNG_juandaLL'=>array('ll'=>'-6.97399,108.490437','nm'=>'Juanda'),
											 'KNG_tamkotLL'=>array('ll'=>'-6.983127,108.476277','nm'=>'Taman Kota'),
									 		 'KNG_perumLL'=>array('ll'=>'-6.969815,108.502166','nm'=>'Perum & Ciporang'),
									 		 'KNG_ancaranLL'=>array('ll'=>'-6.971732,108.516594','nm'=>'Ancaran'),
									 		 'KNG_luragungLL'=>array('ll'=>'-7.018098,108.638152','nm'=>'Luragung Timur'),
											 'KNG_lengkongLL'=>array('ll'=>'-6.994543,108.516677','nm'=>'Lengkong'),
											 'KNG_ciawigebangLL'=>array('ll'=>'-6.970667,108.578307','nm'=>'Ciawi Gebang'),
											 'KNG_tarajuLL'=>array('ll'=>'-6.966429,108.529748','nm'=>'Taraju & Babakanreuma'),
									 		 'KNG_winduhajiLL'=>array('ll'=>'-6.980294,108.502883','nm'=>'Winduhaji & Sengkahan'),
									 		 'KNG_veteranLL'=>array('ll'=>'-6.979442,108.470954','nm'=>'Jalan Veteran'),
											 'KNG_sukamulyaLL'=>array('ll'=>'-6.97957,108.456019','nm'=>'Sukamulya & Cileuleuy'),
											 'KNG_pertanianLL'=>array('ll'=>'-6.983191,108.467628','nm'=>'Cigadung & Kamukten'),
											 'KNG_sudirmanLL'=>array('ll'=>'-6.973095,108.465119','nm'=>'Jl.Sudirman'),
									 		 'KNG_cigugurLL'=>array('ll'=>'-6.96858,108.459732','nm'=>'Cigugur'),
											 'KNG_gunungkelingLL'=>array('ll'=>'-6.955758,108.468876','nm'=>'G.Keling & Cipari'),
											 'KNG_palutunganLL'=>array('ll'=>'-6.947387,108.446084','nm'=>'Cisantana & Palutungan'),
											 'KNG_babatanLL'=>array('ll'=>'-6.992988,108.458873','nm'=>'Cipondok & Babatan'),
											 'KNG_kadugedeLL'=>array('ll'=>'-7.00319,108.447222','nm'=>'Kadugede & Nusaherang'),
											 'KNG_haurkuningLL'=>array('ll'=>'-7.013008,108.426858','nm'=>'Darma & Nusaherang'),
											 'KNG_cikijingLL'=>array('ll'=>'-7.01912,108.387237','nm'=>'Cipasung & Cikijing')
										)
						 );
		/* Looping coordinate poin */
		foreach($data_kordinat['var'] as $k=>$kordinat){ $o .= "var $k = [\"$kordinat[ll]\",\"$kordinat[nm]\"];"; }
		/* Get center coordinate looping with array random */
		$koor = array();
		$o .= "var center_LL = [";
		foreach($data_kordinat['var'] as $k=>$kordinat){$koor[] = $k; }
		$o .= join(', ',$koor);
		$o .= "];";

		/* Define standard zoom peta */
		$o .= "var ZoomPeta = 17;";
		$o .= "var KordinatAcak = getRandomArr(center_LL);";
		$o .= "var SudutKordinat = KordinatAcak[0];";
		$o .= "var NamaKordinat = KordinatAcak[1];";
		$o .= "jQuery(document).ready(function(){maps_loading('start',searching_nearby+' '+NamaKordinat);});";
		if(class_exists('Meta_Box_Foursquare_Venue')){
		$o .= "var foursquare_categories = {\"fsqcat\":[".getCategoriesVenue()."]};";
		} else {
		$o .= "var foursquare_categories = {\"fsqcat\":[\"0\"]};";
		}
		/* Define Kuas Pariwara for display Advertise banner into pin Bubble */
		if(function_exists('get_wp125_single_ad')):
		$kuas_ads_slot_5 = get_wp125_single_ad(7);
		$o .= "var kuas_pariwara_pin = {\"pariwara\":[".$kuas_ads_slot_5."]};";
		endif;

		}

		$o .= "\r\n/* ]]> */</script>";
		print $o;
	}
}

/**
 *
 * KUAS DEFINE GOOGLE MAPS API RANDOM
 *
 * @since KuAs 1.0
 *
 */
if(!function_exists('kuas_gmap_api_random')) {
	function kuas_gmap_api_random($print=1){
		$generic_map_api = 'ABQIAAAAN0JyO4tW04-1OKNW7bg9gxSPySWqAfkZkuZG2U8jr6yyIuV3XBSrEn410_O9d9QPJh3dbWV85Qad8w';
		$gmap_api_keys = array( $generic_map_api, 'AIzaSyDqTk9v4VHI8ztJffbjsRnOlhY29lJzrgA', 'AIzaSyDikdjDnehD5IKVez1cKYZ4DJpusliMM1E' );
		$random_gmap_api = randomArrayVar($gmap_api_keys);
		$o = $random_gmap_api;
		if($print==1){echo $o;}else{return $o;}	
	}
}

/**
 *
 * KUAS DEFINE MAPS CSS STYLING POST BY VENUE ID
 *
 * @since KuAs 1.0
 *
 */
if(!function_exists('kuas_header_content_style')) {
    function kuas_header_content_style() {
    	if(is_home()){
    		wp_enqueue_style('kuas_widget_header', get_template_directory_uri() . '/css/kuas_widget_header.min.css');
    	}
    }
}

/**
 *
 * KUAS DEFINE HTML TAG MAPS HEADER (front page)
 *
 * @since KuAs 1.0
 *
 */
if(!function_exists('kuas_header_content')){
	function kuas_header_content($twitter_parm='%40Kuningan_Asri',$print=1){
		$output = '<div id="containner_top">';
		$output .= kuas_render_twitter_trend(0);
		$output .= '<div id="top_content">';
		$output .= kuas_render_foursquare_list(0);		
		$output .= '</div></div>';
		if($print==1){echo $output;}else{return $output;}
	}
}

/**
 *
 * KUAS DEFINE RENDER TWITTER WIDGET
 *
 * @since KuAs 1.0
 *
 */
if(!function_exists('kuas_render_twitter_trend')){
	function kuas_render_twitter_trend($print=1){
		$render = '<div id="twitrend" class="side_left sidetop">';
		/*$render .= '<h3 class="sidetop_title bgColorBaseKuas_y2 headingColorBaseKuas">Twit Wargi</h3>';
		$render .= '<div id="twitrend_containner">';
		$render .= '<a class="twitter-timeline" height="260" data-chrome="noheader noborders transparent" data-show-replies="false" href="https://twitter.com/search?q=near%3AKuningan+within%3A20km+OR+geocode%3A-7.013734%2C108.57007%2C20km+exclude%3Aretweets+-source%3Atweet_button" data-widget-id="397839294426906625">Memuat Topik...</a></div>';*/
		$render .= '</div>';
		if($print==1){echo $render;}else{return $render;}
	}
}

/**
 *
 * KUAS DEFINE MAPS CONTAINNER HTML RENDERING
 *
 * @since KuAs 1.0
 *
 */
if(!function_exists('kuas_render_foursquare_list')){
	function kuas_render_foursquare_list($print=1){
		$render = '<div id="containner_map">';
		$render .= '<div id="map_venue"></div>';
		$render .= '<div id="more_venue" class="zoomIn normalTip" title="'.__('Klik untuk mencari tempat lain','kuas-beta')."\r\n".__('Gunakan dengan kata kunci','kuas-beta').'"><i>'.__('Perbesar','kuas-beta').'</i></div>';
		$render .= '</div>';
		if($print==1){echo $render;}else{return $render;}		
	}
}

?>