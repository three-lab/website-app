<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\Auth\ChangePasswordRequest;
use App\Models\Employee;
use App\Http\Requests\Api\Auth\ForgotPasswordRequest;
use App\Http\Requests\Api\Auth\LoginRequest;
use App\Http\Requests\Api\Auth\ResetPasswordRequest;
use App\Http\Requests\Api\Auth\VerifyCodeRequest;
use App\Http\Resources\EmployeeResource;
use App\Traits\ApiResponser;
use System\Support\Facades\Auth;

class AuthController
{
    use ApiResponser;

    public function login(LoginRequest $request)
    {
        $attempt = Auth::attempt(['username' => $request->username], $request->password);

        if(!$attempt)
            return $this->error([], 'Username atau password salah', 403);

        return $this->success(['token' => $attempt], 'Berhasil login');
    }

    public function forgotPass(ForgotPasswordRequest $request)
    {
        $user = (new Employee)->get(['username' => $request->username], true);
        if(!$user) return $this->error([], 'Username tidak ditemukan');

        if(Auth::sendVerify($user))
            return $this->success([], 'Berhasil mengirimkan kode verifikasi');

        return $this->error([], 'Terdapat masalah saat mengirimkan kode verifikasi');
    }

    public function verifyCode(VerifyCodeRequest $request)
    {
        $user = (new Employee)->get(['username' => $request->username], true);
        if(!$user) return $this->error([], 'Username tidak ditemukan');

        $verify = Auth::attemptCode($user, $request->code, true);

        if(!$verify->status)
            return $this->error([], $verify->message);

        return $this->success([], 'Berhasil memverifikasi kode');
    }

    public function resetPass(ResetPasswordRequest $request)
    {
        $user = (new Employee)->get(['username' => $request->username], true);
        if(!$user) return $this->error([], 'Username tidak ditemukan');

        $verify = Auth::attemptCode($user, $request->code);
        $password = password($request->password);

        if(!$verify->status)
            return $this->error([], $verify->message);

        $user->update(compact('password'));
        return $this->success([], 'Berhasil mereset password');
    }

    public function user()
    {
        $user = EmployeeResource::make(Auth::user());
        return $this->success(compact('user'));
    }

    public function changePassword(ChangePasswordRequest $request)
    {
        $user = Auth::user();

        if(!Auth::attempt(['username' => $user->username], $request->old_password))
            return $this->error(message: 'Password tidak sesuai', code: 401);

        $user->update([
            'password' => password($request->new_password),
        ]);

        return $this->success(message: 'Berhasil mereset password');
    }
}
