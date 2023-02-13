<?php global $woocommerce;
$icons = new Icons; ?>
<div class="floating-cart invisible w-full h-screen fixed top-0 z-40">
  <div class="overlay bg-black bg-opacity-30 w-full h-screen absolute top-0 z-30 opacity-0 transition-all duration-200">
  </div>
  <div
    class="floating-cart-container bg-white w-full max-w-md h-full absolute right-0 z-40  transition-all duration-200 px-4 translate-x-full"
    id="modal-cart">
    <div class="flex border-b border-gray-400 items-center flex-none h-20 justify-between ">
      <button class="close-floating-cart cursor-pointer">
        <img src="<?php echo get_theme_file_uri('/assets/images/close.webp'); ?>" />
      </button>
    </div>
    <?php theme_woocommerce_floating_cart(); ?>
  </div>
</div>
