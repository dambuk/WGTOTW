<?php
namespace dabu\Users;
 
/**
 * A controller for users and admin related events.
 *
 */
class UsersController implements \Anax\DI\IInjectionAware
{
    use \Anax\DI\TInjectable;
 
/**
 * Initialize the controller.
 *
 * @return void
 */
public function initialize()
{
    $this->users = new \dabu\Users\User();
    $this->users->setDI($this->di);

}

/**
 * List all users.
 *
 * @return void
 */
public function listAction()
{
 
    $all = $this->users->findAll();
    
    $this->theme->setTitle("Users");
    $this->views->add('users/list-all', [
        'users' => $all,
        'title' => "All users",
        'userinfo' => $this->users
    ], 'main');
   
}

/**
 * List most active users.
 *
 * @return void
 */
public function listMostActiveAction()
{
    //$this->db->setVerbose();
    $answers = $this->users->findMostAnswers();
    $questions = $this->users->findMostquestions();
    $comments = $this->users->findMostComments();
    $users = $this->users->findMostActive();
    
    $this->views->add('users/list-most-active', [
       'title' => "Most active users",
       'users'  => $users,
       'activity' => 'totalt',
    ], 'sidebar');
}


public function listUserScoresAction($id) 
{

    $user = $this->users->find($id);
    $answers = $this->users->findNumAnswers($user->acronym);
    $questions = $this->users->findNumquestions($user->acronym);
    $comments = $this->users->findNumComments($user->acronym);
    
    $this->views->add('users/list-user-scores', [
       'title' => "Ranking",
       'user'  => $user,
       'answer' => $answers[0]->total,
       'questions' => $questions[0]->total,
       'comments' => $comments[0]->total,
    ], 'main');


}

/**
 * List user with id.
 *
 * @param int $id of user to display
 *
 * @return void
 */
public function idAction($id = null)
{
    $user = $this->users->find($id);
    
    if(empty($user)) {
	
	$url = $this->url->create('users/no-such-user');
	$this->response->redirect($url);
	
	}
    $acronym = $this->users->getAcronym($id);
    
    
 
    $this->theme->setTitle("User");
    $this->views->add('users/view', [
        'user' => $user,
        'userinfo'   => $this->users,
    ], 'main');
    $this->dispatcher->forward([
        'controller' => 'users',
        'action'     => 'list-user-scores',
        'params'     => [$id],

    ]);
    $this->dispatcher->forward([
        'controller' => 'questions',
        'action'     => 'list-by-user',
        'params'     => [$acronym],

    ]);
}

/**
 * Add new user.
 *
 * @param string $acronym of user to add.
 *
 * @return void
 */
public function addAction($acronym = null)
{

$this->di->theme->setTitle("Add user");
    
        
    $form = new \dabu\HTMLForm\CFormUserAdd();
    $form->setDI($this->di);
    $status = $form->check();
    
    $info = $this->di->fileContent->get('users-addinfo.md');
    $info = $this->di->textFilter->doFilter($info, 'shortcode, markdown');
  
    
    $this->di->views->add('default/page', [
        'title' => "Add user",
        'content' => $form->getHTML(), 
        
        ], 'main');
     

}

/**
 * Update user.
 *
 * @param $id of user to update.
 *
 * @return void
 */
public function updateAction($id = null)
{
$this->di->theme->setTitle("Edit user");
if ($this->users->isLoggedIn()) {

    $user = $this->users->find($id);
     if(empty($user)) {
	
	$url = $this->url->create('users/no-such-user');
	$this->response->redirect($url);
	
    }
    
    $name = $user->getProperties()['name'];
    $acronym = $user->getProperties()['acronym'];
    $email = $user->getProperties()['email'];
    $active = $user->getProperties()['active'];
    $deleted = $user->getProperties()['deleted'];
    $created = $user->getProperties()['created'];
    
    if ($this->users->getLoggedInUser() == $acronym || $this->users->getLoggedInUser() == 'admin') {
    
    $form = new \Anax\HTMLForm\CFormUserUpdate($id, $acronym, $name, $email, $active, $created);
    $form->setDI($this->di);
    $status = $form->check();
    
    
    $this->di->views->add('default/page', [
        'title' => "Edit user",
        'content' => "<h4>".$user->getProperties()['acronym']." 
(id ".$user->getProperties()['id'].")</h4>".$form->getHTML()
        ]);

      }
      
      else {
      
      $this->views->add('users/loginedituser-message', [
    ], 'flash'); 
     }
    }
 
  else {
     $this->views->add('users/login-message', [
    ], 'flash'); 
     }
  
  
}


public function insertUserAction($acronym, $email=null, $name=null)
{

    if (!isset($acronym)) {
        die("Missing acronym");
    }
    $now = gmdate('Y-m-d H:i:s');

    $this->users->save([
        'acronym' => $acronym,
        'email' => $email,
        'name' => $acronym,
        'password' => password_hash($acronym, PASSWORD_DEFAULT),
        'created' => $now,
        'active' => $now,
    ]);

}

/**
 * Delete user.
 *
 * @param integer $id of user to delete.
 *
 * @return void
 */
public function deleteAction($id = null)
{
    if (!isset($id)) {
        die("Missing id");
    }
 
    $res = $this->users->delete($id);
 
    $url = $this->url->create('users');
    $this->response->redirect($url);
}


/**
 * Delete (soft) user.
 *
 * @param integer $id of user to delete.
 *
 * @return void
 */
public function activateAction($id = null,$route1=null,$route2=null)
{
    if (!isset($id)) {
        die("Missing id");
    }
    
    $route1 = isset($route1) ? $route1:'users';
    
    $route2 = isset($route2) ? "/".$route2:null;
 
    $now = gmdate('Y-m-d H:i:s');
 
    $user = $this->users->find($id);
    
    if ($user->deleted != null) {
      $user->deleted = null;
    }
    elseif ($user->active == null) { 
      $user->active = $now;
    }
    else {
      $user->active = null;
    }
    $user->save();
 
    $url = $this->url->create($route1.$route2);
    $this->response->redirect($url);
}


/**
 * Delete (soft) user.
 *
 * @param integer $id of user to delete.
 *
 * @return void
 */
public function softDeleteAction($id = null)
{
    if (!isset($id)) {
        die("Missing id");
    }
    

    $now = gmdate('Y-m-d H:i:s');
 
    $user = $this->users->find($id);
 
    $user->deleted = $now;
    $user->save();
 
    $url = $this->url->create('users/id/' . $id);
    $this->response->redirect($url);
}

/**
 * List all active and not deleted users.
 *
 * @return void
 */
public function activeAction()
{
    $all = $this->users->query()
        ->where('active IS NOT NULL')
        ->andWhere('deleted is NULL')
        ->execute();
 
    $this->theme->setTitle("Active users");
    $this->views->add('users/list-all', [
        'users' => $all,
        'title' => "Active users",
         'userinfo' => $this->users
    ], 'main');
    $this->views->add('users/usermenu', [], 'sidebar');

}


public function discardedAction()

{
    if ($this->users->isLoggedIn()) {
    
    $all = $this->users->query()
        ->where('deleted is NOT NULL')
        ->execute();
 
    $this->theme->setTitle("Trashcan");
    $this->views->add('users/list-deleted', [
        'users' => $all,
        'title' => "Trashcan",
    ], 'main');
    $this->views->add('users/usermenu', [], 'sidebar');
    
    }
    
    else {
    
    $this->views->add('users/login-admin-message', [
    ], 'flash');
    
    }
}

public function createTableAction()
{


    $this->db->dropTableIfExists('user')->execute();
 
    $this->db->createTable(
        'user',
        [
            'id' => ['integer', 'primary key', 'not null', 'auto_increment'],
            'acronym' => ['varchar(50)', 'unique', 'not null'],
            'email' => ['varchar(80)'],
            'name' => ['varchar(80)'],
            'password' => ['varchar(255)'],
            'created' => ['datetime'],
            'updated' => ['datetime'],
            'deleted' => ['datetime'],
            'active' => ['datetime'],
            'gravatar' => ['varchar(255)'],
        ]
    )->execute();
    
 
    }
    
