<?php
$gamas = get_sub_field("select_gamas");
$all_terms = get_terms(
    array(
    'taxonomy'      => 'gamas',
    'hide_empty'    => false
    )
);
$selected_terms=[];
foreach ($all_terms as $key => $termo) {
    if(in_array($termo->slug, $gamas)) {
        $tamanhos = get_field('tamanhos', $termo->taxonomy.'_'.$termo->term_id);
        $modelo = get_field('modelo', $termo->taxonomy.'_'.$termo->term_id);
        $maquete = get_field('maquete', $termo->taxonomy.'_'.$termo->term_id);
        $termo->tamanhos = $tamanhos;
        $termo->modelo = $modelo;
        $termo->maquete = $maquete;
        array_push($selected_terms, $termo);
    }
}
?>
<section class="px-4 md:px-6">
<?php foreach ($selected_terms as $key => $termo):?>
<div>
    <?php if(str_contains(get_page_link(), "guia-de-tamanhos")) : ?>
    <h3 class="uppercase text-2xl md:text-4xl font-medium font-roboto py-4 md:py-6 border-t border-b border-gray-400"><?php echo $termo->name ?></h3>
    <?php endif; ?>
  <div class="flex flex-col lg:flex-row lg:items-center gap-4 py-4 md:py-8 max-w-screen-2xl mx-auto">
    <div class="flex-none flex justify-center">
      <img src="<?php echo $termo->modelo?>"/> 
    </div>
    <div class="relative flex-1 overflow-x-auto lg:flex lg:justify-center">
      <img class="object-contain min-w-[600px] max-w-[800px]" src="<?php echo $termo->tamanhos?>"/> 
    </div>
  </div>
  <div class="mb-8 md:mb-16">
    <p class="text-center italic font-garamond mb-6"><?php echo $termo->description;?></p>
    <?php if($termo->maquete) :?>
    <div class="w-full flex justify-center items-center">
      <a href="<?php echo $termo->maquete?>" class="btn btn-primary" download><?php echo __("Download de Maquete", "tailwind-theme");?></a>
    </div>
    <?php endif;?>
  </div>
</div>
<?php endforeach; ?>
</section>
