<?php
$icons = new Icons;
$logo = get_theme_mod('custom_logo');
$image = wp_get_attachment_image_src($logo, 'full');
$image_url = '';
if (is_array($image)) {
  $image_url = $image[0];
}
$menu_id = get_nav_menu_locations()['main'];
$menu = apply_filters('mount_menu_tree', $menu_id);
// var_dump($menu);
?>
<div class="mega-menu w-full h-screen invisible fixed top-0 z-40">
  <div class="overlay bg-[#0000004d] w-full h-screen absolute top-0 z-30 opacity-0"></div>
  <div
    class="mega-menu-container bg-white w-full max-w-md h-full absolute left-0 z-40 transition-all duration-200 px-4 -translate-x-full">
    <div class="flex flex-col h-full">
      <div class="flex border-b border-gray-400 items-center flex-none h-20 justify-between ">
        <div class="flex items-center"><img class="max-w-[200px] lg:pl-4" src="<?php echo $image_url ?>" /></div>
        <button class="close-mega-menu cursor-pointer">
          <img src="<?php echo get_theme_file_uri('/assets/images/close.webp'); ?>" />
        </button>
      </div>
      <div
        class="mega-menu-nav flex-1 flex flex-col relative transition-all duration-300 ease-in-out overflow-y-auto w-full">
        <ul class="flex flex-col w-full " role="navigation">
          <?php foreach ($menu as $key => $menu_item): ?>
            <li class="w-full">
              <?php if (isset($menu_item->children_arr)): ?>
                <button class="btn-menu w-full has_sub_menu" data-submenu="<?php echo 'sub_menu_id_' . $menu_item->ID; ?>">
                  <?php echo $menu_item->title; ?>
                  <img width="15" class="is_close" src="<?php echo get_theme_file_uri('/assets/images/add.webp'); ?>" />
                  <img width="15" class="is_open hidden"
                    src="<?php echo get_theme_file_uri('/assets/images/minus.webp'); ?>" />
                </button>
                <ul class="flex-col w-full h-0 overflow-hidden transition-all duration-300"
                  id="<?php echo 'sub_menu_id_' . $menu_item->ID ?>">
                  <?php foreach ($menu_item->children_arr as $key => $sub_menu_item): ?>
                    <li class="w-full">
                      <a class="btn-submenu w-full font-light" href="<?php echo $sub_menu_item->url; ?>"><?php echo $sub_menu_item->title; ?></a>
                    </li>
                  <?php endforeach; ?>
                </ul>
              <?php else: ?>
                <a class="btn-menu w-full" href="<?php echo $menu_item->url; ?>"><?php echo $menu_item->title; ?></a>
              <?php endif; ?>
            </li>
          <?php endforeach; ?>
        </ul>
        <div class="pt-12 px-4 pb-6"><a class="btn-big" href="/personalizar">Personalizar artigos</a></div>
      </div>
      <div class="h-24 border-t border-gray-400 flex flex-col">
        <div class="pt-3 flex-1 flex justify-center gap-4">
          <a class="btn-social btn-facebook" href="https://facebook.com/PactoCycling" target="_blank" rel="noopener"
            rel="noreferrer"></a>
          <a class="btn-social btn-instagram" href="https://instagram.com/pacto_cycling" target="_blank" rel="noopener"
            rel="noreferrer"></a>
          <a class="btn-social btn-youtube" href="https://youtube.com/@pacto8198" target="_blank" rel="noopener"
            rel="noreferrer"></a>
        </div>
        <div class="font-roboto font-light text-sm text-center py-2">
          <?php echo date("Y"); ?>&nbsp;PACTO © Todos os direitos reservados
        </div>
      </div>
    </div>
  </div>
</div>