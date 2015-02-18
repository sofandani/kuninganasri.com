<?php
/**
 * The template for displaying Comments.
 *
 * The area of the page that contains both current comments
 * and the comment form. The actual display of comments is
 * handled by a callback to kuas_beta_comments() which is
 * located in the functions.php file.
 *
 * @file      comments.php
 * @package   kuas-beta
 * @author    Sami Ch.
 * @link 	  http://gazpo.com
 */

	do_action('kuas_before_comments') ?>

	<div id="comments">

	<?php if ( post_password_required() ) : ?>
		<p class="nopassword"><?php _e( 'Tulisan ini khusus untuk beberapa pengguna, silahkan masukan password untuk membukanya', 'kuas-beta' ); ?></p>
	</div><!-- #comments -->
	<?php
			/* Stop the rest of comments.php from being processed,
			 * but don't kill the script entirely -- we still have
			 * to fully load the template.
			 */
			return;
		endif;
	?>

	<?php // You can start editing here -- including this comment! ?>

	<?php if ( have_comments() ) : ?>
		<h2 id="comments-title">
			<?php
				printf( _n( 'Satu komentar untuk tulisan:<br /><span class="quote_styling">%2$s</span>', '%1$s komentar untuk tulisan:<br /><span class="quote_styling">%2$s</span>', get_comments_number(), 'kuas-beta' ),
					number_format_i18n( get_comments_number() ), '<span>' . get_the_title() . '</span>' );
			?>
		</h2>

		<ol class="commentlist">
			<?php
				/* Loop through and list the comments. Tell wp_list_comments()
				 * to use kuas_beta_comments() to format the comments.
				 * If you want to overload this in a child theme then you can
				 * define kuas_beta_comments() and that will be used instead.
				 * See kuas_beta_comments() in functions.php for more.
				 */
				wp_list_comments( array( 'callback' => 'kuas_beta_comments' ) );
			?>
		</ol>

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // are there comments to navigate through ?>
		<div id="comment-nav-below">
			<h3 class="assistive-text"><?php _e( 'Lihat komentar lainnya', 'kuas-beta' ); ?></h3>
			<div class="nav-previous nav-child"><?php previous_comments_link( __( '&laquo; Terlawas', 'kuas-beta' ) ); ?></div>
			<div class="nav-next nav-child"><?php next_comments_link( __( 'Terbaru &raquo;', 'kuas-beta' ) ); ?></div>
		</div>
		<?php endif; // check for comment navigation ?>

	<?php
		/* If there are no comments and comments are closed, let's leave a little note, shall we?
		 * But we don't want the note on pages or post types that do not support comments.
		 */
		elseif ( ! comments_open() && ! is_page() && post_type_supports( get_post_type(), 'comments' ) ) :
	?>
		
	<?php endif; ?> <!-- /have_comments -->

	<?php if (comments_open()) : ?>
	
    <?php
    $fields = array(
        'author' => '<p class="comment-form-author">' . '<input id="author" name="author" type="text" value="' . esc_attr($commenter['comment_author']) . '" size="30" />'.'<label for="author">' . __('Name','kuas-beta') . '</label> ' . ( $req ? '<span class="required">*</span>' : '' ) . '</p>',
		
        'email' => '<p class="comment-form-email">'.'<input id="email" name="email" type="text" value="' . esc_attr($commenter['comment_author_email']) . '" size="30" />'.'<label for="email">' . __('E-mail','kuas-beta') . '</label> ' . ( $req ? '<span class="required">*</span>' : '' ) .'</p>',
		
        'url' => '<p class="comment-form-url">'.'<input id="url" name="url" type="text" value="' . esc_attr($commenter['comment_author_url']) . '" size="30" />'.'<label for="url">' . __('Website','kuas-beta') . '</label>' . '</p>',
    );

    $defaults = array('fields' => apply_filters('comment_form_default_fields', $fields));

    comment_form($defaults);
    ?>

    <?php endif; ?> <!-- /comments_open -->
	
</div><!-- /comments -->
