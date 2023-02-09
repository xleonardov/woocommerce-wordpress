<?php
/**
 * Block Name: WLB - Base block
 *
 * Description: Displays the base block definition.
 */

// The block attributes
$block = $args['block'];

// The block data
$data = $args['data'];

// The block ID
$block_id = $args['block_id'];

// The block class names
$class_name = $args['class_name'];
?>

<!-- Our front-end template -->
<div id="<?php echo $block_id; ?>" class="<?php echo $class_name; ?>">
    <?php if ($data['title']) : ?>
        <h1 class="text-5xl text-red-500"><?php echo $data['title'] ?> </h1>
    <?php endif; ?>
    <?php if ($data['image']) : ?>
        <img src="<?php echo $data['image']?>" />
    <?php endif; ?>
    <?php if ($data['description']) :?>
        <div class="text-red-500 text-4xl"><?php echo $data['description'] ?></div>
    <?php endif; ?>
</div>
