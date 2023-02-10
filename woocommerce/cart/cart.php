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
<div class="grid grid-cols-1 lg:grid-cols-[1fr_minmax(0,374px)] gap-4 py-8 tracking-wide">
	<div class="">
		<form class="woocommerce-cart-form w-full grid gap-4" action="<?php echo esc_url(wc_get_cart_url()); ?>"
			method="post">
			<?php //do_action( 'woocommerce_before_cart_table' ); ?>
			<div class="w-full bg-white p-4">
				<div class="grid gap-4 items-center cart-content ">
					<div colspan="6" class="actions hidden">
						<button type="submit" class="button" name="update_cart"
							value="<?php esc_attr_e('Update cart', 'woocommerce'); ?>"><?php esc_html_e('Update cart', 'woocommerce'); ?></button>
						<?php do_action('woocommerce_cart_actions'); ?>
						<?php wp_nonce_field('woocommerce-cart', 'woocommerce-cart-nonce'); ?>
					</div>
					<div class=" grid-cols-[28px_160px_2fr_1fr_1fr_1fr] uppercase text-sm font-semibold hidden lg:grid">
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
								class="grid border-b pb-4 last:border-none last:pb-0 grid-cart-products gap-y-4 lg:grid-cols-[28px_160px_2fr_1fr_1fr_1fr] items-center woocommerce-cart-form__cart-item <?php echo esc_attr(apply_filters('woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key)); ?>">
								<div class="remove-product flex justify-end lg:justify-start">
									<?php
									echo apply_filters(
										// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
										'woocommerce_cart_item_remove_link',
										sprintf(
											'<a href="%s" class="remove hover:text-secondary transition-all duration-300" aria-label="%s" data-product_id="%s" data-product_sku="%s">' . $icons->get_icon('BsTrash') . '</a>',
											esc_url(wc_get_cart_remove_url($cart_item_key)),
											esc_html__('Remove this item', 'woocommerce'),
											esc_attr($product_id),
											esc_attr($_product->get_sku())
										),
										$cart_item_key
									);
									?>
								</div>
								<div class="image-product">
									<?php
									$thumbnail = apply_filters('woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key);

									if (!$product_permalink) {
										echo $thumbnail; // PHPCS: XSS ok.
									} else {
										printf('<a href="%s">%s</a>', esc_url($product_permalink), $thumbnail); // PHPCS: XSS ok.
									}
									?>
								</div>
								<div class="name-product text-xs font-medium lg:text-base">
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
								<div class="price-product hidden lg:block">
									<?php
									echo apply_filters('woocommerce_cart_item_price', WC()->cart->get_product_price($_product), $cart_item, $cart_item_key); // PHPCS: XSS ok.
									?>
								</div>
								<div class="quantity-product">
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
								<div class="subtotal-product text-sm lg:text-base">
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
			<div class="bg-white p-4">
				<?php if (wc_coupons_enabled()) { ?>
					<div class="coupon">
						<label for="coupon_code" class="block mb-4 uppercase text-sm font-semibold tracking-wide">
							<?php esc_html_e('Coupon', 'woocommerce'); ?>
						</label>
						<div class="grid gap-4 grid-cols-[auto_144px]">
							<input type="text" name="coupon_code"
								class="input-text w-full px-4 py-2 uppercase tracking-wide border text-xs" id="coupon_code" value=""
								placeholder="<?php esc_attr_e('Coupon code', 'woocommerce'); ?>" />
							<button type="submit" class="btn btn-primary w-48 flex justify-center items-center" name="apply_coupon"
								value="<?php esc_attr_e('Apply coupon', 'woocommerce'); ?>"><?php esc_attr_e('Apply coupon', 'woocommerce'); ?></button>
						</div>
						<?php do_action('woocommerce_cart_coupon'); ?>
					</div>
				<?php } ?>
			</div>
		</form>
	</div>
	<div class="">
		<div class="bg-[#EAEAEA] bg-opacity-20 p-4">
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