    public function FillTableAction() 
    {
    
    $this->db->insert(
        'user',
        ['acronym', 'email', 'name', 'password', 'created', 'active', 'gravatar']
    );
 
    $now = date('Y-m-d H:i:s');
 
    $this->db->execute([
        'admin',
        'admin@dbwebb.se',
        'Administrator',
        password_hash('admin', PASSWORD_DEFAULT),
        $now,
        $now,
        'http://www.gravatar.com/avatar/' . md5(strtolower(trim('admin@dbwebb.se'))) . '.jpg'
    ]);
 
    $this->db->execute([
        'doe',
        'doe@dbwebb.se',
        'John/Jane Doe',
        password_hash('doe', PASSWORD_DEFAULT),
        $now,
        $now,
        'http://www.gravatar.com/avatar/' . md5(strtolower(trim('doe@dbwebb.se'))) . '.jpg'
     ]);
     
         $this->db->execute([
        'damian',
        'damian@post.utfors.se',
        'Damian',
        password_hash('dabu', PASSWORD_DEFAULT),
        $now,
        $now,
        'http://www.gravatar.com/avatar/' . md5(strtolower(trim('damian@post.utfors.se'))) . '.jpg'
     ]);
     
    
    
}

  public function setupAndFillAction() 
  {
  
    $this->CreateTableAction();
    $this->FillTableAction();
    
  
  }

  public function noSuchUserAction()
  {
  
  $this->theme->setTitle("Error");
    $this->views->add('default/error', [
	'title' => "Error",
	'content' => "User does not exists.",
    ], 'main');
    $this->views->add('users/usermenu', [], 'sidebar');
  }

}