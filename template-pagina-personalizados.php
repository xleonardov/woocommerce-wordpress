<?php
// Template Name: Personalizados

get_header(); ?>


<div class="w-full">
  <?php if (!is_front_page()): ?>
    <section class="px-4 md:px-6 font-roboto text-sm my-4 md:my-8">
      <div class="border-t border-b border-gray-400 py-2">
        <?php echo do_shortcode(' [wpseo_breadcrumb] '); ?>
      </div>
    </section>
  <?php endif; ?>
  <section class="w-full px-4 md:px-6 mx-auto my-4 md:my-8 ">
    <div class="md:py-20 h-full">
      <?php echo do_shortcode('[personalizados]') ?>
    </div>
  </section>
</div>
<?php get_footer(); ?>