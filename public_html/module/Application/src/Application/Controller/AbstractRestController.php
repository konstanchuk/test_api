<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;
use Zend\Http\Response;

abstract class AbstractRestController extends AbstractRestfulController
{
    protected $_currentUser = null;

    protected function formatResponse($status = Response::STATUS_CODE_200, $message = 'success', $data = array())
    {
        $response = $this->getResponse();
        $response->setStatusCode($status);
        $responseData = array(
            'status' => $response->isSuccess(),
            'message' => $message,
        );
        if (count($data)) {
            $responseData['data'] = $data;
        }
        //$format = $this->params()->fromRoute('format', 'json');
        return new JsonModel($responseData);
    }

    protected function isLogin()
    {
        return (bool)$this->getCurrentUser();
    }

    protected function getToken()
    {
        $token = $this->getRequest()->getHeader('Token');
        return $token ? $token->getFieldValue() : false;
    }

    protected function getCurrentUser()
    {
        if (is_null($this->_currentUser)) {
            $token = $this->getToken();
            if ($token) {
                $tokensTable = $this->getServiceLocator()->get('Application\Model\TokensTable');
                $this->_currentUser = $tokensTable->getUserByToken($token);
            } else {
                $this->_currentUser = false;
            }
        }
        return $this->_currentUser;
    }
}