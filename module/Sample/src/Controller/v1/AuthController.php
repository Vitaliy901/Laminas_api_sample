<?php

declare(strict_types=1);

namespace Sample\Controller\v1;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\JsonModel;
use Sample\Auth\AuthManager;
use Sample\InputFilters\User\LoginFilter;
use Sample\Repositories\UserRepository;

class AuthController extends AbstractActionController
{
    private UserRepository $rUser;

    public function __construct(UserRepository $repository)
    {
        $this->rUser = $repository;
    }

    public function loginAction()
    {
        $loginFilter = new LoginFilter();
        $request = $this->getRequest();
        if ($request->isPost()) {
            $credentials = json_decode($request->getContent(), true);
            $inputFilter = $loginFilter->getInputFilter();
            $inputFilter->setData($credentials);
            if ($inputFilter->isValid()) {

                return new JsonModel([
                    'user' =>  '',
                    'status' => 200,
                ]);
            }

        }
        return new JsonModel([
            'user' => '',
            'status' => 403,
        ]);
    }


    // to do...
    /*        $auth = new Adapter($data['email'], $data['password'], $this->table->adapter);

            dd($auth->authenticate()->getMessages());*/
}
