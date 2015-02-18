<?php
/**
 * New Post Form front end user
 * name: FrontPress
 *
 * @file      user.post.php
 * @package   kuas-beta
 * @author    Kuningan_Asri.
 * @link 	  http://kuas.com
 */

// creating Ajax call for WordPress
//add_action( 'wp_ajax_nopriv_kuas_frontpress_send', 'kuas_frontpress_send' );
add_action( 'wp_ajax_kuas_frontpress_send', 'kuas_frontpress_send' );
if(wp_is_mobile()==false){
	add_action( 'wp_enqueue_scripts', 'kuas_frontpress_tinymce_scripts' );
}

if(!function_exists('kuas_frontpress_tinymce_scripts')){
		function kuas_frontpress_tinymce_scripts(){
		if(is_user_logged_in() && !is_admin()){
		    wp_enqueue_script('tinymce', get_template_directory_uri() . '/tinymce/jquery.tinymce.min.js', array('jquery') );
		    wp_enqueue_script('tinymce-init', get_template_directory_uri() . '/tinymce/init.js', array('jquery') );
		}
	}
}

function kuas_frontpress_send(){
	
	check_ajax_referer('kuas_frontpress_send','kuas_frontpress_nonce');

	$current_author_log = wp_get_current_user();
	$author = $current_author_log->ID;
	$title = $_POST['FrontPressTitle'];
	$content =	$_POST['FrontPressContent'];
	$category = $_POST['FrontPressCategory'];
	$venueID = $_POST['FrontPressVenue'];

	if(empty($title) OR empty($content)){
		$code_response = 0;
		$message_response = __('Isian wajib masih kosong','kuas-beta');	
	}
	elseif(str_word_count($title) < 2){
		$code_response = 2;
		$message_response = __('Judul minimal 2 kata','kuas-beta').'<br /><small>('.__('Tulisan belum dikirim','kuas-beta').')</small>';	
	}
	elseif(str_word_count($title) > 10){
		$code_response = 2;
		$message_response = __('Judul maksimal 10 kata','kuas-beta').'<br /><small>('.__('Tulisan belum dikirim','kuas-beta').')</small>';	
	}
	elseif(str_word_count($content) < 100){
		$code_response = 2;
		$message_response = __('Isi tulisan minimal 100 kata','kuas-beta').'<br /><small>('.__('Tulisan belum dikirim','kuas-beta').')</small>';	
	}
	elseif(empty($category)){
		$code_response = 2;
		$message_response = __('Pilih kategori tulisan minimal 1','kuas-beta').'<br /><small>('.__('Tulisan belum dikirim','kuas-beta').')</small>';	
	}
	elseif(empty($_FILES['FrontPostImage'])){
		$code_response = 2;
		$message_response = __('Pilih gambar untuk cover tulisan','kuas-beta').'<br /><small>('.__('Tulisan belum dikirim','kuas-beta').')</small>';	
	}
	else {
		// Setup extension for Featured Image Posts (attachment post)
		$ext = preg_match('/\.([^.]+)$/', $_FILES['FrontPostImage']['name'], $matches) ? strtolower($matches[1]) : false;
		$image_exts = array( 'jpg', 'jpeg', 'jpe', 'gif', 'png' );
		// If in_array match is valid image and process will continue
		if(in_array($ext, $image_exts)){
			$post_id = wp_insert_post(array(
			'post_type'		=> 'post',
			'post_title' 	=> esc_attr(strip_tags($title)),
			'post_content'	=> $content,
			'post_status'	=> 'pending',
			'post_category'	=> $category,
			'post_author'	=> $author
			));
			
			if($post_id != 0){
				if(!empty($venueID)){
					add_post_meta( $post_id, 'Venue_ID', esc_attr(strip_tags($venueID)), true ) || update_post_meta($post_id, 'Venue_ID', esc_attr(strip_tags($venueID)));
				}
				
				if(!empty($_FILES['FrontPostImage'])){
					kuas_frontpost_image_handler($post_id,$author,$_FILES['FrontPostImage']);
				}

				$code_response = 1;
				$message_response = __('Tulisan terkirim','kuas-beta');

			}
			else {
				$code_response = 0;
				$message_response = __('Gagal mengirim tulisan','kuas-beta');
			}
		}
		// If in_array not match return invalid
		else {
			$code_response = 0;
			$message_response = __('File cover tulisan bukan gambar','kuas-beta').'<br /><small>('.__('Tulisan belum dikirim','kuas-beta').')</small>';			
		}
	}

	$results = '{"code":"'.$code_response.'","msg":"'.$message_response.'"}';

	// Return the String
	die($results);	
}

