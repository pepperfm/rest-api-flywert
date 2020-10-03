<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use Illuminate\Http\JsonResponse;

use Exception;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

use App\Http\Requests\Api\User\UserUpdateRequest;

class UserController extends Controller
{
    /**
     * Update the specified resource in storage.
     *
     * @param UserUpdateRequest $request
     * @return JsonResponse
     */
    public function update(UserUpdateRequest $request)
    {
        $user = Auth::user();
        $input = $request->validated();

        try {
            $user->name = $input['name'];
            $user->first_name = $input['first_name'];
            $user->last_name = $input['last_name'];
            $user->email = $input['email'];
            $user->phone = $input['phone'];
            $user->save();

            // Auth::user()->update($request->validated()); -- хотел сделать через $guarded = [] для краткости,
            // но обновлялся только email

        } catch (Exception $e) {
            Log::debug($e->getMessage());

            return new JsonResponse(['message' => 'Ошибка', 'error' => "{$e->getMessage()}"], 422);
        }

        return new JsonResponse(['message' => 'Success']);
    }
}
