<?php
namespace dabu\Content;
 
/**
 * A controller for content and admin related events.
 *
 */
class TagBasicController implements \Anax\DI\IInjectionAware
{
    use \Anax\DI\TInjectable;
    
 
/**
 * Initialize the controller.
 *
 * @return void
 */
public function initialize()
{
    $this->content = new \dabu\Content\TagBasic();
    $this->content->setDI($this->di);
    $this->user = new \Anax\Users\User();
    $this->user->setDI($this->di);
}

/**
 * List all content.
 *
 * @return void
 */
public function listAction()
{
 
    $all = $this->content->findAll();
    
    $this->theme->setTitle("Tags");
    
    $this->views->add('tags/list-all', [
        'content' => $all,
        'title' => "Tags",
    ], 'main');

}


/**
 * List content with id.
 *
 * @param int $id of content post to display
 *
 * @return void
 */
public function idAction($id = null)
{
    $post = $this->content->find($id);
    
    if(empty($post)) {
	
	$url = $this->url->create('tag-basic/invalid-dbresult');
	$this->response->redirect($url);
	
	}
 
    $this->theme->setTitle("Content");
    $this->views->add('tags/view', [
        'controller' => 'tag-basic',
        'post' => $post,
    ], 'main');

}

/**
 * Setup table.
 *
 * @return void
 */

public function setupContentAction()
{

    //$this->db->setVerbose();
 
    $this->db->dropTableIfExists('tagbasic')->execute();
 
    $this->db->createTable(
        'tagbasic',
        [
            'id' => ['integer', 'primary key', 'not null', 'auto_increment'],
            'tagname' => ['varchar(20)', 'not null', 'unique'],
            'tagslug' => ['varchar(25)', 'not null', 'unique']
        ]
    )->execute();
    
       
    }
    
    /**
    * Populate the database with some test data.
    *
    * @return void
    */
    
    public function autoPopulateAction()
    {
    

    $this->db->insert(
        'tagbasic',
        ['tagname', 'tagslug']
    );
 

    $this->db->execute([
        'patch',
        'patch'
    ]);
 
    $this->db->execute([
        'middle',
        'middle'
     ]);
     
     $this->db->execute([
        'top',
        'top'
     ]);
     
      $this->db->execute([
        'jungle',
        'jungle'
    ]);
 
    $this->db->execute([
        'bottom',
        'bottom'
     ]);
     
     $this->db->execute([
        'support',
        'support'
     ]);
     
      $this->db->execute([
        'items',
        'items'
    ]);
 
    $this->db->execute([
        'champion',
        'champion'
     ]);
     
     $this->db->execute([
        'adc',
        'adc'
     ]);
     
     $this->db->execute([
        'tank',
        'tank'
     ]);

     $this->db->execute([
        'runes',
        'runes'
     ]);
     $this->db->execute([
        'masteries',
        'masteries'
     ]);
     $this->db->execute([
        'skins',
        'skins'
     ]);
     $this->db->execute([
        'build',
        'build'
     ]);
     $this->db->execute([
        'bugs',
        'bugs'
     ]);
     $this->db->execute([
        'news',
        'news'
     ]);
  
}

  public function setupPopulateAction() 
  {
  
    $this->setupContentAction();
    $this->autoPopulateAction();
    
  
  }
  
    public function invalidInputAction()
  {
  
  $this->theme->setTitle("Error");
    $this->views->add('default/error', [
	'title' => "Something went wrong",
	'content' => "Informations are missing to show the page:<br>".$this->di->request->getGet('url'),
    ], 'main');
  }
    
     public function invalidDbresultAction()
  {
  
  $this->theme->setTitle("Error");
    $this->views->add('default/error', [
	'title' => "Something went wrong",
	'content' => "Searching in databes gave no results",
    ], 'main');
  }

}