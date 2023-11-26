<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\ChangePasswordRequest;
use App\Models\User;
use App\Notifications\PasswordHasChangedNotification;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class ChangePasswordController extends Controller
{
    use ApiResponse;

    public function __invoke(ChangePasswordRequest $request)
    {
        $user = $request->user();

        if (!Hash::check($request->current_password, $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => ['Kata Sandi yang Anda masukkan tidak sesuai.']
            ]);
        }

        $user = User::query()
            ->where('id', $user->id)
            ->first();

        $user->password = bcrypt($request->new_password);
        if ($user->save()) {
            $user->notify(new PasswordHasChangedNotification());

            return $this->apiResponse(200, 'Kata Sandi berhasil diubah.');
        }

        throw ValidationException::withMessages([
            'current_password' => ['Gagal mengubah Kata Sandi.']
        ]);
    }
}
