<?php
namespace dabu\Content;
 
/**
 * A controller for content and admin related events.
 *
 */
class ContentController extends ContentBasicController 
{
    
    private $columns;
 
/**
 * Initialize the controller.
 *
 * @return void
 */
public function initialize()
{
    $this->content = new \dabu\Content\Content();
    $this->content->setDI($this->di);
    
    $this->controllername = 'content';
    $this->dbname = 'content';
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
    $this->views->add('content/view', [
        'controller' => $this->controllername,
        'post' => $post,
    ], 'main');

}

/**
 * List all unpublished and not deleted content.
 *
 * @return void
 */
public function unpublishedAction()
{
    $all = $this->content->query()
        ->where('published IS NULL')
        ->andWhere('deleted is NULL')
        ->execute();
 
    $this->theme->setTitle("Unpublished content");
    $this->views->add('content/list-all', [
        'content' => $all,
        'title' => "Unpublished content",
    ], 'main');

}

/**
 * List content as html table based on selected columns
 *
 * @return void
 */
public function listAction()
{
    $columnlist = $this->setColumns();
    

    $all = $this->content
        ->query($columnlist)
        ->execute();
    
    $table = new \dabu\HTMLTable\SimpleHTMLTable;
    $contenttable = $table->createTable($this->columns, $all);
    
    $this->theme->setTitle("Content");
    $this->views->add('default/page', [
        'content' => $contenttable,
        'title' => "Whole content",
    ], 'main');
    
}
/**
 * List all published and not deleted content.
 *
 * @return void
 */
public function publishedAction()
{
    $all = $this->content->query()
        ->where('published IS NOT NULL')
        ->andWhere('deleted is NULL')
        ->execute();
 
    $this->theme->setTitle("Published content");
    $this->views->add('content/list-all', [
        'content' => $all,
        'title' => "Published content",
    ], 'main');

}

public function discardedAction()
{

  $columnlist = $this->setColumns();
    

    $all = $this->content
      ->query($columnlist)
      ->where('deleted is NOT NULL')
      ->execute();
      
    $table = new \dabu\HTMLTable\SimpleHTMLTable;
    $contenttable = $table->createTable($this->columns, $all);
 
    $this->theme->setTitle("Trashcan");
    $this->views->add('default/page', [
        'content' => $contenttable,
        'title' => "Trashcan",
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
 
    $form = new \Anax\HTMLForm\CFormContentAdd();
    $form->setDI($this->di);
    $status = $form->check();
    
    $this->di->theme->setTitle("Add content");
    $this->di->views->add('default/page', [
        'title' => "Add content",
        'content' => $form->getHTML(), 
        
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

    if (!isset($id)) {
        die("Missing id");
    }
    
    $content = $this->content->find($id);
    $title = $content->getProperties()['title'];
    $url = $content->getProperties()['url'];
    $slug = $content->getProperties()['slug'];
    $data = $content->getProperties()['data'];
    $acronym = $content->getProperties()['acronym'];
    $filter = $content->getProperties()['filter'];
    $type = $content->getProperties()['type'];
    $deleted = $content->getProperties()['deleted'];
    $published = $content->getProperties()['published'];
    
    $form = new \Anax\HTMLForm\CFormContentEdit($id, $title, $url, $slug, $data, $acronym, $filter, $type, $published, $deleted);
    $form->setDI($this->di);
    $status = $form->check();
    
    $this->di->theme->setTitle("Edit content");
    $this->di->views->add('default/page', [
        'title' => "Edit content",
        'content' => $form->getHTML()
        ]);

    

}

  public function setColumns() 
  {
  
      $url = $this->url->create($this->controllername.'/id');

    $this->columns = array([
      'name' => 'id',
      'label' => 'ID',
    ],
    [
      'name' => 'title',
      'label' => 'Rubrik',
      'linkbase' => $url.'/',
      'linkkey' => 'id',
    ],
    [
      'name' => 'acronym',
      'label' => 'Av',
      ],
    [
      'name' => 'published',
      'label' => 'Publicerad',
      'display' => 'yes-no',
    ],
    [
      'name' => 'created',
      'label' => 'Skapad',
      'display' => 'convert-datestr',
      'displayformat' => 'Y-m-d H:i',
    ],
    [
      'name' => 'type',
      'label' => 'Typ',
    ]
    );
    
    $columnlist = '';
    if (!empty($this->columns)) {
        foreach ($this->columns as $column) {
         $columnlist .= $column['name'].', ';
        }
        $columnlist = trim($columnlist, ', ');
    }
    else $columnslist = '*';
    
    return $columnlist;
  }



} 