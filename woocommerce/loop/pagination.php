<?php
/**
 * Pagination - Show numbered pagination for catalog pages
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/pagination.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.3.1
 */

if (!defined('ABSPATH')) {
	exit;
}

$total = isset($total) ? $total : wc_get_loop_prop('total_pages');
$current = isset($current) ? $current : wc_get_loop_prop('current_page');
$base = isset($base) ? $base : esc_url_raw(str_replace(999999999, '%#%', remove_query_arg('add-to-cart', get_pagenum_link(999999999, false))));
$format = isset($format) ? $format : '';
$category = isset($category) ? $category : '';

if ($total <= 1) {
	return;
}
?>

<div class="flex justify-center items-center pt-8">
	<button id="load-more-products" data-taxonomy="<?= $category ?>" class="btn btn-primary">Carregar
		mais produtos</button>
</div>

<?php /*
 <nav class="flex items-center justify-center pt-8">
 <?php
 $paginate_links =  paginate_links(
 apply_filters( 'woocommerce_pagination_args',
 array(
 'base'         => $base,
 'format'       => $format,
 'add_args'     => false,            
 'current'      => max( 1, $current ),
 'total'        => $total,
 'prev_text' 	 => '<div class="pagination_prev"></div>',
 'next_text' 	 => '<div class="pagination_next"></div>',
 'type'         => 'array',
 'end_size'     => 3,
 'mid_size'     => 3,
 ) 
 )
 );
 if ( is_array( $paginate_links ) ) { ?>
 <ul class="pagination flex flex-row space-x-4">
 <?php foreach ($paginate_links as $paginate_link) { ?>
 <li class="" >
 <?php
 $class = 'h-10 w-10 page-link flex justify-center items-center rounded relative ' ;
 (!str_contains($paginate_link, 'class="next') && !str_contains($paginate_link, 'class="prev')) ? $class .=' bg-white hover:text-white hover:bg-secondary transition-all duration-200' : '';
 $paginate_link = str_replace( 'page-numbers', $class, $paginate_link );
 echo wp_kses_post($paginate_link)
 ?>
 </li>   
 <?php } ?>  
 </ul>
 <?php }?>
 </nav>
 */
?>