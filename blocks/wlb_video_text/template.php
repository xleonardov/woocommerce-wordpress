
<?php
/**
 * Block Name: WLB - Video & Text 
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
        <h2 class="text-5xl text-red-500"><?php echo $data['title'] ?> </h2>
    <?php endif; ?>
    <?php if ($data['video']) : ?>
        <img src="<?php echo $data['video']?>" />
    <?php endif; ?>
</div>
