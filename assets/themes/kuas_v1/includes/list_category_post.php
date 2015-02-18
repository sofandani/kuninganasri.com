<?php
//USAGE:
//wp_list_categories( array( 'title_li' => '', 'echo' => false , 'walker' => new KuasWalkerCategory() ) ) .

class KuasWalkerCategory extends Walker_Category {

  function end_el(&$output, $page, $depth, $args) {
      global $wpdb;
      $output .= "called with: " . $page->term_id;
      $posts = $wpdb->get_results("select object_id as ID from wp_term_relationships r " . "join wp_posts p on r.object_id = p.ID where p.post_status = 'publish' and r.term_taxonomy_id = " . "(SELECT term_taxonomy_id FROM wp_term_taxonomy WHERE taxonomy = 'category' and term_id = " . $page->term_id . ")");
      if($posts) :
        $output .= '<ul class="posts">'; 
    	foreach($posts as $post) { 
    		$output .= '<li>'; $output .= '<a title="link to ' . get_the_title($post->ID) . '" href="' . get_permalink($post->ID) . '">' . get_the_title($post->ID) . '</a>'; $output .= '</li>';
    	}
    	$output .= '</ul>';
      endif;
      parent::end_el(&$output, $page, $depth, $args);
    }
}
?>