function kuas_frontpost_image_handler($post_id,$author,$image_files){
	// require two files that are included in the wp-admin but not on the front end
	// These give you access to some special functions below.
	if(!function_exists('wp_handle_upload') && !function_exists('wp_generate_attachment_metadata')){
		require_once (ABSPATH . '/wp-admin/includes/media.php');
		require_once (ABSPATH . '/wp-admin/includes/file.php');
		require_once (ABSPATH . '/wp-admin/includes/image.php');
	}

	// required for wp_handle_upload() to upload the file
	$upload_overrides = array( 'test_form' => FALSE );
	
	$fix_folder = date('Y/m');

	$uploads = site_url().'/'.get_option('upload_path').'/'.$fix_folder.'/';
 
	// create an array of the $_FILES for each file
	$file_array = array(
		'name' 		=> $image_files['name'],
		'type'		=> $image_files['type'],
		'tmp_name'	=> $image_files['tmp_name'],
		'error'		=> $image_files['error'],
		'size'		=> $image_files['size'],
	);
 
	// check to see if the file name is not empty
	if ( !empty( $file_array['name'] ) ) {
 
	 	// upload the file to the server
	    $uploaded_file = wp_handle_upload( $file_array, $upload_overrides, $fix_folder );
 
		// checks the file type and stores in in a variable
	    $wp_filetype = wp_check_filetype( basename( $uploaded_file['file'] ) );	
 
	    // set up the array of arguments for "wp_insert_attachment();"
	    $attachment = array(
	    	'post_mime_type' => $wp_filetype['type'],
	    	'post_title' => preg_replace('/\.[^.]+$/', '', basename( $uploaded_file['file'] ) ),
	    	'post_content' => preg_replace('/\.[^.]+$/', '', basename( $uploaded_file['file'] ) ),
	    	'post_author' => $author,
	    	'post_status' => 'inherit',
	    	'post_type' => 'attachment',
	    	'post_parent' => $post_id,
	    	'guid' => $uploads . $file_array['name']
	    );
 
	    // insert the attachment post type and get the ID
	    $attachment_id = wp_insert_attachment( $attachment, $uploaded_file['file'] );
 
		// generate the attachment metadata
		$attach_data = wp_generate_attachment_metadata( $attachment_id, $uploaded_file['file'] );
 
		// update the attachment metadata
		wp_update_attachment_metadata( $attachment_id,  $attach_data );
 
        // you could set up a separate form to give a specific user the ability to change the post thumbnail
        set_post_thumbnail( $post_id, $attachment_id );
	}
}

