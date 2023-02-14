<?php
global $woocommerce;
?>

<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
  <?php get_template_part('template-parts/layout/floating-cart'); ?>

  <?php get_template_part('template-parts/layout/mega-menu'); ?>

  <header class="bg-white shadow sticky top-0 z-20">
    <div class="px-4 md:px-6 py-3 grid grid-cols-3 items-center">
      <div class="flex-none">
        <button class="hamburger"><img
            src="<?php echo get_theme_file_uri('/assets/images/hamburger.webp'); ?>" /></button>
      </div>
      <div class="flex-1 flex justify-center">
        <?php the_custom_logo(); ?>
      </div>
      <div class="flex gap-4 md:gap-6 justify-self-end">
        <button class="w-4 h-4">
          <img src="<?php echo get_theme_file_uri('assets/images/lupa.webp'); ?>" />
        </button>
        <button class="w-4 h-4">
          <img src="<?php echo get_theme_file_uri('assets/images/my-account.webp'); ?>" />
        </button>
        <div class="shopping-bag icons w-6 h-6 mr-[10px]">
          <button class="open-floating-cart shop-cart relative cursor-pointer">
            <div class="w-4 h-4">
              <img src="<?php echo get_theme_file_uri('assets/images/bag.webp'); ?>" />
            </div>
            <span
              class="shop-cart-counter absolute top-[-10px] right-[-10px] bg-primary text-white p-[10px] text-xs rounded-full w-4 h-4 flex justify-center items-center">
              <?php echo $woocommerce->cart->cart_contents_count ?>
            </span>
          </button>
        </div>
      </div>
    </div>
  </header>

  <?php do_action('show_notices'); ?>
