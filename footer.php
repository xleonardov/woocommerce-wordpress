<?php
$top_area = get_field("top_area", "option");
$menu_principal_id = get_nav_menu_locations()['footer-main'];
$menu_secundario_id = get_nav_menu_locations()['footer-secondary'];
$menu_terciario_id = get_nav_menu_locations()['footer-tertiatry'];
$menu_principal = apply_filters('mount_menu_tree', $menu_principal_id);
$menu_secundario = apply_filters('mount_menu_tree', $menu_secundario_id);
$menu_terciario = apply_filters('mount_menu_tree', $menu_terciario_id);
?>
<section class="py-4 md:py-8 px-4 md:px-6 mt-4">
  <div class="border-t border-gray-400"></div>
  <?php if (have_rows('top_area', 'option')) : ?>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 py-4 md:py-8 leading-tight">
        <?php while (have_rows('top_area', 'option')):
            the_row();
            $titulo = get_sub_field('titulo');
            $conteudo = get_sub_field('conteudo');
            ?>
        <div>
          <h3 class="font-roboto text-lg uppercase mb-3">
            <?php echo $titulo; ?>
          </h3>
          <div>
            <?php echo $conteudo ?>
          </div>
        </div>
        <?php endwhile; ?>
    </div>
  <?php endif; ?>
  <div class="border-t border-gray-400"></div>
  <div class="grid grid-cols-1 md:grid-cols-2 py-4 md:py-8">
    <div>
      <h3 class="text-lg font-roboto uppercase mb-3">
        <?php echo __("Pacto", "theme-tailwind") ?>
      </h3>
      <ul class="flex flex-col md:flex-row space-y-1 md:space-y-0 md:space-x-4">
        <?php foreach ($menu_principal as $key => $menu_item): ?>
          <li class="text-sm font-roboto uppercase">
            <a class="hover:underline" href="<?php echo $menu_item->url; ?>">
              <?php echo $menu_item->title; ?>
            </a>
          </li>
        <?php endforeach; ?>
      </ul>
    </div>
    <div>
      <h3 class="text-lg font-roboto uppercase mt-6 md:mt-0 mb-3">
        <?php echo __("Apoio cliente", "theme-tailwind") ?>
      </h3>
      <ul class="flex flex-col md:flex-row space-y-1 md:space-y-0 md:space-x-4">
        <?php foreach ($menu_secundario as $key => $menu_item): ?>
          <li class="text-sm font-roboto uppercase">
            <a class="hover:underline" href="<?php echo $menu_item->url; ?>">
              <?php echo $menu_item->title; ?>
            </a>
          </li>
        <?php endforeach; ?>
      </ul>
    </div>
  </div>
  <div class="border-t border-gray-400"></div>
  <div class="grid grid-cols-1 md:grid-cols-2 py-4 md:py-8">
    <div>
      <ul class="flex flex-col md:flex-row space-y-1 md:space-y-0 md:space-x-4 items-start mb-4 md:mb-0 md:items-center h-full">
        <?php foreach ($menu_terciario as $key => $menu_item): ?>
          <li class="text-xs font-roboto uppercase">
            <a class="hover:underline" href="<?php echo $menu_item->url; ?>">
              <?php echo $menu_item->title; ?>
            </a>
          </li>
        <?php endforeach; ?>
      </ul>
    </div>
    <div class="flex md:justify-end justify-start">
      <img src="<?php echo get_theme_file_uri('/assets/images/payment_logos.webp'); ?>" class="object-contain" width="333" alt="payment methods" />
    </div>
  </div>
  <div class="w-full border-t border-gray-400 grid grid-cols-1 space-y-4 md:space-y-0 md:grid-cols-3 items-center pt-4">
    <div class="place-self-center md:place-self-start">
      <?php the_custom_logo(); ?>
    </div>
    <div class="font-roboto text-sm flex justify-center text-center font-light">
      <?php echo date("Y"); ?>&nbsp;PACTO © Todos os direitos reservados
    </div>
    <div class="justify-self-center md:justify-self-end"><img
        src="<?php echo get_theme_file_uri('/assets/images/logo_willbe.webp'); ?>" /></div>
  </div>
</section>

<?php wp_footer(); ?>
</body>

</html>
