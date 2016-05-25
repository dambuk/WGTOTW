<?php
require __DIR__.'/config_with_app.php';
$app->session();
$di->setShared('db', function() {
    $db = new \Mos\Database\CDatabaseBasic();
    $db->setOptions(require ANAX_APP_PATH . 'config/config_mysql.php');
    $db->connect();
    return $db;
});

$di->set('CommentsController', function() use ($di) {
    $controller = new \Phpmvc\Comment\CommentsController();
    $controller->setDI($di);
    return $controller;
});

$di->set('QuestionsController', function() use ($di) {
    $controller = new \dabu\Content\QuestionsController();
    $controller->setDI($di);
    return $controller;
});

$di->set('AnswerController', function() use ($di) {
    $controller = new \Phpmvc\Comment\AnswerController();
    $controller->setDI($di);
    return $controller;
});

$di->set('TagBasicController', function() use ($di) {
    $controller = new \dabu\Content\TagBasicController();
    $controller->setDI($di);
    return $controller;
});

$di->set('ContentTagController', function() use ($di) {
    $controller = new \dabu\Content\ContentTagController();
    $controller->setDI($di);
    return $controller;
});

$di->set('FormController', function () use ($di) {
    $controller = new \Anax\HTMLForm\FormController();
    $controller->setDI($di);
    return $controller;
});

$di->set('UserLoginController', function() use ($di){
    $controller = new \dabu\Users\UserLoginController();
    $controller->setDI($di);
    return $controller;
});

$di->set('UsersController', function() use ($di) {
    $controller = new \dabu\Users\UsersController();
    $controller->setDI($di);
    return $controller;
});

$di->set('form', '\Mos\HTMLForm\CForm');

$app = new \Anax\MVC\CApplicationBasic($di);
$app->theme->configure(ANAX_APP_PATH . 'config/theme-wgtotw.php'); 
$app->url->setUrlType(\Anax\Url\CUrl::URL_CLEAN); 
$app->navbar->configure(ANAX_APP_PATH . 'config/navbar-wgtotw.php');


$app->router->add('', function() use ($app) {
    $app->theme->setTitle('Home');
    $main = $app->fileContent->get('home.md'); 
    $main = $app->textFilter->doFilter($main, 'shortcode, markdown');

    
    $app->views->add('me/page', [ 
        'content' => $main, 
            ], 'flash'
    ); 
    $app->dispatcher->forward([
        'controller' => 'questions',
        'action' => 'show-latest',
        'params' => [10],
    ]);
    
    $app->dispatcher->forward([
    'controller' => 'users',
    'action'     => 'list-most-active',

    ]);
        $app->dispatcher->forward([
    'controller' => 'content-tag',
    'action'     => 'list-most-used',
    'params'     => ['tagid', 8, 'sidebar'],
    ]);


});

$app->router->add('questions', function() use ($app) {
        $app->dispatcher->forward([
        'controller' => 'questions',
        'action'     => 'list',
    ]);
});

$app->router->add('about', function() use ($app) {
  
    $app->theme->setTitle("About");
    
    $content = $app->fileContent->get('about.md');
    $content = $app->textFilter->doFilter($content, 'shortcode, markdown');
  
    $app->views->add('me/page', [
    'title' => null,
        'content' => $content,
    ], 'flash');
    

});
 

$app->router->add('users', function() use ($app) {
    $app->dispatcher->forward([
    'controller' => 'users',
    'action'     => 'list',
    ]);
});

$app->router->add('login', function() use ($app) {

    $app->dispatcher->forward([
    'controller' => 'user-login',
    'action'     => 'show-login',
    ]);
});

$app->router->add('tags', function() use ($app) {
    $app->dispatcher->forward([
    'controller' => 'tag-basic',
    'action'     => 'list',
    ]);
    
    $app->dispatcher->forward([
    'controller' => 'content-tag',
    'action'     => 'list-most-used',
    'params'     => ['tagid', 5, 'sidebar'],
    ]);
});


$app->router->add('setup', function() use ($app) {
 
    $app->db->setVerbose();
    $app->theme->setTitle("Återställ databasen");
    
    $app->dispatcher->forward([
        'controller' => 'questions',
        'action' => 'setup-and-fill',
        ]);
    $app->dispatcher->forward([
        'controller' => 'users',
        'action' => 'setup-and-fill',
        ]);

    $app->dispatcher->forward([
        'controller' => 'tag-basic',
        'action'     => 'setup-populate',
    ]);
     
    $app->dispatcher->forward([
        'controller' => 'content-tag',
        'action'     => 'setup-populate',
    ]);
    
    $app->dispatcher->forward([
        'controller' => 'answer',
        'action'     => 'setup-populate',
    ]);
    
    $app->dispatcher->forward([
        'controller' => 'comments',
        'action'     => 'setup-populate',
    ]);
});



$app->router->handle();
$app->theme->render();