<?php
/**
 * Wrapper for functions defined in ABSPATH . 'wp-admin/includes/export.php' 
 *
 */	
	/**
	 * Wrap given string in XML CDATA tag.
	 *
	 * @since 2.1.0
	 *
	 * @param string $str String to wrap in XML CDATA tag.
	 * @return string
	 */
	function wxr_cdata( $str ) {
		if ( seems_utf8( $str ) == false )
			$str = utf8_encode( $str );

		// $str = ent2ncr(esc_html($str));
		$str = '<![CDATA[' . str_replace( ']]>', ']]]]><![CDATA[>', $str ) . ']]>';

		return $str;
	}

	/**
	 * Return the URL of the site
	 *
	 * @since 2.5.0
	 *
	 * @return string Site URL.
	 */
	function wxr_site_url() {
		// ms: the base url
		if ( is_multisite() )
			return network_home_url();
		// wp: the blog url
		else
			return get_bloginfo_rss( 'url' );
	}

	/**
	 * Output a cat_name XML tag from a given category object
	 *
	 * @since 2.1.0
	 *
	 * @param object $category Category Object
	 */
	function wxr_cat_name( $category ) {
		if ( empty( $category->name ) )
			return;

		echo '<wp:cat_name>' . wxr_cdata( $category->name ) . '</wp:cat_name>';
	}

	/**
	 * Output a category_description XML tag from a given category object
	 *
	 * @since 2.1.0
	 *
	 * @param object $category Category Object
	 */
	function wxr_category_description( $category ) {
		if ( empty( $category->description ) )
			return;

		echo '<wp:category_description>' . wxr_cdata( $category->description ) . '</wp:category_description>';
	}

	/**
	 * Output a tag_name XML tag from a given tag object
	 *
	 * @since 2.3.0
	 *
	 * @param object $tag Tag Object
	 */
	function wxr_tag_name( $tag ) {
		if ( empty( $tag->name ) )
			return;

		echo '<wp:tag_name>' . wxr_cdata( $tag->name ) . '</wp:tag_name>';
	}

	/**
	 * Output a tag_description XML tag from a given tag object
	 *
	 * @since 2.3.0
	 *
	 * @param object $tag Tag Object
	 */
	function wxr_tag_description( $tag ) {
		if ( empty( $tag->description ) )
			return;

		echo '<wp:tag_description>' . wxr_cdata( $tag->description ) . '</wp:tag_description>';
	}

	/**
	 * Output a term_name XML tag from a given term object
	 *
	 * @since 2.9.0
	 *
	 * @param object $term Term Object
	 */
	function wxr_term_name( $term ) {
		if ( empty( $term->name ) )
			return;

		echo '<wp:term_name>' . wxr_cdata( $term->name ) . '</wp:term_name>';
	}

	/**
	 * Output a term_description XML tag from a given term object
	 *
	 * @since 2.9.0
	 *
	 * @param object $term Term Object
	 */
	function wxr_term_description( $term ) {
		if ( empty( $term->description ) )
			return;

		echo '<wp:term_description>' . wxr_cdata( $term->description ) . '</wp:term_description>';
	}

		/**
	 * Output list of taxonomy terms, in XML tag format, associated with a post
	 *
	 * @since 2.3.0
	 */
	function wxr_post_taxonomy() {
		$post = get_post();

		$taxonomies = get_object_taxonomies( $post->post_type );
		if ( empty( $taxonomies ) )
			return;
		$terms = wp_get_object_terms( $post->ID, $taxonomies );

		foreach ( (array) $terms as $term ) {
			echo "\t\t<category domain=\"{$term->taxonomy}\" nicename=\"{$term->slug}\">" . wxr_cdata( $term->name ) . "</category>\n";
		}
	}