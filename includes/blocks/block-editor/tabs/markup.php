<?php
/**
 * Markup for the Tabs block wrapper
 *
 * @package PublisherMediaKit\Blocks
 */

$class_name = ( ! empty( $attributes['className'] ) ) ? $attributes['className'] : '';
?>
<div class="tabs horizontal <?php echo esc_attr( $class_name ); ?>">
	<!-- Tabs Placeholder -->
	<div class="tab-group">
		<?php echo wp_kses_post( $content ); ?>
	</div> <!-- /.tab-group -->
</div> <!-- /.tabs -->
