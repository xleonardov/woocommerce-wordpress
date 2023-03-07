<?php
$titulo = get_sub_field("titulo");
$items = get_sub_field("items");
?>
<section class="px-4 md:px-6">
<?php if($items) : ?>
  <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-8 relative">
    <?php if($titulo) : ?>
      <div class="absolute z-[1] -translate-y-1/2 ">
        <h1 class="text-6xl md:text-9xl uppercase font-roboto "><?php echo $titulo; ?></h1>
      </div>
    <?php endif; ?>
    <?php if(count($items) == 2) : ?>
        <?php foreach ($items as $key => $item): ?>
        <div class="w-full aspect-[10/15] relative">
          <img src="<?php echo $item['imagem']; ?>" class="img-fill" alt="<?php echo $titulo; ?>"/>
        </div>
        <?php endforeach; ?>
    <?php else: ?>
        <?php foreach ($items as $key => $item): ?>
        <div class="<?php echo ($key==0) ? 'md:col-span-2 aspect-[1/1.6] md:aspect-[1.6/1]':'col-span-1 aspect-[1/1.6]'; ?> w-full  relative">
          <img src="<?php echo $item['imagem']; ?>" class="img-fill" alt="<?php echo $titulo; ?>"/>
          <div class="absolute bg-black bg-opacity-70 z-0 w-full p-4 md:p-6 bottom-0">
            <h3 class="text-white text-3xl font-roboto uppercase"><?php echo $item['titulo']; ?></h3> 
            <div class="text-white font-garamond"><?php echo $item['texto'];?></div>
          </div>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>
  </div>
<?php endif; ?>
</section>
