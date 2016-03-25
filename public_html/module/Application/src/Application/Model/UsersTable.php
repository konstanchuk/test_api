<?php

namespace Application\Model;

use Zend\Db\Adapter\Adapter;
use Zend\ServiceManager\ServiceLocatorInterface;

class UsersTable extends AbstractTable
{
    protected $table = 'users';

    public function __construct(Adapter $adapter, ServiceLocatorInterface $sm)
    {
        parent::__construct($adapter, $sm, 'Application\Model\User');
    }

    public function getUser($id)
    {
        return parent::getById($id);
    }

    public function getUserByName($username)
    {
        $rowset = $this->select(array(
            'username' => $username,
        ));
        return $rowset->current();
    }

    public function saveUser(User $user)
    {
        $data = array(
            'username' => $user->getUserName(),
            'password' => $user->getEncryptPassword(),
            'salt' => $user->getSalt(),
        );

        $id = $user->getId();
        parent::save($id, $data);
    }

    public function deleteUser($id)
    {
        parent::deleteById($id);
    }
}