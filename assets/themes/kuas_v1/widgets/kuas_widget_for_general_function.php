<?php
/*
 * Widget Name: Sub Categories Widget
 * Widget URI: http://kuas.com/
 * General Function Custom KuAs
   Include:
   1. Social Share Counts via API data provider
   2. Instagram user mini gallery
 *
 */

if(!function_exists('kuas_lazy_share')){
	function kuas_lazy_share($id_post,$print=1){
		if($id_post){
			$render = '<div class="lazy-share-widget" id="sharing-'.$id_post.'">';
			$render .= '<div class="platform facebook" id="fb-kuas-share-'.$id_post.'"></div>';
			$render .= '<div class="platform twitter" id="tweet-kuas-share-'.$id_post.'"></div>';
			$render .= '<div class="platform gplus"><span id="gplus-kuas-share-'.$id_post.'"></span></div>';
			$render .= '</div>';
		}
		else {
			$render = '[ID POST NOT DEFINE]';
		}
		if($print==1){echo $render;}else{return $render;}	
	}
}
/**
 *
 * KUAS RENDER SOCIAL SHARE COUNT (rendering data social counts per-post with HTML tag options)
 *
 * @since KuAs 1.0
 *
 */
if(!function_exists('kuas_render_social_share_count')){
	function kuas_render_social_share_count($url,$type='list',$id_post='',$process=0,$print=0){
		$url = $url? $url : site_utl();
		$render = '<!-- '.get_bloginfo('name').' Social Counts -->';
		switch($type){
			case 'list':
				$data_share_counts = kuas_social_share_count_process($url,'data',$id_post,$process);
				$twit_count = $data_share_counts['twit_count'];
				$fb_count = $data_share_counts['fb_count'];
				$gplus_count = $data_share_counts['gplus_count'];
				$render .= '<ul id="social-share-count" class="list-share" data-time="'.$data_share_counts['fetch_time'].'" data-info="'.$data_share_counts['fetch_info'].'">';
				$render .= '<li class="twit-count">'.thousandsCurrencyFormat($twit_count).'</li>';
				$render .= '<li class="fb-count">'.thousandsCurrencyFormat($fb_count).'</li>';
				$render .= '<li class="gplus-count">'.thousandsCurrencyFormat($gplus_count).'</li>';
				$render .= '</ul>';
			break;
			case 'summary':
				$data_share_counts = kuas_social_share_count_process($url,'total',$id_post,$process);
				$render .= '<div id="social-share-count" class="summary-share" data-time="'.$data_share_counts['fetch_time'].'" data-info="'.$data_share_counts['fetch_info'].'">';
				$render .= '<span class="data-share-count fixedTip"  title="'.sprintf('&quot;%1$s&quot; '.__('sudah dibagikan sebanyak %2$s kali oleh pengunjung','kuas-beta'),get_the_title($id_post),thousandsCurrencyFormat($data_share_counts['count_info'])).'" >'.thousandsCurrencyFormat($data_share_counts['count_info']).'</span>';
				$render .= '<span class="data-share-text">'.__('dibagikan ke media sosial','kuas-beta').'</span>';
				$render .= '</div>';
			break;
			case 'count':
				$data_share_counts = kuas_social_share_count_process($url,'total',$id_post,$process);
				$render .= '<div id="social-share-count" class="count-share" data-time="'.$data_share_counts['fetch_time'].'" data-info="'.$data_share_counts['fetch_info'].'">';
				$render .= '<span class="data-share-count">'.thousandsCurrencyFormat($data_share_counts['count_info']).'</span>';
				$render .= '</div>';
			break;
			case 'init':
				$data_share_counts = kuas_social_share_count_process($url,'total',$id_post,$process);
				$render .= '<span data-info="'.$data_share_counts['fetch_info'].'" data-time="'.$data_share_counts['fetch_time'].'">'.thousandsCurrencyFormat($data_share_counts['count_info']).'</span>';
			break;
			case 'total':
				$data_share_counts = kuas_social_share_count_process($url,'total',$id_post,$process);
				$render .= $data_share_counts['count_info'];
			break;
			default: $render .= null;
		}
		if($print!=0){echo $render;}else{return $render;}
	}
}

/**
 *
 * KUAS SOCIAL SHARE COUNT PROSES (bridge before or passing render HTML to get data counts)
 *
 * @since KuAs 1.0
 *
 */
