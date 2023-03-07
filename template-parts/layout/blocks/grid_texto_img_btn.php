<?php
$items = get_sub_field("items");
?>
<section class="px-4 md:px-6">
  <ul class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-8">
<?php foreach ($items as $key => $value) : ?>
<li class="relative aspect-[598/518] overflow-hidden flex items-center justify-center grayscale hover:grayscale-0 transition-all duration-500">
  <div class="w-full h-full absolute top-0 left-0 ">
    <img src="<?php echo $value['imagem'] ?>" class="img-fill"/> 
  </div>
  <div class="relative flex flex-col items-center justify-center space-y-4">
    <h3 class="text-4xl md:text-6xl xl:text-7xl text-white font-bold font-roboto uppercase"><?php echo $value['titulo'] ?></h3>
    <div>
      <a class="btn btn-negative" href="<?php echo $value['link_btn'] ?>"><?php echo $value['label_btn'] ?></a>
    </div>
  </div>
</li>  
<?php endforeach; ?> 
  </ul>
</section>
