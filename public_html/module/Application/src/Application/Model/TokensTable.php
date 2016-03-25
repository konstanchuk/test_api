<?php

namespace Application\Model;

use Zend\Db\Adapter\Adapter;
use Zend\ServiceManager\ServiceLocatorInterface;

class TokensTable extends AbstractTable
{
    protected $table = 'tokens';

    public function __construct(Adapter $adapter, ServiceLocatorInterface $sm)
    {
        parent::__construct($adapter, $sm, 'Application\Model\Token');
    }

    public function getToken($id)
    {
        return parent::getById($id);
    }

    public function getUserIdByToken($token)
    {
        $rowset = $this->select(array(
            'token' => $token,
        ));
        if ($row = $rowset->current()) {
            return $row->getUserId();
        }
        return false;
    }

    public function getUserByToken($token)
    {
        if ($id = $this->getUserIdByToken($token)) {
            return $this->_sm->get('Application\Model\UsersTable')
                ->getUser($id);
        }
        return false;
    }

    public function saveToken(Token $token)
    {
        $data = array(
            'token' => $token->getToken(),
            'user_id' => $token->getUserId(),
            'expires' => $token->getExpires()
        );

        $id = $token->getId();
        parent::save($id, $data);
    }

    public function deleteToken($id)
    {
        parent::deleteById($id);
    }
}