<?php
/**
 * Single variation cart button
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.4.0
 */

defined('ABSPATH') || exit;

global $product;

if ($product->is_type('simple')) { //if simple product
    $percentage = round((($product->regular_price - $product->sale_price) / $product->regular_price) * 100);
} else { //if variable product
    $percentage = apply_filters('get_variable_sale_percentage', $product);
}

?>
<div class="woocommerce-variation-add-to-cart variations_button">
    <?php do_action('woocommerce_before_add_to_cart_button'); ?>
    <div class="add_to_cart_with_size">
        <div class="tamanhos">
            <div class="tamanhos_select">
                <div class="flex justify-center items-center w-full relative">
                    <div
                        class="variation-alert bg-red-500 rounded-3xl px-2 py-1 text-white min-w-[160px] uppercase text-xs font-roboto absolute z-[1] -top-10 transition-all duration-200 ease-in-out opacity-0">
                        ⚠️ Selecione um tamanho
                    </div>
                    <?php
                    $available_variations = $product->get_available_variations();
                    $tempArray = [];
                    foreach ($available_variations as $variation) {
                        foreach ($variation['attributes'] as $key => $attribute) {
                            $variation_obj = new WC_Product_variation($variation['variation_id']);
                            array_push($tempArray, array('name' => $attribute, 'stock' => $variation_obj->get_stock_quantity(), 'id' => $variation_obj->get_id()));
                        }
                    }
                    ?>
                    <div class="flex gap-1 items-center justify-center flex-wrap relative">
                        <?php foreach ($tempArray as $variation) { ?>
                            <button
                                class="tamanho_option  <?= $variation['stock'] <= 0 ? 'btn-quad-disabled' : 'btn-quad' ?> "
                                data-value="<?= $variation['id'] ?>" data-name="<?= $variation['name'] ?>"
                                data-stock="<?= $variation['stock'] ?>">
                                <?= $variation['name'] ?></button>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="flex justify-center items-center my-6">
            <button
                class="uppercase font-roboto text-xs text-gray-500 text-center underline cursor-pointer hover:text-gray-900 transition-all duration-200">
                Guia de Tamanhos
            </button>
        </div>
        <div class="flex justify-between  pt-4 border-t-gray-400 border-t">
            <div class="flex w-full justify-between items-center ">
                <div class="flex w-full items-center">
                    <div class="text-secondary price-box uppercase text-center font-roboto text-2xl">
                        <?php echo $product->get_price_html(); ?>
                    </div>
                    <?php if ($percentage && $percentage > 0) { ?>
                        <div>
                            <span class="bg-amarelo text-white rounded-sm text-sm px-1 py-px sm:px-2 sm:py-1 font-roboto">
                                <?= $percentage ?> %
                            </span>
                        </div>
                    <?php } ?>
                </div>
            </div>
            <button type="submit" class="variation_add_to_cart_button btn btn-primary alt">Adicionar</button>
        </div>
    </div>
    <?php do_action('woocommerce_after_add_to_cart_button'); ?>
    <input type="hidden" name="add-to-cart" value="<?php echo absint($product->get_id()); ?>" />
    <input type="hidden" name="product_id" value="<?php echo absint($product->get_id()); ?>" />
    <input type="hidden" name="variation_id" class="variation_id" value="0" />
</div>