<?php
namespace dabu\Content;

class QuestionsController implements \Anax\DI\IInjectionAware
{
    use \Anax\DI\TInjectable;


    public function initialize()
    {
        $this->content = new \dabu\Content\Questions();
        $this->content->setDI($this->di);

        $this->user = new \dabu\Users\User();
        $this->user->setDI($this->di);

        $this->contenttags = new \dabu\Content\ContentTag();
        $this->contenttags->setDI($this->di);

        $this->tags = new \dabu\Content\TagBasic();
        $this->tags->setDI($this->di);

    }
    /*

    Show all questions

    */
    public function listAction()
    {
        $all = $this->content->findAllMatches('answer', 'id', 'pagekey');

        if(empty($all))
        {
            $url = $this->url->create('questions/invalid-dbresult');
            $this->response->redirect($url);

        }

        $this->theme->setTitle("Questions");

        if($this->user->isLoggedIn())
        {

            $this->views->add('contenttags/new-question', [], 'flash');
        }
        else
        {
            $this->views->add('users/login-message', [], 'flash');
        }

        $this->views->add('contenttags/list-all-headers', [
            'content' => $all,
            'user' => $this->user,
            'subtitle' => "Latest Questions",
            ], 'main');

     }

    /*

    Show latest questions

    */
    public function showLatestAction($num)
    {

        $all = $this->content->findAllMatchesLim('answer', 'id', 'pagekey', $num);

        $this->views->add('contenttags/list-all-headers', [
            'content' => $all,
            'user' => $this->user,
            'subtitle' => "Latest Questions",
            'link' => "<a id='show-questions' href='".$this->url->create('questions/list')."'>Show all questions</a>",
            ], 'main');

    }


/**
 * List content by tag.
 *
 * @return void
 */
public function listByTagAction($tagid)
{
    if(empty($tagid)) {
    
    $url = $this->url->create('questions/invalid-input').'?url='.$this->di->request->getCurrentUrl();
    $this->response->redirect($url);
    
    }
 
    $all = $this->content->findAllMatchesByTag($tagid);
    $tag = $this->tags->find($tagid);
    
    
    if(empty($tag)) {
    
    $url = $this->url->create('questions/invalid-dbresult');
    $this->response->redirect($url);
    
    }
    $tagname = $tag->getProperties()['tagname'];
    
    $this->theme->setTitle("Questions in category: ".$tagname);
    $this->views->add('contenttags/list-all-headers', [
        'content' => $all,
        'user' => $this->user,
        'subtitle' => "Questions in category <em class='red'>".$tagname."</em>",
        'link' => "<p><a href='".$this->url->create('questions/list')."'>Show all questions <i class='fa fa-long-arrow-right'></i></a></p>",
    ], 'main');
    

}

/**
 * List content from a user.
 *
 * @return void
 */
public function listByUserAction($acronym)
{
 
    $all = $this->content->findAllMatchesUser('answer', 'id', 'pagekey', $acronym);
    
    $this->views->add('contenttags/list-all-headers', [
        'content' => $all,
        'user' => $this->user,
        'subtitle' => "Questions",
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
    
    $url = $this->url->create('questions/invalid-dbresult');
    $this->response->redirect($url);
     }
      
    
    $tags = $this->contenttags->findTagsByPost($id, 'tagbasic', 'questions');
    $issueposter = $post->getProperties()['acronym'];
   
    $this->theme->setTitle("Questions: ". $post->getProperties()['title']);
    $this->views->add('contenttags/questionslink', [
    ], 'flash');
   if ($this->user->isLoggedIn()) {

     
    }
    else 
    {
    $this->views->add('users/login-message', [
    ], 'flash');    
    }
    

    $this->views->add('contenttags/view', [
        'controller' => 'questions',
        'post' => $post,
        'tags' => $tags,
        'user' => $this->user,
    ], 'main');
        
    
    
    
    $this->di->dispatcher->forward([
        'controller' => 'comments',
        'action'     => 'view',
        'params'     => [$id, null,'questions/id/'.$id, 'questions', 'questions'],
    ]);


    $this->di->dispatcher->forward([
        'controller' => 'answer',
        'action'     => 'view',
        'params'     => [$id, null,'questions/id/'.$id, $issueposter],
    ]);

}




/**
 * Add new content.
 * Shows a view with a add content form 
 *
 * @return void
 */
public function addAction()
{
    $tags = $this->tags->findAll();
    $acronym = $this->user->getLoggedInUser();
    
    $taglist = array();
    
    foreach ($tags as $tag) {
        $taglist[] = $tag->getProperties()['tagname'];
    }

    $this->di->theme->setTitle("Ställ fråga");
    
     if ($this->user->isLoggedIn()) {

    $this->showFormAction('questions', $acronym, $taglist);
     }
     else {
     $this->views->add('users/login-message', [
    ], 'flash'); 
     }
    
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

    $content = $this->content->find($id);
    
    if(empty($content)) {
    
    $url = $this->url->create('questions/invalid-dbresult');
    $this->response->redirect($url);
     }
    
    $postacronym = $content->getProperties()['acronym'];
    $title = $content->getProperties()['title'];
    $data = $content->getProperties()['data'];

        
    $useracronym = $this->user->getLoggedInUser();
    $tags = $this->tags->findAll();
    $taglist = array();
    
    foreach ($tags as $tag) {
        $taglist[] = $tag->getProperties()['tagname'];
    }
    
    $checktags = $this->contenttags->findTagsByPost($id);
    $checked = array();
    
    foreach ($checktags as $tag) {
        $checked[] = $tag->getProperties()['tagname'];
    }


    $this->di->theme->setTitle("Edit questions");
    
     if ($this->user->isLoggedIn()) {
     
      if ($useracronym == $postacronym) {
    $this->showFormEditAction($id, $title, $data, $postacronym, $taglist, $checked, 'questions/id/'.$id);
    
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


/**
 * Shows an add form.
 *
 * @param $acronym of the original poster.
 * @param array $taglist all tags to choose from.
 *
 * @return void
 */
public function showFormAction($redirect, $acronym, $taglist) 
    {
    $form = new \dabu\HTMLForm\CFormIssueAdd($redirect, $acronym, $taglist);
    $form->setDI($this->di);
    $form->check();
        
    $this->di->views->add('default/page', [
       'title' => 'Ask a question',
       'content' => $form->getHTML(), 
    ], 'main');
    
        
    }
    
    /**
 * Shows an edit form.
 *
 * @param $acronym of the original poster.
 * @param array $taglist all tags to choose from.
 * @param $title of the original post.
 * @param array $checked all checked tags.
 * @param $id of the original post.
 *
 * @return void
 */
    
    public function showFormEditAction($id, $title, $data, $acronym, $taglist, $checked, $redirect) 
    {
    $form = new \dabu\HTMLForm\CFormIssueEdit($id, $title, $data, $acronym, $taglist, $checked, $redirect);
    $form->setDI($this->di);
    $form->check();
        
    $this->di->views->add('me/page', [
       'content' => $form->getHTML(), 
    ], 'main');
        
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
     if(empty($id)) {
    
    $url = $this->url->create('questions/invalid-dbresult');
    $this->response->redirect($url);
     }
 
    $this->content->delete($id);
 
    $url = $this->url->create('questions/list');
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
 
    $url = $this->url->create('questions/id/' . $id);
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
 
    $url = $this->url->create('questions/id/' . $id);
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
 
    $this->theme->setTitle("Published content");
    $this->views->add('content/list-all', [
        'content' => $all,
        'title' => "Published content",
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
 * List all soft-deleted content.
 *
 * @return void
 */

public function discardedAction()
{
    $all = $this->content->query()
        ->where('deleted is NOT NULL')
        ->execute();
 
    $this->theme->setTitle("Trash");
    $this->views->add('content/list-deleted', [
        'users' => $all,
        'title' => "Trash",
    ], 'main');

}

    /*
    
    Create table for questions


    */
    public function resetTableAction()
    {
        $this->db->dropTableIfExists('questions')->execute();

        $this->db->createTable(
            'questions',
            [
                'id' => ['integer', 'primary key', 'not null', 'auto_increment'],
                'title' => ['varchar(200)', 'not null'],
                'slug' => ['varchar(100)', 'unique'],
                'data' => ['text'],
                'acronym' => ['varchar(50)'],
                'created' => ['datetime'],
                'updated' => ['datetime'],
                'deleted' => ['datetime'],
                'published' => ['datetime'],
            ]
        )->execute();

    }

    /*

    Put some basic shit to database ;]

    */

    public function fillDatabaseAction()
    {
        $this->db->insert(
                'questions',
                ['title', 'slug', 'data', 'acronym', 'created', 'published']
            );

        $now = date('Y-m-d H:i:s');

        $this->db->execute([
            'Which champion is best to start play on mid lane? ',
            'which-champion-is-best-to-start-play-on-mid-lane',
            'I want to start play on mid lane and want to know which champion is best to start play with',
            'damian',
            $now,
            $now
        ]);

        $this->db->execute([
            'What do you think about new champion ?',
            'what-do-you-think-about-new-champion',
            'As I wrote in question, what do you think about him ?',
            'admin',
            $now,
            $now
        ]);

        $this->db->execute([
            'What are your feelings after new patch ?',
            'What-are-your-feelings-after-new-patch',
            'How feels nerfed and buffed champions, how does it feel to play with them after patch',
            'admin',
            $now,
            $now,
        ]);

}
        public function setupAndFillAction()
        {
            $this->resetTableAction();
            $this->fillDatabaseAction();
        }






}