<?php
/**
 * Login Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-nonegin.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 4.1.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

do_action('woocommerce_before_customer_login_form'); ?>

<div class="w-full">
    <?php if (!is_front_page()): ?>
        <section class="font-roboto text-sm my-4 md:my-8">
            <div class="border-t border-b border-gray-400 py-2">
                <?php echo do_shortcode(' [wpseo_breadcrumb] '); ?>
            </div>
        </section>
    <?php endif; ?>
</div>
<div class="py-4 md:pt-8 max-w-screen-lg mx-auto">
    <?php if ('yes' === get_option('woocommerce_enable_myaccount_registration')): ?>
        <div class="u-columns col2-set grid grid-cols-1 lg:grid-cols-2 gap-16 my-12 font-roboto" id="customer_login">
            <div class="u-column1 col-1">
            <?php endif; ?>
            <div class="bg-white rounded-none p-0">
                <h2 class="uppercase text-left text-xl md:text-3xl font-semibold tracking-wide mb-4">
                    <?php esc_html_e('Login', 'woocommerce'); ?>
                </h2>
                <form class="woocommerce-form woocommerce-form-login login" method="post">
                    <?php do_action('woocommerce_login_form_start'); ?>
                    <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                        <label class="uppercase text-sm font-normal tracking-wide" for="username">
                            <?php esc_html_e('Username or email address', 'woocommerce'); ?>&nbsp;<span
                                class="required">*</span>
                        </label>
                        <input type="text"
                            class="woocommerce-Input woocommerce-Input--text input-text w-full px-4 py-2  tracking-wide border text-sm rounded-none h-10"
                            name="username" id="username" autocomplete="username"
                            value="<?php echo (!empty($_POST['username'])) ? esc_attr(wp_unslash($_POST['username'])) : ''; ?>" /><?php
                                   // @codingStandardsIgnoreLine ?>
                    </p>
                    <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                        <label class="uppercase text-sm font-normal tracking-wide" for="password">
                            <?php esc_html_e('Password', 'woocommerce'); ?>&nbsp;<span class="required">*</span>
                        </label>
                        <input
                            class="woocommerce-Input woocommerce-Input--text input-text w-full px-4 py-2  tracking-wide border text-sm rounded-none h-10"
                            type="password" name="password" id="password" autocomplete="current-password" />
                    </p>
                    <?php do_action('woocommerce_login_form'); ?>
                    <div class="form-row pt-4">
                        <div class="flex justify-between items-center mb-4">
                            <label
                                class="woocommerce-form__label woocommerce-form__label-for-checkbox woocommerce-form-login__rememberme">
                                <input class="woocommerce-form__input woocommerce-form__input-checkbox "
                                    name="rememberme" type="checkbox" id="rememberme" value="forever" /> <span
                                    class="uppercase text-sm">
                                    <?php esc_html_e('Remember me', 'woocommerce'); ?>
                                </span>
                            </label>
                            <p class="woocommerce-LostPassword lost_password">
                                <a class="underline hover:text-secondary transition-all duration-200 uppercase text-sm"
                                    href="<?php echo esc_url(wp_lostpassword_url()); ?>"><?php esc_html_e('Lost your password?', 'woocommerce'); ?></a>
                            </p>
                        </div>
                        <?php wp_nonce_field('woocommerce-login', 'woocommerce-login-nonce'); ?>
                        <button type="submit"
                            class="woocommerce-button button woocommerce-form-login__submit alt wc-forward btn btn-primary"
                            name="login" value="<?php esc_attr_e('Log in', 'woocommerce'); ?>"><?php esc_html_e('Log in', 'woocommerce'); ?></button>
                    </div>
                    <?php do_action('woocommerce_login_form_end'); ?>
                </form>
            </div>
            <?php if ('yes' === get_option('woocommerce_enable_myaccount_registration')): ?>
            </div>
            <div class="u-column2 col-2">
                <div class="bg-white rounded-none p-0">
                    <h2 class="uppercase text-left text-xl md:text-3xl font-semibold tracking-wide mb-4">
                        <?php esc_html_e('Register', 'woocommerce'); ?>
                    </h2>
                    <form method="post" class="woocommerce-form woocommerce-form-register register font-roboto" <?php
                    do_action('woocommerce_register_form_tag'); ?>>
                        <?php do_action('woocommerce_register_form_start'); ?>
                        <?php if ('no' === get_option('woocommerce_registration_generate_username')): ?>
                            <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                                <label class="uppercase text-sm font-normal tracking-wide" for="reg_username">
                                    <?php esc_html_e('Username', 'woocommerce'); ?>&nbsp;<span class="required">*</span>
                                </label>
                                <input type="text"
                                    class="woocommerce-Input woocommerce-Input--text input-text w-full px-4 py-2  tracking-wide border text-sm rounded-none h-10"
                                    name="username" id="reg_username" autocomplete="username"
                                    value="<?php echo (!empty($_POST['username'])) ? esc_attr(wp_unslash($_POST['username'])) : ''; ?>" /><?php
                                           // @codingStandardsIgnoreLine ?>
                            </p>
                        <?php endif; ?>
                        <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                            <label class="uppercase text-sm font-normal tracking-wide" for="reg_email">
                                <?php esc_html_e('Email address', 'woocommerce'); ?>&nbsp;<span class="required">*</span>
                            </label>
                            <input type="email"
                                class="woocommerce-Input woocommerce-Input--text input-text w-full px-4 py-2 tracking-wide border text-sm rounded-none h-10"
                                name="email" id="reg_email" autocomplete="email"
                                value="<?php echo (!empty($_POST['email'])) ? esc_attr(wp_unslash($_POST['email'])) : ''; ?>" /><?php
                                       // @codingStandardsIgnoreLine ?>
                        </p>
                        <div class="my-4">
                            <?php if ('no' === get_option('woocommerce_registration_generate_password')): ?>
                                <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                                    <label class="uppercase text-sm font-normal tracking-wide" for="reg_password">
                                        <?php esc_html_e('Password', 'woocommerce'); ?>&nbsp;<span class="required">*</span>
                                    </label>
                                    <input type="password"
                                        class="woocommerce-Input woocommerce-Input--text input-text w-full px-4 py-2 tracking-wide border text-sm rounded-none h-10"
                                        name="password" id="reg_password" autocomplete="new-password" />
                                </p>
                            <?php else: ?>
                                <p class="text-sm">
                                    <?php esc_html_e('A link to set a new password will be sent to your email address.', 'woocommerce'); ?>
                                </p>
                            <?php endif; ?>
                            <div class="text-sm">
                                <?php do_action('woocommerce_register_form'); ?>
                            </div>
                        </div>
                        <p class="woocommerce-form-row form-row ">
                            <?php wp_nonce_field('woocommerce-register', 'woocommerce-register-nonce'); ?>
                            <button type="submit"
                                class="woocommerce-Button woocommerce-button button woocommerce-form-register__submit btn btn-outline flex justify-center"
                                name="register" value="<?php esc_attr_e('Register', 'woocommerce'); ?>"><?php
                                   esc_html_e('Register', 'woocommerce'); ?></button>
                        </p>
                        <?php do_action('woocommerce_register_form_end'); ?>
                    </form>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php do_action('woocommerce_after_customer_login_form'); ?>
</div>