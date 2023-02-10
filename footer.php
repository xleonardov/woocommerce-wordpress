<?php 
$top_area = get_field("top_area", "option");
?>
<section class="py-4 md:py-8 px-4 md:px-6 mt-4">
  <div class="border-t border-gray-400"></div>
  <?php if(have_rows('top_area', 'option')) : ?>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 py-4 md:py-8 leading-tight">
        <?php while(have_rows('top_area', 'option')): 
              the_row(); 
              $titulo = get_sub_field('titulo');
              $conteudo = get_sub_field('conteudo');
            ?>
          <div>
            <h3 class="font-roboto text-lg uppercase mb-3"><?php echo $titulo; ?></h3>
            <div><?php echo $conteudo ?></div>
          </div>
        <?php endwhile; ?>
    </div>
  <?php endif; ?>
  <div class="w-full border-t border-gray-400 grid grid-cols-1 space-y-4 md:space-y-0 md:grid-cols-3 items-center pt-4">
    <div class="place-self-center md:place-self-start">
      <?php the_custom_logo(); ?>
    </div>
    <div class="font-roboto text-sm flex justify-center text-center font-light"><?php echo date("Y");?>&nbsp;PACTO © Todos os direitos reservados</div>
    <div class="justify-self-center md:justify-self-end"><img src="<?php echo get_theme_file_uri('/assets/images/logo_willbe.webp');?>"/></div>
  </div>
</section>

<?php wp_footer(); ?>
</body>
</html>
