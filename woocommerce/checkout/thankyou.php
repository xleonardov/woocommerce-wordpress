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

defined( 'ABSPATH' ) || exit;
?>

<div class="woocommerce-order">

	<?php
	if ( $order ) :

		do_action( 'woocommerce_before_thankyou', $order->get_id() );
		?>

		<?php if ( $order->has_status( 'failed' ) ) : ?>

			<p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed"><?php esc_html_e( 'Unfortunately your order cannot be processed as the originating bank/merchant has declined your transaction. Please attempt your purchase again.', 'woocommerce' ); ?></p>

			<p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed-actions">
				<a href="<?php echo esc_url( $order->get_checkout_payment_url() ); ?>" class="button pay"><?php esc_html_e( 'Pay', 'woocommerce' ); ?></a>
				<?php if ( is_user_logged_in() ) : ?>
					<a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" class="button pay"><?php esc_html_e( 'My account', 'woocommerce' ); ?></a>
				<?php endif; ?>
			</p>

		<?php else : ?>

			<p class="woocommerce-notice woocommerce-notice--success woocommerce-thankyou-order-received text-center uppercase tracking-wide text-sm mt-4"><?php echo apply_filters( 'woocommerce_thankyou_order_received_text', esc_html__( 'Thank you. Your order has been received.', 'woocommerce' ), $order ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
			<p class="text-center uppercase tracking-wide text-sm">Encomenda nº: <strong><?php echo $order->get_order_number(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></strong></p>
			
			<div class="flex justify-center my-4">
				<?php do_action( 'woocommerce_thankyou_' . $order->get_payment_method(), $order->get_id() ); ?>
			</div>
			<div class="grid grid-cols-1 lg:grid-cols-3 mt-4 gap-4">
				<div class="bg-white rounded-lg p-4">
					<h4 class="pb-2 mb-4 uppercase text-sm text-gray-400 font-medium tracking-wide"><?php esc_html_e( 'Detalhes da Encomenda', 'woocommerce' ); ?></h4>
					<div class="text-sm">
						<div class="flex space-x-2">
							<label class="font-medium"><?php esc_html_e( 'Date:', 'woocommerce' ); ?></label>
							<p><?php echo wc_format_datetime( $order->get_date_created() ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
						</div>

						<?php if ( is_user_logged_in() && $order->get_user_id() === get_current_user_id() && $order->get_billing_email() ) : ?>
							<div class="flex space-x-2">
								<label class="font-medium"><?php esc_html_e( 'Email:', 'woocommerce' ); ?></label>
								<p><?php echo $order->get_billing_email(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
							</div>
						<?php endif; ?>

						<div class="flex space-x-2">
							<label class="font-medium"><?php esc_html_e( 'Total:', 'woocommerce' ); ?></label>
							<p><?php echo $order->get_formatted_order_total(); ?></p>
						</div>

						<?php if ( $order->get_payment_method_title() ) : ?>
							<div class="flex space-x-2">
								<label class="font-medium"><?php esc_html_e( 'Payment method:', 'woocommerce' ); ?></label>
								<p><?php echo wp_kses_post( $order->get_payment_method_title() ); ?></p>
							</div>
						<?php endif; ?>
					</div>
				</div>
				<div class="bg-white rounded-lg p-4">
					<h4 class=" pb-2 mb-4 uppercase text-sm text-gray-400 font-medium tracking-wide"><?php esc_html_e( 'Detalhes de faturação', 'woocommerce' ); ?></h4>
					<div class="text-sm">
						<?php if($order->get_billing_first_name() || $order->get_billing_last_name()) :?>
							<div class="flex space-x-2">
								<label class="font-medium"><?php esc_html_e( 'Nome: ', 'woocommerce' ); ?></label>
								<p><?php echo $order->get_billing_first_name() .' '.$order->get_billing_last_name(); ?></p>
							</div>
						<?php endif; ?>

						<?php if($order->get_billing_address_1() || $order->get_billing_address_2()) :?>
							<div class="flex space-x-2">
								<label class="font-medium"><?php esc_html_e( 'Morada: ', 'woocommerce' ); ?></label>
								<p><?php echo $order->get_billing_address_1() .' '.$order->get_billing_address_2(); ?></p>
							</div>
						<?php endif; ?>

						<?php if($order->get_billing_city()) :?>
							<div class="flex space-x-2">
								<label class="font-medium"><?php esc_html_e( 'Cidade: ', 'woocommerce' ); ?></label>
								<p><?php echo $order->get_billing_city(); ?></p>
							</div>
						<?php endif; ?>

						<?php if($order->get_billing_postcode()) :?>
							<div class="flex space-x-2">
								<label class="font-medium"><?php esc_html_e( 'Código Postal: ', 'woocommerce' ); ?></label>
								<p><?php echo $order->get_billing_postcode(); ?></p>
							</div>
						<?php endif; ?>

						<?php if($order->get_billing_email()) :?>
							<div class="flex space-x-2">
								<label class="font-medium"><?php esc_html_e( 'Email: ', 'woocommerce' ); ?></label>
								<p><?php echo $order->get_billing_email(); ?></p>
							</div>
						<?php endif; ?>

						<?php if($order->get_billing_phone()) :?>
							<div class="flex space-x-2">
								<label class="font-medium"><?php esc_html_e( 'Telemóvel: ', 'woocommerce' ); ?></label>
								<p><?php echo $order->get_billing_phone(); ?></p>
							</div>
						<?php endif; ?>
						<?php 
							$order_data = $order->get_data();
							if($order_data['meta_data'][0]->__get('value')) :?>
							<div class="flex space-x-2">
								<label class="font-medium"><?php esc_html_e( 'NIF: ', 'woocommerce' ); ?></label>
								<p><?php echo $order_data['meta_data'][0]->__get('value'); ?></p>
							</div>
						<?php endif; ?>
					</div>
				</div>
				<div class="bg-white rounded-lg p-4">
					<h4 class=" pb-2 mb-4 uppercase text-sm text-gray-400 font-medium tracking-wide"><?php esc_html_e( 'Detalhes de Envio', 'woocommerce' ); ?></h4>
					<div class="text-sm">
						<?php if($order->get_shipping_first_name() || $order->get_shipping_last_name()) :?>
							<div class="flex space-x-2">
								<label class="font-medium"><?php esc_html_e( 'Nome: ', 'woocommerce' ); ?></label>
								<p><?php echo $order->get_shipping_first_name() .' '.$order->get_shipping_last_name(); ?></p>
							</div>
						<?php endif; ?>

						<?php if($order->get_shipping_address_1() || $order->get_shipping_address_2()) :?>
							<div class="flex space-x-2">
								<label class="font-medium"><?php esc_html_e( 'Morada: ', 'woocommerce' ); ?></label>
								<p><?php echo $order->get_shipping_address_1() .' '.$order->get_shipping_address_2(); ?></p>
							</div>
						<?php endif; ?>

						<?php if($order->get_shipping_city()) :?>
							<div class="flex space-x-2">
								<label class="font-medium"><?php esc_html_e( 'Cidade: ', 'woocommerce' ); ?></label>
								<p><?php echo $order->get_shipping_city(); ?></p>
							</div>
						<?php endif; ?>

						<?php if($order->get_shipping_postcode()) :?>
							<div class="flex space-x-2">
								<label class="font-medium"><?php esc_html_e( 'Código Postal: ', 'woocommerce' ); ?></label>
								<p><?php echo $order->get_shipping_postcode(); ?></p>
							</div>
						<?php endif; ?>

						<?php if($order->get_shipping_phone()) :?>
							<div class="flex space-x-2">
								<label class="font-medium"><?php esc_html_e( 'Telemóvel: ', 'woocommerce' ); ?></label>
								<p><?php echo $order->get_shipping_phone(); ?></p>
							</div>
						<?php endif; ?>
					</div>
				</div>
			</div>
			<div class="flex justify-center my-4 flex-col">
				<h3 class="font-medium text-lg tracking-wide uppercase my-4">O que vai acontecer a seguir?</h3>
				<div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
					<div class="bg-white rounded-lg p-4">
						<h4 class="pb-2 mb-4 uppercase text-sm text-gray-400 font-medium tracking-wide"><?php esc_html_e( 'Confirmação da Encomenda', 'woocommerce' ); ?></h4>
						<p class="text-sm">Um email de confirmação com os detalhes do seu pedido foi enviado para o endereço de email fornecido.</p>
					</div>
					<div class="bg-white rounded-lg p-4">
						<h4 class="pb-2 mb-4 uppercase text-sm text-gray-400 font-medium tracking-wide"><?php esc_html_e( 'Envio da Encomenda', 'woocommerce' ); ?></h4>
						<p class="text-sm">Assim que o pedido for despachado, receberá outro e-mail a notificar.</p>
					</div>
					<div class="bg-white rounded-lg p-4">
						<h4 class="pb-2 mb-4 uppercase text-sm text-gray-400 font-medium tracking-wide"><?php esc_html_e( 'Entrega', 'woocommerce' ); ?></h4>
						<p class="text-sm">Irá receber a sua encomenda muito em breve. A entrega requer uma assinatura. Certifique-se de que estará disponível</p>
					</div>
				</div>
			</div>

		<?php endif; ?>


		<div class="bg-white rounded-lg p-4">
			<?php 
			// wc_get_template( 'checkout/review-order.php', $order->get_id() );
			do_action( 'woocommerce_thankyou', $order->get_id() ); 
			?>
		</div>

	<?php else : ?>

		<p class="woocommerce-notice woocommerce-notice--success woocommerce-thankyou-order-received"><?php echo apply_filters( 'woocommerce_thankyou_order_received_text', esc_html__( 'Thank you. Your order has been received.', 'woocommerce' ), null ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>

	<?php endif; ?>

</div>
