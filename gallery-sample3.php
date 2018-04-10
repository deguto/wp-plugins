<?php 
/**
Template Page for the gallery overview

Follow variables are useable :

	$gallery     : Contain all about the gallery
	$images      : Contain all images, path, title
	$pagination  : Contain the pagination content

 You can check the content when you insert the tag <?php var_dump($variable) ?>
 If you would like to show the timestamp of the image ,you can use <?php echo $exif['created_timestamp'] ?>
**/
?>
<?php if (!defined ('ABSPATH')) die ('No direct access allowed'); ?><?php if (!empty ($gallery)) : ?>

<ul id="ngg-gallery-<?php echo $gallery->ID ?>" class="sample3">

<!-- Thumbnails -->
<?php foreach ($images as $image) : ?>

<li id="ngg-image-<?php echo $image->pid ?>">
<a href="<?php echo $image->imageURL ?>" title="<?php echo $image->description ?>" <?php echo $image->thumbcode ?> >
<span></span>
<em><?php echo $image->alttext ?></em>
<img title="<?php echo $image->alttext ?>" alt="<?php echo $image->alttext ?>" src="<?php echo $image->thumbnailURL ?>" <?php echo $image->size ?> />
</a>
</li>
<?php endforeach; ?>
</ul>


<?php endif; ?>