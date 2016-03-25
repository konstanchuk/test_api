<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        $connection = true;
        try {
            $dbInstance = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
            $dbInstance->getDriver()->getConnection()->connect();
        } catch (\Exception $e) {
            $connection = false;
        }
        return new ViewModel(array('db' => array(
            'connection' => $connection
        )));
    }
}