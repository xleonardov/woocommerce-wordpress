<?php
/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
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

global $product;
$icons = new Icons();
$id = $product->get_id();


if ($product->get_type() == 'variable') {

    $tempArray = [];
    $available_variations = $product->get_available_variations();
    foreach ($available_variations as $variation) {
        foreach ($variation['attributes'] as $key => $attribute) {
            $variation_obj = new WC_Product_variation($variation['variation_id']);
            array_push($tempArray, array('name' => $attribute, 'stock' => $variation_obj->get_stock_quantity()));
        }
    }

    $sizes = array(
        0 => "XXS",
        1 => "XS",
        2 => "S",
        3 => "M",
        4 => "L",
        5 => "XL",
        6 => "XXL",
        7 => "3XL",
        8 => "4XL",
        9 => "5XL"
    );

    $new_arr = array();

    foreach ($sizes as $size) {
        $key = array_search(strtolower($size), array_column($tempArray, 'name'));
        array_push($new_arr, $tempArray[$key]);
    }
}
// Ensure visibility.
if (empty($product) || !$product->is_visible()) {
    return;
}
$post_thumbnail_id = $product->get_image_id();
$image = wp_get_attachment_image_url($post_thumbnail_id, 'full');
$gallery = $product->get_gallery_image_ids();
?>
<li <?php wc_product_class('col-span-1 border-b group border-gray-400 px-0 pb-4 md:pb-8 relative', $product); ?>>
    <?php
    /**
     * Hook: woocommerce_before_shop_loop_item.
     *
     * @hooked woocommerce_template_loop_product_link_open - 10
     */
    do_action('woocommerce_before_shop_loop_item');

    // /**
    //  * Hook: woocommerce_before_shop_loop_item_title.
    //  *
    //  * @hooked woocommerce_show_product_loop_sale_flash - 10
    //  * @hooked woocommerce_template_loop_product_thumbnail - 10
    //  */
    // do_action( 'woocommerce_before_shop_loop_item_title' );
    
    ?>

    <div class="relative w-full ">
        <div class="overflow-hidden aspect-productImg relative md:mx-6">
            <div
                class="aspect-productImg absolute bg-white w-full  <?php echo (count($gallery)) > 1 ? 'group-hover:opacity-0 transition-all duration-[350ms] ease-in-out' : '' ?>">
                <img src="<?php echo $image ?>" class="image-fill" alt="<?php echo $product->get_name() ?>" />
            </div>
            <?php if ($gallery && count($gallery) > 1) {
                $image_g = wp_get_attachment_image_url($gallery[1], 'full');
                ?>
                <div
                    class="aspect-productImg absolute bg-white w-full opacity-0  group-hover:opacity-100 transition-all duration-[300ms] ease-in-out">
                    <img src="<?php echo $image_g ?>" class="image-fill" alt="<?php echo $product->get_name() ?>" />
                </div>
            <?php } ?>
        </div>
    </div>
    <div class="font-roboto relative z-10">
        <?php if ($product->get_type() == 'variable') { ?>
            <div
                class="absolute w-full bg-white bg-opacity-50 left-0 py-2 justify-center item-center gap-2 -top-3 -translate-y-full hidden lg:flex">
                <?php foreach ($tempArray as $variation) { ?>
                    <div
                        class="text-xs xl:text-sm uppercase font-roboto <?php echo $variation['stock'] === 0 ? 'text-gray-400 line-through' : '' ?>">
                        <?php echo $variation['name'] ?>
                    </div>
                <?php } ?>
            </div>
        <?php } else { ?>
            <div
                class="absolute w-full  bg-white bg-opacity-50 left-0 py-2 justify-center item-center gap-2 -top-3 -translate-y-full hidden lg:flex">
                <div
                    class="text-xs xl:text-sm uppercase font-roboto <?php echo $product->get_stock_quantity() === 0 ? 'text-gray-400 line-through' : '' ?>">
                    <?php echo __('Tamanho único', 'theme-tailwind'); ?>
                </div>
            </div>
        <?php } ?>

        <h3 class="text-sm md:text-base uppercase px-2 text-center mb-2">
            <?php echo $product->get_name() ?>
        </h3>
        <div class="text-center">
            <?php

            /**
             * Hook: woocommerce_after_shop_loop_item_title.
             *
             * @hooked woocommerce_template_loop_rating - 5
             * @hooked woocommerce_template_loop_price - 10
             */
            do_action('woocommerce_after_shop_loop_item_title'); ?>
        </div>
    </div>
    <?php

    /**
     * Hook: woocommerce_after_shop_loop_item.
     *
     * @hooked woocommerce_template_loop_product_link_close - 5
     * @hooked woocommerce_template_loop_add_to_cart - 10
     */
    do_action('woocommerce_after_shop_loop_item');
    ?>

</li>
