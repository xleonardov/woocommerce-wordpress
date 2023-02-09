<?php
/**
 * Proceed to checkout button
 *
 * Contains the markup for the proceed to checkout button on the cart.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/proceed-to-checkout-button.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 2.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>
<a href="<?php echo esc_url( wc_get_checkout_url() ); ?>" class="checkout-button flex items-center justify-center alt wc-forward w-full bg-secondary px-4 py-2 h-12 text-white text-center transition-all duration-200 hover:bg-primary disabled:opacity-20 uppercase tracking-wide rounded-lg">
	<?php esc_html_e( 'Proceed to checkout', 'woocommerce' ); ?>
</a>
