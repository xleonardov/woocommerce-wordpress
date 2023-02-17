<?php
/**
 * Cart Page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.8.0
 */

defined('ABSPATH') || exit;
$icons = new Icons;

do_action('woocommerce_before_cart'); ?>
<div class="hidden">
	<div class="inline mr-2 w-8 h-8 text-white animate-spin fill-primary opacity-50 pointer-events-none"></div>
</div>
<div class="grid grid-cols-1 md:grid-cols-[1fr_minmax(0,374px)] gap-4 py-8 tracking-wide font-roboto">
	<div class="">
		<form class="woocommerce-cart-form w-full grid gap-4" action="<?php echo esc_url(wc_get_cart_url()); ?>"
			method="post">
			<?php //do_action( 'woocommerce_before_cart_table' ); ?>
			<div class="w-full">
				<div class="grid gap-4 items-center cart-content ">
					<div colspan="6" class="actions hidden">
						<button type="submit" class="button" name="update_cart"
							value="<?php esc_attr_e('Update cart', 'woocommerce'); ?>"><?php esc_html_e('Update cart', 'woocommerce'); ?></button>
						<?php do_action('woocommerce_cart_actions'); ?>
						<?php wp_nonce_field('woocommerce-cart', 'woocommerce-cart-nonce'); ?>
					</div>
					<div
						class="md:grid-cols-[12px_64px_2fr_1fr_1fr_1fr] lg:grid-cols-[28px_100px_2fr_1fr_1fr_1fr] uppercase text-sm font-semibold hidden lg:grid">
						<div class="product-remove pb-4">&nbsp;</div>
						<div class="product-thumbnail pb-4">&nbsp;</div>
						<div class="product-name">
							<?php esc_html_e('Product', 'woocommerce'); ?>
						</div>
						<div class="product-price">
							<?php esc_html_e('Price', 'woocommerce'); ?>
						</div>
						<div class="product-quantity">
							<?php esc_html_e('Quantity', 'woocommerce'); ?>
						</div>
						<div class="product-subtotal">
							<?php esc_html_e('Subtotal', 'woocommerce'); ?>
						</div>
					</div>
					<?php
					foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
						$_product = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
						$product_id = apply_filters('woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key);
						if ($_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters('woocommerce_cart_item_visible', true, $cart_item, $cart_item_key)) {
							$product_permalink = apply_filters('woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink($cart_item) : '', $cart_item, $cart_item_key); ?>
							<div
								class="grid border-b pb-4 last:border-none last:pb-0 grid-cart-products gap-y-4 grid-cols-[12px_64px_2fr_1fr] lg:grid-cols-[28px_100px_2fr_1fr_1fr_1fr] items-center woocommerce-cart-form__cart-item <?php echo esc_attr(apply_filters('woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key)); ?>">
								<div class="remove-product flex justify-end md:justify-start">
									<?php
									echo apply_filters(
										// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
										'woocommerce_cart_item_remove_link',
										sprintf(
											'<a href="%s" class="remove hover:text-secondary transition-all duration-300 text-gray-400 hover:text-black" aria-label="%s" data-product_id="%s" data-product_sku="%s"><svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
											<circle cx="6" cy="6" r="5.5" stroke="currentColor"/>
											<path d="M3 9L9 3M3 3L9 9L3 3Z" stroke="currentColor"/>
											</svg>
											</a>',
											esc_url(wc_get_cart_remove_url($cart_item_key)),
											esc_html__('Remove this item', 'woocommerce'),
											esc_attr($product_id),
											esc_attr($_product->get_sku())
										),
										$cart_item_key
									);
									?>
								</div>
								<div class="image-product w-16 lg:w-24">
									<?php
									$thumbnail = apply_filters('woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key);

									if (!$product_permalink) {
										echo $thumbnail; // PHPCS: XSS ok.
									} else {
										printf('<a href="%s">%s</a>', esc_url($product_permalink), $thumbnail); // PHPCS: XSS ok.
									}
									?>
								</div>
								<div class="name-product text-xs font-medium md:text-base">
									<?php
									if (!$product_permalink) {
										echo wp_kses_post(apply_filters('woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key) . '&nbsp;');
									} else {
										echo wp_kses_post(apply_filters('woocommerce_cart_item_name', sprintf('<a href="%s">%s</a>', esc_url($product_permalink), $_product->get_name()), $cart_item, $cart_item_key));
									}

									do_action('woocommerce_after_cart_item_name', $cart_item, $cart_item_key);

									// Meta data.
									echo wc_get_formatted_cart_item_data($cart_item); // PHPCS: XSS ok.
							
									// Backorder notification.
									if ($_product->backorders_require_notification() && $_product->is_on_backorder($cart_item['quantity'])) {
										echo wp_kses_post(apply_filters('woocommerce_cart_item_backorder_notification', '<p class="backorder_notification">' . esc_html__('Available on backorder', 'woocommerce') . '</p>', $product_id));
									}
									?>
								</div>
								<div class="price-product md:block flex flex-col text-sm text-right lg:text-left">
									<?php if ($_product->get_sale_price()) {

										$percentage = 0;
										if ($_product->get_sale_price()) {
											$percentage = round(((floatval($_product->get_regular_price()) - floatval($_product->get_sale_price())) / floatval($_product->get_regular_price())) * 100);
										}

										?>
										<div class="flex space-x-2 items-center mb-2">
											<div class="text-sm text-gray-500 line-through">
												<?= number_format($_product->get_regular_price(), 2) ?>€
											</div>
											<div>
												<span class="bg-amarelo text-white rounded-sm text-sm px-1 py-px sm:px-2 sm:py-1 font-roboto">
													<?='-' . $percentage ?>%
												</span>
											</div>
										</div>
									<?php } ?>
									<?php
									echo apply_filters('woocommerce_cart_item_price', WC()->cart->get_product_price($_product), $cart_item, $cart_item_key); // PHPCS: XSS ok.
									?>
								</div>
								<div class="quantity-product col-span-2 lg:col-auto">
									<?php
									if ($_product->is_sold_individually()) {
										$product_quantity = sprintf('1 <input type="hidden" class="qty" name="cart[%s][qty]" value="1" />', $cart_item_key);
									} else {
										$product_quantity = woocommerce_quantity_input(
											array(
												'input_class' => 'qty',
												'input_name' => "cart[{$cart_item_key}][qty]",
												'input_value' => $cart_item['quantity'],
												'max_value' => $_product->get_max_purchase_quantity(),
												'min_value' => '0',
												'product_name' => $_product->get_name(),
											),
											$_product,
											false
										);
									}

									echo apply_filters('woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item); // PHPCS: XSS ok.
									?>
								</div>
								<div class="subtotal-product text-sm md:text-base col-span-2 lg:col-auto pl-4 lg:pl-0">
									<?php
									echo apply_filters('woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal($_product, $cart_item['quantity']), $cart_item, $cart_item_key); // PHPCS: XSS ok.
									?>
								</div>
							</div>
						<?php
						}
					}
					?>
				</div>
			</div>
			<?php //do_action( 'woocommerce_after_cart_table' ); ?>
			<div class="">
				<?php if (wc_coupons_enabled()) { ?>
					<div class="coupon">
						<label for="coupon_code" class="block mb-4 uppercase text-sm font-semibold tracking-wide">
							<?php esc_html_e('Coupon', 'woocommerce'); ?>
						</label>
						<div class="flex items-center space-y-2 md:space-y-0 md:space-x-4 flex-col  md:flex-row">
							<input type="text" name="coupon_code"
								class="input-text w-full px-4 py-2 uppercase tracking-wide border text-xs h-[50px]" id="coupon_code"
								value="" placeholder="<?php esc_attr_e('Coupon code', 'woocommerce'); ?>" />
							<div>
								<button type="submit" class="btn btn-primary w-48 flex justify-center items-center" name="apply_coupon"
									value="<?php esc_attr_e('Apply coupon', 'woocommerce'); ?>"><?php esc_attr_e('Apply coupon', 'woocommerce'); ?></button>
							</div>
						</div>
						<?php do_action('woocommerce_cart_coupon'); ?>
					</div>
				<?php } ?>
			</div>
		</form>
	</div>
	<div class="">
		<div class="bg-[#EAEAEA] bg-opacity-25 p-4">
			<?php do_action('woocommerce_before_cart_collaterals'); ?>
			<div class="cart-collaterals">
				<?php
				/**
				 * Cart collaterals hook.
				 *
				 * @hooked woocommerce_cross_sell_display
				 * @hooked woocommerce_cart_totals - 10
				 */
				do_action('woocommerce_cart_collaterals');
				?>
			</div>
		</div>
	</div>
</div>

<?php do_action('woocommerce_after_cart'); ?>