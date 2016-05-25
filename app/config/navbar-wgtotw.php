<?php
/**
 * Config-file for navigation bar.
 *
 */
return [

    // Use for styling the menu
    'class' => 'navbar',
 
    // Here comes the menu strcture
    'items' => [

        
        // This is a menu item
        'home'  => [
            'text'  => 'Home',
            'url'   => $this->di->get('url')->create(''),
            'title' => 'Hem',
            'mark-if-parent-of' => 'home',
            'class' => 'Home'
        ],
 
        
        // This is a menu item
        'questions' => [
            'text'  =>'Questions',
            'url'   => $this->di->get('url')->create('questions'),
            'title' => 'Questions',
            'mark-if-parent-of' => 'questions',
            'class' => 'questions',
                ],


        // This is a menu item
        'tags' => [
            'text'  =>'Tags',
            'url'   => $this->di->get('url')->create('tags'),
            'title' => 'Tags',
            'class' => 'tags',
        ],
        
        
        // This is a menu item
        'users' => [
            'text'  =>'Users',
            'url'   => $this->di->get('url')->create('users'),
            'title' => 'Users', 
            'mark-if-parent-of' => 'users',
            'class' => 'users',
        ],

         // This is a menu item
        'about' => [
            'text'  =>'About',
            'url'   => $this->di->get('url')->create('about'),
            'title' => 'About',
            'class' => 'about',
        ],
        
        // This is a menu item
        'login' => [
            'text'  =>'Login/Logout',
            'url'   => $this->di->get('url')->create('login'),
            'title' => 'Login/Logout',
            'class' => 'login',
        ],
    ],
 


    /**
     * Callback tracing the current selected menu item base on scriptname
     *
     */
    'callback' => function ($url) {
        if ($url == $this->di->get('request')->getCurrentUrl(false)) {
            return true;
        }
    },



    /**
     * Callback to check if current page is a decendant of the menuitem, this check applies for those
     * menuitems that has the setting 'mark-if-parent' set to true.
     *
     */
    'is_parent' => function ($parent) {
        $route = $this->di->get('request')->getRoute();
        return !substr_compare($parent, $route, 0, strlen($parent));
    },



   /**
     * Callback to create the url, if needed, else comment out.
     *
     */
   /*
    'create_url' => function ($url) {
        return $this->di->get('url')->create($url);
    },
    */
];
