<?php
$categorias = get_sub_field("categoria");
?>

<section class="px-4 md:px-6 relative">
  <div class="relative w-full grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-6">
    <?php if (count($categorias) > 0) {
      foreach ($categorias as $categoria) { ?>
        <a class="relative aspect-[4/5] md:aspect-[65/93] block overflow-hidden group"
          href="<?php echo $categoria['link']; ?>">
          <div class="absolute w-full h-full top-0 left-0">
            <img src="<?php echo $categoria['imagem']; ?>" alt="<?php echo $categoria['titulo']; ?>"
              class="img-fill transition-all duration-200 group-hover:scale-105" />
          </div>
          <div class="w-full h-full absolute cta_category_bg">
            <div class="absolute bottom-0 p-6 w-full">
              <h6 class="text-white font-bold text-3xl font-roboto md:text-5xl uppercase">
                <?php echo $categoria['titulo']; ?>
              </h6>
            </div>
          </div>
        </a>
      <?php }
    } ?>
  </div>
</section>