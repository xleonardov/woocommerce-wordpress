<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.6.0
 */

defined('ABSPATH') || exit;

$icons = new Icons();

global $product;
$id = $product->get_id();

/**
 * Hook: woocommerce_before_single_product.
 *
 * @hooked woocommerce_output_all_notices - 10
 */
do_action('woocommerce_before_single_product');

if (post_password_required() ) {
    echo get_the_password_form(); // WPCS: XSS ok.
    return;
}
?>

<section class="px-4 md:px-6 bg-red-100">
  <div id="product-<?php the_ID(); ?>" <?php wc_product_class('', $product); ?> >
    <div class="max-w-xl mx-auto bg-green-100">
    <div>
      <h2 class="uppercase font-roboto text-xs text-gray-400 text-center"> INSERIR GAMA </h2>
      <h1 class="uppercase font-roboto text-2xl md:text-3xl text-center font-bold"><?php echo $product->get_name(); ?></h1>
    </div>
    <div>
      <?php do_action('woocommerce_before_single_product_summary'); ?>
    </div>
    <div>
      <?php do_action('woocommerce_single_product_summary'); ?>
    </div>
  </div>
</section>
<?php get_template_part('woocommerce/single-product/related'); ?>

<?php do_action('woocommerce_after_single_product'); ?>
