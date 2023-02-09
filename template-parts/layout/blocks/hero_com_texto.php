<?php 
$titulo = get_sub_field("titulo");
$texto = get_sub_field("texto");
$imagem = get_sub_field("imagem");
?>
<section class="px-4 md:px-6">
  <h1 class="font-roboto text-3xl md:text-5xl font-bold mb-4 md:mb-8"><?php echo $titulo ?></h1>
  <div class="w-full aspect-[852/1280] md:aspect-[1280/852] max-h-[60vh] md:max-h-[80vh] relative mb-4 md:mb-8">
    <img src="<?php echo $imagem ?>" class="img-fill" alt="<?php echo $titulo ?>"/> 
  </div>
  <div class="font-garamond text-lg max-w-screen-xl mx-auto columns-1 md:columns-2 gap-8">
    <?php echo $texto ?>
  </div>
</section>
