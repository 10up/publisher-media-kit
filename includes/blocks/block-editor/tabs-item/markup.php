<?php
/**
 * Markup for the Tabs Item block
 *
 * @package PublisherMediaKit\Blocks
 */

$class_name = ( ! empty( $attributes['className'] ) ) ? $attributes['className'] : '';

if ( empty( $attributes['header'] ) ) {
	return;
}
?>
<div class="tab-content <?php echo esc_attr( $class_name ); ?>" id="tab-item-<?php echo esc_attr( sanitize_title_with_dashes( $attributes['header'] ) ); ?>" role="tabpanel">
	<?php echo wp_kses_post( $content ); ?>
</div>
