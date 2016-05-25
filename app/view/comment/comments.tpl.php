<?php $controller = isset($controller) ? $controller : 'comment'; ?>


<div class='comments'>

<?php if (is_array($comments)) : ?>
<?php /*$comments = array_reverse($comments)*/ ?>
<?php foreach ($comments as $id => $comment) : ?>
<?php $id = (is_object($comment)) ? $comment->id : $id; ?>
<?php $comment = (is_object($comment)) ? get_object_vars($comment) : $comment; ?> 
<?php $isloggedin = ($user->getLoggedInUser()) ?>
<?php $isloggedinposter = ($user->getLoggedInUser() == $comment['name']) ?>

<div class='comment'>
<div class='comment-id'>
<?php $gravatar = $user->getGravatarForAcronym($comment['name'])?>
<div class='comment-content'>
<?php $userid = $user->getIdForAcronym($comment['name'])?>
<?php $content = $this->di->textFilter->doFilter($comment['content'], 'markdown');?>
<div><?=$content?></div>
<div class="user-comment"> — <a href='<?=$this->url->create('users/id/'.$userid)?>'><?=$comment['name']?></a> för 
<?php $elapsedsec = (time()-strtotime($comment['timestamp'])); ?>
<?php if (($elapsedsec) < 60): ?>
<?=round($elapsedsec)?> s ago
<?php elseif (($elapsedsec/60) < 60): ?>
<?=round($elapsedsec/60)?> minutes ago
<?php elseif (($elapsedsec/(60*60)) < 24): ?>
<?=round($elapsedsec/(60*60))?> h ago
<?php elseif (($elapsedsec/(60*60*24)) < 7): ?>
<?=round($elapsedsec/(60*60*24))?> days ago
<?php elseif (($elapsedsec/(60*60*24)) < 30) : ?>
<?=round($elapsedsec/(60*60*24*7))?> weeks ago
<?php else : ?>
<?=round($elapsedsec/(60*60*24*30))?> months ago
<?php endif; ?>

<?php if($isloggedinposter): ?>
<a href='<?=$this->url->create($controller .'/edit/'.$pagekey.'/'.$id.'/'.$redirect)?>' title='redigera'><i class="fa fa-pencil"></i></a> 
<?php endif; ?>
</div>
</div>
</div>
<div class='comment-divider'></div>
<?php endforeach; ?>

<?php endif; ?>
<?php if (is_string($comments)) : ?>

<p class='comment'><?=$comments?></p>

<?php endif; ?>
</div>
<div class='comment-divider'></div>