<?php 
$titulo = get_sub_field("titulo");
$hashtag = get_sub_field("hashtag");
$texto = get_sub_field("texto");
$imagem = get_sub_field("imagem");
?>
<section class="px-4 md:px-6">
  <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-8 xl:gap-16 items-center">
    <div class="relative w-full aspect-[596/320]">
      <img src="<?php echo $imagem ?>" alt="<?php echo $titulo; ?>" class="img-fill"/> 
    </div>
    <div class="flex flex-col space-y-4 lg:space-y-8">
      <h3 class="font-roboto text-lg italic opacity-25"><?php echo $hashtag?></h3>   
      <h1 class="font-roboto text-2xl md:text-3xl xl:text-4xl font-bold uppercase"><?php echo $titulo; ?></h1>
      <div class="text-lg font-garamond max-w-lg">
        <?php echo $texto; ?> 
      </div>
    </div>
  </div>
</section>
