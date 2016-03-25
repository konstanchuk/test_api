<?php

namespace Application\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\ServiceManager\ServiceLocatorInterface;

abstract class AbstractTable extends AbstractTableGateway
{
    protected $_sm;
    protected $_idColumn = 'id';

    public function __construct(Adapter $adapter, ServiceLocatorInterface $sm, $prototypeName)
    {
        $this->_sm = $sm;
        $this->adapter = $adapter;
        $this->resultSetPrototype = new ResultSet();
        $this->resultSetPrototype->setArrayObjectPrototype($sm->get($prototypeName));
        $this->initialize();
    }

    public function getById($id)
    {
        $rowset = $this->select(array(
            $this->_idColumn => $id,
        ));
        return $rowset->current();
    }

    public function deleteById($id)
    {
        $this->delete(array(
            $this->_idColumn => $id,
        ));
    }

    public function save($id, $data)
    {
        if ($id == 0) {
            $this->insert($data);
        } elseif ($this->getById($id)) {
            $this->update(
                $data,
                array(
                    $this->_idColumn => $id,
                )
            );
        } else {
            throw new \Exception($this->_idColumn . ' does not exist');
        }
    }
}