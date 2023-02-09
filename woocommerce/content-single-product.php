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

defined( 'ABSPATH' ) || exit;

$icons = new Icons();

global $product;
$id = $product->get_id();

$pn = get_field('pn', $id);
$ean = get_field('ean', $id);
$garantia = $product->get_attribute( 'garantia' );
$vinheta = get_field('selo_classe_energetica', $id);

/**
 * Hook: woocommerce_before_single_product.
 *
 * @hooked woocommerce_output_all_notices - 10
 */
do_action( 'woocommerce_before_single_product' );

if ( post_password_required() ) {
	echo get_the_password_form(); // WPCS: XSS ok.
	return;
}
?>

<div id="product-<?php the_ID(); ?>" <?php wc_product_class( '', $product ); ?>>
	<div class="main-info w-full hidden lg:flex flex-row space-x-4 my-4 text-sm rounded-lg px-4 py-6 bg-white mb-6">
		<div class="uppercase"><label class="font-semibold"><?= _e( "Part-Number", "wlb_theme") ?>:</label> <?= $pn ?></div>
		<div class="text-gray-300">|</div>
		<div class="uppercase"><label class="font-semibold"><?= _e( "Código EAN", "wlb_theme") ?>:</label> <?= $ean ?></div>
		<div class="text-gray-300">|</div>
		<div class="uppercase"><label class="font-semibold"><?= _e( "Garantia", "wlb_theme") ?>:</label> <?= $garantia ?></div>
	</div>
	<div class="grid grid-cols-1 lg:grid-cols-[3fr_2fr] gap-6">
		<div class="grid gap-6">
			<div class="bg-white rounded-lg p-4">
				<?php do_action( 'woocommerce_before_single_product_summary' ); ?>
				<div class="mt-4 block lg:hidden space-y-4">
				<?php get_template_part( 'woocommerce/single-product/sale-flash' ); ?>
				<h1 class="text-3xl tracking-wide"><?= $product->get_name(); ?></h1>
				<div class="flex w-full justify-between items-center">
					<p class="text-3xl text-secondary font-semibold"><?php echo $product->get_price_html(); ?></p>
					<?php if($vinheta){ ?>
						<img class="w-14" src="<?= $vinheta['url'] ?>" />
					<?php } ?>
				</div>
				<?php 
					if($product->is_in_stock()){
						?>
							<div class="stock text-green-500 flex items-center space-x-2">
								<?= $icons->get_icon('BsCheckCircleFill');?> <lable class="font-medium tracking-wide"><?= _e( "Em stock", "wlb_theme") ?></lable>
							</div>
						<?php
					}else{
					?>
							<div class="stock text-red-500 flex items-center space-x-2">
								<?= $icons->get_icon('BsFillXCircleFill');?> <lable class="font-medium tracking-wide"><?= _e( "Esgotado", "wlb_theme") ?></lable>
							</div>
					<?php
					}
				?>
				<?php get_template_part( 'woocommerce/single-product/add-to-cart/simple' ); ?>
				<div class="flex flex-col space-y-2">
					<div class="uppercase text-xs"><label class="font-semibold"><?= _e( "Part-Number", "wlb_theme") ?>:</label> <?= $pn ?></div>
					<div class="uppercase text-xs"><label class="font-semibold"><?= _e( "Código EAN", "wlb_theme") ?>:</label> <?= $ean ?></div>
					<div class="uppercase text-xs"><label class="font-semibold"><?= _e( "Garantia", "wlb_theme") ?>:</label> <?= $garantia ?></div>
				</div>
				<?php get_template_part( 'woocommerce/single-product/short-description' ); ?>
				</div>
			</div>
			<div class="bg-white rounded-lg p-4">
				<?php get_template_part( 'woocommerce/single-product/tabs/tabs' ); ?>
			</div>
		</div>
		<div class="flex flex-col">
			<div class="hidden lg:grid gap-6 bg-white rounded-lg p-4 sticky top-[97px] transition-top duration-300 ease-out ">
				<?php get_template_part( 'woocommerce/single-product/sale-flash' ); ?>
				<h1 class="text-3xl tracking-wide"><?= $product->get_name(); ?></h1>
				<div class="flex w-full justify-between items-center">
					<p class="text-3xl text-secondary font-semibold"><?php echo $product->get_price_html(); ?></p>
					<?php if($vinheta){ ?>
						<img class="w-14" src="<?= $vinheta['url'] ?>" />
					<?php } ?>
				</div>
				<?php 
					if($product->is_in_stock()){
						?>
							<div class="stock text-green-500 flex items-center space-x-2">
								<?= $icons->get_icon('BsCheckCircleFill');?> <lable class="font-medium tracking-wide"><?= _e( "Em stock", "wlb_theme") ?></lable>
							</div>
						<?php
					}else{
					?>
							<div class="stock text-red-500 flex items-center space-x-2">
								<?= $icons->get_icon('BsFillXCircleFill');?> <lable class="font-medium tracking-wide"><?= _e( "Esgotado", "wlb_theme") ?></lable>
							</div>
					<?php
					}
				?>
				<?php get_template_part( 'woocommerce/single-product/add-to-cart/simple' ); ?>
				<?php get_template_part( 'woocommerce/single-product/short-description' ); ?>
			</div>
	</div>
</div>
<?php get_template_part( 'woocommerce/single-product/related' ); ?>

<?php do_action( 'woocommerce_after_single_product' ); ?>
