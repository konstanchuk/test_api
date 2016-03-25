<?php

namespace Application\Model;

class User extends AbstractData
{
    protected $_id;
    protected $_username;
    protected $_password;
    protected $_salt = false;

    public function exchangeArray($data)
    {
        if (isset($data['username']) &&
            isset($data['password']) &&
            isset($data['salt'])
        ) {
            $this->_id = isset($data['id']) ? $data['id'] : null;
            $this->_username = $data['username'];
            $this->_password = $data['password'];
            $this->_salt = $data['salt'];
        } else {
            throw new \Exception('invalid data');
        }
        return $this;
    }

    public function setUserName($username)
    {
        $this->_username = $username;
    }

    public function getUserName()
    {
        return $this->_username;
    }

    public function setPassword($password)
    {
        $this->_password = $this->encryptPassword($password);
    }

    public function getEncryptPassword()
    {
        return $this->_password;
    }

    public function getSalt()
    {
        if (!$this->_salt) {
            $this->_salt = $this->generateSalt();
        }
        return $this->_salt;
    }

    public function getId()
    {
        return (int)$this->_id;
    }

    protected function generateSalt()
    {
        return substr(sha1(mt_rand()), 0, 21);
    }

    protected function encryptPassword($password)
    {
        return sha1($this->getSalt() . $password);
    }

    public function compare(User $user)
    {
        return $this->_username == $user->getUserName();
    }

    public function isValidPassword($password)
    {
        return $this->getEncryptPassword() == $this->encryptPassword($password);
    }
}

