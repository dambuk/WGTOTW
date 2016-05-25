<?php
require __DIR__.'/config_with_app.php'; 
$app->url->setUrlType(\Anax\Url\CUrl::URL_CLEAN); 
$app->navbar->configure(ANAX_APP_PATH . 'config/navbar-me.php');
$app->theme->configure(ANAX_APP_PATH . 'config/theme-grid.php');


$app->router->add('', function() use ($app) {
    
    $app->theme->setTitle("New Theme Page");
    
    // Get view content and store in variables.
    $main = $app->fileContent->get('theme/main.md');
    $main = $app->textFilter->doFilter($main, 'shortcode, markdown');
    $sidebar = $app->fileContent->get('theme/sidebar.md');
    $sidebar = $app->textFilter->doFilter($sidebar, 'shortcode, markdown');
    $footerCol = $app->fileContent->get('theme/footer-col.md');
    $footerCol = $app->textFilter->doFilter($footerCol, 'shortcode, markdown');
    $triptychCol = $app->fileContent->get('theme/triptych.md');
    $triptychCol = $app->textFilter->doFilter($triptychCol, 'shortcode, markdown');

    
    // Create views with attached content
    $app->views->add('default/article', ['content' => $main], 'main')
               ->add('default/article', ['content' => $sidebar], 'sidebar')
                ->addString('<i class="fa fa-clock-o fa-3x"></i> <br>Featured 1', 'featured-1')
                ->addString('<i class="fa fa-internet-explorer fa-3x"></i> <br>Featured 2', 'featured-2')
                ->addString('<i class="fa fa-thumbs-o-up fa-3x"></i> <br>Featured 3', 'featured-3')

               ->add('default/article', ['content' => $triptychCol], 'triptych-1')
               ->add('default/article', ['content' => $triptychCol], 'triptych-2')
               ->add('default/article', ['content' => $triptychCol], 'triptych-3')
                ->add('default/article', ['content' => $footerCol], 'footer-col-1')
                ->add('default/article', ['content' => $footerCol], 'footer-col-2')
                ->add('default/article', ['content' => $footerCol], 'footer-col-3')
                ->add('default/article', ['content' => $footerCol], 'footer-col-4');
});

$app->router->add('regions', function() use ($app) {
    $app->theme->setTitle('Regions');
    $app->theme->addStylesheet('css/anax-grid/regions_demo.css');

    $app->views->add('default/article', [
        'content' => null,
    ]);

    $app->views->addString('flash', 'flash')
               ->addString('featured-1', 'featured-1')
               ->addString('featured-2', 'featured-2')
               ->addString('featured-3', 'featured-3')
               ->addString('main', 'main')
               ->addString('sidebar', 'sidebar')
               ->addString('triptych-1', 'triptych-1')
               ->addString('triptych-2', 'triptych-2')
               ->addString('triptych-3', 'triptych-3')
               ->addString('footer-col-1', 'footer-col-1')
               ->addString('footer-col-2', 'footer-col-2')
               ->addString('footer-col-3', 'footer-col-3')
               ->addString('footer-col-4', 'footer-col-4');
 

});

$app->router->add('typography', function() use ($app){
    $app->theme->setTitle("Typography");

    $content = $app->fileContent->get('typography.html');

    $app->views->add('default/article', ['content' => $content], 'main')
                ->add('default/article', ['content' => $content], 'sidebar');

});

$app->router->add('fontawesome', function() use ($app) {
    
    $app->theme->setTitle("Font Awesome");
    
    $contentMain = $app->fileContent->get('fa-main.html');
    $contentSidebar = $app->fileContent->get('fa-sidebar.html');
    
    $app->views->add('default/article', ['content' => $contentMain], 'main')
               ->add('default/article', ['content' => $contentSidebar], 'sidebar');
    
});

 
$app->router->handle();
$app->theme->render();