if(!function_exists('kuas_social_share_count_process')){
	function kuas_social_share_count_process($url,$type=null,$id_post='',$process=0){
		$url = $url? $url : site_utl();

		if($process==1){ $result = kuas_social_share_count_add_meta($url,$id_post); } 
		else { $result = kuas_social_share_count_get_meta($url,$id_post); }

		switch($type){
			case 'data':
				$merge_all_counts = array('fetch_info'=>$result['fetch_info'],'fetch_time'=>$result['fetch_time'],'twit_count'=>$result['twit_count'],'fb_count'=>$result['fb_count'],'gplus_count'=>$result['gplus_count']);
			break;
			case 'total':
				$merge_all_counts = array('fetch_info'=>$result['fetch_info'],'fetch_time'=>$result['fetch_time'],'count_info'=>($result['twit_count'])+($result['fb_count'])+($result['gplus_count']));
			break;
			default: $merge_all_counts = null;
		}
		
		return $merge_all_counts;
	}
}

/**
 *
 * KUAS SOCIAL SHARE COUNT ADD META (add new meta post kuas_social_count with data API provider result)
 *
 * @since KuAs 1.0
 *
 */
if(!function_exists('kuas_social_share_count_add_meta')){
	function kuas_social_share_count_add_meta($url,$id_post){
		$url = $url? $url : site_utl();
		if($id_post){
			$social_count_meta_post = get_post_meta( $id_post, 'kuas_social_count', 'single');
			$social_count_meta_post_arr = explode(':', $social_count_meta_post);
			/* Define date from index array [3], if is less than time() | EPOCH now time */
			if(intval($social_count_meta_post_arr[3]) > time()){
				return kuas_social_share_count_get_meta($url,$id_post);	
			}
			else {
				$sharedcount_json = file_get_contents("http://api.sharedcount.com/?url=" . rawurlencode($url));
				$sharedcount_response = json_decode($sharedcount_json, true);
				$result_count_twitter =  $sharedcount_response? $sharedcount_response["Twitter"] : kuas_twitter_share_count($url);
				$result_count_facebook =  $sharedcount_response? $sharedcount_response["Facebook"]["like_count"] + $sharedcount_response["Facebook"]["share_count"] : kuas_facebook_share_count($url);
				$result_count_gplus =  $sharedcount_response? $sharedcount_response["GooglePlusOne"] : kuas_gplus_share_count($url);
				
				$total_count = intval($result_count_twitter) + intval($result_count_facebook) + intval($result_count_gplus);
				if($total_count > 0){
				// 3 hari batas reload nilai hitungan
				$nextDay = time() + (3 * 24 * 60 * 60);
				}
				else {
				// 1 hari batas reload nilai hitungan
				$nextDay = time() + (1 * 24 * 60 * 60);
				}
				$result_social_merging_to_meta = $result_count_twitter.':'.$result_count_facebook.':'.$result_count_gplus.':'.$nextDay;
				add_post_meta( $id_post, 'kuas_social_count', $result_social_merging_to_meta, true ) || update_post_meta( $id_post, 'kuas_social_count', $result_social_merging_to_meta );				
				$fetch_info = 'fetch-api-'.$id_post;
				return array('fetch_info'=>$fetch_info,'fetch_time'=>$nextDay,'twit_count'=>$result_count_twitter,'fb_count'=>$result_count_facebook,'gplus_count'=>$result_count_gplus);
			}
		}
		else{
			return array('fetch_info'=>'error-meta','twit_count'=>0,'fb_count'=>0,'gplus_count'=>0);
		}
	}
}

/**
 *
 * KUAS SOCIAL SHARE COUNT GET META (get data counts from meta_post had adding)
 *
 * @since KuAs 1.0
 *
 */
if(!function_exists('kuas_social_share_count_get_meta')){
	function kuas_social_share_count_get_meta($url,$id_post){
		$url = $url? $url : site_utl();
		if($id_post){
			$social_count_meta_post = get_post_meta( $id_post, 'kuas_social_count', 'single');
			if($social_count_meta_post){
				$social_count_meta_post_arr = explode(':', $social_count_meta_post);
				$result_count_twitter = $social_count_meta_post_arr[0];
				$result_count_facebook =  $social_count_meta_post_arr[1];
				$result_count_gplus =  $social_count_meta_post_arr[2];
				$fetch_info = 'fetch-meta-'.$id_post;
				return array('fetch_info'=>$fetch_info,'fetch_time'=>$social_count_meta_post_arr[3],'twit_count'=>$result_count_twitter,'fb_count'=>$result_count_facebook,'gplus_count'=>$result_count_gplus);
			}
			else {
				return array('fetch_info'=>'error-meta','fetch_time'=>time(),'twit_count'=>0,'fb_count'=>0,'gplus_count'=>0);
			}
		}
		else{
			return array('fetch_info'=>'error-meta','fetch_time'=>time(),'twit_count'=>0,'fb_count'=>0,'gplus_count'=>0);
		}
	}
}

/**
 *
 * KUAS TWITTER SHARE COUNT (get twitter counts by URL detection using data API request)
 *
 * @since KuAs 1.0
 *
 */
if(!function_exists('kuas_twitter_share_count')){
	function kuas_twitter_share_count($url){
	$url= urlencode($url);
	$data = file_get_contents("http://urls.api.twitter.com/1/urls/count.json?url={$url}");
	$json = json_decode($data, true);
	$count = $json["count"];
	return $count ? $count : 0;
	}
}

