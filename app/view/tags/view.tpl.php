<article>

<h4><?=$post->getProperties()['title']?></h4>

<p>

    <a 
href="<?=$this->url->create($controller.'/update').'/'.$post->getProperties()['id']?>" 
title='Edit'>Edit content
</a>

</p>
</article>
