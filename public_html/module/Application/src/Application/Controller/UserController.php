<?php

/*
 * http://test.loc/api/user/register
 */

namespace Application\Controller;

use Zend\Http\Response;

class UserController extends AbstractRestController
{
    public function indexAction()
    {
        return $this->notFoundAction();
    }

    public function registerAction()
    {
        $request = $this->getRequest();
        if (!$request->isPost()) {
            return $this->formatResponse(Response::STATUS_CODE_405, 'invalid method');
        } else {
            if ($this->isLogin()) {
                return $this->formatResponse(Response::STATUS_CODE_200, 'user already logged');
            }
            $username = $request->getPost('username', null);
            $password = $request->getPost('password', null);
            if ($username && $password && preg_match('/^[A-Za-z0-9]+(?:[ _-][A-Za-z0-9]+)*$/', $username)) {
                $sm = $this->getServiceLocator();
                $usersTable = $sm->get('Application\Model\UsersTable');
                if ($usersTable->getUserByName($username)) {
                    return $this->formatResponse(Response::STATUS_CODE_403, 'user already exists');
                } else {
                    $user = $sm->get('Application\Model\User');
                    $user->setUserName($username);
                    $user->setPassword($password);
                    $usersTable->saveUser($user);
                    return $this->formatResponse(Response::STATUS_CODE_200, 'user created');
                }
            } else {
                return $this->formatResponse(Response::STATUS_CODE_400, 'lacks parameters or not valid');
            }
        }
    }

    public function loginAction()
    {
        $request = $this->getRequest();
        if (!$request->isPost()) {
            return $this->formatResponse(Response::STATUS_CODE_405, 'invalid method');
        } else {
            if ($this->isLogin()) {
                return $this->formatResponse(Response::STATUS_CODE_200, 'user already logged');
            }
            $username = $request->getPost('username', null);
            $password = $request->getPost('password', null);
            if ($username && $password) {
                $sm = $this->getServiceLocator();
                $usersTable = $sm->get('Application\Model\UsersTable');
                $user = $usersTable->getUserByName($username);
                if ($user && $user->isValidPassword($password)) {
                    $token = $sm->get('Application\Model\Token');
                    $token->setUser($user);
                    $response = array(
                        'expires' => $token->getExpires(),
                        'token' => $token->generateToken(),
                    );
                    $tokensTable = $sm->get('Application\Model\TokensTable');
                    $tokensTable->saveToken($token);
                    return $this->formatResponse(Response::STATUS_CODE_200, 'user was logged', $response);
                } else {
                    return $this->formatResponse(Response::STATUS_CODE_403, 'invalid credentials');
                }
            } else {
                return $this->formatResponse(Response::STATUS_CODE_400, 'lacks parameters');
            }
        }
    }
}
