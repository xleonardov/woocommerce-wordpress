<?php
/**
 * Single Product Image
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/product-image.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.5.1
 */

defined('ABSPATH') || exit;

// Note: `wc_get_gallery_image_html` was added in WC 3.3.2 and did not exist prior. This check protects against theme overrides being used on older versions of WC.
if (!function_exists('wc_get_gallery_image_html')) {
    return;
}
$icons = new Icons();
global $product;

$gallery = $product->get_gallery_image_ids();

$columns = apply_filters('woocommerce_product_thumbnails_columns', 1);
$post_thumbnail_id = $product->get_image_id();
$wrapper_classes = apply_filters(
    'woocommerce_single_product_image_gallery_classes',
    array(
    'woocommerce-product-gallery',
    'woocommerce-product-gallery--' . ($post_thumbnail_id ? 'with-images' : 'without-images'),
    'woocommerce-product-gallery--columns-' . absint(2),
    'images',
    )
);
?>

<div class="<?php echo esc_attr(implode(' ', array_map('sanitize_html_class', $wrapper_classes))); ?>">
  <div class="swiper product-images w-[400px]">
    <div class="swiper-wrapper">
      <?php foreach ($gallery as $image) {
            $url = wp_get_attachment_image_url($image, 'full');
            ?>
        <div class="swiper-slide woocommerce-product-gallery__trigger">
          <img src="<?php echo $url ?>" alt="<?php echo $product->get_name() ?>" width="400" />
        </div>
      <?php } ?>
    </div>
    <?php if (count($gallery) > 1) { ?>

      <button
        class="swiper-button-prev hidden opacity-50 text-primary transition-all duration-300 hover:shadow-btn_hover  hover:opacity-100  rounded-full w-9 h-9 justify-center items-center"
        type="button" aria-label="Previous slide" aria-controls="splide01-track">
        <svg width="16" viewBox="0 0 10 17" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path
            d="M10 14.2915L3.81916 8.09717L10 1.90284L8.09717 4.87651e-06L1.06182e-06 8.09717L8.09717 16.1943L10 14.2915Z"
            fill="currentColor" />
        </svg>
      </button>
      <button
        class="swiper-button-next hidden opacity-50 text-primary transition-all duration-300 hover:shadow-btn_hover  hover:opacity-100  rounded-full w-9 h-9 justify-center items-center"
        type="button" aria-label="Next slide" aria-controls="splide01-track">
        <svg width="16" viewBox="0 0 11 17" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path
            d="M0.180054 2.7085L6.36089 8.90283L0.180053 15.0972L2.08289 17L10.1801 8.90283L2.08289 0.805663L0.180054 2.7085Z"
            fill="currentColor" />
        </svg>
      </button>
    <?php } ?>
    <div class="relative ">
      <div class="swiper-pagination bottom-0"></div>
    </div>
  </div>
  <div class="flex-viewport" style="display: none;">
    <figure class="woocommerce-product-gallery__wrapper"
      style="width: 400%; transition-duration: 0s; transform: translate3d(0px, 0px, 0px);">
      <?php foreach ($gallery as $key => $image) {
            $url = wp_get_attachment_image_url($image, 'full');
            ?>
        <div data-thumb="<?php echo $url ?>" data-thumb-alt="<?php echo $product->get_name() ?>"
          class="woocommerce-product-gallery__image flex-active-slide" style="">
          <a href="<?php echo $url ?>">
            <img width="300" height="427" src="<?php echo $url ?>" class="" alt="" decoding="async" loading="lazy"
              title="<?php echo $product->get_name() . ' - ' . ($key + 1) ?>" data-caption="<?php echo $product->get_name() ?>"
              data-src="<?php echo $url ?>" data-large_image="<?php echo $url ?>" data-large_image_width="1800"
              data-large_image_height="2560" draggable="false">
          </a>
        </div>
        <div class="swiper-slide woocommerce-product-gallery__trigger">
          <img src="<?php echo $url ?>" alt="<?php echo $product->get_name() ?>" />
        </div>
      <?php } ?>
    </figure>
  </div>
</div>

<script>
  var swiper = new Swiper(".product-images", {
    loop:  <?php echo count($gallery) > 1 ? 'true' : 'false' ?>,
    navigation: {
      nextEl: ".swiper-button-next",
      prevEl: ".swiper-button-prev",
    },
    pagination: {
      el: '.swiper-pagination',
      clickable: true,
    },
  });
</script>
