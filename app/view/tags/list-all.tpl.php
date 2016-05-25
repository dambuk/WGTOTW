<div class='content-tags'>
<?php if (isset($title)):?><h1><?=$title?></h1><?php endif?>
<?php if (isset($subtitle)):?><h3><?=$subtitle?></h3><?php endif?>
<?php if (is_array($content)) : ?>
<?php foreach ($content as $id => $post) : ?>
<?php $id = (is_object($post)) ? $post->id : $id; ?>
<?php $post = (is_object($post)) ? get_object_vars($post) : $post; ?> 
 
<div class='all-tags'>
<a href='questions/list-by-tag/<?=$id?>'><?=$post['tagname']?></a>

</div>

<?php endforeach; ?>

<?php endif; ?>
</div>

