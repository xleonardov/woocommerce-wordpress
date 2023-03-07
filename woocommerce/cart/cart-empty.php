<?php
/**
 * Empty cart page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart-empty.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.5.0
 */

defined('ABSPATH') || exit;

/*
 * @hooked wc_empty_cart_message - 10
 */
// do_action( 'woocommerce_cart_is_empty' );
$icons = new Icons;
?>
<div class="flex flex-col justify-center items-center space-y-4 mb-4">
	<p class="text-center text-xl ">O seu carrinho está vazio.</p>
	<div class="text-[8rem]">
		<?= $icons->get_icon('AiOutlineShoppingCart') ?>
	</div>
</div>
<?php
if (wc_get_page_id('shop') > 0): ?>
	<p class="return-to-shop flex items-center justify-center">
		<a class="button wc-backward flex items-center justify-center alt wc-forward bg-secondary px-4 py-2 h-12 text-white text-center transition-all duration-200 hover:bg-primary disabled:opacity-20 uppercase tracking-wide rounded-none"
			href="<?php echo esc_url(apply_filters('woocommerce_return_to_shop_redirect', wc_get_page_permalink('shop'))); ?>">
			<?php
			/**
			 * Filter "Return To Shop" text.
			 *
			 * @since 4.6.0
			 * @param string $default_text Default text.
			 */
			echo esc_html(apply_filters('woocommerce_return_to_shop_text', __('Return to shop', 'woocommerce')));
			?>
		</a>
	</p>
<?php endif; ?>