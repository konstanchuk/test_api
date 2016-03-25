<?php

namespace Application\Model;

use Zend\Db\Adapter\Adapter;
use Zend\ServiceManager\ServiceLocatorInterface;

class PostsTable extends AbstractTable
{
    protected $table = 'posts';

    public function __construct(Adapter $adapter, ServiceLocatorInterface $sm)
    {
        parent::__construct($adapter, $sm, 'Application\Model\Post');
    }

    public function getPost($id)
    {
        return parent::getById($id);
    }

    public function getPostByUserAndPostId(User $user, $id)
    {
        $rowset = $this->select(array(
            'user_id' => $user->getId(),
            $this->_idColumn => $id,
        ));
        return $rowset->current();
    }

    public function savePost(Post $post)
    {
        $data = array(
            'user_id' => $post->getUserId(),
            'text' => $post->getText(),
            'updated' => $post->getDateUpdated(),
            'created' => $post->getDateCreated(),
        );

        $id = $post->getId();
        parent::save($id, $data);
    }

    public function deletePost($id)
    {
        parent::deleteById($id);
    }
}