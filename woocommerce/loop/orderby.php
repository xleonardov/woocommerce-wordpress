<?php
/**
 * Show options for ordering
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/orderby.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     3.6.0
 */

if (!defined('ABSPATH')) {
	exit;
}

?>
<div>
	<h5 class="text-xs text-gray-400 uppercase mb-2">
		Ordenar por:
	</h5>
	<div class="flex flex-col space-y-1">
		<?php foreach ($catalog_orderby_options as $id => $name): ?>
			<div class="flex items-center space-x-2">
				<input type="radio" id="<?php echo esc_attr($id); ?>" class="w-4 h-4" name="orderby"
					value="<?php echo esc_attr($id); ?>" data-taxonomy="<?= isset($term->slug) ? $term->slug : '' ?>" <?php checked($orderby, $id); ?>>
				<label for="<?php echo esc_attr($id); ?>" class="uppercase"><?php echo esc_html($name); ?></label>
			</div>
		<?php endforeach; ?>
	</div>
</div>