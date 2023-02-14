<?php 
$index = $args['index'];
$icon_aberto = get_sub_field("icon_aberto");
$icon_fechado = get_sub_field("icon_fechado");
$items = get_sub_field("items");
?>

<section class="px-4 md:px-6">
  <div class="mx-auto max-w-2xl w-full">
    <?php if($items) : ?> 
      <ul class="w-full relative">
        <?php foreach ($items as $key => $item): ?>
          <li class="w-full border-b border-gray-400 py-2 px-2 md:px-4">
            <button data-target="<?php echo 'acordeao_'.$index.'_'.$key?>" class="btn-acordeao w-full flex justify-between items-center font-roboto uppercase text-xl py-2">
              <div class="text-left">
                <?php echo $item['titulo']; ?>
              </div>
              <div class="is_open hidden flex-none">
                <img width="15" src="<?php echo $icon_aberto?>" />
              </div>
              <div class="is_close flex-none">
                <img width="15" src="<?php echo $icon_fechado?>" />
              </div>
            </button>
            <div id="<?php echo 'acordeao_'.$index.'_'.$key?>" class="transition-all duration-300 font-garamond h-0 overflow-hidden"><?php echo $item['texto']; ?></div>
          </li>
        <?php endforeach; ?>
      </ul>
    <?php endif; ?>
  </div>
</section>
