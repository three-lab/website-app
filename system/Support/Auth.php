<?php

namespace System\Support;

use App\Models\User;

class Auth
{
    private User $userModel;

    public function __construct()
    {
        $this->userModel = new User;
    }

    public function attempt(array $columns, string $password): bool
    {
        $user = $this->userModel->get($columns);

        if(empty($user)) return false;
        if(!password_verify($password, $user[0]->password)) return false;

        session()->set('user', $user);
        return true;
    }
}
