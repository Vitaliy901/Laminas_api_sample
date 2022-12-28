<?php

namespace Sample\Repositories;

use Laminas\Db\Adapter\AdapterInterface;
use Laminas\Db\TableGateway\TableGatewayInterface;
use Sample\Model\User;

class UserRepository
{
    private TableGatewayInterface $tableGateway;
    public  AdapterInterface $adapter;

    public function __construct(TableGatewayInterface $tableGateway)
    {
        $this->tableGateway = $tableGateway;
        $this->adapter = $tableGateway->getAdapter();
    }

    public function fetchAll()
    {
        return $this->tableGateway->select();
    }

    public function getUser($id)
    {
        $rowset = $this->tableGateway->select(['id' => $id]);
        $row = $rowset->current();
        if (!$row) {
            throw new \RuntimeException(sprintf(
                'Could not find row with identifier %d',
                $id
            ));
        }
        return $row;
    }

    public function getByEmail(string $email) {

        $rowset = $this->tableGateway->select(['email' => $email]);
        $row = $rowset->current();
        if (!$row) {
            throw new \RuntimeException(sprintf(
                'Could not find row with identifier %d',
                $email
            ));
        }
        return $row;
    }

    public function saveUser(User $user)
    {
        $data = $user->getArrayCopy();
        array_shift($data);

        $id = (int)$user->id;

        if ($id === 0) {
            $this->tableGateway->insert($data);
            return;
        }

        try {
            $this->getUser($id);
        } catch (\RuntimeException $e) {
            throw new \RuntimeException(sprintf(
                'Cannot update album with identifier %d; does not exist',
                $id
            ));
        }
        $this->tableGateway->update(array_filter($data), ['id' => $id]);
    }

    public function deleteUser($id)
    {
        $this->tableGateway->delete(['id' => $id]);
    }
}