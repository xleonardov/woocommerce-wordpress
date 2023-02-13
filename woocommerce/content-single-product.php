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

$collection_gallery = get_field('collection_gallery', $id);
$care = get_field('care', $id);


$careIndex = [
  'A temperatura máxima da água para lavagem da peça é de 40 graus.' => 'cuidados_1.webp',
  'Lavagem à máquina em ciclo delicado com temperatura maxima de 40 graus.' => 'cuidados_2.webp',
  'A temperatura máxima da água para lavagem da peça é de 30 graus.' => 'cuidados_3.webp',
  'Não secar na máquina.' => 'cuidados_4.webp',
  'A peça não suporta branqueamento nem pré-tratamento de nódoas com produtos à base de cloro ou oxigénio. Verifique o rótulo dos produtos de limpeza antes de utilizar.' => 'cuidados_5.webp',
  'Limpeza a seco somente percloroetileno.' => 'cuidados_6.webp',
  'Engomar a média temperatura (até 150 oC).' => 'cuidados_7.webp',
  'Não limpar a seco. Não usar produtos tira-nódoas que contenham solventes.' => 'cuidados_8.webp',
  'Não engomar.' => 'cuidados_9.webp',
  'Lavar à mão.' => 'cuidados_10.webp',
];



/**
 * Hook: woocommerce_before_single_product.
 *
 * @hooked woocommerce_output_all_notices - 10
 */
do_action('woocommerce_before_single_product');

if (post_password_required()) {
  echo get_the_password_form(); // WPCS: XSS ok.
  return;
}
$gamas = wp_get_post_terms($id, array('gamas'), array("fields" => "names"));
?>

<section class="px-4 md:px-6 ">
  <div id="product-<?php the_ID(); ?>" <?php wc_product_class('', $product); ?>>
    <div class="max-w-xl mx-auto  mt-8">
      <div>
        <h2 class="uppercase font-roboto text-sm mb-4 text-gray-400 text-center">
          <?= $gamas ? 'GAMA ' . $gamas[0] : '' ?>
        </h2>
        <h1 class="uppercase font-roboto text-2xl md:text-3xl text-center font-bold">
          <?php echo $product->get_name(); ?>
        </h1>
      </div>
      <div>
        <?php get_template_part('woocommerce/single-product/product-image'); ?>
      </div>

      <?php
      if ($product->get_type() === "simple") {
        get_template_part('woocommerce/single-product/add-to-cart/simple');
      } elseif ($product->get_type() === "variable") {
        get_template_part('woocommerce/single-product/add-to-cart/variable');
      }
      ?>
    </div>
    <div class="mt-8">
      <ul
        class="border-t border-b border-gray-400 py-3 flex flex-col space-y-2 md:space-y-0 md:flex-row md:justify-around font-roboto uppercase">
        <li class="flex items-center space-x-2">
          <span class="text-xl">
            <?= $icons->get_icon('AiFillGift') ?>
          </span>
          <span class="text-sm">
            Envio grátis em compras superiores a 50€
          </span>
        </li>
        <li class="flex items-center space-x-2">
          <span class="text-xl">
            <?= $icons->get_icon('FaShippingFast') ?>
          </span>
          <span class="text-sm">Envios em 24h rápidos e seguros</span>
        </li>
        <li class="flex items-center space-x-2">
          <span class="text-xl">
            <?= $icons->get_icon('RiSecurePaymentFill') ?>
          </span>
          <span class="text-sm">Pagamentos seguros</span>
        </li>
      </ul>
    </div>
    <?php if ($collection_gallery) { ?>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-8 relative mt-8">
        <?php if (count($collection_gallery) == 2): ?>
          <?php foreach ($collection_gallery as $key => $item): ?>
            <div class="w-full aspect-[10/15] relative">
              <img src="<?php echo $item; ?>" class="img-fill" alt="<?php echo $product->get_name(); ?>" />
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <?php foreach ($collection_gallery as $key => $item): ?>
            <div
              class="<?php echo ($key == 0) ? 'md:col-span-2 aspect-[1/1.6] md:aspect-[1.6/1]' : 'col-span-1 aspect-[1/1.6]'; ?> w-full  relative">
              <img src="<?php echo $item; ?>" class="img-fill" alt="<?php echo $product->get_name(); ?>" />
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
    <?php } ?>
    <div>
      <div class="mx-auto max-w-2xl w-full mt-8">
        <ul class="w-full relative">
          <li class="w-full border-b border-gray-400 py-2 px-2 md:px-4">
            <button data-target="acordeao_0_descricao"
              class="btn-acordeao w-full flex justify-between items-center font-roboto uppercase text-xl py-2">
              Descrição do Produto
              <div class="is_open hidden">
                <img width="15" src="<?= get_theme_file_uri('/assets/images/minus.webp') ?>" />
              </div>
              <div class="is_close">
                <img width="15" src="<?= get_theme_file_uri('/assets/images/add.webp') ?>" />
              </div>
            </button>
            <div id="acordeao_0_descricao" class="transition-all duration-300 font-garamond h-0 overflow-hidden">
              <?php echo $product->get_description(); ?>
            </div>
          </li>
          <li class="w-full border-b border-gray-400 py-2 px-2 md:px-4">
            <button data-target="acordeao_1_cuidados"
              class="btn-acordeao w-full flex justify-between items-center font-roboto uppercase text-xl py-2">
              Cuidados
              <div class="is_open hidden">
                <img width="15" src="<?= get_theme_file_uri('/assets/images/minus.webp') ?>" />
              </div>
              <div class="is_close">
                <img width="15" src="<?= get_theme_file_uri('/assets/images/add.webp') ?>" />
              </div>
            </button>
            <div id="acordeao_1_cuidados" class="transition-all duration-300 font-garamond h-0 overflow-hidden">
              <?php if ($care) { ?>
                <ul class="grid grid-cols-1 gap-2 pt-4">
                  <?php foreach ($care as $careItem) { ?>
                    <li class="flex items-start space-x-4 justify-start">
                      <div class="relative w-6 h-6 flex-none">
                        <img width="24" src="<?= get_theme_file_uri('/assets/images/' . $careIndex[$careItem]) ?>" />
                      </div>

                      <span
                        class="font-garamond text-sm border-b border-b-transparent mb-2 font-light transition-all duration-200 leading-loose relative">
                        <?= $careItem ?>
                      </span>
                    </li>
                  <?php } ?>
                </ul>
              <?php } ?>
            </div>
          </li>
        </ul>
      </div>
    </div>
</section>
<section class="px-4 md:px-6 ">
  <?php get_template_part('woocommerce/single-product/related'); ?>
</section>
<?php do_action('woocommerce_after_single_product'); ?>