<?php
/**
 * Markup for the Tabs Item block
 *
 * @package TenupBlockLibrary\Blocks
 */

?>
<div class="tab-content" id="tab-item-<?php echo esc_attr( sanitize_title_with_dashes( $attributes['header'] ) ); ?>" role="tabpanel">
	<?php echo wp_kses_post( $content ); ?>
</div>
