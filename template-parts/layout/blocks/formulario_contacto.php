<?php 
$id = get_sub_field("id");
?>
<section class="px-4 md:px-6">
  <?php if($id) : ?>
    <div class="mx-auto max-w-md">
        <?php echo do_shortcode('[contact-form-7 id="'.$id.'" title="Contactos"]'); ?> 
    </div>
  <?php endif; ?>
</section>
