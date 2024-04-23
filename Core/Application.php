<?php

namespace App\Core;

use App\Controller\RoleController;
use App\Controller\UserController;

class Application
{
    protected Request $request;
    public function __construct()
    {
        $this->request = Request::create();

        // MVC Design here
    }

    public function routing()
    {
        switch ($this->request->getUri()) {
            case '/auth/login':
                if ($this->request->getMethod() == 'POST') {
                    $userController = new UserController($this->request);
                    $userController->login();
                }
                break;
            case '/auth/logout':
                if ($this->request->getMethod() == 'POST') {
                    $userController = new UserController($this->request);
                    $userController->logout();
                }
                break;
            case '/auth/check':
                if ($this->request->getMethod() == 'GET') {
                    $userController = new UserController($this->request);
                    $userController->checkAuth();
                }
                break;
            case '/user/profile':
                if ($this->request->getMethod() == 'GET') {
                    $userController = new UserController($this->request);
                    $userController->userProfile();
                }
                break;
            case '/role':
                if ($this->request->getMethod() == 'GET') {
                    $roleController = new RoleController();
                    $roleController->getAllRoles();
                } elseif ($this->request->getMethod() == 'POST') {
                    $roleController = new RoleController();
                    $roleController->createRole();
                } elseif ($this->request->getMethod() == 'DELETE') {
                    $roleController = new RoleController();
                    $roleController->deleteRole();
                } elseif ($this->request->getMethod() == 'PUT') {
                    $roleController = new RoleController();
                    $roleController->updateRole();
                }
                break;
            default:
                // pass
                break;
        }
    }
}