<?php

declare(strict_types=1);

namespace Sample\Controller\v1;

use Laminas\Crypt\Password\Bcrypt;
use Laminas\Mvc\Controller\AbstractRestfulController;
use Laminas\Paginator\Adapter\ArrayAdapter;
use Laminas\Paginator\Paginator;
use Laminas\View\Model\JsonModel;
use Sample\InputFilters\User\CreateFilter;
use Sample\InputFilters\User\UpdateFilter;
use Sample\Model\User;
use Sample\Repositories\UserRepository;

class UserController extends AbstractRestfulController
{
    private UserRepository $rUser;

    public function __construct(UserRepository $repository)
    {
        $this->rUser = $repository;
    }

    public function getList()
    {
        $params = $this->getRequest()->getQuery();
        $users = $this->rUser->fetchAll();
        $paginator = new Paginator(new ArrayAdapter($users->toArray()));
        $paginator->setItemCountPerPage($params['per_page']);
        $paginator->setCurrentPageNumber($params['page']);

        return new JsonModel([
            'data' => [
                'list' => $paginator->getCurrentItems()->getArrayCopy(),
                'total' => $paginator->getTotalItemCount(),
                'page' => $paginator->getCurrentPageNumber(),
                'per_page' => $paginator->getItemCountPerPage(),
                'total_pages' => $paginator->getPages()->pageCount,
            ],
        ]);
    }

    public function create($data)
    {
        $user = new User();
        $filter = new CreateFilter($this->rUser->adapter);
        $inputFilter = $filter->getInputFilter();
        $inputFilter->setData($data);

        if (!$inputFilter->isValid()) {
            return new JsonModel([
                'errors' => $inputFilter->getMessages(),
                'status' => 403,
            ]);
        }
        $datavalid = $inputFilter->getValues();

        $crypt = new Bcrypt();
        $datavalid['password'] = $crypt->create($datavalid['password']);

        $user->exchangeArray($datavalid);
        $this->rUser->saveUser($user);
        return new JsonModel([
            'user' => $user,
            'status' => 200,
        ]);
    }

    public function get($id): JsonModel
    {
        $user = $this->rUser->getUser($id);
        return new JsonModel([
            'user' => $user,
        ]);
    }

    public function update($id, $data)
    {
        $user = $this->rUser->getUser($id);

        $filter = new UpdateFilter($this->rUser->adapter);
        $inputFilter = $filter->getInputFilter();
        $inputFilter->setData($data);

        if (!$inputFilter->isValid()) {
            return new JsonModel([
                'errors' => $inputFilter->getMessages(),
                'status' => 403,
            ]);
        }
        $datavalid = $inputFilter->getValues();
        $crypt = new Bcrypt();
        !$datavalid['password'] ?: $datavalid['password'] = $crypt->create($datavalid['password']);
        $datavalid['created_at'] = $user->created_at;

        $user->exchangeArray($datavalid);
        $user->id = $id;

        $this->rUser->saveUser($user);
        return new JsonModel([
            'user' => $user,
            'status' => 200,
        ]);
    }

    public function delete($id)
    {
        $this->rUser->deleteUser($id);
        return new JsonModel([
            'user' => [],
            'status' => 200,
        ]);
    }
}
