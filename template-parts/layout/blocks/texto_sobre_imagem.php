<?php 
$titulo = get_sub_field("titulo");
$imagem = get_sub_field("imagem");
$label_btn = get_sub_field("label_botao");
$link_btn = get_sub_field("link_botao");
?>

<section class="px-4 md:px-6 relative">
  <div class="relative max-h-[80vh] aspect-[4/5] md:aspect-[5/2] w-full flex items-center justify-center">
    <div class="absolute w-full h-full top-0 left-0">
      <img src="<?php echo $imagem; ?>" alt="<?php echo $titulo; ?>" class="img-fill" />
    </div>
    <div class="relative flex flex-col items-center justify-center space-y-8">
      <h1 class="text-white text-2xl md:text-4xl lg:text-5xl font-bold uppercase max-w-[300px] md:max-w-[430px] text-center font-roboto"><?php echo $titulo; ?></h1>
      <a class="btn btn-negative" href="<?php echo $link_btn ?>"><?php echo $label_btn ?></a>
    </div>
  </div>
</section>
