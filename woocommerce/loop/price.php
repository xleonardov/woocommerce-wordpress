<?php
/**
 * Loop Price
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/price.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     1.6.4
 */

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

global $product;
$percentage = 0;
if ($product->is_type('simple')) { //if simple product
	if ($product->sale_price) {
		$percentage = round(((floatval($product->regular_price) - floatval($product->sale_price)) / floatval($product->regular_price)) * 100);
	}
} else { //if variable product
	$percentage = apply_filters('get_variable_sale_percentage', $product);
}
?>

<?php if ($price_html = $product->get_price_html()): ?>
	<div class="flex justify-center items-center space-x-2">
		<span class="loop-price text-base uppercase text-center">
			<?php echo $price_html; ?>
		</span>
		<?php if ($percentage && $percentage > 0) { ?>
			<div>
				<span class="bg-amarelo text-white rounded-sm text-sm px-1 py-px sm:px-2 sm:py-1 font-roboto">
					<?= $percentage ?> %
				</span>
			</div>
		<?php } ?>
	</div>
<?php endif; ?>