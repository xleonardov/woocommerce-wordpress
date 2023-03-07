<?php
/**
 * Lost password confirmation text.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/lost-password-confirmation.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.9.0
 */

defined('ABSPATH') || exit;

?>
<div class="w-full">
    <?php if (!is_front_page()) : ?>
        <section class="font-roboto text-sm my-4 md:my-8">
            <div class="border-t border-b border-gray-400 py-2">
                <?php echo do_shortcode(' [wpseo_breadcrumb] '); ?>
            </div>
        </section>
    <?php endif; ?>
</div>
<div class="text-black  py-2 rounded-none my-4">
  <?php
    wc_print_notice(esc_html__('Password reset email has been sent.', 'woocommerce'));
    ?>
</div>

<?php do_action('woocommerce_before_lost_password_confirmation_message'); ?>

<div class="bg-white rounded-none font-roboto my-4 mx-auto max-w-screen-sm">
  <p>
    <?php echo esc_html(apply_filters('woocommerce_lost_password_confirmation_message', esc_html__('A password reset email has been sent to the email address on file for your account, but may take several minutes to show up in your inbox. Please wait at least 10 minutes before attempting another reset.', 'woocommerce'))); ?>
  </p>
</div>

<?php do_action('woocommerce_after_lost_password_confirmation_message'); ?>
