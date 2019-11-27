<?php
/**
 * Sea-Port Recommendation System
 *
 * @category   Application_Core
 * @package    seaport-recommender
 * @author     Suman Barua
 * @developer  Suman Barua <sumanbarua576@gmail.com>
 */

/**
 * @category   Application_Core
 * @package    seaport-recommender
 */

return array(
    'landing_home' => array(
        'route' => '/:action/*',
        'defaults' => array(
            'module' => 'default',
            'controller' => 'index',
            'action' => 'index'
        ),
        'reqs' => array(
            'controller' => '\D+',
            'action' => '\D+',
        )
    ),
    'member_login' => array(
        'route' => 'login/*',
        'defaults' => array(
            'module' => 'default',
            'controller' => 'auth',
            'action' => 'login'
        ),
        'reqs' => array(
            'action' => '\D+',
            'controller' => '\D+'
        )
    ),
    'member_logout' => array(
        'route' => 'logout/*',
        'defaults' => array(
            'module' => 'default',
            'controller' => 'auth',
            'action' => 'logout'
        ),
        'reqs' => array(
            'action' => '\D+',
            'controller' => '\D+'
        )
    ),
    'member_signup' => array(
        'route' => 'register/*',
        'defaults' => array(
            'module' => 'default',
            'controller' => 'auth',
            'action' => 'register'
        ),
        'reqs' => array(
            'action' => '\D+',
            'controller' => '\D+'
        )
    ),
    'account_activation' => array(
        'route' => 'activation/*',
        'defaults' => array(
            'module' => 'default',
            'controller' => 'auth',
            'action' => 'activation'
        ),
        'reqs' => array(
            'action' => '\D+',
            'controller' => '\D+'
        )
    ),
    'reset_password' => array(
        'route' => 'reset/:key/*',
        'defaults' => array(
            'module' => 'default',
            'controller' => 'auth',
            'action' => 'reset'
        ),
        'reqs' => array(
            'action' => '\D+',
            'controller' => '\D+'
        )
    ),
    'activate_account' => array(
        'route' => 'activate/:key/*',
        'defaults' => array(
            'module' => 'default',
            'controller' => 'auth',
            'action' => 'activate'
        ),
        'reqs' => array(
            'action' => '\D+',
            'controller' => '\D+'
        )
    ),
    'admin_dashboard' => array(
        'route' => 'admin/:action/*',
        'defaults' => array(
            'module' => 'default',
            'controller' => 'admin',
            'action' => 'index'
        ),
        'reqs' => array(
            'action' => '\D+',
            'controller' => '\D+'
        )
    ),
    'user_dashboard' => array(
        'route' => 'dashboard/*',
        'defaults' => array(
            'module' => 'default',
            'controller' => 'user',
            'action' => 'index'
        ),
        'reqs' => array(
            'action' => '\D+',
            'controller' => '\D+'
        )
    ),
    'admin_port_general' => array(
        'route' => 'admin/sea-ports/:action/*',
        'defaults' => array(
            'module' => 'default',
            'controller' => 'admin-ports',
            'action' => 'browse'
        ),
        'reqs' => array(
            'id' => '\d+',
            'action' => '\D+',
            'controller' => '\D+'
        )
    ),
    'admin_vessel_general' => array(
        'route' => 'admin/vessels/:action/*',
        'defaults' => array(
            'module' => 'default',
            'controller' => 'admin-vessels',
            'action' => 'browse'
        ),
        'reqs' => array(
            'id' => '\d+',
            'action' => '\D+',
            'controller' => '\D+'
        )
    ),
    'admin_page_general' => array(
        'route' => 'admin/pages/:action/*',
        'defaults' => array(
            'module' => 'default',
            'controller' => 'admin-pages',
            'action' => 'browse'
        ),
        'reqs' => array(
            'action' => '\D+',
            'controller' => '\D+'
        )
    ),
    'admin_page_action' => array(
        'route' => 'admin/page/:id/:action/*',
        'defaults' => array(
            'module' => 'default',
            'controller' => 'admin-pages',
            'action' => ''
        ),
        'reqs' => array(
            'id' => '\d+',
            'controller' => '\D+',
            'action' => '(edit|delete)',
        )
    ),
    'admin_mail_general' => array(
        'route' => 'admin/mails/:action/*',
        'defaults' => array(
            'module' => 'default',
            'controller' => 'admin-mail',
            'action' => 'browse'
        ),
        'reqs' => array(
            'action' => '\D+',
            'controller' => '\D+'
        )
    ),
    'admin_mail_action' => array(
        'route' => 'admin/mail/:id/:action/*',
        'defaults' => array(
            'module' => 'default',
            'controller' => 'admin-mail',
            'action' => ''
        ),
        'reqs' => array(
            'id' => '\d+',
            'controller' => '\D+',
            'action' => '(edit|status|delete|test-mail|reset)',
        )
    ),
);
?>
