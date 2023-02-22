<?php get_header(); ?>

<div class="w-full">
    <?php if (!is_front_page()) : ?>
        <section class="px-4 md:px-6 font-roboto text-sm my-4 md:my-8">
            <div class="border-t border-b border-gray-400 py-2 text-gray-400">
                <?php echo do_shortcode(' [wpseo_breadcrumb] '); ?>
            </div>
        </section>
    <?php endif; ?>
    <?php
    $index = 0;
    if (have_rows('layout_de_pagina')) :
        while (have_rows('layout_de_pagina')):
            the_row();
            if (get_row_layout() == 'video_e_texto') :
                get_template_part('template-parts/layout/blocks/video_e_texto', 'video_e_texto', array('index' => $index));
            endif;
            if (get_row_layout() == 'imagem_e_texto') :
                get_template_part('template-parts/layout/blocks/imagem_e_texto', 'imagem_e_texto', array('index' => $index));
            endif;
            if (get_row_layout() == 'texto_sobre_imagem') :
                get_template_part('template-parts/layout/blocks/texto_sobre_imagem', 'texto_sobre_imagem', array('index' => $index));
            endif;
            if (get_row_layout() == 'spacer') :
                get_template_part('template-parts/layout/blocks/spacer', 'spacer', array('index' => $index));
            endif;
            if (get_row_layout() == 'hero_com_texto') :
                get_template_part('template-parts/layout/blocks/hero_com_texto', 'hero_com_texto', array('index' => $index));
            endif;
            if (get_row_layout() == 'texto_e_btn') :
                get_template_part('template-parts/layout/blocks/texto_e_btn', 'texto_e_btn', array('index' => $index));
            endif;
            if (get_row_layout() == 'imagem_texto_hashtag') :
                get_template_part('template-parts/layout/blocks/imagem_texto_hashtag', 'imagem_texto_hashtag', array('index' => $index));
            endif;
            if (get_row_layout() == 'grid_texto_img_btn') :
                get_template_part('template-parts/layout/blocks/grid_texto_img_btn', 'grid_texto_img_btn', array('index' => $index));
            endif;
            if (get_row_layout() == 'grid_masonry') :
                get_template_part('template-parts/layout/blocks/grid_masonry', 'grid_masonry', array('index' => $index));
            endif;
            if (get_row_layout() == 'acordeao') :
                get_template_part('template-parts/layout/blocks/acordeao', 'acordeao', array('index' => $index));
            endif;
            if (get_row_layout() == 'listagem_de_tamanhos') :
                get_template_part('template-parts/layout/blocks/listagem_de_tamanhos', 'listagem_de_tamanhos', array('index' => $index));
            endif;
            if (get_row_layout() == 'cta_manequim') :
                get_template_part('template-parts/layout/blocks/cta_manequim', 'cta_manequim', array('index' => $index));
            endif;
            if (get_row_layout() == 'produtos_em_destaque') :
                get_template_part('template-parts/layout/blocks/produtos_em_destaque', 'produtos_em_destaque', array('index' => $index));
            endif;
            if (get_row_layout() == 'formulario_contacto') :
                get_template_part('template-parts/layout/blocks/formulario_contacto', 'formulario_contacto', array('index' => $index));
            endif;
            $index++;
        endwhile;
    endif;
    ?>
</div>
<?php get_footer(); ?>
