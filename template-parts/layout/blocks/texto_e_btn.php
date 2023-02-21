<?php
$index = $args['index'];
$titulo = get_sub_field("titulo");
$texto = get_sub_field("texto");
$label_btn = get_sub_field("label_btn");
$link_btn = get_sub_field("link_btn");?>
<section class="px-4 md:px-6">
  <div class="<?php if($index != 0) : echo 'border-t border-b border-gray-400 py-8 md:py-16'; 
 endif; ?> grid grid-cols-1 md:grid-cols-2 items-center md:place-items-center">
    <div class="mb-4 md:mb-0 w-full">
      <?php if($titulo) : ?>
        <h1 class="font-roboto text-3xl md:text-4xl xl:text-5xl font-bold uppercase mb-4"><?php echo $titulo; ?></h1> 
      <?php endif; ?>
      <?php if($texto) :?>
        <div class="font-garamond text-lg max-w-screen-md"><?php echo $texto; ?></div>
      <?php endif; ?>
    </div>
    <div>
      <a class="btn btn-primary" href="<?php echo $link_btn; ?>"><?php echo $label_btn; ?></a>
    </div>
  </div>
</section>
