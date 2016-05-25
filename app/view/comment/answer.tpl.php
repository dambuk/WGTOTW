<?php $controller = isset($controller) ? $controller : 'answer'; ?>
<?php $userid = $user->getIdForAcronym($answer->getProperties()['name'])?>
<div class='answers'>

<div class='answer'>
<?php $isloggedin = ($user->getLoggedInUser() == $answer->getProperties()['name']) ?>
<?php $isloggedinposter = ($user->getLoggedInUser() == $issueposter) ?>
<div class='answer-id'>
</div>
<div class='answer-content'>
<?php $gravatar = $user->getGravatarForAcronym($answer->getProperties()['name'])?>

<?php $content = $this->di->textFilter->doFilter($answer->getProperties()['content'], 'markdown');?>
<div><?=$content?></div>


<p class='smaller dark-grey'>— <a href='<?=$this->url->create('users/id/'.$userid)?>'><?=$answer->getProperties()['name']?></a> för 
<?php $elapsedsec = (time()-strtotime($answer->getProperties()['timestamp'])); ?>
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
<?php endif; ?> <?php if($isloggedin) :?><a href='<?=$this->url->create($controller .'/edit/'.$pagekey.'/'.$id.'/'.$redirect)?>' title='redigera'><i class="fa fa-pencil"></i></a><?php endif;?>

<?php if (!empty($answer->getProperties()['web'])) : ?>
<?php $prefix = preg_match('/^[www]/', $answer->getProperties()['web']) ? 'http://' : '';?>
<a href='<?=$prefix.$answer->getProperties()['web']?>' target='_blank'>Homepage</a>
<?php endif; ?>
<?php if (!empty($answer->getProperties()['updated'])) : ?>
Edited <?=$answer->getProperties()['updated']?>
</p>
<?php endif; ?>

</div>
</div>
</div>
<div class='answer-divider'></div>
