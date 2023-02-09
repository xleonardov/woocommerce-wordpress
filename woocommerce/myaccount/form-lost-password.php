<?php
/**
 * Lost password form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-lost-password.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.5.2
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_lost_password_form' );
?>

<form method="post" class="woocommerce-ResetPassword lost_reset_password ">

<div class="flex flex-col space-y-4 bg-white rounded-lg p-4 my-4">
	<p><?php echo apply_filters( 'woocommerce_lost_password_message', esc_html__( 'Lost your password? Please enter your username or email address. You will receive a link to create a new password via email.', 'woocommerce' ) ); ?></p><?php // @codingStandardsIgnoreLine ?>
	
	<p class="woocommerce-form-row woocommerce-form-row--first form-row form-row-first">
		<label class="uppercase text-xs font-semibold tracking-wide" for="user_login"><?php esc_html_e( 'Username or email', 'woocommerce' ); ?></label>
		<input class="woocommerce-Input woocommerce-Input--text input-text w-full px-4 py-2 uppercase tracking-wide border text-xs rounded-lg h-10" type="text" name="user_login" id="user_login" autocomplete="username" />
	</p>
	
	<?php do_action( 'woocommerce_lostpassword_form' ); ?>
	
	<p class="woocommerce-form-row form-row">
		<input type="hidden" name="wc_reset_password" value="true" />
		<div class="flex justify-end">
			<div>
				<button type="submit" class="woocommerce-Button button flex items-center justify-center alt wc-forward w-full bg-secondary px-4 py-2 h-12 text-white text-center transition-all duration-200 hover:bg-primary disabled:opacity-20 uppercase tracking-wide rounded-lg text-sm" value="<?php esc_attr_e( 'Reset password', 'woocommerce' ); ?>"><?php esc_html_e( 'Reset password', 'woocommerce' ); ?></button>
			</div>
		</div>
	</p>
	
	<?php wp_nonce_field( 'lost_password', 'woocommerce-lost-password-nonce' ); ?>

</div>

</form>
<?php
do_action( 'woocommerce_after_lost_password_form' );
