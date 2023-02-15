<?php 
$imagem = get_sub_field("imagem");
$produtos = get_sub_field("produtos");
?>
<section class="px-4 md:px-6">
  <div class="grid grid-cols-1 gap-4 md:gap-8 grid-rows-[auto_85vh] md:grid-cols-2 md:grid-rows-1">
    <div class="flex items-center justify-center">
      <div class="relative aspect-[4/5] max-h-[90vh] md:max-h-[80vh]">
        <img class="md:object-contain" src="<?php echo $imagem ?>" alt="Pacto #dresstowin"/>
      </div>
    </div>
    <div class="flex flex-col">
      <div class="flex-1 relative mb-10 md:mb-0">
        <?php foreach ($produtos as $key => $item): ?>
            <?php $produto = wc_get_product($item); ?>
          <a id="<?php echo 'cta_manequim_'.$produto->get_id();?>" href="<?php echo get_permalink($produto->get_id())?>" class="cta_manequim_active_item <?php echo $key!==0?'pointer-events-none opacity-0':''?> transition-all duration-300">
            <div class="absolute top-0 left-0 w-full h-full bg-opacity-30 flex items-center justify-center">
              <?php $img_id = $produto->get_image_id(); $img = wp_get_attachment_image_url($img_id, "large"); ?>
              <?php $nome = $produto->get_name();?>
              <img src="<?php echo $img ?>" alt="<?php echo $nome;?>" class="max-w-full max-h-full object-contain">
              <div class="font-roboto text-sm md:text-base text-center bottom-4 px-4 absolute bg-white bg-opacity-50 py-2">
                <div class="uppercase"><?php echo $nome; ?></div> 
                <div><?php echo $produto->get_price_html(); ?></div>
              </div>
            </div>
          </a>
        <?php endforeach; ?>
      </div> 
      <ul class="flex justify-around flex-none">
      <?php foreach ($produtos as $key => $item): ?>
            <?php $produto = wc_get_product($item); ?>
        <li>
          <button data-target="<?php echo 'cta_manequim_'.$produto->get_id();?>" class="btn-cta-manequim">
            <?php echo $produto->get_image()?> 
          </button>
        </li>
      <?php endforeach; ?>
      </ul>
    </div>
  </div>
<script>
  let btns = document.querySelectorAll(".btn-cta-manequim");
  btns.forEach(btn => {
    btn.addEventListener("click", (e) => {
      e.preventDefault();
      let target_id = btn.getAttribute("data-target");
      let target_el = document.getElementById(target_id);
      console.log(target_el)
      let disable_items = document.querySelectorAll(".cta_manequim_active_item");
      disable_items.forEach(item => {
        item.classList.add("pointer-events-none");
        item.classList.add("opacity-0");
      })
      target_el.classList.remove("pointer-events-none"); 
      target_el.classList.remove("opacity-0"); 
    })
  })
</script>
</section>
