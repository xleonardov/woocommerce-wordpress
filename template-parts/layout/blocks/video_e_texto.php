<?php
$titulo = get_sub_field("titulo");
$video = get_sub_field("video");
?>
<section class="px-4 md:px-6">
  <div class="aspect-[852/1280] md:aspect-[1280/852] w-full max-h-[60vh]i md:max-h-[85vh] relative overflow-hidden">
    <video autoplay loop playsinline class="w-full h-full object-cover">
      <source src="<?php echo $video ?>" type="video/mp4"/> 
    </video>
    <div class="absolute max-w-xs top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2">
      <h1 class="font-roboto font-bold text-4xl sm:text-5xl md:text-7xl uppercase text-center text-white">
        <?php echo $titulo;?> 
      </h1>
    </div>
  </div>
</section>
