<?php
$icons = new Icons;
$logo = get_theme_mod('custom_logo');
$image = wp_get_attachment_image_src($logo, 'full');
$image_url = '';
if (is_array($image)) {
  $image_url = $image[0];
}
?>
<div class="mega-menu invisible w-full h-screen fixed top-0 z-40">
  <div class="overlay bg-black bg-opacity-30 w-full h-screen absolute top-0 z-30 opacity-0"></div>
  <div
    class="mega-menu-container bg-white w-full max-w-lg h-full absolute left-0 z-40 transition-all duration-200 grid grid-cols-[32px_auto] lg:grid-cols-[40px_auto] grid-rows-[32px_auto] lg:grid-rows-[40px_auto] px-4 lg:px-8 py-8 lg:py-14 translate-x-[-100%] overflow-y-auto">
    <div class="flex items-center">
      <div class="close-mega-menu text-[28px] cursor-pointer">
        <?= $icons->get_icon('GrClose'); ?>
      </div>
    </div>
    <div class="flex items-center">
      <div class="flex items-center"><img class="max-w-[200px] lg:pl-4" src="<?= $image_url ?>" /></div>
    </div>
    <div class="controller">
      <div id="mega-menu-go-back" class="opacity-0 cursor-pointer transition-all duration-200 mt-8 text-3xl">
        <?= $icons->get_icon('BsArrowLeft'); ?>
      </div>
    </div>
    <div class="mega-menu-nav py-8 flex flex-row relative transition-all duration-300 ease-in-out">
      <div class="navigation">
        aaa
      </div>
    </div>
  </div>
</div>