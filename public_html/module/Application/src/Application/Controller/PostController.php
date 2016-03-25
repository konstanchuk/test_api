<?php

/*
 * http://test.loc/api/post/add
*/

namespace Application\Controller;

use Zend\Http\Response;

class PostController extends AbstractRestController
{
    public function indexAction()
    {
        return $this->notFoundAction();
    }

    public function addAction()
    {
        if (!$this->isLogin()) {
            return $this->formatResponse(Response::STATUS_CODE_403, 'please log-in');
        }
        $request = $this->getRequest();
        if (!$request->isPost()) {
            return $this->formatResponse(Response::STATUS_CODE_405, 'invalid method');
        }

        $postText = $request->getPost('text', null);
        if (!$postText) {
            return $this->formatResponse(Response::STATUS_CODE_400, 'lacks parameters');
        }
        $currentUser = $this->getCurrentUser();
        $sm = $this->getServiceLocator();
        $postsTable = $sm->get('Application\Model\PostsTable');
        $post = $sm->get('Application\Model\Post');
        $post->setUser($currentUser);
        $post->setText($postText);
        $postsTable->savePost($post);
        return $this->formatResponse(Response::STATUS_CODE_200, 'post added', array(
            'post_id' => (int)$postsTable->getLastInsertValue()
        ));
    }

    public function deleteAction()
    {
        if (!$this->isLogin()) {
            return $this->formatResponse(Response::STATUS_CODE_403, 'please log-in');
        }
        $request = $this->getRequest();
        if ($request->isPost()) {
            $id = (int)$request->getPost('id', null);
            if (!$id) {
                return $this->formatResponse(Response::STATUS_CODE_400, 'lacks parameters or not valid');
            }
            $sm = $this->getServiceLocator();
            $postsTable = $sm->get('Application\Model\PostsTable');
            if ($postsTable->getPostByUserAndPostId($this->getCurrentUser(), $id)) {
                $postsTable->deletePost($id);
                return $this->formatResponse(Response::STATUS_CODE_200, 'post was deleted');
            }
            return $this->formatResponse(Response::STATUS_CODE_403, 'no rights to edit');
        } else {
            return $this->formatResponse(Response::STATUS_CODE_405, 'invalid method');
        }
    }

    public function updateAction()
    {
        if (!$this->isLogin()) {
            return $this->formatResponse(Response::STATUS_CODE_200, 'please log-in');
        }
        $request = $this->getRequest();
        if ($request->isPost()) {
            $id = (int)$request->getPost('id', null);
            $text = $request->getPost('text', null);
            if ($id && $text) {
                $sm = $this->getServiceLocator();
                $postsTable = $sm->get('Application\Model\PostsTable');
                if ($post = $postsTable->getPostByUserAndPostId($this->getCurrentUser(), $id)) {
                    $post->setText($text);
                    $post->setDateUpdated(date('Y-m-d H:i:s'));
                    $postsTable->savePost($post);
                    return $this->formatResponse(Response::STATUS_CODE_200, 'post was updated');
                }
                return $this->formatResponse(Response::STATUS_CODE_403, 'no rights to edit');
            }
            return $this->formatResponse(Response::STATUS_CODE_400, 'lacks parameters or not valid');
        } else {
            return $this->formatResponse(Response::STATUS_CODE_405, 'invalid method');
        }
    }
}