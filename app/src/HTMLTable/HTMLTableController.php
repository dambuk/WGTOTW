<?php
namespace dabu\HTMLTable;
 
/**
 * A controller for content and admin related events.
 *
 */
class HTMLTableController implements \Anax\DI\IInjectionAware
{
    use \Anax\DI\TInjectable;
 
/**
 * Initialize the controller.
 *
 * @return void
 */
public function initialize()
{
    $this->table = new \dabu\HTMLTable\HTMLTable();
    $this->table->setDI($this->di);
}

/**
 * List all content.
 *
 * @return void
 */
public function listAction()
{
 
    $all = $this->content->findAll();
    
    $this->theme->setTitle("Innehåll");
    $this->views->add('content/list-all', [
        'content' => $all,
        'title' => "Allt innehåll",
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
 
    $this->theme->setTitle("Innehåll");
    $this->views->add('content/view', [
        'post' => $post,
    ], 'main');
    //$this->views->add('users/adminmenu', [], 'sidebar');
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
    
    //$info = $this->di->fileContent->get('users-addinfo.md');
    //$info = $this->di->textFilter->doFilter($info, 'shortcode, markdown');
  
    $this->di->theme->setTitle("Lägg till innehåll");
    $this->di->views->add('default/page', [
        'title' => "Lägg till innehåll",
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
    
    //$info = $this->di->fileContent->get('users-editinfo.md');
    //$info = $this->di->textFilter->doFilter($info, 'shortcode, markdown');
    
    $this->di->theme->setTitle("Redigera innehåll");
    $this->di->views->add('default/page', [
        'title' => "Redigera innehåll",
        'content' => $form->getHTML()
        ]);

    

}

/*
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
*/

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
 
    $url = $this->url->create('content');
    $this->response->redirect($url);
}


/**
 * Undo soft delete.
 *
 * @param integer $id of content to undo delete.
 *
 * @return void
 */
 

public function undoDeleteAction($id = null)
{
    if (!isset($id)) {
        die("Missing id");
    }
 
    $content = $this->content->find($id);
    
    $content->deleted = null;
    $content->save();
 
    $url = $this->url->create('content/id/' . $id);
    $this->response->redirect($url);
}


/**
 * Delete (soft) content.
 *
 * @param integer $id of content to delete.
 *
 * @return void
 */
public function softDeleteAction($id = null)
{
    if (!isset($id)) {
        die("Missing id");
    }
 
    $now = gmdate('Y-m-d H:i:s');
 
    $content = $this->content->find($id);
 
    $content->deleted = $now;
    $content->save();
 
    $url = $this->url->create('content/id/' . $id);
    $this->response->redirect($url);
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
 
    $this->theme->setTitle("Publicerat innehåll");
    $this->views->add('content/list-all', [
        'content' => $all,
        'title' => "Publicerat innehåll",
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
 
    $this->theme->setTitle("Opublicerat innehåll");
    $this->views->add('content/list-all', [
        'content' => $all,
        'title' => "Opublicerat innehåll",
    ], 'main');

}

/**
 * List all soft-deleted content.
 *
 * @return void
 */

public function discardedAction()
{
    $all = $this->content->query()
        ->where('deleted is NOT NULL')
        ->execute();
 
    $this->theme->setTitle("Papperskorgen");
    $this->views->add('content/list-deleted', [
        'users' => $all,
        'title' => "Papperskorgen",
    ], 'main');

}

/**
 * Setup table.
 *
 * @return void
 */

public function setupContentAction()
{

    $this->db->setVerbose();
 
    $this->db->dropTableIfExists('content')->execute();
 
    $this->db->createTable(
        'content',
        [
            'id' => ['integer', 'primary key', 'not null', 'auto_increment'],
            'title' => ['varchar(100)', 'not null'],
            'slug' => ['varchar(100)', 'unique'],
            'url' => ['varchar(100)', 'unique'],
            'type' => ['varchar(80)'],
            'data' => ['text'],
            'filter' => ['varchar(80)'],
            'acronym' => ['varchar(20)'],
            'created' => ['datetime'],
            'updated' => ['datetime'],
            'deleted' => ['datetime'],
            'published' => ['datetime'],
        ]
    )->execute();
    /*
    $this->db->insert(
        'user',
        ['acronym', 'email', 'name', 'password', 'created', 'active']
    );
 
    $now = date('Y-m-d H:i:s');
 
    $this->db->execute([
        'admin',
        'admin@dbwebb.se',
        'Administrator',
        password_hash('admin', PASSWORD_DEFAULT),
        $now,
        $now
    ]);
 
    $this->db->execute([
        'doe',
        'doe@dbwebb.se',
        'John/Jane Doe',
        password_hash('doe', PASSWORD_DEFAULT),
        $now,
        $now
     ]);
     
         $this->db->execute([
        'maria',
        'choklad@post.utfors.se',
        'Maria',
        password_hash('maria', PASSWORD_DEFAULT),
        $now,
        null
     ]);
     */
     /*
     $this->dispatcher->forward([
        'controller' => 'users',
        'action'     => 'list',
        //'params'     => [],
    ]);
    */
    
}

} 