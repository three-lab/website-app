<?php

namespace System\Support;

use App\Models\Verification;
use Cake\Chronos\Chronos;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\SignatureInvalidException;
use System\Components\Model;
use System\Enums\AuthGuard;
use Throwable;

class Authentication
{
    private Model $model;
    private Verification $verifyModel;
    private ?AuthGuard $guard;

    public function __construct()
    {
        $this->verifyModel = new Verification;
        $this->guard = null;
    }

    public function guard(AuthGuard $guard)
    {
        $this->guard = $guard;
        return $this;
    }

    public function model(string $model)
    {
        $this->model = new $model;
        return $this;
    }

    public function user(): ?Model
    {
        if($this->guard == AuthGuard::WEB)
            return session()->get('user');

        if($this->guard == AuthGuard::API) {
            $token = request()->header('authorization');
            $key = new Key(config('app.jwt_secret'), config('app.jwt_algo'));

            if(!$token) return null;

            try {
                $jwt = JWT::decode($token, $key);
                if(time() > $jwt->expiration) return null;

                return $this->model->find($jwt->id);
            } catch(Throwable $e) {
                return null;
            }
        }

        return null;
    }

    public function attempt(array $columns, string $password): bool|string
    {
        $user = $this->model->get($columns, true);

        if(!$user) return false;
        if(!password_verify($password, $user->password)) return false;

        if($this->guard == AuthGuard::WEB) {
            session()->set('user', $user);
            return true;
        }

        return JWT::encode([
            'id' => $user->id,
            'model' => $user::class,
            'expiration' => Chronos::now()->addHours(5)->timestamp,
        ], config('app.jwt_secret'), config('app.jwt_algo'));
    }

    public function sendVerify(Model $user): bool
    {
        $code = random_int(100000, 999999);
        $expiration = Chronos::now()->addMinutes(config('app.verify_expiration'));

        $this->verifyModel->insert([
            'model' => $user::class,
            'user_id' => $user->id,
            'code' => $code,
            'expiration' => $expiration->format('Y-m-d H:i:s'),
        ]);

        return (new Mailer)
            ->send($user->email, 'Verification Code', view('mail.verify', compact('code')));
    }

    public function attemptCode(Model $user, string $code, bool $onlyAttempt = false): object
    {
        $verify = $this->verifyModel->get([
            'model' => $user::class,
            'user_id' => $user->id,
            'code' => $code,
        ], true);

        if(!$verify) return (object) [
            'status' => false,
            'message' => 'Kode yang dimasukkan tidak valid',
        ];

        if(Chronos::now()->greaterThan($verify->expiration)) {
            $verify->delete();

            return (object) [
                'status' => false,
                'message' => 'Kode telah kedaluwarsa',
            ];
        }

        if(!$onlyAttempt)
            $this->verifyModel->deleteAll([
                'model' => $user::class,
                'user_id' => $user->id,
            ]);

        return (object) ['status' => true];
    }

    public function logout()
    {
        session()->remove('user');
    }
}
