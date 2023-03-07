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
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.4.0
 */

defined('ABSPATH') || exit;

global $wp_query;
global $post;

$term = get_queried_object();

get_header('shop');

$icons = new Icons();

$parentcats = null;
$parent = null;
if (isset($term->parent) && $term->parent !== 0) {
    $parent = get_term($term->parent);
}

/**
 * Hook: woocommerce_before_main_content.
 *
 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
 * @hooked woocommerce_breadcrumb - 20
 * @hooked WC_Structured_Data::generate_website_data() - 30
 */
// do_action('woocommerce_before_main_content');

?>
<section class="px-4 md:px-6 font-roboto text-sm mt-4 md:mt-8 ">
  <div class="border-t border-b border-gray-400 py-2 text-gray-400">
    <?php echo do_shortcode(' [wpseo_breadcrumb] '); ?>
  </div>
</section>
<section class="relative w-full px-4 md:px-6">
  <div class="">
    <div class=" flex justify-between border-b border-gray-400 py-4 items-center">
      <div class="flex flex-col ">
        <div class="flex flex-col md:flex-row items-baseline gap-0 md:gap-4">
          <h1 class="uppercase text-left text-xl md:text-3xl font-roboto font-bold flex flex-col">
            <?php if (apply_filters('woocommerce_show_page_title', true)) : ?>
                <?php woocommerce_page_title(); ?>
            <?php endif; ?>
          </h1>
          <?php do_action('woocommerce_archive_description'); ?>
          <?php $args = array(
            'total' => wc_get_loop_prop('total'),
            'per_page' => wc_get_loop_prop('per_page'),
            'current' => wc_get_loop_prop('current_page'),
          );
          wc_get_template('loop/result-count.php', $args);
?>
        </div>
      </div>
      <button class=" open-filters-drawer flex items-center self-end gap-2 text-2xl flex-col md:flex-row">
        <?php echo $icons->get_icon('IoFilterSharp') ?>
        <div class="font-roboto uppercase text-xs md:text-sm font-medium ">Filtros</div>
      </button>
    </div>
  </div>
  <div class="selected_filters">
    <ul id="selected_filters_box" class="flex space-x-2 py-2">
      <?php foreach ($_GET as $k => $item) {
            if ($k === 'orderby' || $k === 'page') {
                continue;
            }
            $terms = explode("_", $item);
            foreach ($terms as $key => $it) { ?>
          <li>
            <button class="btn-pill" data-key="<?php echo $k ?>" data-value="<?php echo $it ?>"
              data-taxonomy="<?php echo property_exists($term, 'slug') ? $term->slug : '' ?>"><?php echo $it ?></button>
          </li>
            <?php } ?>
      <?php } ?>
      <?php if (count($_GET) > 0) { ?>
        <li>
          <button class="clear-all-filters text-xs uppercase underline" type="button" data-taxonomy="homem-manga-curta">
            Limpar Filtros
          </button>
        </li>
      <?php } ?>
    </ul>
  </div>

  <?php
    if (woocommerce_product_loop()) {
        echo '<div class="woocommerce-notices-wrapper">';
        wc_print_notices();
        echo '</div>';
        ?>
    <div class="skeleton-inject-classes hidden sr-only animate-pulse text-gray-200 h-2.5 bg-gray-300 w-48"></div>
    <div class="skeleton-inject-classes hidden sr-only text-gray-200 bg-gray-200 w-20 h-[488px]"></div>
            <?php get_template_part(
                'template-parts/layout/filters-drawer',
                'filters',
                array(
                'term' => $term,
                'category' => $term,
                'tax_query' => $wp_query->query_vars['tax_query']
                )
            ); ?>
    <div class="grid grid-cols-1  gap-4 auto-rows-min mb-16">
      <div class="flex space-y-4 flex-col">
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
        get_template_part(
            'template-parts/layout/filters-drawer',
            'filters',
            array(
            'term' => $term,
            'category' => $term,
            'tax_query' => $wp_query->query_vars['tax_query']
            )
        );
        do_action('woocommerce_no_products_found');
    }

    ?>
</section>
<?php get_footer('shop'); ?>
