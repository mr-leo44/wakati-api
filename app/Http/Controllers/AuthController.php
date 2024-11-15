<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(LoginRequest $request) 
    {
        try {
            $fieldType = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
            $user = User::with('account')->where($fieldType, $request->login)->first();
            if ($user === null || !Hash::check($request->password, $user->password)) {
                return response()->json(['error' => 'Identifiants incorrects'], 401);
            } else {
                $token = $user->createToken($user->email)->plainTextToken;
                $type_user = $user->account->accountables->getMorphClass();
                $type_user = class_basename($type_user);

                return response()->json([
                    'user' => $user,
                    'tooken' => $token,
                    'type_user' => $type_user,
                ]);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erreur interne de serveur'], 500);
        }
    }
}
