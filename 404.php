<?php get_header(); ?>

<div class="w-full">
    <?php if (!is_front_page()) : ?>
        <section class="px-4 md:px-6 font-roboto text-sm my-4 md:my-8">
            <div class="border-t border-b border-gray-400 py-2">
                <?php echo do_shortcode(' [wpseo_breadcrumb] '); ?>
            </div>
        </section>
    <?php endif; ?>
<section class="px-4 md:px-6 pb-8">
  <div class="max-w-screen-sm flex flex-col items-center justify-center mx-auto">
    <h1 class="font-roboto text-2xl md:text-4xl uppercase text-center font-bold my-4 md:my-8"><?php echo __("Página não encontrada", "theme_tailwind");?></h1>
    <p class="font-garamond md:text-lg mb-6 md:mb-10">
      <?php echo __("Alguma coisa correu mal, ou o link estava errado ou a página foi removida.", "theme_tailwind");?> 
    </p>
    <a class="btn btn-primary" href="/"><?php echo __("Voltar ao início", "theme_tailwind");?></a>
  </div>
</section>
</div>

<?php get_footer(); ?>
