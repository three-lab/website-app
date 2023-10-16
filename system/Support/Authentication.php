<?php

namespace System\Support;

use App\Models\User;
use App\Models\Verification;
use Cake\Chronos\Chronos;
use Firebase\JWT\JWT;
use System\Enums\AuthGuard;

class Authentication
{
    private User $userModel;
    private Verification $verifyModel;
    private ?AuthGuard $guard;

    public function __construct()
    {
        $this->userModel = new User;
        $this->verifyModel = new Verification;
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

    public function sendVerify(object $user)
    {
        $code = random_int(100000, 999999);
        $expiration = Chronos::now();
        $expiration = $expiration->addMinutes(config('app.verify_expiration'));

        $this->verifyModel->insert([
            'user_id' => $user->id,
            'code' => $code,
            'expiration' => $expiration->format('Y-m-d H:i:s'),
        ]);

        return (new Mailer)
            ->send($user->email, 'Verification Code', view('mail.verify', compact('code')));
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

    public function attemptCode(object $user, string $code): object
    {
        $verify = $this->verifyModel->get([
            'user_id' => $user->id,
            'code' => $code,
        ], true);

        if(!$verify) return (object) [
            'status' => false,
            'message' => 'Kode yang dimasukkan tidak valid',
        ];

        $exp = Chronos::createFromFormat('Y-m-d H:i:s', $verify->expiration);

        if(Chronos::now()->greaterThan($exp)) {
            (new Verification)->delete($verify->id);
            return (object) [
                'status' => false,
                'message' => 'Kode telah kedaluwarsa',
            ];
        }

        return (object) ['status' => true];
    }
}
