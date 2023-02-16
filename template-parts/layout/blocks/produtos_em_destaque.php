<?php
$titulo = get_sub_field("titulo");
$produtos = get_sub_field("produtos");
?>
<section class="px-4 md:px-6">
  <div class="border-b border-gray-400 mb-4"></div>
  <?php if($titulo) : ?>
    <h2 class="uppercase font-roboto text-center text-2xl md:text-4xl font-bold py-4 md:py-8 border-b border-gray-400"><?php echo $titulo; ?></h2>
  <?php endif; ?>
  <ul class="grid grid-cols-2 gap-4 md:gap-8 md:grid-cols-4">
  <?php foreach ($produtos as $key => $item):
        $produto = wc_get_product($item); 
        $nome = $produto->get_name();
        $ids_imagens = $produto->get_gallery_image_ids();
        $imagens = [];
        $variations = [];
        $available_variations = $produto->get_available_variations();
        foreach ($available_variations as $variation) {
            foreach ($variation['attributes'] as $key => $attribute) {
                $variation_obj = new WC_Product_variation($variation['variation_id']);
                $order = 0;
                if($attribute === "xxs") { $order = 0;
                }
                if($attribute === "xs") { $order = 10;
                }
                if($attribute === "s") { $order = 20;
                }
                if($attribute === "m") { $order = 30;
                }
                if($attribute === "l") { $order = 40;
                }
                if($attribute === "xl") { $order = 50;
                }
                if($attribute === "xxl") { $order = 60;
                }
                if($attribute === "3xl") { $order = 70;
                }
                if($attribute === "4xl") { $order = 80;
                }
                if($attribute === "5xl") { $order = 90;
                }
                
                array_push($variations, array('order'=>$order, 'name' => $attribute, 'stock' => $variation_obj->get_stock_quantity()));
            }
        }
        asort($variations); 
        
        foreach ($ids_imagens as $key => $id_imagem) {
            array_push($imagens, wp_get_attachment_image_url($id_imagem, "full"));
        } 
        ?>
    <li class="relative group">
      <a href="<?php echo get_permalink($produto->get_id());?>">
        <div class="relative aspect-[211/300] w-full max-h-[300px] ">
          <?php foreach ($imagens as $key => $imagem): ?>
            <div class="bg-white absolute left-1/2 aspect-[211/300] h-full -translate-x-1/2 <?php echo $key!==0?'opacity-0 pointer-events-none':''?> transition-all duration-300 group-hover:opacity-100">
              <img src="<?php echo $imagem ?>" alt="<?php echo $nome?>" class="object-contain"/>
            </div>
          <?php endforeach; ?>
        </div>
        <div>
          <ul class="flex flex-wrap p-4 gap-2 justify-center items-center">
            <?php foreach ($variations as $key => $variation): ?>
              <li class="font-roboto uppercase text-xs md:text-sm <?php echo $variation['stock'] <= 0?'line-through text-gray-400':''?>"><?php echo $variation['name'] ?></li>
            <?php endforeach; ?>
          </ul>
          <h3 class="text-sm md:text-base uppercase px-2 text-center mb-2 font-roboto"><?php echo $nome; ?></h3>
          <div class="text-sm md:text-base uppercase px-2 text-center font-roboto"><?php echo $produto->get_price_html(); ?></div>
        </div>
      </a>
    </li>
  <?php endforeach; ?>
  </ul>
  <div class="border-b border-gray-400 mt-4 md:mt-8"></div>
</section>
