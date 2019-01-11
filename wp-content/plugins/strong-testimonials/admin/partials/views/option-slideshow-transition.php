<?php /* translators: In the view editor. */ ?>
<th>
	<?php _e( 'Transition', 'strong-testimonials' ); ?>
</th>
<td>
	<div class="row">

		<div class="inline inline-middle">
			<label for="view-pause">
				<?php _ex( 'Show slides for', 'slideshow setting', 'strong-testimonials' ); ?>
			</label>
			<input type="number" id="view-pause" class="input-incremental"
			       name="view[data][slideshow_settings][pause]" min=".1" step=".1"
			       value="<?php echo $view['slideshow_settings']['pause']; ?>" size="3"/>
			<?php _ex( 'seconds', 'time setting', 'strong-testimonials' ); ?>
		</div>

		<div class="inline inline-middle then then_max_slides then_1 then_not_2 then_not_3 fast" style="display: none;">
			<label for="view-effect">
				<?php _e( 'then', 'strong-testimonials' ); ?>
			</label>
			<select id="view-effect" name="view[data][slideshow_settings][effect]" class="if selectnot">
				<?php foreach ( $view_options['slideshow_effect'] as $key => $label ) : ?>
					<option value="<?php echo $key; ?>"
						<?php selected( $view['slideshow_settings']['effect'], $key ); ?>
						<?php echo 'none' == $key ? 'class="trip"' : ''; ?>><?php echo $label; ?></option>
				<?php endforeach; ?>
			</select>
		</div>

        <div class="inline inline-middle then then_max_slides then_not_1 then_2 then_3 fast" style="display: none;">
            <?php _e( 'then', 'strong-testimonials' ); ?> <?php _ex( 'scroll horizontally', 'slideshow transition option', 'strong-testimonials' ); ?>
        </div>

		<div class="inline inline-middle then then_effect then_none">
			<label for="view-speed">
				<?php _e( 'for', 'strong-testimonials' ); ?>
			</label>
			<input type="number" id="view-speed" class="input-incremental"
			       name="view[data][slideshow_settings][speed]" min=".1" step=".1"
			       value="<?php echo $view['slideshow_settings']['speed']; ?>" size="3"/>
			<?php _ex( 'seconds', 'time setting', 'strong-testimonials' ); ?>
		</div>

	</div>
</td>
