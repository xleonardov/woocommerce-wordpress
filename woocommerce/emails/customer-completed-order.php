<?php
/**
 * Customer completed order email
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/customer-completed-order.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates\Emails
 * @version 3.7.0
 */

if (!defined('ABSPATH')) {
	exit;
}

$tracking_code = get_post_meta($order->id, '_wlb_tracking_code', true);
$shipping_company = get_post_meta($order->id, '_wlb_shipping_company', true);

/*
 * @hooked WC_Emails::email_header() Output the email header
 */
do_action('woocommerce_email_header', $email_heading, $email); ?>

<?php /* translators: %s: Customer first name */?>
<p>
	<?php printf(esc_html__('Hi %s,', 'woocommerce'), esc_html($order->get_billing_first_name())); ?>
</p>
<p>
	<?php esc_html_e('We have finished processing your order.', 'woocommerce'); ?>
</p>

<?php
if ($tracking_code) { ?>
	<p style="text-align:center">
		Siga a sua encomenda.
	</p>
	<p style="text-align:center; padding-bottom:1rem;">
		O seu código de rastreio<br />
		<?php if ($shipping_company === 'gls') {
			echo 'GLS';
		} else if ($shipping_company === 'correos_express') {
			echo 'Correos Express';
		} else if ($shipping_company === 'ctt') {
			echo 'CTT';
		} ?>
		<br />
		<b>
			<?= $tracking_code ?>
		</b>
	</p>
	<?php if ($shipping_company === 'ctt') { ?>
		<div style="text-align:center; padding-bottom:2rem;">
			<a href="https://appserver.ctt.pt/CustomerArea/PublicArea_Detail?IsFromPublicArea=true&ObjectCodeInput=<?= $tracking_code ?>&SearchInput=<?= $tracking_code ?>"
				target="_blank" style="
														text-decoration: none;
														padding: 12px 45px;
														width: 210px;
														height: 48px;
														border: none;
														border-radius: 0px;
														background-color: #000;
														border: 1px solid #000;
														font-weight: 500;
														color: #fff;
														font-family: 'Inter', Arial,
															sans-serif;
																							">
				Seguir Encomenda
			</a>
		</div>
	<?php } ?>
<?
}


/*
 * @hooked WC_Emails::order_details() Shows the order details table.
 * @hooked WC_Structured_Data::generate_order_data() Generates structured data.
 * @hooked WC_Structured_Data::output_structured_data() Outputs structured data.
 * @since 2.5.0
 */
do_action('woocommerce_email_order_details', $order, $sent_to_admin, $plain_text, $email);

/*
 * @hooked WC_Emails::order_meta() Shows order meta data.
 */
do_action('woocommerce_email_order_meta', $order, $sent_to_admin, $plain_text, $email);

/*
 * @hooked WC_Emails::customer_details() Shows customer details
 * @hooked WC_Emails::email_address() Shows email address
 */
do_action('woocommerce_email_customer_details', $order, $sent_to_admin, $plain_text, $email);

/**
 * Show user-defined additional content - this is set in each email's settings.
 */
if ($additional_content) {
	echo wp_kses_post(wpautop(wptexturize($additional_content)));
}

/*
 * @hooked WC_Emails::email_footer() Output the email footer
 */
do_action('woocommerce_email_footer', $email);