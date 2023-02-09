<!DOCTYPE html>
<html <?php language_attributes(); ?>>
  <head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
  </head>
  <body <?php body_class(); ?>>
    <header class="bg-white shadow sticky top-0 z-10">
      <div class="px-4 md:px-6 py-3 grid grid-cols-3 items-center">
        <div class="flex-none">
          <button><img src="<?php echo get_theme_file_uri('/assets/images/hamburger.webp');?>"/></button>
        </div>
        <div class="flex-1 flex justify-center">
          <?php the_custom_logo(); ?>
        </div>
        <div class="flex gap-4 md:gap-6 justify-self-end">
          <button class="w-4 h-4">
            <img src="<?php echo get_theme_file_uri('assets/images/lupa.webp');?>"/>        
          </button> 
          <button class="w-4 h-4">
            <img src="<?php echo get_theme_file_uri('assets/images/my-account.webp');?>"/>        
          </button> 
          <button class="w-4 h-4">
            <img src="<?php echo get_theme_file_uri('assets/images/bag.webp');?>"/>        
          </button> 
        </div>
      </div>
    </header>
