<?php

namespace Phpmvc\Comment;

/**
 * To attach comments-flow to a page or some content.
 *
 */
class CommentsController implements \Anax\DI\IInjectionAware
{
    use \Anax\DI\TInjectable;

    private $errormessage;

    /**
     * View all comments.
     *
     * @return void
     */
    public function viewAction($pagekey = null, $formvisibility = null, $redirect='', $pagetype = null, $formid = null)
    {

	$controller = 'comments';
        
        $comments = new \dabu\Comments\Comments();
        $comments->setDI($this->di);
        $all = $comments->findAll($pagekey, $pagetype);
        
        $user = new \dabu\Users\User();
        $user->setDI($this->di);
        $acronym = $user->getLoggedInUser();
             
        if ($user->isLoggedIn()) {
        
	  if ($this->getFormVisibility() == 'show-form' && $formid == $this->getFormId()) {
	  
      
	    $this->showFormAction($pagekey, $redirect, $acronym, $pagetype, $formid);
	  }
	  
	  else {
	   $this->showHideForm($pagekey, $redirect, $formid);
	  }
        }
        
        else {
	  //$this->hideForm();
        }

        $this->views->add('comment/comments', [
            'comments' => $all,
            'pagekey'   => $pagekey,
            'redirect'  => $redirect,
            'controller' => $controller,
            'user' => $user,
        ]);
    }
       
    public function getFormVisibility() 
    {
	$formvisibility = $this->di->request->getGet('form');
	return $formvisibility;

    }
    
    public function getFormId() 
    {
      $formid = $this->di->request->getGet('formid');
      return $formid;
    } 

        
    public function showFormAction($pagekey, $redirect, $acronym, $pagetype, $formid) 
    {
	$form = new \dabu\HTMLForm\CFormCommentAdd($pagekey, $redirect, $acronym, $pagetype, $formid);
	$form->setDI($this->di);
	$form->check();
	
	$this->di->views->add('me/page', [
	'content' => $form->getHTML().$undourl, 
	], 'main');
        
    }
    

    
    public function hideForm() 
    {
    
      $this->di->views->addString('Logga in för att kommentera', 'main');
    }

    public function showHideForm($pagekey, $redirect, $formid) 
    {
	$this->di->views->add('comment/getformhide', [
	'redirect' => $redirect,
	'pagekey' => $pagekey,
	'formid' => $formid,
	], 'main');
    }
    
    /**
    *
    * Edit a comment
    *
    * @param string $pagekey selects the array with the page-id.
    * @param $id selects the comment to edit.
    *
    */      
    public function editAction($pagekey, $id, $redirect='')
    {
 
   	
    	$form1 = new \dabu\HTMLForm\CFormCommentUndo($redirect);
	$form1->setDI($this->di);
	$form1->check();
	$undourl = $form1->getHTML();
	    
        $comments = new \dabu\Comments\Comments();
        $controller = 'comments';
        $comments->setDI($this->di);
        
        $comment = $comments->findComment($pagekey, $id);
        
        if(empty($comment)) {
	
	$url = $this->url->create('comments/invalid-dbresult');
	$this->response->redirect($url);
	
	}
        
        $comment = (is_object($comment[0])) ? get_object_vars($comment[0]) : $comment;
        
        $user = new \dabu\Users\User();
        $user->setDI($this->di);

        $form = new \dabu\HTMLForm\CFormCommentEdit($id, $comment['content'], $comment['name'], $pagekey, 'questions/id/'.$pagekey);
	$form->setDI($this->di);
	$form->check();
        
        $this->theme->setTitle("Redigera kommentar");
        
        if ($user->isLoggedIn()) {
	  if ($user->getLoggedInUser() == $comment['name']) {
        
        $this->di->views->add('default/page', [
	    'title' => "Redigera kommentar",
	    'content' => '<h4>Kommentarid #'.$id.'</h4>'.$form->getHTML().$undourl, 
	    ], 'main');
	    
	  }
	  
	   else {
	 $this->views->add('users/loginedit-message', [
	  ], 'flash'); 
	 
	 }
	}
	else {
	 $this->views->add('users/login-message', [
	  ], 'flash'); 
	 }
        
    }
    
    public function setupCommentAction() 
    {
 
      $this->di->db->dropTableIfExists('comments')->execute();
  
      $this->di->db->createTable(
	  'comments',
	  [
	      'id' => ['integer', 'primary key', 'not null', 'auto_increment'],
	      'content' => ['text', 'not null'],
	      'name'    => ['varchar(80)'],
	      'pagekey' => ['integer'],
	      'pagetype' => ['varchar(80)'],
	      'timestamp' => ['datetime'],
	      'updated' => ['datetime'],
	      'ip'      => ['varchar(80)']
	      
	  ]
      )->execute();
    }
    
    public function autoPopulateAction() 
    {
      $this->di->db->insert(
	  'comments',
	  ['content', 'name', 'pagekey', 'pagetype', 'timestamp', 'updated', 'ip']
      );
  
      $now = date('Y-m-d H:i:s');
  
      $this->di->db->execute([
	  'En första kommentar',
	  'admin',
	  '1',
	  'questions',
	  $now,
	  null,
	  $this->di->request->getServer('REMOTE_ADDR')
      ]);
      
	  $this->di->db->execute([
	  'Hej!',
	  'damian',
	  '2',
	  'answer',
	  $now,
	  null,
	  $this->di->request->getServer('REMOTE_ADDR')
      ]);
      

    
    }

  public function setupPopulateAction() 
  {
  
    $this->setupCommentAction();
    $this->autoPopulateAction();
    
  
  }

    /**
     * Remove all comments.
     *
     * @return void
     */
    public function removeAllAction()
    {
        $comments = new \dabu\Comments\Comments();
        $comments->setDI($this->di);
                
        $comments->deleteAll();
        
        $this->theme->setTitle("Raderat");
        
        $this->di->views->add('default/page', [
	    'title' => "Raderat",
	    'content' => 'Kommentarer har tagits bort.', 
	    ], 'main');
        
        
    }
    
      public function invalidInputAction()
  {
  
  $this->theme->setTitle("Fel");
    $this->views->add('default/error', [
	'title' => "Något blev fel",
	'content' => "Information saknas för att kunna visa sidan:<br>".$this->di->request->getGet('url'),
    ], 'main');
  }
    
     public function invalidDbresultAction()
  {
  
  $this->theme->setTitle("Fel");
    $this->views->add('default/error', [
	'title' => "Något blev fel",
	'content' => "En sökning i databasen gav inga resultat",
    ], 'main');
  }
    

}
