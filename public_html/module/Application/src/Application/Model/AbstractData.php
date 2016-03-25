<?php

namespace Application\Model;

use Zend\ServiceManager\ServiceLocatorInterface;

abstract class AbstractData
{
    protected $_sm;

    public function __construct(ServiceLocatorInterface $sm)
    {
        $this->_sm = $sm;
    }

    abstract public function exchangeArray($data);
}