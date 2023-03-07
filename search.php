<?php
/**
 * The template for displaying Search Results pages.
 *
 * @package Shape
 * @since   Shape 1.0
 */

get_header(); ?>

<div class="w-full px-4 md:px-6 mx-auto my-4 md:my-8 font-roboto">
  <?php if (have_posts()) : ?>
    <div class=" flex justify-between border-b border-gray-400 py-4 items-center">
      <div class="flex flex-col ">
        <div class="flex flex-col md:flex-row items-baseline gap-0 md:gap-4">
          <h1 class="uppercase text-left text-xl md:text-3xl font-roboto font-bold flex flex-row">
            <?php printf(__('Resultados de pesquisa para:  %s', 'wlb_theme'), '<span>' . get_search_query() . '</span>'); ?>
          </h1>
        </div>
      </div>
    </div>
        <?php /* Start the Loop */?>
    <ul class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 col-span-2 md:col-span-3">
        <?php while (have_posts()):
            the_post(); ?>
            <?php
            if (get_post_type(get_the_ID()) == 'product') {
                wc_get_template_part('content', 'product');
            }
            ?>
        <?php endwhile; ?>
    </ul>
    <nav class="flex items-center justify-center pt-8">
        <?php
        $paginate_links = paginate_links(
            array(
            'prev_text' => '<div class="pagination_prev"><</div>',
            'next_text' => '<div class="pagination_next">></div>',
            'type' => 'array',
            'end_size' => 3,
            'mid_size' => 3,
            )
        );

        if (is_array($paginate_links)) { ?>
        <ul class="pagination flex flex-row space-x-4">
            <?php foreach ($paginate_links as $paginate_link) { ?>
            <li class="">
                <?php
                $class = 'btn-quad ';
                (!str_contains($paginate_link, 'class="next') && !str_contains($paginate_link, 'class="prev')) ? $class .= ' bg-white hover:text-white hover:bg-secondary transition-all duration-200' : '';
                $paginate_link = str_replace('page-numbers', $class, $paginate_link);
                echo wp_kses_post($paginate_link)
                ?>
            </li>
            <?php } ?>
        </ul>
        <?php } ?>
    </nav>
  <?php else: ?>
    <header class="page-header">
      <h1 class="page-title text-left text-2xl lg:text-4xl uppercase tracking-wide">
        <?php printf(__('Resultados de pesquisa para: %s', 'wlb_theme'), '<span>' . get_search_query() . '</span>'); ?>
      </h1>
    </header><!-- .page-header -->
    <div class="my-8">
      <p class="text-left">Nenhum artigo encontrado com a keyword: <b>
          <?php echo get_search_query() ?>
        </b></p>
    </div>
  <?php endif; ?>
</div>


<?php get_footer(); ?>
