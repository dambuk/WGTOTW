<div class="all-users-title"><h1><?=$title?></h1></div>
<?php error_reporting(E_ERROR | E_PARSE); ?>
<?php $adminloggedin = ('admin' == $userinfo->getLoggedInUser()) ?>
<?php if (!empty($users)) : ?>
<table class="users-table">
  <tbody> 
<tr><th class='th-id'></th><th class='th-status'></th><th class='th-user'>User</th><th class='th-name'>Name</th><th class='th-symbol'></th><th class='th-symbol'></th></tr>
    <?php foreach ($users as $user) : ?>
    <?php $userisloggedin = ($user->getProperties()['acronym'] == $userinfo->getLoggedInUser()) ?>
    
    <?php 
    $class = "";
    if ($user->getProperties()['deleted'] != null) {
      $faclass = "fa fa-user-times fa-fw";
      $class = "user-deleted";
    }
    elseif ($user->getProperties()['active'] == null) {
      $faclass = "fa fa-user fa-fw";
      $class = "user-inactive";
    }
    else {
      $faclass = "fa fa-user fa-fw";
      $class = "user-active";
    } ?>
    <tr>
    <td><img  class="gravatar" src='<?=$user->getProperties()['gravatar']?>?s=20' title='gravatar' alt='gravatar'></td>
    <td>
    <?php if($adminloggedin) : ?>
    <a href="<?=$this->url->create('users/activate').'/'.$user->getProperties()['id'].'/'.$this->request->getRoute()?>" class="<?=$class?>"><?php endif?><i class="<?=$faclass." ".$class?>"></i><?php if($adminloggedin) : ?></a><?php endif?></td>
    <td><a href="<?=$this->url->create('users/id').'/'.$user->getProperties()['id']?>" class="<?=$class?>"><?=$user->getProperties()['acronym']?></a></td>
    <td><?=$user->getProperties()['name']?></td>
    <td>
    <?php if ($userisloggedin || $adminloggedin) : ?>
    <?php if ($user->getProperties()['deleted'] == null) : ?>
    <a href="<?=$this->url->create('users/update').'/'.$user->getProperties()['id']?>" title='Ändra'><i class="fa fa-pencil"></i>
</a><?php endif; ?>
<?php endif; ?>
    </td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>
<p></p>


<?php elseif (empty($users)) : ?>
<p>There is no users to show.</p>
<?php endif; ?>