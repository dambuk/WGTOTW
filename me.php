<?php
require __DIR__.'/config_with_app.php'; 
$app->url->setUrlType(\Anax\Url\CUrl::URL_CLEAN); 
$app->navbar->configure(ANAX_APP_PATH . 'config\navbar-me.php');
$app->theme->configure(ANAX_APP_PATH . 'config\theme-me.php');


$app->router->add('', function() use ($app) {
    $app->theme->setTitle('Home');
    $content = $app->fileContent->get('home.md'); 
    $content = $app->textFilter->doFilter($content, 'shortcode, markdown');

    $byline = $app->fileContent->get('byline.md');
    $byline = $app->textFilter->doFilter($byline, 'shortcode, markdown');

    $app->views->add('me/page', [ 
      'content' => $content, 
      'byline' => $byline, 
  ]); 

});
 
$app->router->add('Report', function() use ($app) {
 
    $app->theme->setTitle("Report");

    $content = $app->fileContent->get('report.md');
    $content = $app->textFilter->doFilter($content, 'shortcode, markdown');

    $byline = $app->fileContent->get('byline.md');
    $byline = $app->textFilter->doFilter($byline, 'shortcode, markdown');

    $app->views->add('me/page', [
        'content' => $content,
        'byline' => $byline,
        ]);
 
});
 
$app->router->add('source', function() use ($app) {
 
    $app->theme->addStylesheet('css/source.css');
    $app->theme->setTitle("Källkod");
 
    $source = new \Mos\Source\CSource([
        'secure_dir' => '..', 
        'base_dir' => '..', 
        'add_ignore' => ['.htaccess'],
    ]);
 
    $app->views->add('me/source', [
        'content' => $source->View(),
    ]);
 
});
$app->router->add('cform', function() use ($app) {

    $app->dispatcher->forward([
        'controller' => 'form',
        'action' => 'index',
        ]);

});


$app->router->handle();
$app->theme->render();