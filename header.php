<?php
global $woocommerce;
$icons = new Icons();
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
  <div id="search-modal">
    <div class="fixed top-0 left-0 w-full h-screen bg-black bg-opacity-90 z-[99999]">
      <div class="w-full h-full flex justify-center items-center">
        <div class="p-4 md:p-8  absolute top-4 right-4">
          <button class="self-start flex-none text-white text-xl lg:text-3xl close-search">
            <?php echo $icons->get_icon('Cross') ?>
          </button>
        </div>
        <form class="px-8 lg:px-4 flex w-full justify-center items-center" action="/">
          <div class="relative w-full max-w-[700px] ">
            <label htmlFor="s" class="text-white text-xl lg:text-2xl font-roboto">
              Diga-nos o que procura...
            </label>
            <div class="flex w-full space-x-4 items-center">
              <input
                class="border-b border-b-white py-4 px-0 text-2xl lg:text-6xl bg-transparent font-roboto w-full outline-none text-white placeholder:text-white"
                name="s" />
              <button class=" text-white text-3xl lg:text-6xl" type="submit">
                <?php echo $icons->get_icon('FiSearch') ?>
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>

  <header class="bg-white sticky top-0 z-20">
    <div class="px-4 md:px-6 py-3 grid grid-cols-3 items-center">
      <div class="flex-none">
        <button class="hamburger scale-75 md:scale-100"><img
            src="<?php echo get_theme_file_uri('/assets/images/hamburger.webp'); ?>" /></button>
      </div>
      <div class="flex-1 flex justify-center">
        <?php the_custom_logo(); ?>
      </div>
      <div class="flex gap-4 md:gap-6 justify-self-end">
        <button class="w-4 h-4" id="trigger-search">
          <img src="<?php echo get_theme_file_uri('assets/images/lupa.webp'); ?>" />
        </button>
        <a href="<?php echo get_permalink(get_option('woocommerce_myaccount_page_id')) ?>" class="w-4 h-4">
          <img src="<?php echo get_theme_file_uri('assets/images/my-account.webp'); ?>" />
        </a>
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
