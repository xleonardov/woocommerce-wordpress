<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/archive-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.4.0
 */

defined('ABSPATH') || exit;

global $wp_query;

$term = get_queried_object();

get_header('shop');

$icons = new Icons();

/**
 * Hook: woocommerce_before_main_content.
 *
 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
 * @hooked woocommerce_breadcrumb - 20
 * @hooked WC_Structured_Data::generate_website_data() - 30
 */
do_action('woocommerce_before_main_content');

?>
<div class="relative w-full pb-16">
	<section class="">
		<div class=" flex justify-between border-b border-gray-300 py-4 items-center">
			<div class="flex flex-col ">
				<h3 class="font-roboto uppercase text-sm text-gray-400">
					parent cat
				</h3>
				<div class="flex flex-col md:flex-row items-baseline gap-0 md:gap-4">
					<h1 class="uppercase text-left text-xl md:text-3xl font-roboto font-bold flex flex-col">
						<?php if (apply_filters('woocommerce_show_page_title', true)): ?>
							<?php woocommerce_page_title(); ?>
						<?php endif; ?>
					</h1>
					<?php
					/**
					 * Hook: woocommerce_archive_description.
					 *
					 * @hooked woocommerce_taxonomy_archive_description - 10
					 * @hooked woocommerce_product_archive_description - 10
					 */
					do_action('woocommerce_archive_description');
					?>

					<?php
					$args = array(
						'total' => wc_get_loop_prop('total'),
						'per_page' => wc_get_loop_prop('per_page'),
						'current' => wc_get_loop_prop('current_page'),
					);
					wc_get_template('loop/result-count.php', $args);
					?>

				</div>
			</div>
			<button class="flex items-center self-end gap-2 text-2xl flex-col md:flex-row">
				<?= $icons->get_icon('IoFilterSharp') ?>
				<div class="font-roboto uppercase text-xs md:text-sm font-medium ">
					Filtros
				</div>
			</button>
		</div>
	</section>

	<?php
	if (woocommerce_product_loop()) {

		/**
		 * Hook: woocommerce_before_shop_loop.
		 *
		 * @hooked woocommerce_output_all_notices - 10
		 * @hooked woocommerce_result_count - 20
		 * @hooked woocommerce_catalog_ordering - 30
		 */

		// do_action( 'woocommerce_before_shop_loop' );
		echo '<div class="woocommerce-notices-wrapper">';
		wc_print_notices();
		echo '</div>';
		?>

		<div class="skeleton-inject-classes hidden sr-only animate-pulse text-gray-200 h-2.5 bg-gray-300 w-48"></div>
		<div class="skeleton-inject-classes hidden sr-only text-gray-200 bg-gray-200 w-20 h-[488px]"></div>
		<div class="skeleton-inject-classes hidden sr-only w-4"></div>
		<div class="skeleton-inject-classes hidden sr-only w-32 grid-cols-[24px_auto] gap-4 pb-2 mb-4 mt-2"></div>

		<div class="grid grid-cols-1  gap-4 auto-rows-min mb-16">
			<div class="flex space-y-4 flex-col">
				<div
					class="bg-white rounded-lg p-4 lg:justify-between lg:space-x-4 items-center lg:h-14 grid grid-cols-2 gap-4 lg:flex lg:grid-cols-none">
					<div>
						<div
							class="open-filters bg-bodyBackground px-4 py-2 text-center rounded-lg uppercase tracking-wide flex lg:hidden items-center justify-center space-x-2">
							<?= $icons->get_icon("IoOptions") ?><span>Filtros</span>
						</div>
					</div>
					<div class="block lg:flex lg:space-x-6 items-center">
						<div
							class="bg-bodyBackground relative lg:bg-transparent px-4 py-2 text-center rounded-lg uppercase lg:capitalize tracking-wide flex items-center justify-center space-x-2">
							<?php
							if (!wc_get_loop_prop('is_paginated') || !woocommerce_products_will_display()) {
								return;
							}
							$show_default_orderby = 'date' === apply_filters('woocommerce_default_catalog_orderby', get_option('woocommerce_default_catalog_orderby', 'date'));
							$catalog_orderby_options = apply_filters(
								'woocommerce_catalog_orderby',
								array(
									'date' => __("Mais Recentes", "wlb_theme"),
									'price' => __("Preço mais baixo", "wlb_theme"),
									'price-desc' => __("Preço mais alto", "wlb_theme"),
									'popularity' => __("Relevância", "wlb_theme"),
									// 'menu_order' => __( 'Default sorting', 'woocommerce' ),
									// 'rating'     => __( 'Sort by average rating', 'woocommerce' ),
								)
							);

							$default_orderby = wc_get_loop_prop('is_search') ? 'relevance' : apply_filters('woocommerce_default_catalog_orderby', get_option('woocommerce_default_catalog_orderby', ''));
							// phpcs:disable WordPress.Security.NonceVerification.Recommended
							$orderby = isset($_GET['orderby']) ? wc_clean(wp_unslash($_GET['orderby'])) : $default_orderby;
							// phpcs:enable WordPress.Security.NonceVerification.Recommended
						
							if (wc_get_loop_prop('is_search')) {
								$catalog_orderby_options = array_merge(array('relevance' => __('Relevance', 'woocommerce')), $catalog_orderby_options);

								unset($catalog_orderby_options['date']);
							}

							if (!$show_default_orderby) {
								unset($catalog_orderby_options['date']);
							}

							if (!wc_review_ratings_enabled()) {
								unset($catalog_orderby_options['rating']);
							}

							if (!array_key_exists($orderby, $catalog_orderby_options)) {
								$orderby = current(array_keys($catalog_orderby_options));
							}

							wc_get_template(
								'loop/orderby.php',
								array(
									'catalog_orderby_options' => $catalog_orderby_options,
									'orderby' => $orderby,
									'show_default_orderby' => $show_default_orderby,
									'term' => $term,
								)
							);
							?>
						</div>
					</div>
				</div>
				<section class="">
					<div class="w-full relative" id="products_grid">
						<?php
						woocommerce_product_loop_start();
						if (wc_get_loop_prop('total')) {
							while (have_posts()) {
								the_post();
								do_action('woocommerce_shop_loop');
								wc_get_template_part('content', 'product');
							}
						}
						woocommerce_product_loop_end();

						$args = array('tax_query' => $wp_query->query_vars['tax_query']);
						if (isset($term->slug)) {
							$args['category'] = $term->slug;
						}

						wc_get_template('loop/pagination.php', $args);
						?>
					</div>
				</section>
			</div>
		</div>
	<?php
	} else {
		/**
		 * Hook: woocommerce_no_products_found.
		 *
		 * @hooked wc_no_products_found - 10
		 */
		do_action('woocommerce_no_products_found');
	}

	/**
	 * Hook: woocommerce_after_main_content.
	 *
	 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
	 */
	do_action('woocommerce_after_main_content');

	/**
	 * Hook: woocommerce_sidebar.
	 *
	 * @hooked woocommerce_get_sidebar - 10
	 */
	// do_action( 'woocommerce_sidebar' );
	
	get_footer('shop');
	?>
</div>