function kuas_UserFrontPress_Form($print=1,$exclude_cat='',$include_cat='',$hierarchical=1){
	$output = '<div id="UserFrontPress">';

		$output .= '<form action="kuas_frontpress_send" id="FrontPressForm" method="POST" enctype="multipart/form-data">';

		$output .= '<span class="message_frontpost"></span>';
		$output .= '<div class="clear"></div>';
		
			//Show Title Content Post
			$output .= '<fieldset>';
				$output .= '<label for="FrontPressTitle" class="display-block"><font color="red">*</font> '.__('Judul Tulisan','kuas-beta').' (min 2 kata):</label>';
				$output .= '<input type="text"size="65" name="FrontPressTitle" id="FrontPressTitle" class="required" placeholder="Tentukan judul tulisan disini" />';
			$output .= '</fieldset>';

			//Display Categories Select
			$output .= '<fieldset id="frontPostCategoryFieldset">';
			$output .= '<label for="FrontPressCategory" class="display-block"><font color="red">*</font> '.__('Pilih Kategori Tulisan','kuas-beta').'</label>';
			$categories = get_categories(array('hide_empty'=> 0, 'exclude'=>$exclude_cat, 'include'=>$include_cat, 'hierarchical '=>$hierarchical));
			foreach($categories as $category) { 
			$output .= '<input type="checkbox" name="FrontPressCategory[]" id="FrontPressCategory" class="FrontPressCategory" value='.$category->term_id.' />';  
			$output .= $category->cat_name;
			$output .= '<br />';
			}
			$output .= '</fieldset>';

			//Show Image Uploads
			$output .= '<fieldset id="frontPostImageFieldset">';
				$output .= '<label for="FrontPostImage" class="display-block"><font color="red">*</font> '.__('Pilih Gambar','kuas-beta').' - '.get_option('upload_path').'</label>';
				$output .= '<input type="file" id="FrontPostImage" name="FrontPostImage" accept="image/*">';
				$output .= '<span id="KuasImagePreview" src="" alt="Images"></span>';
			$output .= '</fieldset>';

			//Show Textarea Content Post
			$output .= '<fieldset>';				
				$output .= '<label for="FrontPressContent" class="display-block"><font color="red">*</font> '.__('Isi Tulisan','kuas-beta').' (min 100 kata):</label>';
				$output .= '<textarea name="FrontPressContent" id="FrontPressContent" rows="8" cols="50" placeholder="Sertakan isi tulisan berupa ulasan atau berita"></textarea>';
			$output .= '</fieldset>';

			//Show Custom Venue_ID
			$output .= '<fieldset class="normalTip" title="'.__('Gunakan pencarian venue foursquare kemudian copy paste','kuas-beta').'">';
				$output .= '<label for="FrontPressVenue">'.__('Venue ID (option): ', 'kuas-beta').'</label>';
				$output .= '<input size="40" type="text" name="FrontPressVenue" id="FrontPressVenue" placeholder="foursquare.com/v/nama-tempat/VENUE ID" />';
			$output .= '</fieldset>';

			//Show Submit
			$output .= '<fieldset>';
				$output .= wp_nonce_field('kuas_frontpress_send', 'kuas_frontpress_nonce');
				$output .= '<input type="hidden" name="action" id="action" value="kuas_frontpress_send">';
				$output .= '<input type="submit" name="SubmitFrontPress" id="SubmitFrontPress" value="'.__('Oke, Kirim Tulisan Sekarang!','kuas-beta').'" />';
		$output .= '</fieldset>';
		$output .= '<i style="margin:auto;padding:10px;clear: both;display: block;">'.__('Bertanda bintang merah','kuas-beta').'<font color="red">*</font> <strong>'.__('WAJIB','kuas-beta').'</strong> '.__('di isi','kuas-beta').'!</i>';
		$output .= '</form>';
	$output .= '</div>';
	if($print==1){echo $output;}
	else{return $output;}
}

function kuas_valid_frontpress_display($user_id='',$limit=0){
	if(empty($user_id) OR $user_id == '' OR !$user_id){
		$current_author_log = wp_get_current_user();
		$user_id = $current_author_log->ID;
	}
	if(empty($limit) OR $limit=='' OR $limit==0){
		$limit = 5;
	}
	else{
		$limit = $limit;
	}
	$authors_posts = get_posts( array( 'author' => $user_id, 'post_status' => 'pending') );	
	if(count($authors_posts) >= $limit){
		$valid = 0;
	} else {
		$valid = 1;
	}
	return $valid;
}
?>