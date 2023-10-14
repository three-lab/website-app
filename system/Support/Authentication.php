<?php

namespace System\Support;

use App\Models\User;
use Firebase\JWT\JWT;
use System\Enums\AuthGuard;

class Authentication
{
    private User $userModel;
    private ?AuthGuard $guard;

    public function __construct()
    {
        $this->userModel = new User;
        $this->guard = null;
    }

    public function guard(AuthGuard $guard)
    {
        $this->guard = $guard;
        return $this;
    }

    public function user(): object|null
    {
        if($this->guard == AuthGuard::WEB)
            return session()->get('user');
    }

    public function attempt(array $columns, string $password): bool|string
    {
        $user = $this->userModel->get($columns);
        $user = $user[0] ?? false;

        if(!$user) return false;
        if(!password_verify($password, $user->password)) return false;

        if($this->guard == AuthGuard::WEB) {
            session()->set('user', $user);
            return true;
        }

        return JWT::encode([
            'id' => $user->id,
            'email' => $user->email,
        ], config('app.jwt_secret'), config('app.jwt_algo'));
    }
}
