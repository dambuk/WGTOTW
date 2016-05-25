<?php
namespace dabu\Content;
 
/**
 * A controller for content and admin related events.
 *
 */
class ContentTagController implements \Anax\DI\IInjectionAware
{
    use \Anax\DI\TInjectable;
    
 
/**
 * Initialize the controller.
 *
 * @return void
 */
public function initialize()
{
    $this->content = new \dabu\Content\ContentTag();
    $this->content->setDI($this->di);
}

/**
 * List all content.
 *
 * @return void
 */
public function listAction()
{
 
    $all = $this->content->findAll();
    
    $this->theme->setTitle("Content");
    $this->views->add('tags/list-all', [
        'content' => $all,
        'title' => "All content",
    ], 'main');

}

public function listMostUsedAction($val, $num, $placement)
{
    $all = $this->content->findMostUsedTags($num);
    $this->views->add('tags/list-most-used', [
        'content' => $all,
        'subtitle' => "Popular tags",

    ], $placement);

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
 
    $this->theme->setTitle("Content");
    $this->views->add('tags/view', [
        'controller' => 'tag-basic',
        'post' => $post,
    ], 'main');

}

/**
 * Add new content.
 *
 * 
 *
 * @return void
 */
public function addAction()
{

    $this->di->theme->setTitle("Add content");
    $this->di->views->add('tags/add', [
        'title' => "Add content",
             
        ], 'main');

}



/**
 * Update content.
 *
 * @param $id of content to update.
 *
 * @return void
 */
public function updateAction($id = null)
{



}


/**
 * Delete content.
 *
 * @param integer $id of content to delete.
 *
 * @return void
 */
public function deleteAction($id = null)
{
    if (!isset($id)) {
        die("Missing id");
    }
 
    $res = $this->content->delete($id);
 
    $url = $this->url->create('tag-basic/list');
    $this->response->redirect($url);
}






/**
 * Setup table.
 *
 * @return void
 */

public function setupContentAction()
{

    //$this->db->setVerbose();
 
    $this->db->dropTableIfExists('contenttag')->execute();
 
    $this->db->createTable(
        'contenttag',
        [
            'tagid' => ['integer', 'not null'],
            'contentid' => ['integer', 'not null']
        ]
    )->execute();
    
       
    }
    
    public function autoPopulateAction()
    {
    

    $this->db->insert(
        'contenttag',
        ['tagid', 'contentid']
    );
 

    $this->db->execute([
        2,
        1
    ]);
 
    $this->db->execute([
        8,
        1
     ]);
     
     $this->db->execute([
        11,
        1
     ]);
     
      $this->db->execute([
        14,
        1
    ]);
 
    $this->db->execute([
        1,
        2
     ]);
     
     $this->db->execute([
        12,
        2
     ]);
     
      $this->db->execute([
        16,
        2
    ]);
 
    $this->db->execute([
        15,
        2
     ]);
     
     $this->db->execute([
        1,
        3
     ]);
     
     $this->db->execute([
        7,
        3
     ]);
     
     $this->db->execute([
        13,
        3
     ]);
     
      $this->db->execute([
        15,
        3
    ]);
 
    $this->db->execute([
        16,
        3
     ]);
     
    
    
}
    
  public function setupPopulateAction() 
  {
  
    $this->setupContentAction();
    $this->autoPopulateAction();
    
  
  }

}