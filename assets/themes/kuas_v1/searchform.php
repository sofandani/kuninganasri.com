<?php
/**
 * The template for displaying search forms.
 *
 * @file      searchform.php
 * @package   kuas-beta
 * @author    Sami Ch.
 * @link 	  http://gazpo.com
 */
?>
 
<form method="get" id="searchform" action="<?php echo home_url( '/' ); ?>">
	<div>
		<input class="searchfield" type="text" data-watermark="<?php _e('Cari sesuatu','kuas-beta') ?>?" name="s" id="s" />
		<input type="submit" value=" " class="searchsubmit">
	</div>
</form>
