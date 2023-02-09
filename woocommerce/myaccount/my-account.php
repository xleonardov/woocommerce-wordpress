<?php
/**
 * My Account page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/my-account.php.
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

defined( 'ABSPATH' ) || exit;

?>
<div class="grid grid-cols-1 lg:grid-cols-[minmax(0,374px)_1fr] gap-4 my-4">
	<div class="">
		<div class="bg-white rounded-lg p-4 lg:sticky lg:top-[96px]">
			<?php do_action( 'woocommerce_account_navigation' ); ?>
		</div>
	</div>

	<div class="">
		<div class="woocommerce-MyAccount-content bg-white rounded-lg p-4">
			<?php do_action( 'woocommerce_account_content' ); ?>
		</div>
	</div>
</div>