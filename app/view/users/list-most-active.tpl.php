<article class='active'>
<h3><?=$title?></h3>

<?php if (!empty($users)) : ?>
<table>
  <tbody>
    
  
    <?php foreach ($users as $user) : ?>
    <tr>
    <td  ><a href="<?=$this->url->create('users/id').'/'.$user->id?>"><img src='<?=$user->gravatar?>?s=15' width="20px" height="20px" alt='gravatar'></a></td><td class="td-active smaller"><a id="most-active" href="<?=$this->url->create('users/id').'/'.$user->id?>"><?=$user->acronym?></a></td><td class="td-active smaller total"><?=$user->total?></td>
    </tr>
    <?php endforeach; ?>
    
    
  </tbody>
</table>

<?php elseif (empty($users)) : ?>
<p>There is no users to show.</p>
<?php endif; ?>
</article>