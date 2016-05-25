<div class="user">
<h2>User</h2>
<?php error_reporting(E_ERROR | E_PARSE); ?>
<?php $userisloggedin = ($user->getProperties()['acronym'] == $userinfo->getLoggedInUser()) ?>
<?php if ($user->getProperties()['deleted'] != null) : ?>
<p>>This user is blocked. <?php if ($userisloggedin || $userinfo->getLoggedInUser() == 'admin') : ?>Go to <a 
href="<?=$this->di->get('url')->create('users/discarded')?>">Trashcan</a> 
to restore a user.<?php endif;?></p>
<?php endif; ?>

<h4><i class="<?=$faclass?>"></i> <?=$user->getProperties()['acronym']?> 
(id <?=$user->getProperties()['id']?>)</h4>
<table class='userinfo'>
<tbody>
<tr><td>
<img src='<?=$user->getProperties()['gravatar']?>?s=50' alt='gravatar'></td>
<td id="user-info">Username: <?=$user->getProperties()['name']?> 
<?php if ($userinfo->getLoggedInUser()) :?><br><?=$user->getProperties()['email']?><?php endif;?>
<br>Created:  <?=$user->getProperties()['created']?>
<br><?=isset($user->getProperties()['updated'])?"Updated 
".$user->getProperties ( ) [ 'updated' ]:'';?>
</td></tr></tbody></table>
<p>
<?php if ($userisloggedin || $userinfo->getLoggedInUser() == 'admin') : ?>
<?php if ($user->getProperties()['deleted'] == null) : ?>
    <a 
href="<?=$this->url->create('users/update').'/'.$user->getProperties()['id']?>" 
title='Edit'><i class="fa fa-pencil"></i> Edit User
</a>
<?php endif; ?>
<?php endif; ?>
</p>
</div>

