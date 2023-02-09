<div class="py-4 px-4 md:px-6">
  <div class="w-full border-t border-gray-400 grid grid-cols-3 items-center pt-4">
    <div>
      <?php the_custom_logo(); ?>
    </div>
    <div class="font-roboto text-sm flex justify-center"><?php echo date("Y");?>&nbsp;PACTO © Todos os direitos reservados</div>
    <div class="justify-self-end"><img src="<?php echo get_theme_file_uri('/assets/images/logo_willbe.webp');?>"/></div>
  </div>
</div>

<?php wp_footer(); ?>
</body>
</html>
