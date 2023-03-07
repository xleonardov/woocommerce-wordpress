<?php 
$titulo = get_sub_field("titulo");
$texto = get_sub_field("texto");
$imagem = get_sub_field("imagem");
$label_btn = get_sub_field("label_botao");
$link_btn = get_sub_field("link_botao");
?>

<section class="px-4 md:px-6">
  <div class="bg-black grid grid-cols-1 md:grid-cols-2 relative">
    <div class="px-4 md:px-8 xl:px-16 py-8 md:py-16 flex flex-col space-y-8 lg:space-y-16 order-2 md:order-1">
      <h1 class="text-white text-xl md:text-4xl lg:text-5xl uppercase font-roboto font-bold"><?php echo $titulo; ?></h1>
      <div class="text-white font-garamond lg:text-lg max-w-lg"><?php echo $texto; ?></div>
      <div>
        <a class="btn btn-negative" href="<?php echo $link_btn; ?>"><?php echo $label_btn; ?></a>
      </div>
    </div> 
    <div class="relative min-h-[300px] md:min-h-0 order-1 md:order-2">
      <img src="<?php echo $imagem; ?>" class="img-fill"/>
    </div>
  </div>
</section>
