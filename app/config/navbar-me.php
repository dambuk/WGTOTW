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
            'title' => 'Home route of current frontcontroller'
        ],
 
        // This is a menu item
        'report' => [
            'text'  =>'Report',
            'url'   => $this->di->get('url')->create('Report'),
            'title' => 'Url to relative frontcontroller, other file',
        ],

        // This is a menu item
        'source' => [
            'text'  =>'Source',
            'url'   => $this->di->get('url')->create('Source'),
            'title' => 'Internal route within this frontcontroller'
        ],
        'comment' => [
            'text'  =>'Comments',
            'url'   => $this->di->get('url')->create('comments'),
            'title' => 'Lämna kommentarer',
            'mark-if-parent-of' => 'comments',
        ],
        'users' => [
            'text'  =>'Användare',
            'url'   => $this->di->get('url')->create('users'),
            'title' => 'Användare i databasen', 
            'mark-if-parent-of' => 'users',
            
            // Here we add the submenu, with some menu items, as part of a existing menu item
            'submenu' => [

                'items' => [

                    // This is a menu item of the submenu
                    'all'  => [
                        'text'  => 'All Users',
                        'url'   => $this->di->get('url')->create('users/list'),
                        'title' => 'Show All Users'
                    ],                    
                    // This is a menu item of the submenu
                    'active'  => [
                        'text'  => 'Active Users',
                        'url'   => $this->di->get('url')->create('users/active'),
                        'title' => 'Show Active Users'
                    ],
                    
                    // This is a menu item of the submenu
                    'inactive'  => [
                        'text'  => 'Inactive Users',
                        'url'   => $this->di->get('url')->create('users/inactive'),
                        'title' => 'Show Inactive Users'
                    ],
                    
                    // This is a menu item of the submenu
                    'add'  => [
                        'text'  => 'Add User',
                        'url'   => $this->di->get('url')->create('users/add'),
                        'title' => 'Add User'
                    ],
                     // This is a menu item of the submenu
                    'discarded'  => [
                        'text'  => 'Trashcan',
                        'url'   => $this->di->get('url')->create('users/discarded'),
                        'title' => 'Show Trashcan'
                    ],

                    // This is a menu item of the submenu
                    'setup'  => [
                        'text'  => 'Reset Database',
                        'url'   => $this->di->get('url')->create('setup'),
                        'title' => 'Reset Database',
                    ],
                ],
            ],
        ],
         'flash' => [
            'text'  =>'Flash',
            'url'   => $this->di->get('url')->create('flash'),
            'title' => 'flash',
        ],
        'Tema'  => [
            'text'  => 'Theme',
            'url'   => $this->di->get('url')->create('theme.php'),
            'title' => 'Submenu with url as internal route within this frontcontroller',

            // Here we add the submenu, with some menu items, as part of a existing menu item
            'submenu' => [

                'items' => [

                    // This is a menu item of the submenu
                    'regions'  => [
                        'text'  => 'Regions',
                        'url'   => $this->di->get('url')->create('theme.php/regions'),
                        'title' => 'Url as internal route within this frontcontroller'
                    ],

                    // This is a menu item of the submenu
                    'typography'  => [
                        'text'  => 'Typography',
                        'url'   => $this->di->get('url')->asset('theme.php/typography'),
                        'title' => 'Url to sitespecific asset',
                    ],

                    // This is a menu item of the submenu
                    'fontawesome'  => [
                        'text'  => 'Font Awesome',
                        'url'   => $this->di->get('url')->asset('theme.php/fontawesome'),
                        'title' => 'Url to asset relative to frontcontroller',
                    ],
                ],
            ],
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
