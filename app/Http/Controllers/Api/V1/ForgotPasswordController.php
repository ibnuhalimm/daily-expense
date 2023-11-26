<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Auth\ForgotPasswordRequest;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;

class ForgotPasswordController extends Controller
{
    use ApiResponse;

    public function __invoke(ForgotPasswordRequest $request)
    {
        $user = User::query()
            ->where('email', $request->email)
            ->first();

        if (!$user) {
            throw ValidationException::withMessages([
                'email' => [trans('passwords.user')]
            ]);
        }

        $status = Password::sendResetLink([
            'email' => $user->email
        ]);

        if ($status === Password::RESET_LINK_SENT) {
            return $this->apiResponse(200, 'Permintaan reset password terkirim. Silahkan periksa email Anda.');
        }

        throw ValidationException::withMessages([
            'email' => ['Gagal melakukan permintaan reset password.']
        ]);
    }
}
