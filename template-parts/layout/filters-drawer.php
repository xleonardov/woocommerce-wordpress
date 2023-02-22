<?php global $woocommerce;
$icons = new Icons;
$createdAttributes = wc_get_attribute_taxonomy_labels();

$gamas = get_terms(
    array(
    'taxonomy' => 'gamas',
    'hide_empty' => false,
    )
);

?>
<div class="filters-drawer invisible w-full h-screen fixed top-0 z-40 left-0">
  <div class="overlay bg-[#0000004d] w-full h-screen absolute top-0 z-30 opacity-0 transition-all duration-200">
  </div>
  <div
    class="filters-drawer-container bg-white w-full max-w-md h-full absolute right-0 z-40  overflow-y-auto translate-x-[100%] transition-all duration-200 flex flex-col"
    id="modal-filters">
    <form class="grid grid-rows-[auto_1fr_auto] h-screen font-roboto" id="filters-form">
      <input type="hidden" name="taxonomy"
        value="<?php echo property_exists($args['category'], 'slug') ? $args['category']->slug : '' ?>" />
      <div class="p-4 md:p-8 flex justify-between items-center">
        <button class="close-filters-drawer text-2xl cursor-pointer" type="button">
          <?php echo $icons->get_icon('GrClose'); ?>
        </button>
        <button class="clear-all-filters text-xs uppercase underline" type="button"
          data-taxonomy="<?php echo property_exists($args['category'], 'slug') ? $args['category']->slug : '' ?>">
          Limpar Filtros
        </button>
      </div>
      <div class="p-4 md:p-8 flex gap-8 flex-col">
        <?php
        foreach ($createdAttributes as $key => $attribute):
            $id = wc_attribute_taxonomy_id_by_name($key);
            $show = get_option("wc_attribute_show-$id");
            $type = get_option("wc_attribute_type-$id");
            if (!$show) {
                continue;
            }
            ?>
          <div>
            <h5 class="text-xs text-gray-400 uppercase mb-2">
              Filtrar por
              <?php echo $key !== 'cor' ? "Tamanho " : $attribute ?>:
            </h5>
            <?php $terms = get_terms(array('taxonomy' => 'pa_' . $key, 'hide_empty' => false)); ?>
            <div class="flex gap-1 w-full flex-wrap">
              <?php foreach ($terms as $term):
                    if ($type === "color") :
                        $color = get_field('color', 'term_' . $term->term_id);
                        ?>
                  <div>
                    <input type="checkbox" class="hidden f-checkbox" name="<?php echo $key ?>" id="<?php echo $key . '_' . $term->slug ?>"
                      value="<?php echo $term->slug ?>" data-attribute="<?php echo $key ?>"
                      data-taxonomy="<?php echo property_exists($args['category'], 'slug') ? $args['category']->slug : '' ?>" <?php
                             $cleanSlug = str_replace('pa_', '', $key);
                        if (isset($_GET[$cleanSlug])) {
                            $terms = explode("_", $_GET[$cleanSlug]);
                            if (in_array($term->slug, $terms)) {
                                 echo 'checked';
                            }
                        }
                        ?>>
                    <button type="button" class="p-[3px] btn-quad toggle-filter <?php
                    $cleanSlug = str_replace('pa_', '', $key);

                    if (isset($_GET[$cleanSlug])) {
                        $terms = explode("_", $_GET[$cleanSlug]);
                        if (in_array($term->slug, $terms)) {
                            echo 'btn-quad-selected';
                        }
                    }
                    ?>">
                      <div class="w-full h-full" style="background-color:<?php echo $color ?>;"></div>
                    </button>
                  </div>
                        <?php
                else: ?>
                  <div>
                    <input type="checkbox" class="hidden f-checkbox" name="<?php echo $key ?>" id="<?php echo $key . '_' . $term->slug ?>"
                      value="<?php echo $term->slug ?>" data-attribute="<?php echo $key ?>"
                      data-taxonomy="<?php echo property_exists($args['category'], 'slug') ? $args['category']->slug : '' ?>" <?php
                             $cleanSlug = str_replace('pa_', '', $key);
                        if (isset($_GET[$cleanSlug])) {
                            $tms = explode("_", $_GET[$cleanSlug]);
                            if (in_array($term->slug, $tms)) {
                                 echo 'checked';
                            }
                        }
                        ?>>
                    <button type="button" class="btn-quad toggle-filter <?php
                    $cleanSlug = str_replace('pa_', '', $key);

                    if (isset($_GET[$cleanSlug])) {
                        $terms = explode("_", $_GET[$cleanSlug]);
                        if (in_array($term->slug, $terms)) {
                            echo 'btn-quad-selected';
                        }
                    }
                    ?>">
                      <?php echo $term->name ?>
                    </button>
                  </div>
                    <?php
                endif;
              endforeach; ?>
            </div>
          </div>
            <?php
        endforeach;
        ?>

        <?php if ($gamas) : ?>
          <div>
            <h5 class="text-xs text-gray-400 uppercase mb-2">
              Filtrar por Gama:
            </h5>
            <div class="grid grid-cols-2 gap-1">
              <?php foreach ($gamas as $gama) { ?>
                <div class="flex items-center space-x-2">
                  <input type="radio" class="w-4 h-4 f-radio" id="gamas<?php echo '_' . $gama->slug ?>" name="gamas"
                    value="<?php echo $gama->slug ?>" <?php echo isset($_GET['gamas']) && $_GET['gamas'] === $gama->slug ? 'checked' : '' ?>>
                  <label for="<?php echo $gama->term_id ?>" class="uppercase"> <?php echo $gama->name ?></label>
                </div>
              <?php } ?>
            </div>
          </div>
        <?php endif;
        $show_default_orderby = 'date' === apply_filters('woocommerce_default_catalog_orderby', get_option('woocommerce_default_catalog_orderby', 'date'));
        $catalog_orderby_options = apply_filters(
            'woocommerce_catalog_orderby',
            array(
            'date' => __("Mais Recentes", "wlb_theme"),
            'price' => __("Preço mais baixo", "wlb_theme"),
            'price-desc' => __("Preço mais alto", "wlb_theme"),
            'popularity' => __("Relevância", "wlb_theme"),
            )
        );

        $default_orderby = wc_get_loop_prop('is_search') ? 'relevance' : apply_filters('woocommerce_default_catalog_orderby', get_option('woocommerce_default_catalog_orderby', ''));
        // phpcs:disable WordPress.Security.NonceVerification.Recommended
        $orderby = isset($_GET['orderby']) ? wc_clean(wp_unslash($_GET['orderby'])) : $default_orderby;
        // phpcs:enable WordPress.Security.NonceVerification.Recommended
        
        if (wc_get_loop_prop('is_search')) {
            $catalog_orderby_options = array_merge(array('relevance' => __('Relevance', 'woocommerce')), $catalog_orderby_options);

            unset($catalog_orderby_options['date']);
        }

        if (!$show_default_orderby) {
            unset($catalog_orderby_options['date']);
        }

        if (!wc_review_ratings_enabled()) {
            unset($catalog_orderby_options['rating']);
        }

        if (!array_key_exists($orderby, $catalog_orderby_options)) {
            $orderby = current(array_keys($catalog_orderby_options));
        }

        wc_get_template(
            'loop/orderby.php',
            array(
            'catalog_orderby_options' => $catalog_orderby_options,
            'orderby' => $orderby,
            'show_default_orderby' => $show_default_orderby,
            'term' => $args['term'],
            )
        );
        ?>
      </div>
      <div class="flex justify-center items-center p-4 pb-28 md:p-8 flex-col space-y-6">
        <button class="btn btn-primary w-full text-center justify-center items-center" type="submit">
          Filtrar
        </button>
        <button class="text-xs uppercase underline justify-center items-center clear-all-filters" type="button"
          data-taxonomy="<?php echo property_exists($args['category'], 'slug') ? $args['category']->slug : '' ?>">
          Limpar Filtros
        </button>
      </div>
    </form>
  </div>
</div>
