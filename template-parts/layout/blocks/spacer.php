<?php 
$valor = get_sub_field("valor");
$spacer = "h-4 md:h-8";
switch ($valor) {
case "single":
    $spacer = "h-4 md:h-8";
    break;
case "double":
    $spacer = "h-8 md:h-16";
    break;
case "triple":
    $spacer = "h-16 md:h-32";
    break;
}
?>
<div class="<?php echo $spacer; ?>">
</div>
