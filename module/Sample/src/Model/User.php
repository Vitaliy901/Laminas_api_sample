<?php

namespace Sample\Model;

class User
{
    public $id;
    public $first_name;
    public $last_name;
    public $email;
    public $password;
    public $role;
    public $active;
    public $created_at;
    public $updated_at;

    public function exchangeArray(array $data)
    {
        $this->id = !empty($data['id']) ? $data['id'] : null;
        $this->first_name = !empty($data['first_name']) ? $data['first_name'] : null;
        $this->last_name = !empty($data['last_name']) ? $data['last_name'] : null;
        $this->email = !empty($data['email']) ? $data['email'] : null;
        $this->password = !empty($data['password']) ? $data['password'] : null;
        $this->role = !empty($data['role']) ? $data['role'] : null;
        $this->active = !empty($data['active']) ? $data['active'] : null;
        $this->created_at = array_key_exists('created_at', $data) ? $data['created_at'] : date('Y-m-d H:i:s', time());
        $this->updated_at = array_key_exists('updated_at', $data) ? $data['updated_at'] : date('Y-m-d H:i:s', time());
    }

    public function getArrayCopy() {
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'password' => $this->password,
            'role' =>  $this->role,
            'active' => $this->active,
            'created_at' =>  $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}