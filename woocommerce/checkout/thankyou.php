<?php
/**
 * Thankyou page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/thankyou.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.7.0
 */

defined('ABSPATH') || exit;
?>

<div class="woocommerce-order font-roboto my-4">

	<?php
	if ($order):

		do_action('woocommerce_before_thankyou', $order->get_id());
		?>

		<?php if ($order->has_status('failed')): ?>

			<p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed">
				<?php esc_html_e('Unfortunately your order cannot be processed as the originating bank/merchant has declined your transaction. Please attempt your purchase again.', 'woocommerce'); ?>
			</p>

			<p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed-actions">
				<a href="<?php echo esc_url($order->get_checkout_payment_url()); ?>" class="button pay"><?php esc_html_e('Pay', 'woocommerce'); ?></a>
				<?php if (is_user_logged_in()): ?>
					<a href="<?php echo esc_url(wc_get_page_permalink('myaccount')); ?>" class="button pay"><?php esc_html_e('My account', 'woocommerce'); ?></a>
				<?php endif; ?>
			</p>

		<?php else: ?>
			<div
				class="auto-rows-[minmax(min-content, max-content)] mx-auto grid w-full max-w-screen-md grid-cols-6 gap-4 px-4 pb-4 md:gap-10 md:px-8 md:pb-8">
				<div class="col-span-6 flex flex-col justify-center text-center">
					<div class="text-green-600 text-4xl w-full flex justify-center"><svg stroke="currentColor" fill="currentColor"
							stroke-width="0" viewBox="0 0 1024 1024" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
							<path
								d="M699 353h-46.9c-10.2 0-19.9 4.9-25.9 13.3L469 584.3l-71.2-98.8c-6-8.3-15.6-13.3-25.9-13.3H325c-6.5 0-10.3 7.4-6.5 12.7l124.6 172.8a31.8 31.8 0 0 0 51.7 0l210.6-292c3.9-5.3.1-12.7-6.4-12.7z">
							</path>
							<path
								d="M512 64C264.6 64 64 264.6 64 512s200.6 448 448 448 448-200.6 448-448S759.4 64 512 64zm0 820c-205.4 0-372-166.6-372-372s166.6-372 372-372 372 166.6 372 372-166.6 372-372 372z">
							</path>
						</svg></div>
					<h2 class="mb-4 text-lg font-roboto text-green-600 my-4">Encomenda registada com sucesso.</h2>
					<p class="text-center mb-0">Encomenda nº
						<?php echo $order->get_order_number(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</p>
				</div>

				<div class="col-span-6 flex flex-col justify-center text-center">
					<div class="mx-auto flex max-w-sm w-full flex-col items-start border bg-gray-50 p-4">
						<?php do_action('woocommerce_thankyou_' . $order->get_payment_method(), $order->get_id()); ?>
					</div>
				</div>
				<div class="col-span-6 p-4 md:p-8 my-6 bg-gray-50 border">
					<div class=" w-full">
						<h3 class="mb-4 pb-1 text-xl font-medium flex items-center gap-2 ">
							<svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 24 24" height="1em" width="1em"
								xmlns="http://www.w3.org/2000/svg">
								<path fill="none" stroke="#000" stroke-width="2"
									d="M6,16 L16,16 L6,16 L6,16 Z M6,12 L18,12 L6,12 L6,12 Z M6,8 L11,8 L6,8 L6,8 Z M14,1 L14,8 L21,8 M3,23 L3,1 L15,1 L21,7 L21,23 L3,23 Z">
								</path>
							</svg>
							<?php esc_html_e('Resumo da Encomenda nº', 'woocommerce'); ?>
							<?= $order->get_order_number() ?>
						</h3>
						<div class="flex flex-col space-y-4">
							<div class="flex justify-between border-b">
								<label class="text-sm text-gray-500">
									<?php esc_html_e('Total:', 'woocommerce'); ?>
								</label>
								<p>
									<?php echo $order->get_formatted_order_total(); ?>
								</p>
							</div>

							<?php if (is_user_logged_in() && $order->get_user_id() === get_current_user_id() && $order->get_billing_email()): ?>
								<div class="flex justify-between border-b">
									<label class="text-sm text-gray-500">
										<?php esc_html_e('Email:', 'woocommerce'); ?>
									</label>
									<p>
										<?php echo $order->get_billing_email(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
									</p>
								</div>
							<?php endif; ?>

							<?php if ($order->get_payment_method_title()): ?>
								<div class="flex justify-between border-b">
									<label class="text-sm text-gray-500">
										<?php esc_html_e('Payment method:', 'woocommerce'); ?>
									</label>
									<p>
										<?php echo wp_kses_post($order->get_payment_method_title()); ?>
									</p>
								</div>
							<?php endif; ?>
							<div class="flex justify-between border-b">
								<label class="text-sm text-gray-500">
									<?php esc_html_e('Date:', 'woocommerce'); ?>
								</label>
								<p>
									<?php echo wc_format_datetime($order->get_date_created()); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
								</p>
							</div>
						</div>
					</div>
				</div>
				<div class="col-span-6 lg:col-span-3">
					<h4 class=" mb-4 flex gap-2 items-center border-b pb-1 text-xl font-medium">
						<svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 640 512" height="1em" width="1em"
							xmlns="http://www.w3.org/2000/svg">
							<path
								d="M624 352h-16V243.9c0-12.7-5.1-24.9-14.1-33.9L494 110.1c-9-9-21.2-14.1-33.9-14.1H416V48c0-26.5-21.5-48-48-48H112C85.5 0 64 21.5 64 48v48H8c-4.4 0-8 3.6-8 8v16c0 4.4 3.6 8 8 8h272c4.4 0 8 3.6 8 8v16c0 4.4-3.6 8-8 8H40c-4.4 0-8 3.6-8 8v16c0 4.4 3.6 8 8 8h208c4.4 0 8 3.6 8 8v16c0 4.4-3.6 8-8 8H8c-4.4 0-8 3.6-8 8v16c0 4.4 3.6 8 8 8h208c4.4 0 8 3.6 8 8v16c0 4.4-3.6 8-8 8H64v128c0 53 43 96 96 96s96-43 96-96h128c0 53 43 96 96 96s96-43 96-96h48c8.8 0 16-7.2 16-16v-32c0-8.8-7.2-16-16-16zM160 464c-26.5 0-48-21.5-48-48s21.5-48 48-48 48 21.5 48 48-21.5 48-48 48zm320 0c-26.5 0-48-21.5-48-48s21.5-48 48-48 48 21.5 48 48-21.5 48-48 48zm80-208H416V144h44.1l99.9 99.9V256z">
							</path>
						</svg>
						<?php esc_html_e('Endereço de Envio', 'woocommerce'); ?>
					</h4>
					<div class="flex flex-col space-y-2 text-sm">
						<?php if ($order->get_shipping_first_name() || $order->get_shipping_last_name()): ?>
							<div class="flex space-x-2">
								<label class="text-sm text-gray-500">
									<?php esc_html_e('Nome: ', 'woocommerce'); ?>
								</label>
								<p>
									<?php echo $order->get_shipping_first_name() . ' ' . $order->get_shipping_last_name(); ?>
								</p>
							</div>
						<?php endif; ?>

						<?php if ($order->get_shipping_address_1() || $order->get_shipping_address_2()): ?>
							<div class="flex space-x-2">
								<label class="text-sm text-gray-500">
									<?php esc_html_e('Morada: ', 'woocommerce'); ?>
								</label>
								<p>
									<?php echo $order->get_shipping_address_1() . ' ' . $order->get_shipping_address_2(); ?>
								</p>
							</div>
						<?php endif; ?>

						<?php if ($order->get_shipping_city()): ?>
							<div class="flex space-x-2">
								<label class="text-sm text-gray-500">
									<?php esc_html_e('Cidade: ', 'woocommerce'); ?>
								</label>
								<p>
									<?php echo $order->get_shipping_city(); ?>
								</p>
							</div>
						<?php endif; ?>

						<?php if ($order->get_shipping_postcode()): ?>
							<div class="flex space-x-2">
								<label class="text-sm text-gray-500">
									<?php esc_html_e('Código Postal: ', 'woocommerce'); ?>
								</label>
								<p>
									<?php echo $order->get_shipping_postcode(); ?>
								</p>
							</div>
						<?php endif; ?>

						<?php if ($order->get_shipping_phone()): ?>
							<div class="flex space-x-2">
								<label class="text-sm text-gray-500">
									<?php esc_html_e('Telemóvel: ', 'woocommerce'); ?>
								</label>
								<p>
									<?php echo $order->get_shipping_phone(); ?>
								</p>
							</div>
						<?php endif; ?>
					</div>
				</div>
				<div class="col-span-6 lg:col-span-3">
					<h3 class="mb-4 flex gap-2 items-center border-b pb-1 text-xl font-medium">
						<svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 448 512" height="1em" width="1em"
							xmlns="http://www.w3.org/2000/svg">
							<path
								d="M436 160c6.6 0 12-5.4 12-12v-40c0-6.6-5.4-12-12-12h-20V48c0-26.5-21.5-48-48-48H48C21.5 0 0 21.5 0 48v416c0 26.5 21.5 48 48 48h320c26.5 0 48-21.5 48-48v-48h20c6.6 0 12-5.4 12-12v-40c0-6.6-5.4-12-12-12h-20v-64h20c6.6 0 12-5.4 12-12v-40c0-6.6-5.4-12-12-12h-20v-64h20zm-228-32c35.3 0 64 28.7 64 64s-28.7 64-64 64-64-28.7-64-64 28.7-64 64-64zm112 236.8c0 10.6-10 19.2-22.4 19.2H118.4C106 384 96 375.4 96 364.8v-19.2c0-31.8 30.1-57.6 67.2-57.6h5c12.3 5.1 25.7 8 39.8 8s27.6-2.9 39.8-8h5c37.1 0 67.2 25.8 67.2 57.6v19.2z">
							</path>
						</svg>
						<?php esc_html_e('Endereço de Faturação', 'woocommerce'); ?>
					</h3>
					<div class="flex flex-col space-y-2 text-sm">
						<?php if ($order->get_billing_first_name() || $order->get_billing_last_name()): ?>
							<div class="flex space-x-2">
								<label class="text-sm text-gray-500">
									<?php esc_html_e('Nome: ', 'woocommerce'); ?>
								</label>
								<p>
									<?php echo $order->get_billing_first_name() . ' ' . $order->get_billing_last_name(); ?>
								</p>
							</div>
						<?php endif; ?>

						<?php if ($order->get_billing_address_1() || $order->get_billing_address_2()): ?>
							<div class="flex space-x-2">
								<label class="text-sm text-gray-500">
									<?php esc_html_e('Morada: ', 'woocommerce'); ?>
								</label>
								<p>
									<?php echo $order->get_billing_address_1() . ' ' . $order->get_billing_address_2(); ?>
								</p>
							</div>
						<?php endif; ?>

						<?php if ($order->get_billing_city()): ?>
							<div class="flex space-x-2">
								<label class="text-sm text-gray-500">
									<?php esc_html_e('Cidade: ', 'woocommerce'); ?>
								</label>
								<p>
									<?php echo $order->get_billing_city(); ?>
								</p>
							</div>
						<?php endif; ?>

						<?php if ($order->get_billing_postcode()): ?>
							<div class="flex space-x-2">
								<label class="text-sm text-gray-500">
									<?php esc_html_e('Código Postal: ', 'woocommerce'); ?>
								</label>
								<p>
									<?php echo $order->get_billing_postcode(); ?>
								</p>
							</div>
						<?php endif; ?>

						<?php if ($order->get_billing_email()): ?>
							<div class="flex space-x-2">
								<label class="text-sm text-gray-500">
									<?php esc_html_e('Email: ', 'woocommerce'); ?>
								</label>
								<p>
									<?php echo $order->get_billing_email(); ?>
								</p>
							</div>
						<?php endif; ?>

						<?php if ($order->get_billing_phone()): ?>
							<div class="flex space-x-2">
								<label class="text-sm text-gray-500">
									<?php esc_html_e('Telemóvel: ', 'woocommerce'); ?>
								</label>
								<p>
									<?php echo $order->get_billing_phone(); ?>
								</p>
							</div>
						<?php endif; ?>
						<?php
						$order_data = $order->get_data();
						if ($order_data['meta_data'][0]->__get('value')): ?>
							<div class="flex space-x-2">
								<label class="text-sm text-gray-500">
									<?php esc_html_e('NIF: ', 'woocommerce'); ?>
								</label>
								<p>
									<?php echo $order_data['meta_data'][0]->__get('value'); ?>
								</p>
							</div>
						<?php endif; ?>
					</div>
				</div>

			</div>


			<?php /*?>	
			 <div class="bg-[#EAEAEA] bg-opacity-25 rounded-none p-4">
			 <?php
			 // wc_get_template( 'checkout/review-order.php', $order->get_id() );
			 do_action('woocommerce_thankyou', $order->get_id());
			 ?>
			 </div>
			 <?php */?>
		<?php endif; ?>




	<?php else: ?>

		<p class="woocommerce-notice woocommerce-notice--success woocommerce-thankyou-order-received">
			<?php echo apply_filters('woocommerce_thankyou_order_received_text', esc_html__('Thank you. Your order has been received.', 'woocommerce'), null); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</p>

	<?php endif; ?>

</div>