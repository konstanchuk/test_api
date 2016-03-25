<?php

namespace Application\Model;

class Post extends AbstractData
{
    protected $_id;
    protected $_userId;
    protected $_text;
    protected $_created;
    protected $_updated;

    public function exchangeArray($data)
    {
        if (isset($data['user_id']) &&
            isset($data['text'])
        ) {
            $this->_id = isset($data['id']) ? $data['id'] : null;
            $this->_userId = $data['user_id'];
            $this->_text = $data['text'];
            $this->_updated = isset($data['updated']) ? $data['updated'] : null;
            $this->_created = isset($data['created']) ? $data['created'] : null;
        } else {
            throw new \Exception('invalid data');
        }
        return $this;
    }

    public function setUserId($id)
    {
        $this->_userId = $id;
    }

    public function setUser(User $user)
    {
        $this->setUserId($user->getId());
    }

    public function getUserId()
    {
        return $this->_userId;
    }

    public function getText()
    {
        return $this->_text;
    }

    public function getId()
    {
        return (int)$this->_id;
    }

    public function setText($text)
    {
        $this->_text = $text;
    }

    public function setDateCreated($date)
    {
        $this->_created = $date;
    }

    public function getDateCreated()
    {
        if (!$this->_created) {
            $this->_created = date('Y-m-d H:i:s');
        }
        return $this->_created;
    }

    public function setDateUpdated($date)
    {
        $this->_updated = $date;
    }

    public function getDateUpdated()
    {
        if (!$this->_updated) {
            $this->_updated = $this->getDateCreated();
        }
        return $this->_updated;
    }
}