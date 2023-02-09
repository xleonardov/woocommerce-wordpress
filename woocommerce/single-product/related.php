<?php
/**
 * Related Products
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/related.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     3.9.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
global $product;

if( ! is_a( $product, 'WC_Product' ) ){
    $product = wc_get_product(get_the_id());
}
$args = array(
	'posts_per_page' => 5,
	'columns'        => 5,
	'orderby'        => 'rand',
	'order'          => 'desc',
);

$args['related_products'] = array_filter( array_map( 'wc_get_product', wc_get_related_products( $product->get_id(), $args['posts_per_page'], $product->get_upsell_ids() ) ), 'wc_products_array_filter_visible' );
$related_products =  wc_products_array_orderby( $args['related_products'], $args['orderby'], $args['order'] );

if ( $related_products ) : ?>

	<section class="related products py-8 mt-6">
		<h2 class="text-3xl tracking-wide uppercase mb-6"><?= _e( "Sugestões", "wlb_theme") ?></h2>
		<ul class="grid lg:grid-cols-5 gap-4">
			<?php foreach ( $related_products as $related_product ) : ?>
					<?php
					$post_object = get_post( $related_product->get_id() );
					setup_postdata( $GLOBALS['post'] =& $post_object ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited, Squiz.PHP.DisallowMultipleAssignments.Found
					wc_get_template_part( 'content', 'product' );
					?>
			<?php endforeach; ?>
		</ul>
	</section>
	<?php
endif;

wp_reset_postdata();
