<?php
/**
 * Theme override of the Tabs Item template, used by TabsRenderTest.
 *
 * Templates loaded through locate_template() receive their data in `$args`.
 *
 * @package PublisherMediaKit
 */

?>
<section class="theme-tab" data-header="<?php echo esc_attr( $args['attributes']['header'] ); ?>"><?php echo wp_kses_post( $args['content'] ); ?></section>