/**
 *
 * KUAS FACEBOOK SHARE COUNT (get facebook counts by URL detection using data API request)
 *
 * @since KuAs 1.0
 *
 */
if(!function_exists('kuas_facebook_share_count')){
	function kuas_facebook_share_count($url){
	$link = urlencode($url);
	$data = file_get_contents("http://graph.facebook.com/?id=$url");
	$json = json_decode($data, true);
	$count = $json["shares"];
	return $count ? $count : 0;
	}
}

/**
 *
 * KUAS GPLUS SHARE COUNT (get gplus counts by URL detection using data API request)
 *
 * @since KuAs 1.0
 *
 */
if(!function_exists('kuas_gplus_share_count')){
	function kuas_gplus_share_count($url){
	$ch = curl_init();  
	curl_setopt($ch, CURLOPT_URL, "https://clients6.google.com/rpc?key=AIzaSyCKSbrvQasunBoV16zDH9R33D88CeLr9gQ");
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, '[{"method":"pos.plusones.get","id":"p","params":{"nolog":true,"id":"'.$url.'","source":"widget","userId":"@viewer","groupId":"@self"},"jsonrpc":"2.0","key":"p","apiVersion":"v1"}]');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
	$data = curl_exec ($ch);
	curl_close ($ch);
	$json = json_decode($data, true);
 	$count = $json[0]['result']['metadata']['globalCounts']['count'];
 	return $count ? $count : 0;
	}
}

if(!function_exists('kuas_custom_parse_curl')){
	function kuas_custom_parse_curl($encUrl){
		$options = array(
		  CURLOPT_RETURNTRANSFER => true, // return web page
		  CURLOPT_HEADER => false, // don't return headers
		  CURLOPT_FOLLOWLOCATION => true, // follow redirects
		  CURLOPT_ENCODING => "", // handle all encodings
		  CURLOPT_USERAGENT => 'sharrre', // who am i
		  CURLOPT_AUTOREFERER => true, // set referer on redirect
		  CURLOPT_CONNECTTIMEOUT => 5, // timeout on connect
		  CURLOPT_TIMEOUT => 10, // timeout on response
		  CURLOPT_MAXREDIRS => 3, // stop after 10 redirects
		  CURLOPT_SSL_VERIFYHOST => 0,
		  CURLOPT_SSL_VERIFYPEER => false,
		);
		$ch = curl_init();
		$options[CURLOPT_URL] = $encUrl;  
		curl_setopt_array($ch, $options);

		$content = curl_exec($ch);
		$err = curl_errno($ch);
		$errmsg = curl_error($ch);

		curl_close($ch);
		if ($errmsg != '' || $err != '') {
		  /*print_r($errmsg);
		  print_r($errmsg);*/
		}
		return $content;
	}
}

if(!function_exists('kuas_instagram_mini')){
	function kuas_instagram_mini($username,$element=array('parent'=>'ul','child'=>'li'),$achor=1,$limit=0,$print=1){
		if(is_string($username) && $username!=''){
			$output = '';
			$file_json = 'instagram-'.$username.'.json';
			$folder_storing = 'media/json/';
			$cachetime = 24;
			if( file_exists($folder_storing.$file_json) && ((time() - filemtime($folder_storing.$file_json)) > 3600 * $cachetime) ){
				unlink($folder_storing.$file_json);
			}

			if(!file_exists($folder_storing.$file_json)){
				$get_html = file_get_contents('http://instagram.com/'.$username.'/');
				$get_html = strstr($get_html, '["lib');
				$get_html = strstr($get_html, '</script>', true);
				$get_html = substr($get_html,0,-6);
				file_put_contents($folder_storing.'instagram-'.$username.'.json',$get_html);				
			}

			$json = file_get_contents($folder_storing.'instagram-'.$username.'.json'); 
			$instagram_data = json_decode($json);
			$img_data = $instagram_data[2][0]->props->userMedia;	

			$output .= '<'.$element['parent'].' id="insta-'.$username.'">';
			$loop = 0;
			foreach ($img_data as $k => $instagram_images) {
				if($achor==1){
					$output .= '<'.$element['child'].' id="insta-'.$k.'">';
				}
				$output .= '<a href="'.$instagram_images->link.'" target="_blank"><img class="instamini_img" src="'.$instagram_images->images->thumbnail->url.'" /></a>';
				if($achor==1){
					$output .= '</'.$element['child'].'>';
				}
				$loop++;
				if(is_numeric($limit) && $limit!= 0 && $loop == $limit) break;
			}
			$output .= '</'.$element['parent'].'>';
		}
		else{
			$output = 'N/A';
		}
		if($print==0){ return $output; } else { echo $output; }
	}
}

?>