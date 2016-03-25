<?php

namespace Application;


class Module
{
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'Application\Model\UsersTable' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $table = new \Application\Model\UsersTable($dbAdapter, $sm);
                    return $table;
                },
                'Application\Model\TokensTable' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $table = new \Application\Model\TokensTable($dbAdapter, $sm);
                    return $table;
                },
                'Application\Model\PostsTable' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $table = new \Application\Model\PostsTable($dbAdapter, $sm);
                    return $table;
                },
                'Application\Model\Token' => function ($sm) {
                    $table = new \Application\Model\Token($sm);
                    return $table;
                },
                'Application\Model\User' => function ($sm) {
                    $table = new \Application\Model\User($sm);
                    return $table;
                },
                'Application\Model\Post' => function ($sm) {
                    $table = new \Application\Model\Post($sm);
                    return $table;
                },
            ),
        );
    }
}
