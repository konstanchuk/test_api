<?php

namespace Application\Model;


class Token extends AbstractData
{
    protected $_id;
    protected $_token;
    protected $_userId;
    protected $_expires = false;

    public function exchangeArray($data)
    {
        if (isset($data['user_id']) &&
            isset($data['token']) &&
            isset($data['expires'])
        ) {
            $this->_id = isset($data['id']) ? $data['id'] : null;
            $this->_token = $data['token'];
            $this->_userId = $data['user_id'];
            $this->_expires = $data['expires'];
        } else {
            throw new \Exception('invalid data');
        }
        return $this;
    }

    public function generateToken()
    {
        $config = $this->_sm->get('Config');
        $this->_token = substr(base64_encode(sha1(mt_rand(1, 9999999) . $config['salt'])), 0, 25);
        return $this->_token;
    }

    public function getId()
    {
        return (int)$this->_id;
    }

    public function setUserId($id)
    {
        $this->_userId = $id;
    }

    public function setUser(User $user)
    {
        if ($user->getId() == 0) {
            throw new \Exception('invalid user');
        } else {
            $this->setUserId($user->getId());
        }
    }

    public function getUserId()
    {
        return (int)$this->_userId;
    }

    public function getToken()
    {
        return $this->_token;
    }

    public function getExpires()
    {
        if (!$this->_expires) {
            $this->regenerateExpires();
        }
        return $this->_expires;
    }

    public function regenerateExpires()
    {
        $this->_expires = date('Y-m-d H:i:s', strtotime('+2 month'));;
        return $this->_expires;
    }
}
