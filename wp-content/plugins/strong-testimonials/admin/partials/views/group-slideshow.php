<?php
$then_classes = array(
	'then',
	'then_not_display',
	'then_not_form',
	'then_slideshow',
	'then_not_single_template',
	apply_filters( 'wpmtst_view_section', '', 'slideshow' ),
);
?>
<div class="<?php echo esc_attr( join( array_filter( $then_classes ), ' ' ) ); ?>" style="display: none;">
	<h3>
		<?php /* translators: In the view editor. */ ?>
		<?php _e( 'Slideshow', 'strong-testimonials' ); ?>
	</h3>
	<table class="form-table multiple group-select">
		<tr>
			<?php include( 'option-slideshow-num.php' ); ?>
		</tr>
		<tr>
			<?php include( 'option-slideshow-transition.php' ); ?>
		</tr>
		<tr>
			<?php include( 'option-slideshow-behavior.php' ); ?>
		</tr>
		<tr>
			<?php include( 'option-slideshow-navigation.php' ); ?>
		</tr>
	</table>
</div>
