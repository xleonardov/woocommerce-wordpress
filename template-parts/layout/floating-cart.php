<?php global $woocommerce;
$icons = new Icons; ?>
<div class="floating-cart invisible w-full h-screen fixed top-0 z-40">
  <div class="overlay bg-black bg-opacity-30 w-full h-screen absolute top-0 z-30 opacity-0 transition-all duration-200">
  </div>
  <div
    class="floating-cart-container bg-white w-full max-w-lg h-full absolute right-0 z-40  translate-x-[100%] transition-all duration-200 flex flex-col"
    id="modal-cart">
    <div
      class="floating-cart-header flex justify-between items-center bg-primary p-4 lg:p-8 text-white h-[64px] lg:h-[96px]">
      <h3 class="uppercase text-2xl tracking-wide">
        <?= _e('Carrinho de Compras', 'wlb_theme') ?>
      </h3>
      <div class="close-floating-cart text-2xl cursor-pointer">
        <?= $icons->get_icon('GrClose'); ?>
      </div>
    </div>
    <?php theme_woocommerce_floating_cart(); ?>
  </div>
</div>