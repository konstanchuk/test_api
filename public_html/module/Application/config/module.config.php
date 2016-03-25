<?php

namespace Application;

return array(
    'router' => array(
        'routes' => array(
            'home' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller' => 'Application\Controller\Index',
                        'action' => 'index'
                    ),
                ),
            ),
            'user' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/api/user/[:action][/:format]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*/?',
                    ),
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller' => 'Application\Controller\User',
                        'action' => 'index'
                    ),
                ),
            ),
            'post' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/api/post/[:action][/:format]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*/?',
                    ),
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller' => 'Application\Controller\Post',
                        'action' => 'index'
                    ),
                ),
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Application\Controller\Index' => 'Application\Controller\IndexController',
            'Application\Controller\User' => 'Application\Controller\UserController',
            'Application\Controller\Post' => 'Application\Controller\PostController',
        ),
    ),
    'view_manager' => array(
        'strategies' => array(
            'ViewJsonStrategy',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
        'display_not_found_reason' => true,
        'display_exceptions' => true,
        'doctype' => 'HTML5',
        'not_found_template' => 'error/404',
        'exception_template' => 'error/exception'
    ),
    'console' => array(
        'router' => array(
            'routes' => array(),
        ),
    ),
);
