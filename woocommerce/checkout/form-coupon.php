<?php
/**
 * Checkout coupon form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-coupon.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.4.4
 */

defined('ABSPATH') || exit;

if (!wc_coupons_enabled()) { // @codingStandardsIgnoreLine.
	return;
}

?>
<div class="woocommerce-form-coupon-toggle p-4 bg-[#EAEAEA] bg-opacity-25 mt-4 rounded-none text-sm font-roboto">
	<?php wc_print_notice(apply_filters('woocommerce_checkout_coupon_message', esc_html__('Have a coupon?', 'woocommerce') . ' <a href="#" class="showcoupon underline">' . esc_html__('Click here to enter your code', 'woocommerce') . '</a>'), 'notice'); ?>


	<form class="checkout_coupon woocommerce-form-coupon" method="post" style="display:none">

		<p class="mt-4">
			<?php esc_html_e('If you have a coupon code, please apply it below.', 'woocommerce'); ?>
		</p>

		<div class="flex mt-4 space-x-4">
			<p class="form-row form-row-first">
				<input type="text" name="coupon_code"
					class="input-text w-full lg:w-44 px-4 py-2 h-10  uppercase tracking-wide border text-xs rounded-none"
					placeholder="<?php esc_attr_e('Coupon code', 'woocommerce'); ?>" id="coupon_code" value="" />
			</p>

			<p class="form-row form-row-last">
				<button type="submit"
					class="button bg-primary hover:bg-gray-700 transition-all duration-200 w-36 py-3 h-10 flex justify-center items-center text-white uppercase text-xs tracking-wide rounded-none"
					name="apply_coupon" value="<?php esc_attr_e('Apply coupon', 'woocommerce'); ?>"><?php esc_html_e('Apply coupon', 'woocommerce'); ?></button>
			</p>
		</div>

		<div class="clear"></div>
	</form>
</div>