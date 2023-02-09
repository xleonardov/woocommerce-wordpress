<?php
/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.6.0
 */

defined('ABSPATH') || exit;

global $product;
$icons = new Icons();
$id = $product->get_id();

if ($product->get_type() == 'variable') {

	$tempArray = [];
	$available_variations = $product->get_available_variations();
	foreach ($available_variations as $variation) {
		foreach ($variation['attributes'] as $key => $attribute) {
			array_push($tempArray, array('name' => $attribute, 'is_available' => $variation['is_in_stock']));
		}
	}
	$temp_array = array();
	$i = 0;
	$key_array = array();
	$key = 'name';

	foreach ($tempArray as $val) {
		if (!in_array($val[$key], $key_array)) {
			$key_array[$i] = $val[$key];
			$temp_array[$i] = $val;
		}
		$i++;
	}
}
// Ensure visibility.
if (empty($product) || !$product->is_visible()) {
	return;
}
?>
<li <?php wc_product_class('col-span-1 border-b px-0 pb-4 md:pb-8 group relative', $product); ?>>
	<?php
	/**
	 * Hook: woocommerce_before_shop_loop_item.
	 *
	 * @hooked woocommerce_template_loop_product_link_open - 10
	 */
	do_action('woocommerce_before_shop_loop_item');

	// /**
	//  * Hook: woocommerce_before_shop_loop_item_title.
	//  *
	//  * @hooked woocommerce_show_product_loop_sale_flash - 10
	//  * @hooked woocommerce_template_loop_product_thumbnail - 10
	//  */
	// do_action( 'woocommerce_before_shop_loop_item_title' );
	$post_thumbnail_id = $product->get_image_id();
	$image = wp_get_attachment_image_url($post_thumbnail_id, 'full');
	?>

	<div class="relative w-full">
		<div class="overflow-hidden aspect-productImg relative md:mx-6">
			<div class="aspect-productImg absolute bg-white w-full">
				<img src="<?= $image ?>" class="image-fill" alt="<?= $product->get_name() ?>" />
			</div>
		</div>
	</div>
	<div class="font-roboto relative z-20">
		<?php if ($product->get_type() == 'variable') { ?>
			<div class="absolute w-full bg-white bg-opacity-50 left-0 py-2 justify-center item-center gap-2 -top-3
							-translate-y-full hidden lg:flex">
				<?php foreach ($temp_array as $variation) { ?>

					<div
						class="text-xs xl:text-sm uppercase font-roboto <?= $variation['is_available'] === 0 ? 'text-gray-400 line-through' : '' ?>">
						<?= $variation['name'] ?>
					</div>
				<?php } ?>
			</div>
		<?php } else { ?>
			<div
				class="absolute w-full  bg-white bg-opacity-50 left-0 py-2 justify-center item-center gap-2 -top-3 -translate-y-full hidden lg:flex">
				<div
					class="text-xs xl:text-sm uppercase font-roboto <?= $product->get_stock_quantity() === 0 ? 'text-gray-400 line-through' : '' ?>">
					<?= __('Tamanho único', 'theme-tailwind'); ?>
				</div>
			</div>
		<?php } ?>

		<h3 class="text-sm md:text-base uppercase px-2 text-center mb-2">
			<?= $product->get_name() ?>
		</h3>
		<div class="text-center">
			<?php

			/**
			 * Hook: woocommerce_after_shop_loop_item_title.
			 *
			 * @hooked woocommerce_template_loop_rating - 5
			 * @hooked woocommerce_template_loop_price - 10
			 */
			do_action('woocommerce_after_shop_loop_item_title'); ?>
		</div>
	</div>
	<?php

	/**
	 * Hook: woocommerce_after_shop_loop_item.
	 *
	 * @hooked woocommerce_template_loop_product_link_close - 5
	 * @hooked woocommerce_template_loop_add_to_cart - 10
	 */
	do_action('woocommerce_after_shop_loop_item');
	?>

</li>