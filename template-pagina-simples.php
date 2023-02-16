<?php
// Template Name: Paginas simples

get_header(); ?>

<div class="w-full">
  <section class="px-4 md:px-6 font-roboto text-sm my-4 md:my-8">
    <div class="border-t border-b border-gray-400 py-2">
      <?php echo do_shortcode(' [wpseo_breadcrumb] '); ?>
    </div>
  </section>
  <section class="max-w-screen-md px-4 md:px-6 mx-auto my-4 md:my-8">
    <div class="pagina-simples">
      <h1 class="text-3xl md:text-4xl uppercase font-bold font-roboto">
        <?php echo the_title(); ?>
      </h1>
      <?php echo
        the_content();
        ?>
    </div>
  </section>
</div>
<?php get_footer(); ?>
